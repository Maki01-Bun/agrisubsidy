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
    $this->loadModel('Evaluations');
    // $this->loadModel('Feedbacks');
    $this->loadModel('Farmers');
    $this->loadModel('Notifications');


    //Effectiveness Chart Data
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

    //Dashboard Counts


    $totalFarmers = $this->Farmers->find()->count();

    $totalBeneficiaries = $this->Evaluations->find()
        ->distinct(['farmer_id'])
        ->count('farmer_id');

    //Registration Requests

    $notifications = $this->Notifications->find()
        ->where(['status' => 'Pending'])
        ->all();

    $this->set(compact('labels', 'totals', 'notifications'));
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
