<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * Feedback Controller
 *
 * @method \App\Model\Entity\Feedback[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class FeedbacksController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {

    }
    public function survey()
{
    if ($this->request->is('post')) {

        $this->loadModel('Evaluations');
        $this->loadModel('Feedbacks');

        $session = $this->request->getSession();

        $evaluationData = $session->read('EvaluationData');

        if (!$evaluationData) {
            $this->Flash->error('Evaluation session has expired.');
            return $this->redirect(['action' => 'index']);
        }

        // Compute feedback score
        $feedbackScore = (
            (int)$this->request->getData('q1') +
            (int)$this->request->getData('q2') +
            (int)$this->request->getData('q3') +
            (int)$this->request->getData('q4') +
            (int)$this->request->getData('q5') +
            (int)$this->request->getData('q6')
        ) / 6;

        // ==========================
        // Call FastAPI
        // ==========================

        $http = new \Cake\Http\Client();

        $payload = [
            'subsidy_type'      => $evaluationData['subsidy_type'],
            'farm_size'         => (float)$evaluationData['farm_size'],
            'crop_yield_before' => (float)$evaluationData['crop_yield_before'],
            'crop_yield_after'  => (float)$evaluationData['crop_yield_after'],
            'income_before'     => (float)$evaluationData['income_before'],
            'income_after'      => (float)$evaluationData['income_after'],
            'feedback_score'    => $feedbackScore,
            'pest'              => $evaluationData['pest'],
            'calamity'          => $evaluationData['calamity']
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

        $result = $response->getJson();

        // Save Evaluation
        $evaluation = $this->Evaluations->newEmptyEntity();

        $evaluation = $this->Evaluations->patchEntity(
            $evaluation,
            $evaluationData
        );

        $evaluation->feedback_score = $feedbackScore;
        $evaluation->effectiveness_label = $result['effectiveness'] ?? 'Pending';

        if (!$this->Evaluations->save($evaluation)) {
            debug($evaluation->getErrors());
            die('Evaluation could not be saved.');
        }

        // Save Feedback
        $feedback = $this->Feedbacks->newEmptyEntity();

        $feedback = $this->Feedbacks->patchEntity($feedback, [

            'evaluation_id' => $evaluation->id,

            'q1' => $this->request->getData('q1'),
            'q2' => $this->request->getData('q2'),
            'q3' => $this->request->getData('q3'),
            'q4' => $this->request->getData('q4'),
            'q5' => $this->request->getData('q5'),
            'q6' => $this->request->getData('q6'),

            'rating' => $feedbackScore,

            'comments' => $this->request->getData('comments'),

            'feedback_date' => date('Y-m-d')
        ]);

        if (!$this->Feedbacks->save($feedback)) {
            debug($feedback->getErrors());
            die('Feedback could not be saved.');
        }

        // Link Feedback to Evaluation
        $evaluation->feedback_id = $feedback->id;
        $this->Evaluations->save($evaluation);
        // Clear session
        $session->delete('EvaluationData');

        $this->Flash->success('Your feedback has been submitted.');

        return $this->redirect([
            'action' => 'index'
        ]);
    }
}
}
