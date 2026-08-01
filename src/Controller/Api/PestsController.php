<?php
declare(strict_types=1);

namespace App\Controller\Api;

use App\Controller\AppController;

/**
 * Pests Controller
 *
 * @property \App\Model\Table\PestsTable $Pests
 * @method \App\Model\Entity\Pest[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class PestsController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
     public function index()
    {
        $pests = $this->Pests->find();
        return $this->response->withType('application/json')
            ->withStringBody(json_encode($pests));
    }


    /**
     * View method
     *
     * @param string|null $id Pest id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $pest = $this->Pests->get($id, [
            'contain' => [],
        ]);

        $this->set(compact('pest'));
    }
    
    public function getPests()
    {
        $pests = $this->Pests->find();
        return $this->response->withType('application/json')
            ->withStringBody(json_encode(['data'=>$pests]));
    }


    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $pest = $this->Pests->newEmptyEntity();
        if ($this->request->is('post')) {
            $pest = $this->Pests->patchEntity($pest, $this->request->getData());
            if ($this->Pests->save($pest)) {
                $result = ['status' => 'success', 'message' => 'The pest has been saved.'];
            }else {
                $result = ['status'=>'error','message'=>'The pest could not be saved. Please, try again.'];
            }
            return $this->response->withType('application/json')
                ->withStringBody(json_encode($result));
        }
    }

    /**
     * Edit method
     *
     * @param string|null $id Pest id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $pest = $this->Pests->get($id);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $pest = $this->Pests->patchEntity(
                $pest,$this->request->getData() );
            if ($this->Pests->save($pest)) {
                return $this->response->withType('application/json')->withStringBody(json_encode([
                'status' => 'success','message' => 'Pest updated successfully']));
            }
            return $this->response->withType('application/json')
            ->withStringBody(json_encode(['status' => 'error','errors' => $pest->getErrors()]));
        }
        return $this->response
            ->withType('application/json')
            ->withStringBody(json_encode($pest));
    }

    /**
     * Delete method
     *
     * @param string|null $id Pest id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $pest = $this->Pests->get($id);
        if ($this->Pests->delete($pest)) {
            $result = ['status' => 'success', 'message' => 'The Pest has been deleted.'];
        }else {
            $result = ['status'=>'error','message'=>'The Pest could not be deleted. Please, try again.'];
        }
        return $this->response->withType('application/json')
            ->withStringBody(json_encode($result));
    }
}
