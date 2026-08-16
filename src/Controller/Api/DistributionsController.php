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
        $distributions = $this->Distributions->find()->contain(['Farmers'])->all();
        return $this->response->withType('application/json')
            ->withStringBody(json_encode($distributions));
    }

    /**
     * View method
     *
     * @param string|null $id Distribution id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $distribution = $this->Distributions->get($id, [
            'contain' => ['Farmers'],
        ]);

        $this->set(compact('distribution'));
    }

    public function getDistributions()
{
    $distributions = $this->Distributions->find()
        ->select([
            'id' => 'Distributions.id',
            'farmer_id' => 'Distributions.farmer_id',
            'first_name' => 'Farmers.first_name',
            'last_name' => 'Farmers.last_name',
            'subsidy_item' => 'Distributions.subsidy_item',
            'quantity' => 'Distributions.quantity',
            'distribution_date' => 'Distributions.distribution_date',
            'received_date' => 'Distributions.received_date',
            'status' => 'Distributions.status'
        ])
        ->join([
            'Farmers' => [
                'table' => 'farmers',
                'type' => 'LEFT',
                'conditions' => [
                    'Farmers.id = Distributions.farmer_id'
                ]
            ]
        ])
        ->enableHydration(false)
        ->all();

    $data = [];

    foreach ($distributions as $distribution) {
        $data[] = [
            'id' => $distribution['id'],
            'farmer_id' => $distribution['farmer_id'],
            'farmer_name' => trim(
                ($distribution['first_name'] ?? '') . ' ' .
                ($distribution['last_name'] ?? '')
            ),
            'subsidy_item' => $distribution['subsidy_item'],
            'quantity' => $distribution['quantity'],
            'distribution_date' => $distribution['distribution_date'],
            'received_date' => $distribution['received_date'],
            'status' => $distribution['status']
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
        $distribution = $this->Distributions->newEmptyEntity();
        if ($this->request->is('post')) {
            $distribution = $this->Distributions->patchEntity($distribution, $this->request->getData());
            if ($this->Distributions->save($distribution)) {
                $result = ['status' => 'success', 'message' => 'The distribution has been saved.'];
            }else {
                $result = ['status'=>'error','message'=>'The distribution could not be saved. Please, try again.'];
            }
            return $this->response->withType('application/json')
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
            ->withStringBody(json_encode($distribution));
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
