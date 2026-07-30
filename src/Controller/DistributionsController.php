<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * Distribution Controller
 *
 * @method \App\Model\Entity\Distribution[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
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
        $distributions = $this->Distributions->newEmptyEntity();

        $this->loadModel('Farmers');

        $farmers = $this->Farmers->find()->all()->combine(
        'id',
        function ($farmer) {
            return $farmer->first_name . ' ' . $farmer->last_name;})->toArray();
        $this->set(compact('distributions','farmers'));
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
            'contain' => [],
        ]);

        $this->set(compact('distributions'));
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
            $distribution = $this->Distributions->patchEntity($distribution, $this->request->getDistributions());
            if ($this->Distributions->save($distribution)) {
                $this->Flash->success(__('The distributions has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The distribution could not be saved. Please, try again.'));
        }
        $this->loadModel('Farmers');

        $farmers = $this->Farmers->find()->all()->combine(
        'id',
        function ($farmer) {
            return $farmer->first_name . ' ' . $farmer->last_name;})->toArray();

        $this->set(compact('distribution', 'farmers'));

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
            $distribution = $this->Distributions->patchEntity($distribution, $this->request->getDistributions());
            if ($this->Distributions->save($distribution)) {
                $this->Flash->success(__('The distribution has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The distribution could not be saved. Please, try again.'));
        }
        $this->set(compact('distribution'));
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
            $this->Flash->success(__('The distribution has been deleted.'));
        } else {
            $this->Flash->error(__('The distribution could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

}
