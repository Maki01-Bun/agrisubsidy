<?php
declare(strict_types=1);

namespace App\Controller\Api;

use App\Controller\AppController;

/**
 * Farms Controller
 *
 * @property \App\Model\Table\FarmsTable $Farms
 * @method \App\Model\Entity\Farm[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class FarmsController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
     public function index()
{
    $this->loadModel('Farmers');

    $user = $this->request->getSession()->read('Auth.User');
   
    if ($user['role'] === 'admin') {

        // Admin sees all farms
        $farms = $this->Farms->find()
            ->contain(['Farmers'])
            ->all();

    } else {

        // Farmer sees only their farms
        $farmer = $this->Farmers->find()
            ->where(['user_id' => $user['id']])
            ->first();

        $farms = $this->Farms->find()
            ->where(['farmer_id' => $farmer->id])
            ->contain(['Farmers'])
            ->all();
    }

    $this->set(compact('farms'));
}


    /**
     * View method
     *
     * @param string|null $id Farm id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $farm = $this->Farms->get($id, [
            'contain' => ['Farmers'],
        ]);

        $this->set(compact('farm'));
    }
    
    public function getFarms()
{
    $this->request->allowMethod(['get']);

    $this->loadModel('Farmers');

    $user = $this->request->getSession()->read('Auth.User');

    if (!$user) {
        return $this->response
            ->withType('application/json')
            ->withStatus(401)
            ->withStringBody(json_encode([
                'status' => 'error',
                'message' => 'User is not authenticated.',
                'data' => []
            ]));
    }

    $query = $this->Farms->find()
        ->contain(['Farmers'])
        ->order([
            'Farms.id' => 'ASC'
        ]);

    // Farmer can only see their own farms
    if (strtolower($user['role'] ?? '') === 'farmer') {

        $farmer = $this->Farmers->find()
            ->where([
                'Farmers.user_id' => $user['id']
            ])
            ->first();

        if ($farmer) {
            $query->where([
                'Farms.farmer_id' => $farmer->id
            ]);
        } else {
            // No farmer record = no farms
            $query->where([
                'Farms.id IS' => null
            ]);
        }
    }

    $farms = $query->all();

    $data = [];

    foreach ($farms as $farm) {

        $data[] = [
            'id' => $farm->id,
            'farmer_id' => $farm->farmer_id,
            // Get farmer_no from the contained Farmer entity
            'farmer_no' => $farm->farmer->farmer_no ?? '',
            'farm_size' => $farm->farm_size,
            'location' => $farm->location
        ];
    }

    return $this->response
        ->withType('application/json')
        ->withStatus(200)
        ->withStringBody(json_encode([
            'status' => 'success',
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
    $farm = $this->Farms->newEmptyEntity();

    if ($this->request->is('post')) {

        $data = $this->request->getData();

        $farm = $this->Farms->patchEntity($farm, $data);

        if ($this->Farms->save($farm)) {

            return $this->response
                ->withType('application/json')
                ->withStatus(200)
                ->withStringBody(json_encode([
                    'status' => 'success',
                    'message' => 'The farm has been saved successfully.',
                    'data' => $farm->toArray()
                ]));
        }

        // IMPORTANT: show the actual reason why save failed
        return $this->response
            ->withType('application/json')
            ->withStatus(400)
            ->withStringBody(json_encode([
                'status' => 'error',
                'message' => 'The farm could not be saved.',
                'validation_errors' => $farm->getErrors(),
                'submitted_data' => $data
            ]));
    }

    return $this->response
        ->withType('application/json')
        ->withStatus(400)
        ->withStringBody(json_encode([
            'status' => 'error',
            'message' => 'Invalid request.'
        ]));
}

    /**
     * Edit method
     *
     * @param string|null $id Farm id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $farm = $this->Farms->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $farm = $this->Farms->patchEntity($farm, $this->request->getData());
            if ($this->Farms->save($farm)) {
                $result = ['status' => 'success', 'message' => 'The Farm has been saved.'];
            }else {
                $result = ['status'=>'error','message'=>'The Farm could not be saved. Please, try again.'];
            }
            return $this->response->withType('application/json')
                ->withStringBody(json_encode($result));
        }
        return $this->response->withType('application/json')
            ->withStringBody(json_encode($farm));
    }

    /**
     * Delete method
     *
     * @param string|null $id Farm id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $farm = $this->Farms->get($id);
        if ($this->Farms->delete($farm)) {
            $result = ['status' => 'success', 'message' => 'The Farm has been deleted.'];
        }else {
            $result = ['status'=>'error','message'=>'The Farm could not be deleted. Please, try again.'];
        }
        return $this->response->withType('application/json')
            ->withStringBody(json_encode($result));
    }
}
