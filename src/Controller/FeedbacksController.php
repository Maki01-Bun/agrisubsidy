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
    $this->loadModel('Farms');
    $this->loadModel('Schedules');

    // =========================================================
    // GET LOGGED-IN USER
    // =========================================================

    $user = $this->request
        ->getSession()
        ->read('Auth.User');

    $farms = [];
    $farmSizes = [];
    $schedules = [];
    $farmerName = '';

    // =========================================================
    // CHECK USER
    // =========================================================

    if (
        empty($user) ||
        empty($user['id'])
    ) {
        $this->Flash->error('User session not found.');

        return $this->redirect([
            'action' => 'index'
        ]);
    }

    // =========================================================
    // FIND FARMER
    // =========================================================

    $farmer = $this->Farmers->find()
        ->where([
            'Farmers.user_id' => $user['id']
        ])
        ->first();

    if ($farmer) {

        // =====================================================
        // FARMER NAME
        // =====================================================

        $farmerName = trim(
            ($farmer->first_name ?? '') . ' ' .
            ($farmer->middle_name ?? '') . ' ' .
            ($farmer->last_name ?? '')
        );

        // =====================================================
        // GET FARM SIZES
        // =====================================================

        $farmsData = $this->Farms->find()
            ->select([
                'id',
                'farm_size'
            ])
            ->where([
                'Farms.farmer_id' => $farmer->id
            ])
            ->order([
                'Farms.id' => 'ASC'
            ])
            ->all();

        // =====================================================
        // FARM DROPDOWN
        // =====================================================

        $farmNumber = 1;
        foreach ($farmsData as $farm) {
            // Get farm size
            $farmSize = (float)$farm->farm_size;
            // Dropdown label
            $farms[$farm->id] =
                'Farm ' . $farmNumber . ': ' .
                number_format($farmSize, 2) . ' ha';
            // Keep actual farm size for calculation
            $farmSizes[$farm->id] = $farmSize;
            $farmNumber++;
        }
    }

    // =========================================================
    // SCHEDULE LIST
    // =========================================================

    $schedules = $this->Schedules->find('list', [
        'keyField' => 'id',
        'valueField' => 'program_code'
    ])
    ->order([
        'Schedules.start_date' => 'ASC'
    ])
    ->toArray();

    // =========================================================
    // RICE TYPE
    // =========================================================

    $rice_type = [
        0 => 'Inbred',
        1 => 'Hybrid'
    ];

    // =========================================================
    // SEND TO VIEW
    // =========================================================

    $this->set(compact(
        'farms',
        'farmSizes',
        'farmerName',
        'schedules',
        'rice_type'
    ));
}

    public function survey()
    {
        if (!$this->request->is('post')) {
            return $this->redirect([
                'action' => 'index'
            ]);
        }

        // =========================================================
        // LOAD MODELS
        // =========================================================

        $this->loadModel('Evaluations');
        $this->loadModel('Feedbacks');
        $this->loadModel('Farmers');
        $this->loadModel('Farms');

        // =========================================================
        // GET LOGGED-IN USER
        // =========================================================

        $user = $this->request
            ->getSession()
            ->read('Auth.User');

        if (
            empty($user) ||
            empty($user['id'])
        ) {
            $this->Flash->error(
                'User session not found.'
            );

            return $this->redirect([
                'action' => 'index'
            ]);
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
            $this->Flash->error(
                'Farmer record not found.'
            );

            return $this->redirect([
                'action' => 'index'
            ]);
        }

        // =========================================================
        // GET FORM DATA
        // =========================================================

        $evaluationData = $this->request->getData();

        // =========================================================
        // FEEDBACK QUESTIONS
        // =========================================================

        $q1 = (int)(
            $evaluationData['q1'] ?? 0
        );

        $q2 = (int)(
            $evaluationData['q2'] ?? 0
        );

        $q3 = (int)(
            $evaluationData['q3'] ?? 0
        );

        $q4 = (int)(
            $evaluationData['q4'] ?? 0
        );

        $q5 = (int)(
            $evaluationData['q5'] ?? 0
        );

        $q6 = (int)(
            $evaluationData['q6'] ?? 0
        );

        // =========================================================
        // FEEDBACK SCORE
        // =========================================================

        $feedbackScore = (
            $q1 +
            $q2 +
            $q3 +
            $q4 +
            $q5 +
            $q6
        ) / 6;

        // =========================================================
        // SELLING PRICE
        // =========================================================

        $sellingPrice = (float)(
            $evaluationData['selling_price'] ?? 0
        );

        // =========================================================
        // FARM
        // =========================================================

        $farmId =
            $evaluationData['farm_id']
            ?? null;

        if (empty($farmId)) {
            $this->Flash->error(
                'Please select a farm.'
            );

            return $this->redirect(
                $this->referer()
            );
        }

        // =========================================================
        // FIND FARM
        // =========================================================

        $farm = $this->Farms->find()
            ->where([
                'Farms.id' => $farmId
            ])
            ->first();

        if (!$farm) {
            $this->Flash->error(
                'Selected farm not found.'
            );

            return $this->redirect(
                $this->referer()
            );
        }

        // =========================================================
        // FARM VALUES
        // =========================================================

        $farmSize = (float)(
            $farm->farm_size ?? 0
        );

        $averageYield = (float)(
            $farm->average_yield ?? 0
        );

        // =========================================================
        // CROP YIELD AFTER
        // =========================================================

        $cropYieldAfter = (float)(
            $evaluationData['crop_yield_after']
            ?? 0
        );

        // =========================================================
        // SUBSIDY RECEIVED
        // =========================================================
        //
        // YES = 1
        // NO  = 0
        //
        // This numeric value is what the ML model receives.
        //
        // =========================================================

        $subsidyInput =
            $evaluationData['subsidy_received']
            ?? 'No';

        if (is_string($subsidyInput)) {

            $normalizedSubsidy =
                strtolower(trim($subsidyInput));

            if ($normalizedSubsidy === 'yes') {

                $subsidyReceived = 1;

            } elseif ($normalizedSubsidy === '1') {

                $subsidyReceived = 1;

            } else {

                $subsidyReceived = 0;
            }

        } else {

            $subsidyReceived =
                ((int)$subsidyInput === 1)
                ? 1
                : 0;
        }

        // =========================================================
        // FASTAPI PREDICTION
        // =========================================================

        $http = new \Cake\Http\Client();

        // =========================================================
        // MACHINE LEARNING PAYLOAD
        // =========================================================

        $payload = [

            'farm_size' =>
                $farmSize,

            'average_yield' =>
                $averageYield,

            'crop_yield_after' =>
                $cropYieldAfter,

            'selling_price' =>
                $sellingPrice,

            'subsidy_received' =>
                $subsidyReceived,

            'feedback_score' =>
                $feedbackScore,
        ];

        // =========================================================
        // SEND TO FASTAPI
        // =========================================================

        try {

            $response = $http->post(
                'http://127.0.0.1:8000/predict',
                json_encode($payload),
                [
                    'headers' => [
                        'Content-Type' =>
                            'application/json'
                    ]
                ]
            );

            // -----------------------------------------------------
            // CHECK HTTP STATUS
            // -----------------------------------------------------

            if (!$response->isOk()) {

                $this->Flash->error(
                    'Prediction server returned HTTP status ' .
                    $response->getStatusCode()
                );

                return $this->redirect(
                    $this->referer()
                );
            }

            // -----------------------------------------------------
            // GET JSON RESULT
            // -----------------------------------------------------

            $result =
                $response->getJson();

            if (!is_array($result)) {

                $this->Flash->error(
                    'Invalid prediction response from ML server.'
                );

                return $this->redirect(
                    $this->referer()
                );
            }

            // -----------------------------------------------------
            // CHECK FASTAPI SUCCESS
            // -----------------------------------------------------

            if (
                isset($result['success']) &&
                $result['success'] === false
            ) {

                $this->Flash->error(
                    'ML prediction failed: ' .
                    ($result['error'] ?? 'Unknown error.')
                );

                return $this->redirect(
                    $this->referer()
                );
            }

        } catch (\Exception $e) {

            $this->Flash->error(
                'Prediction server error: ' .
                $e->getMessage()
            );

            return $this->redirect(
                $this->referer()
            );
        }

        // =========================================================
        // GET ML PREDICTION CODE
        // =========================================================
        //
        // 0 = Not Effective
        // 1 = Moderately Effective
        // 2 = Effective
        //
        // =========================================================

        $prediction =
            $result['effectiveness_code']
            ?? null;

        // =========================================================
        // CONVERT ML CODE TO READABLE LABEL
        // =========================================================

        $labelMap = [

            0 => 'Not Effective',

            1 => 'Moderately Effective',

            2 => 'Effective',

        ];

        if ($prediction === null) {

            $effectivenessLabel =
                'Not Predicted';

        } else {

            $prediction =
                (int)$prediction;

            $effectivenessLabel =
                $labelMap[$prediction]
                ?? 'Not Predicted';
        }

        // =========================================================
        // PREPARE EVALUATION DATA
        // =========================================================

        $evaluationData['farmer_id'] =
            $farmer->id;

        $evaluationData['farm_id'] =
            $farm->id;

        $evaluationData['farm_size'] =
            $farmSize;

        $evaluationData['average_yield'] =
            $averageYield;

        $evaluationData['crop_yield_after'] =
            $cropYieldAfter;

        $evaluationData['selling_price'] =
            $sellingPrice;

        // IMPORTANT:
        // Store numeric 1/0 in database

        $evaluationData['subsidy_received'] =
            $subsidyReceived;

        $evaluationData['feedback_score'] =
            $feedbackScore;

        // IMPORTANT:
        // Store readable word in database

        $evaluationData['effectiveness_label'] =
            $effectivenessLabel;

        // =========================================================
        // REMOVE PEST ARRAY
        // =========================================================
        //
        // The database field is pest_id,
        // not pest.
        //
        // =========================================================

        unset(
            $evaluationData['pest']
        );

        // =========================================================
        // CREATE EVALUATION
        // =========================================================

        $evaluation =
            $this->Evaluations->newEmptyEntity();

        $evaluation =
            $this->Evaluations->patchEntity(
                $evaluation,
                $evaluationData
            );

        // =========================================================
        // SET EVALUATION VALUES
        // =========================================================

        $evaluation->farmer_id =
            $farmer->id;

        $evaluation->farm_id =
            $farm->id;

        $evaluation->farm_size =
            $farmSize;

        $evaluation->average_yield =
            $averageYield;

        $evaluation->crop_yield_after =
            $cropYieldAfter;

        $evaluation->selling_price =
            $sellingPrice;

        // Store 1 or 0

        $evaluation->subsidy_received =
            $subsidyReceived;

        $evaluation->feedback_score =
            $feedbackScore;

        // Store readable label

        $evaluation->effectiveness_label =
            $effectivenessLabel;

        // =========================================================
        // SAVE EVALUATION
        // =========================================================

        if (
            !$this->Evaluations->save(
                $evaluation
            )
        ) {

            debug(
                $evaluation->getErrors()
            );

            debug($evaluation);

            die(
                'Evaluation could not be saved.'
            );
        }

        // =========================================================
        // CREATE FEEDBACK
        // =========================================================

        $feedback =
            $this->Feedbacks->newEmptyEntity();

        // =========================================================
        // FEEDBACK DATA
        // =========================================================

        $feedbackData = [

            // -----------------------------------------------------
            // Farmer
            // -----------------------------------------------------

            'farmer_id' =>
                $farmer->id,

            // -----------------------------------------------------
            // Evaluation
            // -----------------------------------------------------

            'evaluation_id' =>
                $evaluation->id,

            // -----------------------------------------------------
            // Survey Questions
            // -----------------------------------------------------

            'q1' =>
                $q1,

            'q2' =>
                $q2,

            'q3' =>
                $q3,

            'q4' =>
                $q4,

            'q5' =>
                $q5,

            'q6' =>
                $q6,

            // -----------------------------------------------------
            // Average Feedback Rating
            // -----------------------------------------------------

            'rating' =>
                $feedbackScore,

            // -----------------------------------------------------
            // Comment
            // -----------------------------------------------------

            'comment' =>
                $evaluationData['comment']
                ?? null,

            // -----------------------------------------------------
            // Feedback Date
            // -----------------------------------------------------

            'feedback_date' =>
                date('Y-m-d H:i:s')
        ];

        // =========================================================
        // PATCH FEEDBACK
        // =========================================================

        $feedback =
            $this->Feedbacks->patchEntity(
                $feedback,
                $feedbackData
            );

        // =========================================================
        // SAVE FEEDBACK
        // =========================================================

        if (
            !$this->Feedbacks->save(
                $feedback
            )
        ) {

            debug(
                $feedback->getErrors()
            );

            debug($feedback);

            die(
                'Feedback could not be saved.'
            );
        }

        // =========================================================
        // GET NEW FEEDBACK ID
        // =========================================================

        $feedbackId =
            $feedback->id;

        if (empty($feedbackId)) {

            die(
                'Feedback was saved but no feedback ID was generated.'
            );
        }

        // =========================================================
        // UPDATE EVALUATION WITH FEEDBACK ID
        // =========================================================

        $evaluation =
            $this->Evaluations->get(
                $evaluation->id
            );

        $evaluation->feedback_id =
            (int)$feedbackId;

        if (
            !$this->Evaluations->save(
                $evaluation
            )
        ) {

            debug(
                $evaluation->getErrors()
            );

            debug($evaluation);

            die(
                'Evaluation could not be updated with feedback_id.'
            );
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

