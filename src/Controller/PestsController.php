<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * Pests Controller
 *
 * @method \App\Model\Entity\Pest[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class PestsController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $pest = $this->Pests->newEmptyEntity();

        $this->set(compact('pest'));
    }

    /**
     * View method
     *
     * @param string|null $id Pest id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $pest = $this->Pests->get($id, [
            'contain' => [],
        ]);

        $this->set(compact('pest'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $pest = $this->Pests->newEmptyEntity();
        if ($this->request->is('post')) {
            $pest = $this->Pests->patchEntity($pest, $this->request->getData());
            if ($this->Pests->save($pest)) {
                $this->Flash->success(__('The pest has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The pest could not be saved. Please, try again.'));
        }
        $this->set(compact('pest'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Pest id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $pest = $this->Pests->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $pest = $this->Pests->patchEntity($pest, $this->request->getData());
            if ($this->Pests->save($pest)) {
                $this->Flash->success(__('The pest has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The pest could not be saved. Please, try again.'));
        }
        $this->set(compact('pest'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Pest id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $pest = $this->Pests->get($id);
        if ($this->Pests->delete($pest)) {
            $this->Flash->success(__('The pest has been deleted.'));
        } else {
            $this->Flash->error(__('The pest could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
