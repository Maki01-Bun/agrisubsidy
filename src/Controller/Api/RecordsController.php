<?php
declare(strict_types=1);

namespace App\Controller\Api;

use App\Controller\AppController;
use Carbon\Carbon;
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
            'contain' => ['Farmers', 'Schedules'],
        ]);

        $this->set(compact('record'));
    }

  
 /**
     * =========================================================
     * FORMAT DATE ONLY
     * =========================================================
     *
     * Used for schedule start_date.
     *
     * Example:
     *
     * 2026-09-11
     */
    private function formatDateOnly($date): ?string
    {
        if ($date === null || $date === '') {
            return null;
        }

        try {

            /*
             * CakePHP Date / DateTime objects
             */
            if ($date instanceof \DateTimeInterface) {
                return $date->format('Y-m-d');
            }


            /*
             * Carbon object
             */
            if ($date instanceof Carbon) {
                return $date->format('Y-m-d');
            }


            /*
             * String
             */
            return Carbon::parse((string)$date)
                ->format('Y-m-d');

        } catch (\Throwable $e) {

            return null;
        }
    }


    /**
     * =========================================================
     * FORMAT DATE AND TIME
     * =========================================================
     *
     * Used for received_date.
     *
     * Example:
     *
     * 2026-09-11 16:30:09
     */
    private function formatDateTime($date): ?string
    {
        if ($date === null || $date === '') {
            return null;
        }

        try {

            /*
             * CakePHP DateTime object
             */
            if ($date instanceof \DateTimeInterface) {
                return $date->format('Y-m-d H:i:s');
            }


            /*
             * Carbon object
             */
            if ($date instanceof Carbon) {
                return $date->format('Y-m-d H:i:s');
            }


            /*
             * String datetime
             */
            return Carbon::parse((string)$date)
                ->format('Y-m-d H:i:s');

        } catch (\Throwable $e) {

            return null;
        }
    }


    /**
     * =========================================================
     * FORMAT TIME ONLY
     * =========================================================
     *
     * Used for Schedules.start_time.
     *
     * Example:
     *
     * 16:30:00
     */
    private function formatTimeOnly($time): ?string
    {
        if ($time === null || $time === '') {
            return null;
        }

        try {

            /*
             * CakePHP Time object
             */
            if ($time instanceof \DateTimeInterface) {
                return $time->format('H:i:s');
            }


            /*
             * Carbon object
             */
            if ($time instanceof Carbon) {
                return $time->format('H:i:s');
            }


            /*
             * String time
             */
            return Carbon::parse((string)$time)
                ->format('H:i:s');

        } catch (\Throwable $e) {

            return null;
        }
    }


    /**
     * =========================================================
     * GET RECORDS
     * =========================================================
     *
     * Used by DataTables.
     */
    public function getRecords()
    {
        $records = $this->Records->find()
            ->select([

                /*
                 * =================================================
                 * RECORD IDS
                 * =================================================
                 */
                'id' =>
                    'Records.id',

                'farmer_id' =>
                    'Records.farmer_id',

                'schedule_id' =>
                    'Records.schedule_id',


                /*
                 * =================================================
                 * FARMER
                 * =================================================
                 */
                'first_name' =>
                    'Farmers.first_name',

                'last_name' =>
                    'Farmers.last_name',


                /*
                 * =================================================
                 * RECORD INFORMATION
                 * =================================================
                 */
                'subsidy_item' =>
                    'Records.subsidy_item',

                'quantity' =>
                    'Records.quantity',


                /*
                 * =================================================
                 * SCHEDULE INFORMATION
                 * =================================================
                 */
                'distribution_date' =>
                    'Schedules.start_date',

                'distribution_time' =>
                    'Schedules.start_time',

                'program_code' =>
                    'Schedules.program_code',

                'barangay' =>
                    'Schedules.barangay',


                /*
                 * =================================================
                 * RECORD INFORMATION
                 * =================================================
                 */
                'received_date' =>
                    'Records.received_date',

                'status' =>
                    'Records.status',

                'confirmed_at' =>
                    'Records.confirmed_at',
            ])


            /*
             * =====================================================
             * FARMER JOIN
             * =====================================================
             */
            ->join([
                'Farmers' => [
                    'table' => 'farmers',

                    'type' => 'LEFT',

                    'conditions' => [
                        'Farmers.id = Records.farmer_id'
                    ]
                ],


                /*
                 * =================================================
                 * SCHEDULE JOIN
                 * =================================================
                 */
                'Schedules' => [
                    'table' => 'schedules',

                    'type' => 'LEFT',

                    'conditions' => [
                        'Schedules.id = Records.schedule_id'
                    ]
                ]
            ])


            /*
             * Return arrays instead of entities
             */
            ->enableHydration(false)

            ->all();


        /*
         * =========================================================
         * BUILD DATA TABLE RESPONSE
         * =========================================================
         */

        $data = [];


        foreach ($records as $record) {

            /*
             * =====================================================
             * FARMER NAME
             * =====================================================
             */

            $farmerName = trim(
                ($record['first_name'] ?? '') .
                ' ' .
                ($record['last_name'] ?? '')
            );


            /*
             * =====================================================
             * RECEIVED DATE + TIME
             * =====================================================
             *
             * Example:
             *
             * 2026-09-11 16:30:09
             */

            $receivedDate =
                $this->formatDateTime(
                    $record['received_date'] ?? null
                );


            /*
             * =====================================================
             * DISTRIBUTION DATE
             * =====================================================
             *
             * Comes from:
             *
             * Schedules.start_date
             */

            $distributionDate =
                $this->formatDateOnly(
                    $record['distribution_date'] ?? null
                );


            /*
             * =====================================================
             * DISTRIBUTION TIME
             * =====================================================
             *
             * Comes from:
             *
             * Schedules.start_time
             */

            $distributionTime =
                $this->formatTimeOnly(
                    $record['distribution_time'] ?? null
                );


            /*
             * =====================================================
             * ADD DATA
             * =====================================================
             */

            $data[] = [

                /*
                 * Record IDs
                 */
                'id' =>
                    $record['id'] ?? null,

                'farmer_id' =>
                    $record['farmer_id'] ?? null,

                'schedule_id' =>
                    $record['schedule_id'] ?? null,


                /*
                 * Farmer
                 */
                'farmer_name' =>
                    $farmerName !== ''
                        ? $farmerName
                        : 'N/A',


                /*
                 * Record
                 */
                'subsidy_item' =>
                    $record['subsidy_item']
                    ?? 'Seed Subsidy',

                'quantity' =>
                    $record['quantity']
                    ?? 0,


                /*
                 * Schedule
                 */
                'program_code' =>
                    $record['program_code']
                    ?? 'N/A',

                'barangay' =>
                    !empty($record['barangay'])
                        ? $record['barangay']
                        : 'N/A',


                /*
                 * Distribution
                 */
                'distribution_date' =>
                    $distributionDate,

                'distribution_time' =>
                    $distributionTime,


                /*
                 * Combined distribution datetime
                 *
                 * Example:
                 *
                 * 2026-09-11 16:30:00
                 */
                'distribution_datetime' =>
                    $distributionDate
                    ? (
                        $distributionDate .
                        (
                            $distributionTime
                                ? ' ' . $distributionTime
                                : ''
                        )
                    )
                    : null,


                /*
                 * Received
                 */
                'received_date' =>
                    $receivedDate,


                /*
                 * Status
                 */
                'status' =>
                    $record['status']
                    ?? 'N/A',


                /*
                 * Confirmation
                 */
                'confirmed_at' =>
                    $record['confirmed_at']
                    ?? null,
            ];
        }


        /*
         * =========================================================
         * DATATABLES JSON RESPONSE
         * =========================================================
         */

        return $this->response
            ->withType('application/json')
            ->withStringBody(
                json_encode([
                    'data' => $data
                ])
            );
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
