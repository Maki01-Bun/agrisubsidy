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
            'contain' => ['Farmers','Farms','Feedbacks','Schedules',],
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

            /*
            * ==========================================================
            * GET EVALUATIONS
            * ==========================================================
            *
            * Load all related data needed for the Excel report.
            */
            $evaluations = $this->Evaluations->find()
                ->contain([
                    'Farmers',
                    'Farms',
                    'Feedbacks',
                    'Schedules'
                ])
                ->order([
                    'Evaluations.id' => 'ASC'
                ])
                ->all();

            /*
            * ==========================================================
            * CREATE SPREADSHEET
            * ==========================================================
            */
            $spreadsheet = new Spreadsheet();

            $sheet = $spreadsheet->getActiveSheet();

            $sheet->setTitle('Evaluation Summary');

            /*
            * ==========================================================
            * TITLE
            * ==========================================================
            */
            $sheet->mergeCells('A1:K1');

            $sheet->setCellValue(
                'A1',
                'AGRICULTURAL SUBSIDY PROGRAM - EVALUATION SUMMARY'
            );

            $sheet->getStyle('A1')
                ->getFont()
                ->setBold(true);

            $sheet->getStyle('A1')
                ->getFont()
                ->setSize(14);

            $sheet->getStyle('A1')
                ->getAlignment()
                ->setHorizontal(
                    Alignment::HORIZONTAL_CENTER
                );

            $sheet->getStyle('A1')
                ->getAlignment()
                ->setVertical(
                    Alignment::VERTICAL_CENTER
                );

            /*
            * ==========================================================
            * HEADERS
            * ==========================================================
            */
            $headers = [

                'A3' => 'No.',

                'B3' => 'Farmer Name',

                'C3' => 'Program',

                'D3' => 'Farm Size (ha)',

                'E3' => 'Yield After (bags/ha)',

                'F3' => 'Selling Price (per bag)',

                'G3' => 'Feedback Score',

                'H3' => 'Effectiveness',
            ];

            foreach ($headers as $cell => $value) {

                $sheet->setCellValue(
                    $cell,
                    $value
                );
            }

            /*
            * ==========================================================
            * HEADER STYLE
            * ==========================================================
            */
            $sheet->getStyle('A3:K3')
                ->getFont()
                ->setBold(true);

            $sheet->getStyle('A3:K3')
                ->getAlignment()
                ->setHorizontal(
                    Alignment::HORIZONTAL_CENTER
                );

            $sheet->getStyle('A3:K3')
                ->getAlignment()
                ->setVertical(
                    Alignment::VERTICAL_CENTER
                );

            /*
            * ==========================================================
            * DATA START
            * ==========================================================
            */
            $row = 4;

            $number = 1;

            foreach ($evaluations as $evaluation) {

                /*
                * ======================================================
                * FARMER NAME
                * ======================================================
                */
                $farmerName = '';

                if (!empty($evaluation->farmer)) {

                    $farmerName = trim(
                        ($evaluation->farmer->first_name ?? '') .
                        ' ' .
                        ($evaluation->farmer->middle_name ?? '') .
                        ' ' .
                        ($evaluation->farmer->last_name ?? '')
                    );
                }

                /*
                * If the name is still empty, try full_name.
                */
                if (
                    $farmerName === '' &&
                    !empty($evaluation->farmer)
                ) {

                    $farmerName =
                        $evaluation->farmer->full_name
                        ?? '';
                }

                /*
                * ======================================================
                * FARM SIZE
                * ======================================================
                *
                * Farm size is retrieved from the related Farms table.
                */
                $farmSize = '';

                if (!empty($evaluation->farm)) {

                    $farmSize =
                        $evaluation->farm->farm_size
                        ?? $evaluation->farm->farm_area
                        ?? $evaluation->farm->size
                        ?? $evaluation->farm->area
                        ?? '';
                }
                $program = '';

                if (!empty($evaluation->schedule)) {

                    $program =
                        $evaluation->schedule->program_name
                        ?? '';
                }

                /*
                * ======================================================
                * YIELD AFTER
                * ======================================================
                *
                * This is confirmed by your screenshot.
                */
                $yieldAfter =
                    $evaluation->crop_yield_after
                    ?? '';

                /*
                * ======================================================
                * SELLING PRICE
                * ======================================================
                */
                $sellingPrice =
                    $evaluation->selling_price
                    ?? $evaluation->selling_price_per_bag
                    ?? $evaluation->price_per_bag
                    ?? $evaluation->selling_price_after
                    ?? '';

                $feedbackScore = '';

                if (!empty($evaluation->feedbacks)) {

                    /*
                    * Feedbacks is normally a collection.
                    */
                    foreach (
                        $evaluation->feedbacks
                        as $feedback
                    ) {

                        if (!empty($feedback)) {

                            /*
                            * Try feedback_score first.
                            */
                            if (
                                isset(
                                    $feedback->feedback_score
                                ) &&
                                $feedback->feedback_score !== ''
                            ) {

                                $feedbackScore =
                                    $feedback->feedback_score;

                            /*
                            * Try rating.
                            */
                            } elseif (
                                isset(
                                    $feedback->rating
                                ) &&
                                $feedback->rating !== ''
                            ) {

                                $feedbackScore =
                                    $feedback->rating;

                            /*
                            * Try score.
                            */
                            } elseif (
                                isset(
                                    $feedback->score
                                ) &&
                                $feedback->score !== ''
                            ) {

                                $feedbackScore =
                                    $feedback->score;
                            }

                            /*
                            * Use the first feedback record.
                            */
                            break;
                        }
                    }
                }

                /*
                * ======================================================
                * EFFECTIVENESS
                * ======================================================
                */
                $effectiveness =
                    $evaluation->effectiveness
                    ?? $evaluation->effectiveness_label
                    ?? '';

                /*
                * ======================================================
                * SCHEDULE ID
                * ======================================================
                */
                $scheduleId =
                    $evaluation->schedule_id
                    ?? '';

                /*
                * ======================================================
                * WRITE ROW TO EXCEL
                * ======================================================
                */

                /*
                * A = Number
                */
                $sheet->setCellValue(
                    "A{$row}",
                    $number
                );

                /*
                * B = Farmer Name
                */
                $sheet->setCellValue(
                    "B{$row}",
                    $farmerName
                );

                /*
                * C = Program
                *
                * From Schedules.program_name
                */
                $sheet->setCellValue(
                    "C{$row}",
                    $program
                );

                /*
                * D = Farm Size
                */
                $sheet->setCellValue(
                    "D{$row}",
                    $farmSize
                );

                /*
                * G = Yield After
                */
                $sheet->setCellValue(
                    "E{$row}",
                    $yieldAfter
                );

                /*
                * H = Selling Price
                */
                $sheet->setCellValue(
                    "F{$row}",
                    $sellingPrice
                );

                /*
                * I = Feedback Score
                */
                $sheet->setCellValue(
                    "G{$row}",
                    $feedbackScore
                );

                /*
                * J = Effectiveness
                */
                $sheet->setCellValue(
                    "H{$row}",
                    $effectiveness
                );

                /*
                * NEXT ROW
                */
                $row++;

                $number++;
            }

            /*
            * ==========================================================
            * BORDERS
            * ==========================================================
            */
            if ($row > 4) {

                $sheet->getStyle(
                    'A3:H' . ($row - 1)
                )
                    ->getBorders()
                    ->getAllBorders()
                    ->setBorderStyle(
                        Border::BORDER_THIN
                    );
            }

            /*
            * ==========================================================
            * ALIGNMENT
            * ==========================================================
            */
            if ($row > 4) {

                $sheet->getStyle(
                    'A4:H' . ($row - 1)
                )
                    ->getAlignment()
                    ->setVertical(
                        Alignment::VERTICAL_CENTER
                    );
            }

            /*
            * ==========================================================
            * CENTER NUMERIC COLUMNS
            * ==========================================================
            */
            if ($row > 4) {

                $sheet->getStyle(
                    'A4:A' . ($row - 1)
                )
                    ->getAlignment()
                    ->setHorizontal(
                        Alignment::HORIZONTAL_CENTER
                    );

                $sheet->getStyle(
                    'D4:H' . ($row - 1)
                )
                    ->getAlignment()
                    ->setHorizontal(
                        Alignment::HORIZONTAL_CENTER
                    );
            }

            /*
            * ==========================================================
            * AUTO SIZE
            * ==========================================================
            */
            foreach (
                range('A', 'H')
                as $column
            ) {

                $sheet->getColumnDimension(
                    $column
                )->setAutoSize(true);
            }

            /*
            * ==========================================================
            * FREEZE HEADER
            * ==========================================================
            */
            $sheet->freezePane('A4');

            /*
            * ==========================================================
            * FILE NAME
            * ==========================================================
            */
            $fileName =
                'evaluation_summary_' .
                date('Y-m-d_H-i-s') .
                '.xlsx';

            /*
            * ==========================================================
            * TEMPORARY FILE
            * ==========================================================
            */
            $tempFile = tempnam(
                sys_get_temp_dir(),
                'evaluation_summary_'
            );

            /*
            * ==========================================================
            * WRITE EXCEL FILE
            * ==========================================================
            */
            $writer = new Xlsx(
                $spreadsheet
            );

            $writer->save(
                $tempFile
            );

            /*
            * ==========================================================
            * DOWNLOAD FILE
            * ==========================================================
            */
            return $this->response
                ->withType(
                    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                )
                ->withHeader(
                    'Content-Disposition',
                    'attachment; filename="' .
                    $fileName .
                    '"'
                )
                ->withFile(
                    $tempFile,
                    [
                        'download' => true,
                        'name' => $fileName
                    ]
                );

        } catch (\Throwable $e) {

            /*
            * ==========================================================
            * ERROR LOG
            * ==========================================================
            */
            $this->log(
                'Evaluation Summary Excel Error: ' .
                $e->getMessage(),
                'error'
            );

            /*
            * ==========================================================
            * ERROR MESSAGE
            * ==========================================================
            */
            $this->Flash->error(
                'Unable to generate the evaluation summary: ' .
                $e->getMessage()
            );

            /*
            * ==========================================================
            * RETURN TO INDEX
            * ==========================================================
            */
            return $this->redirect([
                'action' => 'index'
            ]);
        }
    }
    public function viewFeedback($id = null)
    {
        if (!$id) {

            $this->Flash->error(
                'Invalid evaluation ID.'
            );

            return $this->redirect([
                'action' => 'index'
            ]);
        }

        try {

            $evaluation = $this->Evaluations->get(
                $id,
                [
                    'contain' => [
                        'Farmers',
                        'Farms',
                        'Feedbacks',
                        'Schedules'
                    ]
                ]
            );

            $this->set([
                'evaluation' => $evaluation
            ]);

        } catch (\Exception $e) {

            $this->Flash->error(
                'Evaluation record not found.'
            );

            return $this->redirect([
                'action' => 'index'
            ]);
        }
    }


    /**
     * Get Feedback
     *
     * Returns evaluation feedback as JSON.
     *
     * program_name is retrieved from Schedules.
     * subsidy_received is retrieved from Evaluations.
     */
    public function getFeedback($evaluationId = null)
{
    $this->request->allowMethod(['get']);

    $this->autoRender = false;

    /*
     * ==========================================================
     * VALIDATE ID
     * ==========================================================
     */
    if (!$evaluationId) {

        return $this->response
            ->withType('application/json')
            ->withStringBody(
                json_encode([
                    'success' => false,
                    'message' => 'Invalid evaluation ID.'
                ])
            );
    }

    try {

        /*
         * ==========================================================
         * GET EVALUATION
         * ==========================================================
         *
         * IMPORTANT:
         *
         * Evaluations belongsTo Feedbacks through:
         *
         * evaluations.feedback_id
         *          ↓
         * feedbacks.id
         *
         * Therefore the entity property is:
         *
         * $evaluation->feedback
         *
         * NOT:
         *
         * $evaluation->feedbacks
         *
         * ==========================================================
         */
        $evaluation = $this->Evaluations->get(
            $evaluationId,
            [
                'contain' => [
                    'Farmers',
                    'Feedbacks',
                    'Schedules'
                ]
            ]
        );

        /*
         * ==========================================================
         * FARMER INFORMATION
         * ==========================================================
         */
        $farmerName = 'N/A';

        $farmerNumber = 'N/A';

        if (!empty($evaluation->farmer)) {

            $farmerName = trim(
                ($evaluation->farmer->first_name ?? '') .
                ' ' .
                ($evaluation->farmer->middle_name ?? '') .
                ' ' .
                ($evaluation->farmer->last_name ?? '')
            );

            $farmerNumber =
                $evaluation->farmer->farmer_no
                ??
                $evaluation->farmer->farmer_number
                ??
                'N/A';
        }

        /*
         * ==========================================================
         * SCHEDULE INFORMATION
         * ==========================================================
         */
        $scheduleId = $evaluation->schedule_id ?? null;

        $programName = 'N/A';

        if (!empty($evaluation->schedule)) {

            $programName =
                $evaluation->schedule->program_name
                ??
                'N/A';
        }

        /*
         * ==========================================================
         * SUBSIDY RECEIVED
         * ==========================================================
         *
         * This comes directly from Evaluations.
         * ==========================================================
         */
        $subsidyReceived = 'N/A';

        if (
            isset($evaluation->subsidy_received) &&
            $evaluation->subsidy_received !== null &&
            $evaluation->subsidy_received !== ''
        ) {

            $subsidyReceived =
                $evaluation->subsidy_received;
        }

        /*
         * ==========================================================
         * SELLING PRICE
         * ==========================================================
         */
        $sellingPrice = '';

        if (
            isset($evaluation->selling_price) &&
            $evaluation->selling_price !== null &&
            $evaluation->selling_price !== ''
        ) {

            $sellingPrice =
                $evaluation->selling_price;
        }

        /*
         * ==========================================================
         * FEEDBACK INFORMATION
         * ==========================================================
         *
         * IMPORTANT:
         *
         * Feedbacks is belongsTo.
         *
         * Therefore:
         *
         * $evaluation->feedback
         *
         * ==========================================================
         */
        $feedbackRating = 'N/A';

        $comments = 'No comments provided.';

        $feedbackDate = 'N/A';

        /*
         * ==========================================================
         * GET FEEDBACK
         * ==========================================================
         */
        if (!empty($evaluation->feedback)) {

            $feedback = $evaluation->feedback;

            /*
             * ======================================================
             * RATING
             * ======================================================
             *
             * Actual field in Feedbacks:
             *
             * rating
             * ======================================================
             */
            if (
                isset($feedback->rating) &&
                $feedback->rating !== null &&
                $feedback->rating !== ''
            ) {

                $feedbackRating =
                    (float)$feedback->rating;
            }

            /*
             * ======================================================
             * COMMENTS
             * ======================================================
             */
            if (
                isset($feedback->comment) &&
                $feedback->comment !== null &&
                $feedback->comment !== ''
            ) {

                $comments =
                    $feedback->comment;
            }

            /*
             * ======================================================
             * FEEDBACK DATE
             * ======================================================
             */
            if (
                isset($feedback->feedback_date) &&
                $feedback->feedback_date !== null &&
                $feedback->feedback_date !== ''
            ) {

                if (
                    $feedback->feedback_date
                    instanceof
                    \DateTimeInterface
                ) {

                    $feedbackDate =
                        $feedback
                            ->feedback_date
                            ->format(
                                'F d, Y h:i A'
                            );

                } else {

                    $feedbackDate =
                        (string)$feedback->feedback_date;
                }

            } elseif (
                isset($feedback->created) &&
                $feedback->created !== null &&
                $feedback->created !== ''
            ) {

                if (
                    $feedback->created
                    instanceof
                    \DateTimeInterface
                ) {

                    $feedbackDate =
                        $feedback
                            ->created
                            ->format(
                                'F d, Y h:i A'
                            );

                } else {

                    $feedbackDate =
                        (string)$feedback->created;
                }
            }
        }

        /*
         * ==========================================================
         * CROP YIELD AFTER
         * ==========================================================
         */
        $cropYieldAfter = 'None';

        if (
            isset($evaluation->crop_yield_after) &&
            $evaluation->crop_yield_after !== null &&
            $evaluation->crop_yield_after !== ''
        ) {

            $cropYieldAfter =
                $evaluation->crop_yield_after;
        }

        /*
         * ==========================================================
         * AVERAGE YIELD
         * ==========================================================
         */
        $averageYield = 'None';

        if (
            isset($evaluation->average_yield) &&
            $evaluation->average_yield !== null &&
            $evaluation->average_yield !== ''
        ) {

            $averageYield =
                $evaluation->average_yield;
        }

        /*
         * ==========================================================
         * FARM SIZE
         * ==========================================================
         */
        $farmSize = 'None';

        if (
            isset($evaluation->farm_size) &&
            $evaluation->farm_size !== null &&
            $evaluation->farm_size !== ''
        ) {

            $farmSize =
                $evaluation->farm_size;
        }

        /*
         * ==========================================================
         * EFFECTIVENESS LABEL
         * ==========================================================
         */
        $effectivenessLabel = 'Not Predicted';

        if (
            isset($evaluation->effectiveness_label) &&
            $evaluation->effectiveness_label !== null &&
            $evaluation->effectiveness_label !== ''
        ) {

            $effectivenessLabel =
                $evaluation->effectiveness_label;

            /*
             * Convert old numerical values if necessary.
             */
            $labelMap = [
                0 => 'Not Effective',
                1 => 'Moderately Effective',
                2 => 'Effective'
            ];

            if (is_numeric($effectivenessLabel)) {

                $numericLabel =
                    (int)$effectivenessLabel;

                $effectivenessLabel =
                    $labelMap[$numericLabel]
                    ??
                    'Not Predicted';
            }
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
                    'success' => true,

                    'data' => [

                        /*
                         * ------------------------------------------
                         * EVALUATION
                         * ------------------------------------------
                         */
                        'evaluation_id' =>
                            $evaluation->id,

                        /*
                         * ------------------------------------------
                         * FARMER
                         * ------------------------------------------
                         */
                        'farmer_name' =>
                            $farmerName,

                        'farmer_number' =>
                            $farmerNumber,

                        /*
                         * ------------------------------------------
                         * SCHEDULE
                         * ------------------------------------------
                         */
                        'schedule_id' =>
                            $scheduleId,

                        'program_name' =>
                            $programName,

                        /*
                         * ------------------------------------------
                         * SUBSIDY
                         * ------------------------------------------
                         */
                        'subsidy_received' =>
                            $subsidyReceived,

                        /*
                         * ------------------------------------------
                         * FARM INFORMATION
                         * ------------------------------------------
                         */
                        'farm_size' =>
                            $farmSize,

                        'average_yield' =>
                            $averageYield,

                        /*
                         * ------------------------------------------
                         * CROP YIELD
                         * ------------------------------------------
                         */
                        'crop_yield_after' =>
                            $cropYieldAfter,

                        /*
                         * ------------------------------------------
                         * SELLING PRICE
                         * ------------------------------------------
                         */
                        'selling_price' =>
                            $sellingPrice,

                        /*
                         * ------------------------------------------
                         * FEEDBACK
                         * ------------------------------------------
                         */
                        'feedback_rating' =>
                            $feedbackRating,

                        'comments' =>
                            $comments,

                        'feedback_date' =>
                            $feedbackDate,

                        /*
                         * ------------------------------------------
                         * EFFECTIVENESS
                         * ------------------------------------------
                         */
                        'effectiveness_label' =>
                            $effectivenessLabel
                    ]
                ])
            );

    } catch (\Exception $e) {

        return $this->response
            ->withType('application/json')
            ->withStringBody(
                json_encode([
                    'success' => false,
                    'message' => $e->getMessage()
                ])
            );
    }
}
}
