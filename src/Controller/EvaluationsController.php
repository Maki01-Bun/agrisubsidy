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

//     public function survey()
// {
//     if ($this->request->is('post')) {

//         $this->loadModel('Evaluations');
//         $this->loadModel('Feedbacks');

//         $session = $this->request->getSession();

//         $evaluationData = $session->read('EvaluationData');

//         if (!$evaluationData) {
//             $this->Flash->error('Evaluation session has expired.');
//             return $this->redirect(['action' => 'index']);
//         }

//         // Compute feedback score
//         $feedbackScore = (
//             (int)$this->request->getData('q1') +
//             (int)$this->request->getData('q2') +
//             (int)$this->request->getData('q3') +
//             (int)$this->request->getData('q4') +
//             (int)$this->request->getData('q5') +
//             (int)$this->request->getData('q6')
//         ) / 6;

//         // ==========================
//         // Call FastAPI
//         // ==========================

//         $http = new \Cake\Http\Client();

//         $payload = [
//             'subsidy_type'      => $evaluationData['subsidy_type'],
//             'farm_size'         => (float)$evaluationData['farm_size'],
//             'crop_yield_before' => (float)$evaluationData['crop_yield_before'],
//             'crop_yield_after'  => (float)$evaluationData['crop_yield_after'],
//             'income_before'     => (float)$evaluationData['income_before'],
//             'income_after'      => (float)$evaluationData['income_after'],
//             'feedback_score'    => $feedbackScore,
//             'pest'              => $evaluationData['pest'],
//             'calamity'          => $evaluationData['calamity']
//         ];

//         $response = $http->post(
//             'http://127.0.0.1:8000/predict',
//             json_encode($payload),
//             [
//                 'headers' => [
//                     'Content-Type' => 'application/json'
//                 ]
//             ]
//         );

//         $result = $response->getJson();

//         // ==========================
//         // Save Evaluation
//         // ==========================

//         $evaluation = $this->Evaluations->newEmptyEntity();

//         $evaluation = $this->Evaluations->patchEntity(
//             $evaluation,
//             $evaluationData
//         );

//         $evaluation->feedback_score = $feedbackScore;
//         $evaluation->effectiveness_label = $result['effectiveness'] ?? 'Pending';

//         if (!$this->Evaluations->save($evaluation)) {
//             debug($evaluation->getErrors());
//             die('Evaluation could not be saved.');
//         }

//         // ==========================
//         // Save Feedback
//         // ==========================

//         $feedback = $this->Feedbacks->newEmptyEntity();

//         $feedback = $this->Feedbacks->patchEntity($feedback, [

//             'evaluation_id' => $evaluation->id,

//             'q1' => $this->request->getData('q1'),
//             'q2' => $this->request->getData('q2'),
//             'q3' => $this->request->getData('q3'),
//             'q4' => $this->request->getData('q4'),
//             'q5' => $this->request->getData('q5'),
//             'q6' => $this->request->getData('q6'),

//             'rating' => $feedbackScore,

//             'comments' => $this->request->getData('comments'),

//             'feedback_date' => date('Y-m-d')
//         ]);

//         if (!$this->Feedbacks->save($feedback)) {
//             debug($feedback->getErrors());
//             die('Feedback could not be saved.');
//         }

//         // ==========================
//         // Link Feedback to Evaluation
//         // ==========================

//         $evaluation->feedback_id = $feedback->id;
//         $this->Evaluations->save($evaluation);

//         // Clear session

//         $session->delete('EvaluationData');

//         $this->Flash->success('Evaluation completed successfully.');

//         return $this->redirect([
//             'action' => 'index'
//         ]);
//     }
// }
}
