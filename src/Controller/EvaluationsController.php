<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * Evaluations Controller
 *
 * @method \App\Model\Entity\Evaluation[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class EvaluationsController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $evaluation = $this->Evaluations->newEmptyEntity();

        $this->set(compact('evaluation'));
    }

    /**
     * View method
     *
     * @param string|null $id Evaluation id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $evaluation = $this->Evaluations->get($id, [
            'contain' => [],
        ]);

        $this->set(compact('evaluation'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
{
    $evaluation = $this->Evaluations->newEmptyEntity();

    if ($this->request->is('post')) {

        $evaluation = $this->Evaluations->patchEntity(
            $evaluation,
            $this->request->getData()
        );

        try {

            $http = new Client();

            $payload = [
                'subsidy_type'      => $evaluation->subsidy_type,
                'farm_size'         => (float)$evaluation->farm_size,
                'crop_yield_before' => (float)$evaluation->crop_yield_before,
                'crop_yield_after'  => (float)$evaluation->crop_yield_after,
                'income_before'     => (float)$evaluation->income_before,
                'income_after'      => (float)$evaluation->income_after,
                'feedback_score'    => (float)$evaluation->feedback_score,
                'pest'              => $evaluation->pest,
                'calamity'          => $evaluation->calamity
            ];

            $response = $http->post(
                'http://127.0.0.1:8000/predict',
                json_encode($payload),
                [
                    'headers' => [
                        'Content-Type' => 'application/json'
                    ]
                ]
            );

            if ($response->getStatusCode() == 200) {

                $result = $response->getJson();

                $evaluation->effectiveness_label =
                    $result['effectiveness'];

            } else {

                $evaluation->effectiveness_label =
                    'Prediction Failed';
            }

        } catch (\Exception $e) {

            debug($e->getMessage());

            $evaluation->effectiveness_label =
                'Prediction Failed';
        }

        if ($this->Evaluations->save($evaluation)) {

            $this->Flash->success(
                'Evaluation saved successfully.'
            );

            return $this->redirect([
                'action' => 'index'
            ]);
        }

        $this->Flash->error(
            'The evaluation could not be saved.'
        );
    }

    $this->set(compact('evaluation'));
}

    /**
     * Edit method
     *
     * @param string|null $id Evaluation id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $evaluation = $this->Evaluations->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $evaluation = $this->Evaluations->patchEntity($evaluation, $this->request->getData());
            if ($this->Evaluations->save($evaluation)) {
                $this->Flash->success(__('The evaluation has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The evaluation could not be saved. Please, try again.'));
        }
        $this->set(compact('evaluation'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Evaluation id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $evaluation = $this->Evaluations->get($id);
        if ($this->Evaluations->delete($evaluation)) {
            $this->Flash->success(__('The evaluation has been deleted.'));
        } else {
            $this->Flash->error(__('The evaluation could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
