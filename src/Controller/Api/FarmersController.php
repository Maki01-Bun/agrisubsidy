<?php
declare(strict_types=1);

namespace App\Controller\Api;

use App\Controller\AppController;

/**
 * Farmers Controller
 *
 * @property \App\Model\Table\FarmersTable $Farmers
 * @method \App\Model\Entity\Category[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class FarmersController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $farmers = $this->Farmers->find();
        return $this->response->withType('application/json')
            ->withStringBody(json_encode($farmers));
    }

    public function getFarmers()
    {
        $farmers = $this->Farmers->find();
        return $this->response->withType('application/json')
            ->withStringBody(json_encode(['data'=>$farmers]));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $farmers = $this->Farmers->newEmptyEntity();
        if ($this->request->is('post')) {
            $farmers = $this->Farmers->patchEntity($farmers, $this->request->getData());
            if ($this->Farmers->save($farmers)) {
                $result = ['status' => 'success', 'message' => 'The Farmer has been saved.'];
            }else {
                $result = ['status'=>'error','message'=>'The Farmer could not be saved. Please, try again.'];
            }
            return $this->response->withType('application/json')
                ->withStringBody(json_encode($result));
        }
    }

    /**
     * Edit method
     *
     * @param string|null $id Farmer id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $farmer = $this->Farmers->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $farmer = $this->Farmers->patchEntity($farmer, $this->request->getData());
            if ($this->Farmers->save($farmer)) {
                $result = ['status' => 'success', 'message' => 'The Farmer has been saved.'];
            }else {
                $result = ['status'=>'error','message'=>'The Farmer could not be saved. Please, try again.'];
            }
            return $this->response->withType('application/json')
                ->withStringBody(json_encode($result));
        }
        return $this->response->withType('application/json')
            ->withStringBody(json_encode($farmer));
    }

    /**
     * Delete method
     *
     * @param string|null $id Farmer id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $farmer = $this->Farmers->get($id);
        if ($this->Farmers->delete($farmer)) {
            $result = ['status' => 'success', 'message' => 'The Farmer has been deleted.'];
        }else {
            $result = ['status'=>'error','message'=>'The Farmer could not be deleted. Please, try again.'];
        }
        return $this->response->withType('application/json')
            ->withStringBody(json_encode($result));
    }
}
