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
    $this->loadModel('Farms');

    // Logged-in user
    $user = $this->request->getSession()->read('Auth.User');

    // Load pests
    $pests = $this->Pests->find('list', [
        'keyField' => 'id',
        'valueField' => 'pest_name'
    ])->toArray();

    // Default values
    $farms = [];
    $farmerName = '';

    // Get logged-in farmer
    $farmer = $this->Farmers->find()
        ->where(['user_id' => $user['id']])
        ->first();

    if ($farmer) {

        $farmerName = trim(
            $farmer->first_name . ' ' .
            $farmer->middle_name . ' ' .
            $farmer->last_name
        );

        // Only this farmer's farms
        $farms = $this->Farms->find('list', [
            'keyField' => 'id',
            'valueField' => 'farm_name'
        ])
        ->where([
            'farmer_id' => $farmer->id
        ])
        ->toArray();
    }

    $this->set(compact(
        'pests',
        'farms',
        'farmerName'
    ));
}
    public function survey()
    {
            if ($this->request->is('post')) {

                $this->loadModel('Evaluations');    
                $this->loadModel('Feedbacks');
                $this->loadModel('Farmers');
                $this->loadModel('Pests');
                $this->loadModel('Farms');

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

           $farm = $this->Farms->find()
                ->where(['id' => $this->request->getData('farm_id')])->first();
            if (!$farm) {
                $this->Flash->error('Selected farm not found.');
                return $this->redirect($this->referer());
            }
            $farmSize = $farm->farm_size;
            $cropYieldBefore = (float)$farm->crop_yield;
            // Call FastAPI
            $http = new \Cake\Http\Client();
            $payload = [
                'subsidy_type'      => $evaluationData['subsidy_type'],
                'farm_size'         => (float)$farmSize,
                'crop_yield_before' => (float)$cropYieldBefore,
                'crop_yield_after'  => (float)$evaluationData['crop_yield_after'],
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

            // Save first pest ID (because your FK only accepts one pest_id)
            $evaluationData['pest_id'] = !empty($pestIds) ? $pestIds[0] : null;
            $evaluationData['farm_id'] = $farm->id;

            // Automatically save the "before" values from the selected farm
            $evaluationData['farm_size'] = $farmSize;
            $evaluationData['crop_yield_before'] = $cropYieldBefore;

            unset($evaluationData['pest']);

            $evaluation = $this->Evaluations->newEmptyEntity();

            $evaluation = $this->Evaluations->patchEntity(
                $evaluation,
                $evaluationData
            );


            $evaluation->farmer_id = $farmer->id;
            $evaluation->feedback_score = $feedbackScore;
            $evaluation->effectiveness_label = $result['effectiveness'] ?? 'Not Predicted';
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

