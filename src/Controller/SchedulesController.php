<?php
declare(strict_types=1);

namespace App\Controller;

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
     * @return \Cake\Http\Response|null|void
     */
    public function index()
    {
        $schedules = $this->Schedules
            ->find()
            ->order([
                'Schedules.start_date' => 'ASC',
                'Schedules.start_time' => 'ASC'
            ])
            ->all()
            ->toArray();

        $schedule =
            $this->Schedules->newEmptyEntity();

        $this->set(
            compact(
                'schedules',
                'schedule'
            )
        );
    }

    /**
     * View method
     *
     * @param string|null $id Schedule id.
     */
    public function view($id = null)
    {
        $schedule =
            $this->Schedules->get(
                $id,
                [
                    'contain' => ['Beneficiaries'],
                ]
            );

        $this->set(
            compact(
                'schedule'
            )
        );
    }

    /**
     * Add method
     */
    public function add()
    {
        $schedule =
            $this->Schedules->newEmptyEntity();

        if (
            $this->request->is('post')
        ) {

            $schedule =
                $this->Schedules->patchEntity(
                    $schedule,
                    $this->request->getData()
                );

            /*
             * New schedules are Scheduled.
             */

            if (
                empty($schedule->status)
            ) {

                $schedule->status =
                    'Scheduled';
            }

            if (
                $this->Schedules->save(
                    $schedule
                )
            ) {

                $this->Flash->success(
                    __(
                        'The schedule has been saved.'
                    )
                );

                return $this->redirect(
                    [
                        'action' => 'index'
                    ]
                );
            }

            $this->Flash->error(
                __(
                    'The schedule could not be saved. Please, try again.'
                )
            );
        }

        $this->set(
            compact(
                'schedule'
            )
        );
    }

    /**
     * Edit method
     *
     * @param string|null $id Schedule id.
     */
    /**
 * Edit method
 *
 * @param string|null $id Schedule id.
 */
public function edit($id = null)
{
    $schedule =
        $this->Schedules->get(
            $id,
            [
                'contain' => [],
            ]
        );

    if (
        $this->request->is(
            [
                'patch',
                'post',
                'put'
            ]
        )
    ) {

        /*
         * =====================================================
         * SAVE ORIGINAL DATES BEFORE PATCHING
         * =====================================================
         */

        $originalStartDate =
            $schedule->start_date;

        $originalEndDate =
            $schedule->end_date;


        /*
         * =====================================================
         * GET FORM DATA
         * =====================================================
         */

        $data =
            $this->request->getData();


        /*
         * =====================================================
         * DO NOT ALLOW MANUAL STATUS CHANGE
         * =====================================================
         */

        unset(
            $data['status']
        );


        /*
         * =====================================================
         * PATCH SCHEDULE
         * =====================================================
         */

        $schedule =
            $this->Schedules->patchEntity(
                $schedule,
                $data
            );


        /*
         * =====================================================
         * NORMALIZE ORIGINAL DATES
         * =====================================================
         */

        $originalStart =
            $originalStartDate
                ? $originalStartDate->format('Y-m-d')
                : '';

        $originalEnd =
            $originalEndDate
                ? $originalEndDate->format('Y-m-d')
                : '';


        /*
         * =====================================================
         * NORMALIZE NEW DATES
         * =====================================================
         */

        $newStart =
            $schedule->start_date
                ? $schedule->start_date->format('Y-m-d')
                : '';

        $newEnd =
            $schedule->end_date
                ? $schedule->end_date->format('Y-m-d')
                : '';


        /*
         * =====================================================
         * CHECK IF DATE CHANGED
         * =====================================================
         */

        $dateChanged =
            (
                $originalStart !== $newStart ||
                $originalEnd !== $newEnd
            );


        /*
         * =====================================================
         * AUTOMATICALLY MARK AS RE-SCHEDULED
         * =====================================================
         *
         * Only the date change causes this status.
         *
         * Users cannot manually select the status
         * from the edit form.
         */

        if ($dateChanged) {

            $schedule->status =
                'Re-Scheduled';
        }


        /*
         * =====================================================
         * SAVE
         * =====================================================
         */

        if (
            $this->Schedules->save(
                $schedule
            )
        ) {

            /*
             * =================================================
             * SUCCESS MESSAGE
             * =================================================
             */

            if ($dateChanged) {

                $this->Flash->success(
                    __(
                        'The schedule has been re-scheduled successfully.'
                    )
                );

            } else {

                $this->Flash->success(
                    __(
                        'The schedule has been saved.'
                    )
                );
            }


            return $this->redirect(
                [
                    'action' => 'index'
                ]
            );
        }


        /*
         * =====================================================
         * SAVE ERROR
         * =====================================================
         */

        $this->Flash->error(
            __(
                'The schedule could not be saved. Please, try again.'
            )
        );
    }


    $this->set(
        compact(
            'schedule'
        )
    );
}

    /**
     * Delete method
     *
     * @param string|null $id Schedule id.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(
            [
                'post',
                'delete'
            ]
        );

        $schedule =
            $this->Schedules->get(
                $id
            );

        if (
            $this->Schedules->delete(
                $schedule
            )
        ) {

            $this->Flash->success(
                __(
                    'The schedule has been deleted.'
                )
            );

        } else {

            $this->Flash->error(
                __(
                    'The schedule could not be deleted. Please, try again.'
                )
            );
        }

        return $this->redirect(
            [
                'action' => 'index'
            ]
        );
    }

    /**
     * =========================================================
     * ANNOUNCEMENTS
     * =========================================================
     */
   public function announcements()
{
    $title = 'Subsidy Announcements';

    // =====================================================
    // GET LOGGED-IN USER
    // =====================================================

    $user = $this->request
        ->getSession()
        ->read('Auth.User');


    // =====================================================
    // LOAD FARMERS MODEL
    // =====================================================

    $this->loadModel('Farmers');


    // =====================================================
    // FIND LOGGED-IN FARMER
    // =====================================================

    $farmer = null;

    if ($user && !empty($user['id'])) {

        $farmer = $this->Farmers
            ->find()
            ->select([
                'Farmers.id',
                'Farmers.user_id',
                'Farmers.address'
            ])
            ->where([
                'Farmers.user_id' => $user['id']
            ])
            ->first();
    }


    // =====================================================
    // GET FARMER ADDRESS
    // =====================================================

    $farmerAddress = '';

    if ($farmer && !empty($farmer->address)) {
        $farmerAddress = trim($farmer->address);
    }


    // =====================================================
    // GET SCHEDULES
    // =====================================================

    $schedules = $this->Schedules
        ->find()
        ->select([
            'Schedules.id',
            'Schedules.program_code',
            'Schedules.program_name',
            'Schedules.description',
            'Schedules.barangay',
            'Schedules.start_date',
            'Schedules.end_date',
            'Schedules.start_time',
            'Schedules.end_time',
            'Schedules.status'
        ])
        ->order([
            'Schedules.start_date' => 'DESC',
            'Schedules.start_time' => 'ASC'
        ])
        ->all();


    // =====================================================
    // FILTER SCHEDULES BY FARMER ADDRESS
    // =====================================================

    $filteredSchedules = [];


    if (!empty($farmerAddress)) {

        // Normalize farmer address
        $normalizedFarmerAddress = strtoupper(
            trim($farmerAddress)
        );


        foreach ($schedules as $schedule) {

            if (empty($schedule->barangay)) {
                continue;
            }


            // Normalize schedule barangay
            $normalizedBarangay = strtoupper(
                trim($schedule->barangay)
            );


            // =================================================
            // CHECK IF BARANGAY EXISTS IN FARMER ADDRESS
            // =================================================

            if (
                strpos(
                    $normalizedFarmerAddress,
                    $normalizedBarangay
                ) !== false
            ) {

                // Display Barangay in uppercase
                $schedule->barangay =
                    strtoupper(
                        trim($schedule->barangay)
                    );

                $filteredSchedules[] = $schedule;
            }
        }
    }


    // =====================================================
    // SEND FILTERED SCHEDULES TO VIEW
    // =====================================================

    $schedules = $filteredSchedules;


    $this->set(
        compact(
            'schedules',
            'title'
        )
    );
}
}
