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
        if ($this->request->is('post')) {
            $schedule = $this->Schedules->patchEntity($schedule, $this->request->getData());
            if ($this->Schedules->save($schedule)) {
                $result = ['status' => 'success', 'message' => 'The schedule has been saved.'];
            }else {
                $result = ['status'=>'error','message'=>'The schedule could not be saved. Please, try again.'];
            }
            return $this->response->withType('application/json')
                ->withStringBody(json_encode($result));
        }
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
}
