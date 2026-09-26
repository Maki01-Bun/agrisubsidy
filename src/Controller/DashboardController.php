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
            'total' => $this->Evaluations->find()->func()->count('*')
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


    // Get feedback records that contain answers
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

        // Decode Q1-Q10 JSON
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


        // Add this feedback's average to the overall average
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

    // Seed Subsidy Distributed
    $subsidyDistributed = $this->Records
        ->find()
        ->where([
            'status' => 'Received'
        ])
        ->count();


    // Re-Scheduled
    $rescheduled = $this->Schedules
        ->find()
        ->where([
            'Schedules.status' => 'Re-Scheduled'
        ])
        ->count();


    // Cancelled
    $cancelled = $this->Schedules
        ->find()
        ->where([
            'Schedules.status' => 'Cancelled'
        ])
        ->count();


    // =========================================================
    // NOTIFICATIONS
    // =========================================================

    $notifications = $this->Notifications
        ->find()
        ->where([
            'status' => 'Pending'
        ])
        ->all();


    // =========================================================
    // SEND DATA TO DASHBOARD VIEW
    // =========================================================

    $this->set([
        'labels' => $labels,
        'totals' => $totals,

        'avgRating' => $avgRating,
        'positive' => $positive,
        'neutral' => $neutral,
        'negative' => $negative,

        'totalFarmers' => $totalFarmers,
        'totalBeneficiaries' => $totalBeneficiaries,

        // History Records
        'subsidyDistributed' => $subsidyDistributed,
        'rescheduled' => $rescheduled,
        'cancelled' => $cancelled,

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
