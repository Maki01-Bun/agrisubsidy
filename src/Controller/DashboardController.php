<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * Dashboard Controller
 *
 * @method \App\Model\Entity\Dashboard[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class DashboardController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
   public function index()
{
    // =========================================================
    // LOAD MODELS
    // =========================================================

    $this->loadModel('Feedbacks');
    $this->loadModel('Farmers');
    $this->loadModel('Evaluations');
    $this->loadModel('Notifications');
    $this->loadModel('Records');
    $this->loadModel('Schedules');
    $this->loadModel('Users');


    // =========================================================
    // EFFECTIVENESS DATA
    // =========================================================

    $effectivenessData = $this->Evaluations->find()
        ->select([
            'effectiveness_label',
            'total' => $this->Evaluations
                ->find()
                ->func()
                ->count('*')
        ])
        ->group([
            'effectiveness_label'
        ])
        ->toArray();

    $labels = [];
    $totals = [];

    foreach ($effectivenessData as $row) {

        // Skip evaluations that have not yet been evaluated
        if ($row->effectiveness_label === null) {
            continue;
        }

        $labels[] = $row->effectiveness_label;
        $totals[] = $row->total;
    }


    // =========================================================
    // FEEDBACK STATISTICS
    //
    // feedbacks.rating was removed.
    //
    // Q1-Q10 are stored inside feedbacks.answer as JSON.
    //
    // Each feedback submission gets ONE average score.
    // =========================================================

    $avgRating = 0;

    $positive = 0;
    $neutral = 0;
    $negative = 0;

    $totalFeedbackScore = 0;
    $totalFeedbacksWithAnswers = 0;


    // =========================================================
    // GET FEEDBACK RECORDS THAT CONTAIN ANSWERS
    // =========================================================

    $feedbacks = $this->Feedbacks->find()
        ->select([
            'id',
            'answer'
        ])
        ->where([
            'answer IS NOT' => null
        ])
        ->all();


    foreach ($feedbacks as $feedback) {

        // =====================================================
        // DECODE Q1-Q10 JSON
        // =====================================================

        $answers = json_decode(
            (string)$feedback->answer,
            true
        );


        // Skip invalid JSON
        if (!is_array($answers)) {
            continue;
        }


        // =====================================================
        // COLLECT VALID Q1-Q10 ANSWERS
        // =====================================================

        $feedbackScores = [];

        foreach ($answers as $key => $value) {

            // Only accept q1 through q10
            if (!preg_match(
                '/^q([1-9]|10)$/i',
                (string)$key
            )) {
                continue;
            }


            $score = (int)$value;


            // Only accept valid survey scores 1-5
            if ($score < 1 || $score > 5) {
                continue;
            }


            $feedbackScores[] = $score;
        }


        // =====================================================
        // SKIP FEEDBACK WITH NO VALID ANSWERS
        // =====================================================

        if (empty($feedbackScores)) {
            continue;
        }


        // =====================================================
        // CALCULATE THIS FEEDBACK'S AVERAGE
        // =====================================================

        $feedbackAverage =
            array_sum($feedbackScores)
            / count($feedbackScores);


        // Add this feedback's average to overall average
        $totalFeedbackScore += $feedbackAverage;

        $totalFeedbacksWithAnswers++;


        // =====================================================
        // CLASSIFY THIS FEEDBACK
        // =====================================================

        if ($feedbackAverage >= 4) {

            // Positive
            $positive++;

        } elseif ($feedbackAverage >= 3) {

            // Neutral
            $neutral++;

        } else {

            // Negative
            $negative++;
        }
    }


    // =========================================================
    // OVERALL AVERAGE
    // =========================================================

    if ($totalFeedbacksWithAnswers > 0) {

        $avgRating =
            $totalFeedbackScore
            / $totalFeedbacksWithAnswers;
    }


    // =========================================================
    // TOTAL BENEFICIARIES
    // =========================================================

    $totalBeneficiaries = $this->Farmers
        ->find()
        ->count();


    // =========================================================
    // REGISTERED FARMERS
    // =========================================================

    $totalFarmers = $this->Users
        ->find()
        ->where([
            'role' => 'Farmer'
        ])
        ->count();


    // =========================================================
    // HISTORY RECORDS / DASHBOARD CARDS
    // =========================================================

    // =========================================================
    // SEED SUBSIDY DISTRIBUTED
    // =========================================================

    $subsidyDistributed = $this->Records
        ->find()
        ->where([
            'status' => 'Received'
        ])
        ->count();


    // =========================================================
    // RE-SCHEDULED
    // =========================================================

    $rescheduled = $this->Schedules
        ->find()
        ->where([
            'Schedules.status' => 'Re-Scheduled'
        ])
        ->count();


    // =========================================================
    // CANCELLED
    // =========================================================

    $cancelled = $this->Schedules
        ->find()
        ->where([
            'Schedules.status' => 'Cancelled'
        ])
        ->count();


    // =========================================================
    // NOTIFICATIONS
    // =========================================================
    //
    // Only pending notifications are displayed.
    //
    // Registration notification messages are rebuilt from
    // the registration JSON data so the Dashboard always
    // displays the correct farmer name and LGU-RSBSA number.
    //
    // =========================================================

    $notifications = $this->Notifications
        ->find()
        ->where([
            'status' => 'Pending'
        ])
        ->order([
            'id' => 'DESC'
        ])
        ->all();


    // =========================================================
    // FIX REGISTRATION REQUEST MESSAGES
    // =========================================================

    foreach ($notifications as $notification) {

        // -----------------------------------------------------
        // ONLY REGISTRATION NOTIFICATIONS
        // -----------------------------------------------------

        if (
            strtolower(
                trim(
                    (string)$notification->type
                )
            ) !== 'registration'
        ) {
            continue;
        }


        // -----------------------------------------------------
        // DECODE REGISTRATION DATA
        // -----------------------------------------------------

        $data = json_decode(
            (string)$notification->data,
            true
        );


        // -----------------------------------------------------
        // INVALID DATA
        // -----------------------------------------------------

        if (!is_array($data)) {
            continue;
        }


        // -----------------------------------------------------
        // GET FARMER DATA
        // -----------------------------------------------------

        $farmerData = $data['farmer'] ?? [];


        // -----------------------------------------------------
        // GET FIRST NAME
        // -----------------------------------------------------

        $firstName = trim(
            (string)(
                $farmerData['first_name'] ?? ''
            )
        );


        // -----------------------------------------------------
        // GET MIDDLE NAME
        // -----------------------------------------------------

        $middleName = trim(
            (string)(
                $farmerData['middle_name'] ?? ''
            )
        );


        // -----------------------------------------------------
        // GET LAST NAME
        // -----------------------------------------------------

        $lastName = trim(
            (string)(
                $farmerData['last_name'] ?? ''
            )
        );


        // -----------------------------------------------------
        // BUILD FULL NAME
        // -----------------------------------------------------

        $fullName = trim(
            $firstName . ' ' .
            $middleName . ' ' .
            $lastName
        );


        // -----------------------------------------------------
        // FALLBACK NAME
        // -----------------------------------------------------

        if ($fullName === '') {
            $fullName = 'a farmer';
        }


        // -----------------------------------------------------
        // GET LGU-RSBSA NUMBER
        // -----------------------------------------------------

        $farmerNo = trim(
            (string)(
                $farmerData['farmer_no'] ?? ''
            )
        );


        // -----------------------------------------------------
        // CREATE DISPLAY MESSAGE
        // -----------------------------------------------------

        if ($farmerNo !== '') {

            $notification->message =
                'New farmer registration request with LGU-RSBSA number ' .
                $farmerNo .
                ' and requires approval.';

        } else {

            $notification->message =
                'A new farmer registration request from ' .
                $fullName .
                ' requires approval.';
        }
    }


    // =========================================================
    // SEND DATA TO DASHBOARD VIEW
    // =========================================================

    $this->set([

        // -----------------------------------------------------
        // EFFECTIVENESS
        // -----------------------------------------------------

        'labels' => $labels,
        'totals' => $totals,


        // -----------------------------------------------------
        // FEEDBACK
        // -----------------------------------------------------

        'avgRating' => $avgRating,
        'positive' => $positive,
        'neutral' => $neutral,
        'negative' => $negative,


        // -----------------------------------------------------
        // FARMERS
        // -----------------------------------------------------

        'totalFarmers' => $totalFarmers,
        'totalBeneficiaries' => $totalBeneficiaries,


        // -----------------------------------------------------
        // HISTORY
        // -----------------------------------------------------

        'subsidyDistributed' => $subsidyDistributed,
        'rescheduled' => $rescheduled,
        'cancelled' => $cancelled,


        // -----------------------------------------------------
        // NOTIFICATIONS
        // -----------------------------------------------------

        'notifications' => $notifications
    ]);
}
    /**
     * View method
     *
     * @param string|null $id Dashboard id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $sample = $this->Sample->get($id, [
            'contain' => [],
        ]);

        $this->set(compact('sample'));
    }
    
    public function viewRegistration($id)
{
    $this->loadModel('Notifications');

    $notifications = $this->Notifications->get($id);

    $data = json_decode($notifications->data, true);

    $this->set(compact(
        'notifications',
        'data'
    ));
}
}
