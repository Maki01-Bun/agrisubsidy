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
    $this->loadModel('Feedbacks');
    $this->loadModel('Farmers');
    $this->loadModel('Evaluations');
    $this->loadModel('Notifications');
    $this->loadModel('Records');
    $this->loadModel('Users');

    // EFFECTIVENESS DATA
    $effectivenessData = $this->Evaluations->find()
        ->select([
            'effectiveness_label',
            'total' => $this->Evaluations->find()->func()->count('*')
        ])
        ->group(['effectiveness_label'])
        ->toArray();

    $labels = [];
    $totals = [];

    foreach ($effectivenessData as $row) {
        $labels[] = $row->effectiveness_label;
        $totals[] = $row->total;
    }

    // FEEDBACK STATISTICS
    $avgRatingData = $this->Feedbacks->find()
        ->select([
            'avg_rating' => $this->Feedbacks->find()->func()->avg('rating')
        ])
        ->first();

    $avgRating = $avgRatingData->avg_rating ?? 0;

    $positive = $this->Feedbacks->find()
        ->where(['rating >=' => 4])
        ->count();

    $neutral = $this->Feedbacks->find()
        ->where(['rating' => 3])
        ->count();

    $negative = $this->Feedbacks->find()
        ->where(['rating <=' => 2])
        ->count();

    // Total Beneficiaries
    $totalBeneficiaries = $this->Farmers->find()->count();
    
    // Registered Farmers
    $totalFarmers = $this->Users->find()
        ->where(['role' => 'Farmer'])
        ->count();

    // HISTORY RECORDS / DASHBOARD CARDS

    // Fertilizer Distributed
    $fertilizerDistributed = $this->Records->find()
        ->where([
            'subsidy_item' => 'Fertilizer',
            'status' => 'Received'
        ])
        ->count();

    // Re-Scheduled
    $rescheduled = $this->Records->find()
        ->where([
            'status' => 'Re-Scheduled'
        ])
        ->count();

    // Cancelled
    $cancelled = $this->Records->find()
        ->where([
            'status' => 'Cancelled'
        ])
        ->count();

    // NOTIFICATIONS
    $notifications = $this->Notifications->find()
        ->where(['status' => 'Pending'])
        ->all();

    // SEND DATA TO DASHBOARD VIEW
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
        'fertilizerDistributed' => $fertilizerDistributed,
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
