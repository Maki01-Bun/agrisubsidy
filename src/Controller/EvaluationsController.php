<?php
declare(strict_types=1);

namespace App\Controller;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;

/**
 * Evaluations Controller
 *
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
        $evaluation = $this->Evaluations->newEmptyEntity();

        $this->set(compact('evaluation'));
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
            'contain' => [],
        ]);

        $this->set(compact('evaluation'));
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
                $this->Flash->success(__('The evaluation has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The evaluation could not be saved. Please, try again.'));
        }
        $this->set(compact('evaluation'));
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
        $evaluation = $this->Evaluations->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $evaluation = $this->Evaluations->patchEntity($evaluation, $this->request->getData());
            if ($this->Evaluations->save($evaluation)) {
                $this->Flash->success(__('The evaluation has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The evaluation could not be saved. Please, try again.'));
        }
        $this->set(compact('evaluation'));
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
            $this->Flash->success(__('The evaluation has been deleted.'));
        } else {
            $this->Flash->error(__('The evaluation could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }


public function downloadSummary()
{
    try {

        $evaluations = $this->Evaluations->find()
            ->contain([
                'Farmers',
                'Farms',
                'Feedbacks'
            ])
            ->order([
                'Evaluations.id' => 'ASC'
            ])
            ->all();

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Evaluation Summary');

        $sheet->mergeCells('A1:L1');

        $sheet->setCellValue(
            'A1',
            'AGRICULTURAL SUBSIDY PROGRAM - EVALUATION SUMMARY'
        );

        $sheet->getStyle('A1')->getFont()->setBold(true);
        $sheet->getStyle('A1')->getFont()->setSize(14);

        $sheet->getStyle('A1')
            ->getAlignment()
            ->setHorizontal(
                \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
            );

        $headers = [
            'A3' => 'No.',
            'B3' => 'Farmer Name',
            'C3' => 'Program',
            'D3' => 'Farm Size (ha)',
            'E3' => 'Crop Yield After',
            'H3' => 'Feedback Score',
            'I3' => 'Effectiveness',
        ];

        foreach ($headers as $cell => $value) {
            $sheet->setCellValue($cell, $value);
        }

        $sheet->getStyle('A3:L3')->getFont()->setBold(true);

        $sheet->getStyle('A3:L3')
            ->getAlignment()
            ->setHorizontal(
                \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
            );

        $sheet->getStyle('A3:L3')
            ->getAlignment()
            ->setVertical(
                \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
            );

        $row = 4;
        $number = 1;

        foreach ($evaluations as $evaluation) {

            $farmerName = '';

            if (!empty($evaluation->farmer)) {

                $farmerName = trim(
                    ($evaluation->farmer->first_name ?? '') . ' ' .
                    ($evaluation->farmer->middle_name ?? '') . ' ' .
                    ($evaluation->farmer->last_name ?? '')
                );
            }

            $farmSize = '';

            if (!empty($evaluation->farm)) {

                $farmSize =
                    $evaluation->farm->farm_size ?? '';
            }

            $program = '';

            if (!empty($evaluation->program_name)) {

                $program = $evaluation->program_name;

            } elseif (!empty($evaluation->subsidy_type)) {

                $program = $evaluation->subsidy_type;
            }
            $feedbackScore = '';

            if (!empty($evaluation->feedback)) {

                $feedbackScore =
                    $evaluation->feedback->feedback_score
                    ?? $evaluation->feedback->rating
                    ?? '';
            }

            $effectiveness =
                $evaluation->effectiveness
                ?? $evaluation->effectiveness_label
                ?? '';

            $evaluationDate = '';

            if (!empty($evaluation->created)) {

                if ($evaluation->created instanceof \DateTimeInterface) {

                    $evaluationDate =
                        $evaluation->created->format('Y-m-d');

                } else {

                    $evaluationDate =
                        (string)$evaluation->created;
                }
            }
            $sheet->setCellValue(
                "A{$row}",
                $number
            );

            $sheet->setCellValue(
                "B{$row}",
                $farmerName
            );

            $sheet->setCellValue(
                "C{$row}",
                $program
            );

            $sheet->setCellValue(
                "D{$row}",
                $farmSize
            );


            $sheet->setCellValue(
                "E{$row}",
                $evaluation->crop_yield_after ?? ''
            );

            $sheet->setCellValue(
                "H{$row}",
                $feedbackScore
            );

            $sheet->setCellValue(
                "I{$row}",
                $effectiveness
            );

            $row++;
            $number++;
        }
        if ($row > 4) {

            $sheet->getStyle(
                "A3:I" . ($row - 1)
            )
            ->getBorders()
            ->getAllBorders()
            ->setBorderStyle(
                \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
            );
        }
        foreach (range('A', 'L') as $column) {

            $sheet
                ->getColumnDimension($column)
                ->setAutoSize(true);
        }
        $sheet->freezePane('A4');

        /*
         * CREATE FILE
         */
        $fileName =
            'evaluation_summary_' .
            date('Y-m-d_H-i-s') .
            '.xlsx';

        $tempFile = tempnam(
            sys_get_temp_dir(),
            'evaluation_summary_'
        );

        $writer =
            new \PhpOffice\PhpSpreadsheet\Writer\Xlsx(
                $spreadsheet
            );

        $writer->save($tempFile);

        /*
         * DOWNLOAD
         */
        return $this->response
            ->withType(
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
            )
            ->withHeader(
                'Content-Disposition',
                'attachment; filename="' . $fileName . '"'
            )
            ->withFile(
                $tempFile,
                [
                    'download' => true,
                    'name' => $fileName
                ]
            );

    } catch (\Throwable $e) {

        $this->log(
            'Evaluation Summary Excel Error: ' .
            $e->getMessage(),
            'error'
        );

        $this->Flash->error(
            'Unable to generate the evaluation summary: ' .
            $e->getMessage()
        );

        return $this->redirect([
            'action' => 'index'
        ]);
    }
}

    public function viewFeedback($id = null)
    {
        if (!$id) {
            $this->Flash->error('Invalid evaluation ID.');
            return $this->redirect(['action' => 'index']);
        }

        try {

            $evaluation = $this->Evaluations->get($id, [
                'contain' => [
                    'Farmers',
                    'Farms',
                    'Feedbacks'
                ]
            ]);

            $this->set([
                'evaluation' => $evaluation
            ]);

        } catch (\Exception $e) {

            $this->Flash->error('Evaluation record not found.');

            return $this->redirect([
                'action' => 'index'
            ]);
        }
    }

    public function getFeedback($evaluationId = null)
    {
        $this->request->allowMethod(['get']);

        $this->autoRender = false;

        if (!$evaluationId) {
            return $this->response
                ->withType('application/json')
                ->withStringBody(json_encode([
                    'success' => false,
                    'message' => 'Invalid evaluation ID.'
                ]));
        }

        try {

            /*
            * =====================================================
            * GET EVALUATION
            * =====================================================
            *
            * Get:
            * - Farmer
            * - Feedback
            * - Pest
            */
            $evaluation = $this->Evaluations->get(
                $evaluationId,
                [
                    'contain' => [
                        'Farmers',
                        'Feedbacks',
                        'Pests'
                    ]
                ]
            );


            /*
            * =====================================================
            * FARMER INFORMATION
            * =====================================================
            */

            $farmerName = 'N/A';
            $farmerNumber = 'N/A';

            if (!empty($evaluation->farmer)) {

                $farmerName = trim(
                    ($evaluation->farmer->first_name ?? '') . ' ' .
                    ($evaluation->farmer->middle_name ?? '') . ' ' .
                    ($evaluation->farmer->last_name ?? '')
                );

                $farmerNumber =
                    $evaluation->farmer->farmer_no
                    ?? $evaluation->farmer->farmer_number
                    ?? 'N/A';
            }


            /*
            * =====================================================
            * FEEDBACK INFORMATION
            * =====================================================
            */

            $feedbackRating = 'N/A';
            $comments = 'No comments provided.';
            $feedbackDate = 'N/A';

            if (!empty($evaluation->feedback)) {

                /*
                * Because Feedbacks is a hasMany association,
                * CakePHP may return an array/ResultSet.
                *
                * Get the first feedback record.
                */
                if (is_iterable($evaluation->feedback)) {

                    foreach ($evaluation->feedback as $feedback) {
                        break;
                    }

                } else {

                    $feedback = $evaluation->feedback;
                }


                if (!empty($feedback)) {

                    /*
                    * Rating
                    */
                    if (isset($feedback->rating)) {
                        $feedbackRating = $feedback->rating;
                    }


                    /*
                    * Comment
                    */
                    if (!empty($feedback->comment)) {
                        $comments = $feedback->comment;
                    }


                    /*
                    * Feedback date
                    */
                    if (!empty($feedback->feedback_date)) {

                        if ($feedback->feedback_date instanceof \DateTimeInterface) {

                            $feedbackDate =
                                $feedback->feedback_date->format(
                                    'F d, Y h:i A'
                                );

                        } else {

                            $feedbackDate =
                                (string)$feedback->feedback_date;
                        }

                    } elseif (!empty($feedback->created)) {

                        if ($feedback->created instanceof \DateTimeInterface) {

                            $feedbackDate =
                                $feedback->created->format(
                                    'F d, Y h:i A'
                                );

                        } else {

                            $feedbackDate =
                                (string)$feedback->created;
                        }
                    }
                }
            }


            /*
            * =====================================================
            * CROP YIELD AFTER
            * =====================================================
            */

            $cropYieldAfter = 'None';

            if (
                isset($evaluation->crop_yield_after) &&
                $evaluation->crop_yield_after !== null &&
                $evaluation->crop_yield_after !== ''
            ) {
                $cropYieldAfter = $evaluation->crop_yield_after;
            }


            /*
            * =====================================================
            * JSON RESPONSE
            * =====================================================
            */

            return $this->response
                ->withType('application/json')
                ->withStringBody(json_encode([
                    'success' => true,

                    'data' => [

                        'farmer_name' =>
                            $farmerName,

                        'farmer_number' =>
                            $farmerNumber,

                        'feedback_rating' =>
                            $feedbackRating,

                        'comments' =>
                            $comments,

                        'feedback_date' =>
                            $feedbackDate,

                        'crop_yield_after' =>
                            $cropYieldAfter
                    ]
                ]));
        }

        catch (\Exception $e) {

            return $this->response
                ->withType('application/json')
                ->withStringBody(json_encode([
                    'success' => false,
                    'message' => $e->getMessage()
                ]));
        }
    }
}
