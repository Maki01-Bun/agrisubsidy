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
        $records = $this->Records->find()->contain(['Farmers'])->all();
        return $this->response->withType('application/json')
            ->withStringBody(json_encode($records));
    }

    /**
     * View method
     *
     * @param string|null $id Record id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $record = $this->Records->get($id, [
            'contain' => ['Farmers'],
        ]);

        $this->set(compact('record'));
    }

    public function getRecords()
{
    $records = $this->Records->find()
        ->select([
            'id' => 'Records.id',
            'farmer_id' => 'Records.farmer_id',
            'first_name' => 'Farmers.first_name',
            'last_name' => 'Farmers.last_name',
            'subsidy_item' => 'Records.subsidy_item',
            'quantity' => 'Records.quantity',
            'distribution_date' => 'Records.distribution_date',
            'received_date' => 'Records.received_date',
            'status' => 'Records.status'
        ])
        ->join([
            'Farmers' => [
                'table' => 'farmers',
                'type' => 'LEFT',
                'conditions' => [
                    'Farmers.id = Records.farmer_id'
                ]
            ]
        ])
        ->enableHydration(false)
        ->all();

    $data = [];

    foreach ($records as $record) {
        $data[] = [
            'id' => $record['id'],
            'farmer_id' => $record['farmer_id'],
            'farmer_name' => trim(
                ($record['first_name'] ?? '') . ' ' .
                ($record['last_name'] ?? '')
            ),
            'subsidy_item' => $record['subsidy_item'],
            'quantity' => $record['quantity'],
            'distribution_date' => $record['distribution_date'],
            'received_date' => $record['received_date'],
            'status' => $record['status']
        ];
    }

    return $this->response
        ->withType('application/json')
        ->withStringBody(json_encode([
            'data' => $data
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
                $result = ['status' => 'success', 'message' => 'The record has been saved.'];
            }else {
                $result = ['status'=>'error','message'=>'The record could not be saved. Please, try again.'];
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
            $result = ['status' => 'success', 'message' => 'The record has been deleted.'];
        }else {
            $result = ['status'=>'error','message'=>'The record could not be deleted. Please, try again.'];
        }
        return $this->response->withType('application/json')
            ->withStringBody(json_encode($result));
    }
}
