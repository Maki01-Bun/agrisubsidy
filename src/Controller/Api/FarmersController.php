<?php
declare(strict_types=1);

namespace App\Controller\Api;

use App\Controller\AppController;
use Carbon\Carbon;
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
        $farmers = $this->Farmers->find()
            ->order(['Farmers.created' => 'DESC'])
            ->all();
    
        $data = [];
    
        foreach ($farmers as $farmer) {
    
            $created = '';
    
            if (!empty($farmer->created)) {
                $created = $farmer->created
                    ->setTimezone('Asia/Manila')
                    ->format('M d, Y h:i A');
            }
    
            $birthdate = '';
    
            if (!empty($farmer->birthdate)) {
                $birthdate = $farmer->birthdate->format('M d, Y');
            }
    
            $data[] = [
                'id' => $farmer->id,
                'farmer_no' => $farmer->farmer_no ?? '',
                'first_name'  => strtoupper($farmer->first_name ?? ''),
                'last_name'   => strtoupper($farmer->last_name ?? ''),
                'middle_name' => strtoupper($farmer->middle_name ?? ''),
                'birthdate' => $birthdate,
                'gender' => strtoupper(
                    strtolower(trim((string)($farmer->gender ?? ''))) === 'male'
                        ? 'M'
                        : (
                            strtolower(trim((string)($farmer->gender ?? ''))) === 'female'
                                ? 'F'
                                : trim((string)($farmer->gender ?? ''))
                        )
                ),
                'address' => strtoupper((string)($farmer->address ?? '')),
                'contact_no' => $farmer->contact_no ?? '',
                'created' => $created
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
        $farmer = $this->Farmers->newEmptyEntity();
        if ($this->request->is('post')) {
            $farmer = $this->Farmers->patchEntity($farmer, $this->request->getData());
            if ($this->Farmers->save($farmer)) {
                $result = ['status' => 'success', 'message' => 'The farmer has been saved.'];
            }else {
                $result = ['status'=>'error','message'=>'The farmer could not be saved. Please, try again.'];
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
 public function viewRecord()
    {

        $this->request->allowMethod(['get']);

        try {

            $farmerId = $this->request->getQuery('id');

            if (empty($farmerId) || !is_numeric($farmerId)) {
                return $this->response
                    ->withType('application/json')
                    ->withStringBody(json_encode([
                        'status' => 'error',
                        'message' => 'Invalid farmer ID.'
                    ]));
            }

            $farmerId = (int)$farmerId;

            $farmer = $this->Farmers->find()
                ->select([
                    'id',
                    'first_name',
                    'last_name'
                ])
                ->where([
                    'Farmers.id' => $farmerId
                ])
                ->first();

            if (!$farmer) {
                return $this->response
                    ->withType('application/json')
                    ->withStringBody(json_encode([
                        'status' => 'error',
                        'message' => 'Farmer not found.'
                    ]));
            }

            $this->loadModel('Records');

            $records = $this->Records->find()
                ->where([
                    'Records.farmer_id' => $farmerId
                ])
                ->order([
                    'Records.distribution_date' => 'DESC',
                    'Records.id' => 'DESC'
                ])
                ->enableHydration(false)
                ->toArray();
            foreach ($records as &$record) {
                if (
                    isset($record['distribution_date']) &&
                    $record['distribution_date'] instanceof \DateTimeInterface
                ) {
                    $record['distribution_date'] =
                        $record['distribution_date']->format('Y-m-d');
                }

                if (
                    isset($record['received_date']) &&
                    $record['received_date'] instanceof \DateTimeInterface
                ) {
                    $record['received_date'] =
                        $record['received_date']->format('Y-m-d');
                }
            }
            unset($record);

            $farmerData = [
                'id' => $farmer->id,
                'first_name' => $farmer->first_name ?? '',
                'last_name' => $farmer->last_name ?? ''
            ];

            return $this->response
                ->withType('application/json')
                ->withStringBody(json_encode(['status' => 'success','farmer' => $farmerData,'records' => $records
                ], JSON_UNESCAPED_UNICODE));
        } catch (\Throwable $e) {

            \Cake\Log\Log::error(
                'FarmersController::viewRecord(): ' .
                $e->getMessage()
            );

            return $this->response
                ->withStatus(500)->withType('application/json')
                ->withStringBody(json_encode(['status' => 'error','message' => $e->getMessage()])
                );
        }
    }
}
