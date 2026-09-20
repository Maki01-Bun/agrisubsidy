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
             * Do not allow status to be changed
             * through this normal edit form.
             */

            $data =
                $this->request->getData();

            unset(
                $data['status']
            );

            $schedule =
                $this->Schedules->patchEntity(
                    $schedule,
                    $data
                );

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
        $title =
            'Subsidy Announcements';

        /*
         * =====================================================
         * IMPORTANT:
         * Include status.
         * =====================================================
         */

        $schedules =
            $this->Schedules
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

        $this->set(
            compact(
                'schedules',
                'title'
            )
        );
    }
}
