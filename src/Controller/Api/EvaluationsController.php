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
    
            /*
             * ==========================================================
             * GET ANONYMOUS EVALUATIONS
             * ==========================================================
             */
            $evaluations = $this->Evaluations->find()
                ->contain([
                    'Farms',
                    'Schedules'
                ])
                ->order([
                    'Evaluations.id' => 'ASC'
                ])
                ->all();
    
            $data = [];
    
            foreach ($evaluations as $evaluation) {
    
                /*
                 * ======================================================
                 * FARM SIZE
                 * ======================================================
                 */
                $farmSize = 'N/A';
    
                if (!empty($evaluation->farm)) {
    
                    $farmSize =
                        $evaluation->farm->farm_size
                        ?? 'N/A';
                }
    
                /*
                 * ======================================================
                 * PROGRAM
                 * ======================================================
                 */
                $programName = 'N/A';
    
                if (!empty($evaluation->schedule)) {
    
                    $programName =
                        $evaluation->schedule->program_name
                        ?? 'N/A';
                }
    
                /*
                 * ======================================================
                 * YIELD AFTER
                 * ======================================================
                 */
                $cropYieldAfter = 'N/A';
    
                if (
                    isset($evaluation->crop_yield_after) &&
                    $evaluation->crop_yield_after !== null &&
                    $evaluation->crop_yield_after !== ''
                ) {
    
                    $cropYieldAfter =
                        $evaluation->crop_yield_after;
                }
    
                /*
                 * ======================================================
                 * EFFECTIVENESS
                 * ======================================================
                 */
                $effectivenessLabel =
                    'Not Predicted';
    
                if (
                    isset($evaluation->effectiveness_label) &&
                    $evaluation->effectiveness_label !== null &&
                    $evaluation->effectiveness_label !== ''
                ) {
    
                    $effectivenessLabel =
                        $evaluation->effectiveness_label;
    
                    /*
                     * Convert numeric labels.
                     */
                    $labelMap = [
                        0 => 'Not Effective',
                        1 => 'Moderately Effective',
                        2 => 'Effective'
                    ];
    
                    if (
                        is_numeric(
                            $effectivenessLabel
                        )
                    ) {
    
                        $numericLabel =
                            (int)$effectivenessLabel;
    
                        $effectivenessLabel =
                            $labelMap[$numericLabel]
                            ??
                            'Not Predicted';
                    }
                }
    
                /*
                 * ======================================================
                 * ANONYMOUS RESPONSE
                 * ======================================================
                 *
                 * DO NOT include:
                 *
                 * farmer_name
                 * farmer_number
                 * farmer_id
                 */
                $data[] = [
    
                    'id' =>
                        $evaluation->id,
    
                    'program_name' =>
                        $programName,
    
                    'farm_size' =>
                        $farmSize,
    
                    'crop_yield_after' =>
                        $cropYieldAfter,
    
                    'effectiveness_label' =>
                        $effectivenessLabel
                ];
            }
    
            /*
             * ==========================================================
             * JSON RESPONSE
             * ==========================================================
             */
            return $this->response
                ->withType('application/json')
                ->withStringBody(
                    json_encode([
                        'data' => $data
                    ])
                );
    
        } catch (\Throwable $e) {
    
            $this->log(
                'Get Evaluations API Error: ' .
                $e->getMessage(),
                'error'
            );
    
            return $this->response
                ->withStatus(500)
                ->withType('application/json')
                ->withStringBody(
                    json_encode([
                        'data' => [],
                        'success' => false,
                        'message' =>
                            $e->getMessage()
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
}
