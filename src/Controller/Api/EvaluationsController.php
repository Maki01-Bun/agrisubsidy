<?php
declare(strict_types=1);

namespace App\Controller\Api;

use App\Controller\AppController;

/**
 * Evaluations Controller
 *
 * @property \App\Model\Table\EvaluationsTable $Evaluations
 * @method \App\Model\Entity\Evaluation[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class EvaluationsController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
     public function index()
    {
        $evaluations = $this->Evaluations->find();
        return $this->response->withType('application/json')
            ->withStringBody(json_encode($evaluations));
    }


    /**
     * View method
     *
     * @param string|null $id Evaluation id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $evaluation = $this->Evaluations->get($id, [
            'contain' => [
                'Farmers',
                'Feedbacks'
            ],
        ]);
    
        return $this->response
            ->withType('application/json')
            ->withStringBody(json_encode($evaluation));
    }
    
    public function getEvaluations()
{
    $evaluations = $this->Evaluations->find()->contain(['Farmers'])->all();

    $data = [];

    foreach ($evaluations as $evaluation) {

        $fullName = '';
        $feedbackRating = '';

        if (!empty($evaluation->farmer)) {
            $fullName =
                $evaluation->farmer->first_name . ' ' .
                $evaluation->farmer->middle_name . ' ' .
                $evaluation->farmer->last_name;
        }
        // if (!empty($evaluation->feedback)) {
        //     $feedbackRating = $evaluation->feedback->rating;
        // }


        $data[] = [
            'id' => $evaluation->id,
            'farmer_name' => trim($fullName),
            'subsidy_type' => $evaluation->subsidy_type,
            'farm_size' => $evaluation->farm_size,
            'crop_yield_before' => $evaluation->crop_yield_before,
            'crop_yield_after' => $evaluation->crop_yield_after,
            'income_before' => $evaluation->income_before,
            'income_after' => $evaluation->income_after,
            'pest' => $evaluation->pest,
            'calamity' => $evaluation->calamity,
            // 'feedback_rating' => $feedbackRating,
            'effectiveness_label' => $evaluation->effectiveness_label
        ];
    }

    return $this->response
        ->withType('application/json')
        ->withStringBody(json_encode([
            'data' => $data
        ]));
}

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $evaluation = $this->Evaluations->newEmptyEntity();
        if ($this->request->is('post')) {
            $evaluation = $this->Evaluations->patchEntity($evaluation, $this->request->getData());
            if ($this->Evaluations->save($evaluation)) {
                $result = ['status' => 'success', 'message' => 'The evaluation has been saved.'];
            }else {
                $result = ['status'=>'error','message'=>'The evaluation could not be saved. Please, try again.'];
            }
            return $this->response->withType('application/json')
                ->withStringBody(json_encode($result));
        }
    }

    /**
     * Edit method
     *
     * @param string|null $id Evaluation id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $evaluation = $this->Evaluations->get($id);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $evaluation = $this->Evaluations->patchEntity(
                $evaluation,$this->request->getData() );
            if ($this->Evaluations->save($evaluation)) {
                return $this->response->withType('application/json')->withStringBody(json_encode([
                'status' => 'success','message' => 'Evaluation updated successfully']));
            }
            return $this->response->withType('application/json')
            ->withStringBody(json_encode(['status' => 'error','errors' => $evaluation->getErrors()]));
        }
        return $this->response
            ->withType('application/json')
            ->withStringBody(json_encode($evaluation));
    }

    /**
     * Delete method
     *
     * @param string|null $id Evaluation id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $evaluation = $this->Evaluations->get($id);
        if ($this->Evaluations->delete($evaluation)) {
            $result = ['status' => 'success', 'message' => 'The Evaluation has been deleted.'];
        }else {
            $result = ['status'=>'error','message'=>'The Evaluation could not be deleted. Please, try again.'];
        }
        return $this->response->withType('application/json')
            ->withStringBody(json_encode($result));
    }   
}
