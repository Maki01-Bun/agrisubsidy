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
        // =========================================================
        // ONLY ALLOW POST
        // =========================================================

        if (!$this->request->is('post')) {
            return $this->redirect(['action' => 'index']);
        }

        // =========================================================
        // LOAD MODELS
        // =========================================================

        $this->loadModel('Evaluations');
        $this->loadModel('Feedbacks');
        $this->loadModel('Farmers');
        $this->loadModel('Pests');
        $this->loadModel('Farms');

        // =========================================================
        // GET LOGGED-IN USER
        // =========================================================

        $user = $this->request->getSession()->read('Auth.User');

        if (empty($user) || empty($user['id'])) {

            $this->Flash->error('User session not found.');

            return $this->redirect(['action' => 'index']);
        }

        // =========================================================
        // FIND FARMER
        // =========================================================

        $farmer = $this->Farmers->find()
            ->where([
                'Farmers.user_id' => $user['id']
            ])
            ->first();

        if (!$farmer) {

            $this->Flash->error('Farmer record not found.');

            return $this->redirect(['action' => 'index']);
        }

        // =========================================================
        // GET FORM DATA
        // =========================================================

        $evaluationData = $this->request->getData();


        // =========================================================
        // PEST
        // =========================================================

        $pestIds = $evaluationData['pest'] ?? [];

        // Convert single value into array
        if (!is_array($pestIds)) {
            $pestIds = [$pestIds];
        }

        // Remove invalid / empty values
        $pestIds = array_filter($pestIds, function ($id) {

            return $id !== null
                && $id !== ''
                && is_numeric($id)
                && (int)$id > 0;
        });

        // Convert IDs to integers
        $pestIds = array_map('intval', $pestIds);

        // Remove duplicates and reset indexes
        $pestIds = array_values(array_unique($pestIds));

        $pestNames = [];

        // This will be the ONE pest_id saved in evaluations
        $pestId = null;


        // =========================================================
        // VALIDATE PEST IDS AGAINST DATABASE
        // =========================================================

        if (!empty($pestIds)) {

            $pests = $this->Pests->find()
                ->where([
                    'Pests.id IN' => $pestIds
                ])
                ->all();

            foreach ($pests as $pest) {

                $pestNames[] = $pest->pest_name;
            }

            // Get valid database IDs
            $validPestIds = [];

            foreach ($pests as $pest) {

                $validPestIds[] = (int)$pest->id;
            }

            /*
            * evaluations.pest_id is only ONE foreign key.
            *
            * Therefore we save the first valid pest ID.
            */
            if (!empty($validPestIds)) {

                $pestId = $validPestIds[0];
            }
        }

        // String sent to FastAPI
        $pestString = !empty($pestNames)
            ? implode(', ', $pestNames)
            : 'None';


        $calamity = $evaluationData['calamity'] ?? [];

if (is_array($calamity)) {
    $calamity = array_filter($calamity, function ($value) {
        return $value !== null && $value !== '';
    });

    $calamity = implode(', ', $calamity);
}

if (empty($calamity)) {
    $calamity = 'None';
}


        // =========================================================
        // FEEDBACK SCORE
        // =========================================================

        $q1 = (int)($evaluationData['q1'] ?? 0);
        $q2 = (int)($evaluationData['q2'] ?? 0);
        $q3 = (int)($evaluationData['q3'] ?? 0);
        $q4 = (int)($evaluationData['q4'] ?? 0);
        $q5 = (int)($evaluationData['q5'] ?? 0);
        $q6 = (int)($evaluationData['q6'] ?? 0);

        $feedbackScore = (
            $q1 +
            $q2 +
            $q3 +
            $q4 +
            $q5 +
            $q6
        ) / 6;


        // =========================================================
        // FARM
        // =========================================================

        $farmId = $evaluationData['farm_id'] ?? null;

        if (empty($farmId)) {

            $this->Flash->error('Please select a farm.');

            return $this->redirect($this->referer());
        }

        $farm = $this->Farms->find()
            ->where([
                'Farms.id' => $farmId
            ])
            ->first();

        if (!$farm) {

            $this->Flash->error('Selected farm not found.');

            return $this->redirect($this->referer());
        }


        // =========================================================
        // FARM VALUES
        // =========================================================

        $farmSize = (float)$farm->farm_size;

        $cropYieldBefore = (float)$farm->crop_yield;

        $cropYieldAfter = (float)(
            $evaluationData['crop_yield_after'] ?? 0
        );


        // =========================================================
        // FASTAPI PREDICTION
        // =========================================================

        $http = new \Cake\Http\Client();

        $payload = [

            'subsidy_type' => $evaluationData['subsidy_type'] ?? '',

            'farm_size' => $farmSize,

            'crop_yield_before' => $cropYieldBefore,

            'crop_yield_after' => $cropYieldAfter,

            'feedback_score' => $feedbackScore,

            'pest' => $pestString,

            'calamity' => $calamity
        ];


        try {

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

            if (!is_array($result)) {

                $result = [];
            }

        } catch (\Exception $e) {

            $this->Flash->error(
                'Prediction server error: ' . $e->getMessage()
            );

            return $this->redirect($this->referer());
        }


        // =========================================================
        // PREPARE EVALUATION DATA
        // =========================================================

        $evaluationData['farmer_id'] = $farmer->id;

        $evaluationData['farm_id'] = $farm->id;

        /*
        * IMPORTANT:
        *
        * This is a VALID ID from pests.id.
        */
        $evaluationData['pest_id'] = $pestId;

        $evaluationData['farm_size'] = $farmSize;

        $evaluationData['crop_yield_before'] = $cropYieldBefore;

        $evaluationData['calamity'] = $calamity;

        /*
        * Remove pest[] because the database field
        * is pest_id, not pest.
        */
        unset($evaluationData['pest']);


        // =========================================================
        // CREATE EVALUATION
        // =========================================================

        $evaluation = $this->Evaluations->newEmptyEntity();

        $evaluation = $this->Evaluations->patchEntity(
            $evaluation,
            $evaluationData
        );


        // =========================================================
        // SET EVALUATION VALUES
        // =========================================================

        $evaluation->farmer_id = $farmer->id;

        $evaluation->farm_id = $farm->id;

        $evaluation->pest_id = $pestId;

        $evaluation->farm_size = $farmSize;

        $evaluation->crop_yield_before = $cropYieldBefore;

        $evaluation->feedback_score = $feedbackScore;

        $evaluation->effectiveness_label =
            $result['effectiveness'] ?? 'Not Predicted';


        // =========================================================
        // SAVE EVALUATION
        // =========================================================

        if (!$this->Evaluations->save($evaluation)) {

            debug($evaluation->getErrors());
            debug($evaluation);

            die('Evaluation could not be saved.');
        }


        // =========================================================
        // CREATE FEEDBACK
        // =========================================================

        $feedback = $this->Feedbacks->newEmptyEntity();


        // =========================================================
        // FEEDBACK DATA
        // =========================================================

        $feedbackData = [

            /*
            * IMPORTANT FIX:
            *
            * feedbacks.farmer_id
            * references
            * farmers.id
            *
            * Therefore use:
            */
            'farmer_id' => $farmer->id,

            /*
            * Link feedback to the evaluation.
            */
            'evaluation_id' => $evaluation->id,

            'q1' => $q1,

            'q2' => $q2,

            'q3' => $q3,

            'q4' => $q4,

            'q5' => $q5,

            'q6' => $q6,

            'rating' => $feedbackScore,

            'comment' => $evaluationData['comment'] ?? null,

            'feedback_date' => date('Y-m-d H:i:s')
        ];


        // =========================================================
        // PATCH FEEDBACK
        // =========================================================

        $feedback = $this->Feedbacks->patchEntity(
            $feedback,
            $feedbackData
        );


    // =========================================================
    // SAVE FEEDBACK
    // =========================================================

    $feedback = $this->Feedbacks->newEmptyEntity();

    $feedbackData = [
        'farmer_id' => $farmer->id,
        'evaluation_id' => $evaluation->id,

        'q1' => $q1,
        'q2' => $q2,
        'q3' => $q3,
        'q4' => $q4,
        'q5' => $q5,
        'q6' => $q6,

        'rating' => $feedbackScore,
        'comment' => $evaluationData['comment'] ?? null,
        'feedback_date' => date('Y-m-d H:i:s')
    ];

    $feedback = $this->Feedbacks->patchEntity(
        $feedback,
        $feedbackData
    );

    if (!$this->Feedbacks->save($feedback)) {

        debug($feedback->getErrors());
        debug($feedback);

        die('Feedback could not be saved.');
    }


    // =========================================================
    // GET NEW FEEDBACK ID
    // =========================================================

    $feedbackId = $feedback->id;

    if (empty($feedbackId)) {

        die('Feedback was saved but no feedback ID was generated.');
    }


    // =========================================================
    // UPDATE EVALUATION WITH FEEDBACK ID
    // =========================================================

    $evaluation = $this->Evaluations->get($evaluation->id);

    $evaluation->feedback_id = (int)$feedbackId;

    if (!$this->Evaluations->save($evaluation)) {

        debug($evaluation->getErrors());
        debug($evaluation);

        die('Evaluation could not be updated with feedback_id.');
    }


    // =========================================================
    // SUCCESS
    // =========================================================

    $this->Flash->success(
        'Evaluation and feedback submitted successfully.'
    );

    return $this->redirect([
        'action' => 'index',
        '?' => [
            'submitted' => 1
        ]
    ]);
    }

}

