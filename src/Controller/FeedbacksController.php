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
        $this->loadModel('Farmers');
        $this->loadModel('Pests');
        $pests = $this->Pests->find('list', [
            'keyField' => 'id',
            'valueField' => 'pest_name'
        ])->toArray();

        $this->set(compact('pests'));

        $session = $this->request->getSession();
        $user = $session->read('Auth.User');

        $farmer = $this->Farmers->find()->where(['user_id' => $user['id']])->first();
        if ($farmer) {
            $this->set('farmerName', $farmer->first_name . ' ' . $farmer->middle_name . ' ' . $farmer->last_name);
        } else {
            $this->set('farmerName', '');
        }
    }
    public function survey()
    {
            if ($this->request->is('post')) {

                $this->loadModel('Evaluations');    
                $this->loadModel('Feedbacks');
                $this->loadModel('Farmers');
                $this->loadModel('Pests');

                $user = $this->request->getSession()->read('Auth.User');

                $farmer = $this->Farmers->find()->where(['user_id' => $user['id']])->first();

                if (!$farmer) {
                    die('Farmer record not found.');
                }
                $evaluationData = $this->request->getData();
                $pestIds = $evaluationData['pest'] ?? [];

                $pestNames = [];

                if (!empty($pestIds)) {
                        $pests = $this->Pests->find()
                        ->where(['id IN' => $pestIds])
                        ->all();
                    foreach ($pests as $pest) {
                        $pestNames[] = $pest->pest_name;
                    }
                }
            $pestString = implode(', ', $pestNames);
            // Compute feedback score
            $feedbackScore = (
                (int)$this->request->getData('q1') +    
                (int)$this->request->getData('q2') +
                (int)$this->request->getData('q3') +
                (int)$this->request->getData('q4') +
                (int)$this->request->getData('q5') +
                (int)$this->request->getData('q6')
            ) / 6;
            // Call FastAPI
            $http = new \Cake\Http\Client();
            $payload = [
                'subsidy_type'      => $evaluationData['subsidy_type'],
                'farm_size'         => (float)$evaluationData['farm_size'],
                'crop_yield_before' => (float)$evaluationData['crop_yield_before'],
                'crop_yield_after'  => (float)$evaluationData['crop_yield_after'],
                'income_before'     => (float)$evaluationData['income_before'],
                'income_after'      => (float)$evaluationData['income_after'],
                'feedback_score'    => $feedbackScore,
                'pest'              => $pestString,
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
            $pestIds = $evaluationData['pest'] ?? [];

            unset($evaluationData['pest']);
            // Save Evaluation
            $evaluation = $this->Evaluations->newEmptyEntity();
            $evaluation = $this->Evaluations->patchEntity(
                $evaluation,
                $evaluationData
            );


            $evaluation->farmer_id = $farmer->id;
            $evaluation->feedback_score = $feedbackScore;
            $evaluation->effectiveness_label = $result['effectiveness'] ?? 'Pending';
            if (!$this->Evaluations->save($evaluation)) {
                debug($evaluation->getErrors());
                die('Evaluation could not be saved.');
            }
            // Save Feedback
            $feedback = $this->Feedbacks->newEmptyEntity();
            $feedbackData = [
                'evaluation_id' => $evaluation->id,
                // Survey answers
                'q1' => (int)$this->request->getData('q1'),
                'q2' => (int)$this->request->getData('q2'),
                'q3' => (int)$this->request->getData('q3'),
                'q4' => (int)$this->request->getData('q4'),
                'q5' => (int)$this->request->getData('q5'),
                'q6' => (int)$this->request->getData('q6'),
                // Required fields
                'rating' => $feedbackScore,
                'comment' => $this->request->getData('comment'),
                'feedback_date' => date('Y-m-d H:i:s'),
            ];
            $feedback = $this->Feedbacks->patchEntity($feedback, $feedbackData);
            if (!$this->Feedbacks->save($feedback)) {
                debug($feedback->getErrors());
                debug($feedback);
                die('Feedback could not be saved.');
            }
            // Link Feedback to Evaluation
            $evaluation->feedback_id = $feedback->id;
            $this->Evaluations->save($evaluation);
            return $this->redirect(['action' => 'index','?' => ['submitted' => 1]]);
        }
    }
}

