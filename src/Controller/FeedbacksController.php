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
    $this->loadModel('Feedbacks');

    // Logged-in user
    $user = $this->request->getSession()->read('Auth.User');

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

     $schedules = $this->Feedbacks->Schedules
        ->find()
        ->all();


    $this->set(compact(
        'farms',
        'farmerName',
        'schedules'
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

        $evaluationData =
            $this->request->getData();

        // =========================================================
        // FEEDBACK SCORE
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
        //
        // IMPORTANT:
        //
        // selling_price is now one of the ML parameters.
        //
        // It comes from the survey form:
        //
        // name="selling_price"
        //
        // Example:
        //
        // <input
        //     type="number"
        //     name="selling_price"
        // >
        //
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
        // FASTAPI PREDICTION
        // =========================================================

        $http = new \Cake\Http\Client();

        /*
        * =========================================================
        * MACHINE LEARNING PAYLOAD
        * =========================================================
        *
        * These values must match the parameters expected by
        * your FastAPI /predict endpoint and ML model.
        */
        $payload = [

            /*
            * Subsidy type
            */
            'subsidy_type' =>
                $evaluationData['subsidy_type']
                ?? '',

            /*
            * Farm size
            */
            'farm_size' =>
                $farmSize,

            /*
            * Average yield BEFORE subsidy
            */
            'average_yield' =>
                $averageYield,

            /*
            * Crop yield AFTER subsidy
            */
            'crop_yield_after' =>
                $cropYieldAfter,

            /*
            * SELLING PRICE
            *
            * This is the newly added ML parameter.
            */
            'selling_price' =>
                $sellingPrice,

            /*
            * Feedback score
            */
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

            $result =
                $response->getJson();

            if (!is_array($result)) {

                $result = [];
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

        /*
        * SAVE SELLING PRICE
        */
        $evaluationData['selling_price'] =
            $sellingPrice;

        /*
        * SAVE FEEDBACK SCORE
        */
        $evaluationData['feedback_score'] =
            $feedbackScore;

        /*
        * Remove pest[]
        *
        * The database field is pest_id,
        * not pest.
        */
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

        /*
        * NEW:
        *
        * Save selling price to Evaluations.
        */
        $evaluation->selling_price =
            $sellingPrice;

        /*
        * Save feedback score.
        */
        $evaluation->feedback_score =
            $feedbackScore;

        /*
        * Save ML prediction.
        */
        $evaluation->effectiveness_label =
            $result['effectiveness']
            ?? 'Not Predicted';

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

            /*
            * Farmer
            */
            'farmer_id' =>
                $farmer->id,

            /*
            * Evaluation
            */
            'evaluation_id' =>
                $evaluation->id,

            /*
            * Survey questions
            */
            'q1' => $q1,

            'q2' => $q2,

            'q3' => $q3,

            'q4' => $q4,

            'q5' => $q5,

            'q6' => $q6,

            /*
            * Average feedback rating
            */
            'rating' =>
                $feedbackScore,

            /*
            * Comment
            */
            'comment' =>
                $evaluationData['comment']
                ?? null,

            /*
            * Feedback date
            */
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

