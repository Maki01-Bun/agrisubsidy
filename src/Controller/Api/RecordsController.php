<?php
declare(strict_types=1);

namespace App\Controller\Api;

use App\Controller\AppController;

/**
 * Records Controller
 *
 * @property \App\Model\Table\RecordsTable $Records
 * @method \App\Model\Entity\Record[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class RecordsController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $records = $this->Records->find();
        return $this->response->withType('application/json')
            ->withStringBody(json_encode($records));
    }

    public function getRecord($farmId = null)
{
    $this->request->allowMethod(['get']);

    $this->loadModel('Records');

    $record = $this->Records->find()
        ->where(['farm_id' => $farmId])
        ->order(['record_date' => 'DESC'])
        ->first();

    if (!$record) {
        return $this->response
            ->withType('application/json')
            ->withStringBody(json_encode([
                'crop_yield' => '',
                'income' => ''
            ]));
    }

    return $this->response
        ->withType('application/json')
        ->withStringBody(json_encode([
            'crop_yield' => $record->crop_yield,
            'income' => $record->income
        ]));
}

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $record = $this->Records->newEmptyEntity();
        if ($this->request->is('post')) {
            $record = $this->Records->patchEntity($record, $this->request->getData());
            if ($this->Records->save($record)) {
                $result = ['status' => 'success', 'message' => 'The record has been saved.'];
            }else {
                $result = ['status'=>'error','message'=>'The record could not be saved. Please, try again.'];
            }
            return $this->response->withType('application/json')
                ->withStringBody(json_encode($result));
        }
    }
    /**
     * Edit method
     *
     * @param string|null $id Record id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $record = $this->Records->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $record = $this->Records->patchEntity($record, $this->request->getData());
            if ($this->Records->save($record)) {
                $result = ['status' => 'success', 'message' => 'The Record has been saved.'];
            }else {
                $result = ['status'=>'error','message'=>'The Record could not be saved. Please, try again.'];
            }
            return $this->response->withType('application/json')
                ->withStringBody(json_encode($result));
        }
        return $this->response->withType('application/json')
            ->withStringBody(json_encode($record));
    }

    /**
     * Delete method
     *
     * @param string|null $id Record id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $record = $this->Records->get($id);
        if ($this->Records->delete($record)) {
            $result = ['status' => 'success', 'message' => 'The Record has been deleted.'];
        }else {
            $result = ['status'=>'error','message'=>'The Record could not be deleted. Please, try again.'];
        }
        return $this->response->withType('application/json')
            ->withStringBody(json_encode($result));
    }
}
