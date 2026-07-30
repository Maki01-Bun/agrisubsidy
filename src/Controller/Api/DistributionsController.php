<?php
declare(strict_types=1);

namespace App\Controller\Api;

use App\Controller\AppController;

/**
 * Distributions Controller
 *
 * @property \App\Model\Table\DistributionsTable $Distributions
 * @method \App\Model\Entity\Category[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class DistributionsController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $distributions = $this->Distributions->find();
        return $this->response->withType('application/json')
            ->withStringBody(json_encode($distributions));
    }

    public function getDistributions()
    {
        $distributions = $this->Distributions->find();
        return $this->response->withType('application/json')
            ->withStringBody(json_encode(['data'=>$distributions]));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
   public function add()
{
    $distribution = $this->Distributions->newEmptyEntity();

    if ($this->request->is('post')) {

        $distribution = $this->Distributions->patchEntity(
            $distribution,
            $this->request->getData()
        );

        if ($this->Distributions->save($distribution)) {

            $result = [
                'status' => 'success',
                'message' => 'The distribution has been saved.'
            ];

        } else {

            debug($this->request->getData());
            debug($distribution->getErrors());
            debug($distribution);
            die();

            $result = [
                'status' => 'error',
                'message' => 'The distribution could not be saved.'
            ];
        }

        return $this->response
            ->withType('application/json')
            ->withStringBody(json_encode($result));
    }
}


    /**
     * Edit method
     *
     * @param string|null $id Distribution id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $distribution = $this->Distributions->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $distribution = $this->Distributions->patchEntity($distribution, $this->request->getData());
            if ($this->Distributions->save($distribution)) {
                $result = ['status' => 'success', 'message' => 'The distribution has been saved.'];
            }else {
                $result = ['status'=>'error','message'=>'The distribution could not be saved. Please, try again.'];
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
     * @param string|null $id Distribution id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $distribution = $this->Distributions->get($id);
        if ($this->Distributions->delete($distribution)) {
            $result = ['status' => 'success', 'message' => 'The distribution has been deleted.'];
        }else {
            $result = ['status'=>'error','message'=>'The distribution could not be deleted. Please, try again.'];
        }
        return $this->response->withType('application/json')
            ->withStringBody(json_encode($result));
    }
}
