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
                'Feedbacks',
                'Farms',
                'Records',
                'Schedules'
            ],
        ]);
    
        return $this->response
            ->withType('application/json')
            ->withStringBody(json_encode($evaluation));
    }
    
 public function getEvaluations()
{
    $this->request->allowMethod(['get']);
    $this->autoRender = false;

    try {

        $evaluations = $this->Evaluations
            ->find()
            ->order([
                'Evaluations.id' => 'ASC'
            ])
            ->all();

        $data = [];

        foreach ($evaluations as $evaluation) {

            $data[] = [
                'id' => $evaluation->id,

                'program_name' => 'N/A',

                'farm_size' => 'N/A',

                'crop_yield_after' =>
                    $evaluation->crop_yield_after
                    ?? 'N/A'
            ];
        }

        return $this->response
            ->withType('application/json')
            ->withStringBody(
                json_encode([
                    'success' => true,
                    'count' => count($data),
                    'data' => $data
                ])
            );

    } catch (\Throwable $e) {

        $this->log(
            'Get Evaluations API Error: ' . $e->getMessage(),
            'error'
        );

        return $this->response
            ->withStatus(500)
            ->withType('application/json')
            ->withStringBody(
                json_encode([
                    'success' => false,
                    'count' => 0,
                    'data' => [],
                    'message' => $e->getMessage()
                ])
            );
    }
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

    public function getFeedback($id = null)
{
    $this->request->allowMethod(['get']);

    $this->autoRender = false;

    try {

        if ($id === null) {
            return $this->response
                ->withStatus(400)
                ->withType('application/json')
                ->withStringBody(json_encode([
                    'success' => false,
                    'message' => 'Evaluation ID is required.'
                ]));
        }

        $evaluation = $this->Evaluations->get($id);

        /*
         * Get the feedback associated with this evaluation.
         */
        $feedback = null;

        if (!empty($evaluation->feedback_id)) {
            $this->loadModel('Feedbacks');

            $feedback = $this->Feedbacks->find()
                ->where([
                    'Feedbacks.id' => $evaluation->feedback_id
                ])
                ->first();
        }

        /*
         * If evaluation has no feedback_id,
         * try finding feedback using evaluation_id.
         */
        if ($feedback === null) {
            $this->loadModel('Feedbacks');

            $feedback = $this->Feedbacks->find()
                ->where([
                    'Feedbacks.evaluation_id' => $evaluation->id
                ])
                ->first();
        }

        /*
         * Return evaluation values even if feedback
         * is not found.
         */
        $data = [
            'id' => $evaluation->id,

            'rice_type' =>
                $evaluation->rice_type ?? null,

            'average_yield' =>
                $evaluation->average_yield ?? null,

            'crop_yield_after' =>
                $evaluation->crop_yield_after ?? null,

            'selling_price' =>
                $evaluation->selling_price ?? null,

            'subsidy_received' =>
                $evaluation->subsidy_received ?? null,

            'feedback_rating' =>
                $feedback->rating ?? null,

            'comments' =>
                $feedback->comments ?? null
        ];

        return $this->response
            ->withType('application/json')
            ->withStringBody(json_encode([
                'success' => true,
                'data' => $data
            ]));

    } catch (\Throwable $e) {

        $this->log(
            'Get Feedback Error: ' . $e->getMessage(),
            'error'
        );

        return $this->response
            ->withStatus(500)
            ->withType('application/json')
            ->withStringBody(json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]));
    }
}
}
