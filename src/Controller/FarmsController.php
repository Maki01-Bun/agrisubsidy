<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * Farms Controller
 *
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
            'contain' => [],
        ]);

        $this->set(compact('farm'));
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
            $farm = $this->Farms->patchEntity($farm, $this->request->getData());
            if ($this->Farms->save($farm)) {
                $this->Flash->success(__('The farm has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The farm could not be saved. Please, try again.'));
        }
        $this->set(compact('farm'));
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
                $this->Flash->success(__('The farm has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The farm could not be saved. Please, try again.'));
        }
        $this->set(compact('farm'));
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
            $this->Flash->success(__('The farm has been deleted.'));
        } else {
            $this->Flash->error(__('The farm could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
