<?php
declare(strict_types=1);

namespace App\Controller\Api;

use App\Controller\AppController;

/**
 * Schedules Controller
 *
 * @property \App\Model\Table\SchedulesTable $Schedules
 * @method \App\Model\Entity\Schedule[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class SchedulesController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
     public function index()
    {
        $schedules = $this->Schedules->find();
        return $this->response->withType('application/json')
            ->withStringBody(json_encode($schedules));
    }


    /**
     * View method
     *
     * @param string|null $id Schedule id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $schedule = $this->Schedules->get($id, [
            'contain' => ['Farmers'],
        ]);

        $this->set(compact('schedule'));
    }
    
    public function getSchedules()
    {
        $schedules = $this->Schedules->find();
        return $this->response->withType('application/json')
            ->withStringBody(json_encode(['data'=>$schedules]));
    }


    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $schedule = $this->Schedules->newEmptyEntity();

        if (!$this->request->is('post')) {
            return $this->response->withStatus(405)->withType('application/json')
                ->withStringBody(json_encode(['status' => 'error', 'message' => 'Invalid request method.'
                ]));
        }

        $data = $this->request->getData();
        $validationResult = $this->validateScheduleDateTime($data);
        if ($validationResult !== true) {
            return $this->response->withStatus(400)->withType('application/json')
                ->withStringBody(json_encode(['status' => 'error', 'message' => $validationResult
                ]));
        }
        $schedule = $this->Schedules->patchEntity(
            $schedule,
            $data
        );
        if ($this->Schedules->save($schedule)) {
            return $this->response
                ->withType('application/json')->withStringBody(json_encode(['status' => 'success',
                'message' => 'The schedule has been saved.','data' => $schedule
                ]));
        }
        return $this->response
            ->withStatus(400)->withType('application/json')
            ->withStringBody(json_encode(['status' => 'error', 'message' => 'The schedule could not be saved.',
                'errors' => $schedule->getErrors()
            ]));
    }

    /**
     * Edit method
     *
     * @param string|null $id Schedule id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $schedule = $this->Schedules->get($id);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $schedule = $this->Schedules->patchEntity(
                $schedule,$this->request->getData() );
            if ($this->Schedules->save($schedule)) {
                return $this->response->withType('application/json')->withStringBody(json_encode([
                'status' => 'success','message' => 'schedule updated successfully']));
            }
            return $this->response->withType('application/json')
            ->withStringBody(json_encode(['status' => 'error','errors' => $schedule->getErrors()]));
        }
        return $this->response
            ->withType('application/json')
            ->withStringBody(json_encode($schedule));
    }

    /**
     * Delete method
     *
     * @param string|null $id Schedule id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $schedule = $this->Schedules->get($id);
        if ($this->Schedules->delete($schedule)) {
            $result = ['status' => 'success', 'message' => 'The Schedule has been deleted.'];
        }else {
            $result = ['status'=>'error','message'=>'The Schedule could not be deleted. Please, try again.'];
        }
        return $this->response->withType('application/json')
            ->withStringBody(json_encode($result));
    }

    private function validateScheduleDateTime(array $data)
    {
        $startDate = $data['start_date'] ?? '';
        $endDate   = $data['end_date'] ?? '';
        $startTime = $data['start_time'] ?? '';
        $endTime   = $data['end_time'] ?? '';

        /*
        * ==========================================
        * START DATE / END DATE
        * ==========================================
        */

        if (empty($startDate)) {
            return 'Start date is required.';
        }

        if (empty($endDate)) {
            return 'End date is required.';
        }

        /*
        * Convert values to strings.
        */
        if ($startDate instanceof \DateTimeInterface) {
            $startDate = $startDate->format('Y-m-d');
        } else {
            $startDate = trim((string)$startDate);
        }

        if ($endDate instanceof \DateTimeInterface) {
            $endDate = $endDate->format('Y-m-d');
        } else {
            $endDate = trim((string)$endDate);
        }

        /*
        * Parse dates strictly.
        *
        * Expected format:
        * YYYY-MM-DD
        */
        $startDateObj = \DateTime::createFromFormat('!Y-m-d', $startDate);
        $endDateObj   = \DateTime::createFromFormat('!Y-m-d', $endDate);

        /*
        * If the form sends another format, try strtotime().
        */
        if (!$startDateObj) {
            $timestamp = strtotime($startDate);

            if ($timestamp === false) {
                return 'Invalid start date.';
            }

            $startDateObj = new \DateTime(
                date('Y-m-d', $timestamp)
            );
        }

        if (!$endDateObj) {
            $timestamp = strtotime($endDate);

            if ($timestamp === false) {
                return 'Invalid end date.';
            }

            $endDateObj = new \DateTime(
                date('Y-m-d', $timestamp)
            );
        }

        /*
        * Normalize dates.
        */
        $startDateString = $startDateObj->format('Y-m-d');
        $endDateString   = $endDateObj->format('Y-m-d');

        /*
        * ==========================================
        * RULE 1:
        *
        * END DATE MUST NOT BE BEFORE START DATE
        * ==========================================
        */
        if ($endDateObj->getTimestamp() < $startDateObj->getTimestamp()) {

            return 'Invalid schedule: End date (' .
                $endDateString .
                ') cannot be earlier than start date (' .
                $startDateString .
                ').';
        }

        /*
        * ==========================================
        * RULE 2:
        *
        * IF SAME DATE, COMPARE TIMES
        * ==========================================
        */
        if ($startDateString === $endDateString) {

            if (empty($startTime)) {
                return 'Start time is required.';
            }

            if (empty($endTime)) {
                return 'End time is required.';
            }

            /*
            * Convert time objects to strings.
            */
            if ($startTime instanceof \DateTimeInterface) {
                $startTime = $startTime->format('H:i:s');
            } else {
                $startTime = trim((string)$startTime);
            }

            if ($endTime instanceof \DateTimeInterface) {
                $endTime = $endTime->format('H:i:s');
            } else {
                $endTime = trim((string)$endTime);
            }

            /*
            * Parse times.
            */
            $startTimeObj = \DateTime::createFromFormat(
                '!H:i:s',
                $startTime
            );

            $endTimeObj = \DateTime::createFromFormat(
                '!H:i:s',
                $endTime
            );

            /*
            * Try H:i if H:i:s didn't work.
            */
            if (!$startTimeObj) {
                $startTimeObj = \DateTime::createFromFormat(
                    '!H:i',
                    $startTime
                );
            }

            if (!$endTimeObj) {
                $endTimeObj = \DateTime::createFromFormat(
                    '!H:i',
                    $endTime
                );
            }

            /*
            * Invalid time.
            */
            if (!$startTimeObj) {
                return 'Invalid start time.';
            }

            if (!$endTimeObj) {
                return 'Invalid end time.';
            }

            /*
            * ==========================================
            * END TIME MUST NOT BE BEFORE START TIME
            * ==========================================
            */
            if ($endTimeObj->getTimestamp() < $startTimeObj->getTimestamp()) {

                return 'Invalid schedule: End time (' .
                    $endTimeObj->format('H:i:s') .
                    ') cannot be earlier than start time (' .
                    $startTimeObj->format('H:i:s') .
                    ') when the dates are the same.';
            }
        }

        /*
        * ==========================================
        * VALID
        * ==========================================
        */
        return true;
    }
}
