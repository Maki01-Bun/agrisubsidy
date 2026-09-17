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
        $evaluation = $this->Evaluations->get($id, ['Farms','Feedbacks','Schedules',
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
            * Farmer information is intentionally NOT loaded.
            * Evaluation data is anonymous.
            */
            $evaluations = $this->Evaluations->find()
                ->contain([
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
            $sheet->mergeCells('A1:G1');
    
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
    
                'B3' => 'Program',
    
                'C3' => 'Farm Size (ha)',
    
                'D3' => 'Yield After (bags/ha)',
    
                'E3' => 'Selling Price (per bag)',
    
                'F3' => 'Feedback Score',
    
                'G3' => 'Effectiveness',
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
            $sheet->getStyle('A3:G3')
                ->getFont()
                ->setBold(true);
    
            $sheet->getStyle('A3:G3')
                ->getAlignment()
                ->setHorizontal(
                    Alignment::HORIZONTAL_CENTER
                );
    
            $sheet->getStyle('A3:G3')
                ->getAlignment()
                ->setVertical(
                    Alignment::VERTICAL_CENTER
                );
    
            /*
            * ==========================================================
            * HEADER FILL
            * ==========================================================
            */
            $sheet->getStyle('A3:G3')
                ->getFill()
                ->setFillType(
                    \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID
                );
    
            $sheet->getStyle('A3:G3')
                ->getFill()
                ->getStartColor()
                ->setARGB('FFFFFF00');
    
            $sheet->getStyle('A3:G3')
                ->getFill()
                ->getEndColor()
                ->setARGB('FFFFFF00');
    
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
                * FARM SIZE
                * ======================================================
                */
                $farmSize = 'N/A';
    
                if (!empty($evaluation->farm)) {
    
                    $farmSize =
                        $evaluation->farm->farm_size
                        ?? $evaluation->farm->farm_area
                        ?? $evaluation->farm->size
                        ?? $evaluation->farm->area
                        ?? 'N/A';
                }
    
                /*
                * ======================================================
                * PROGRAM
                * ======================================================
                */
                $program = 'N/A';
    
                if (!empty($evaluation->schedule)) {
    
                    $program =
                        $evaluation->schedule->program_name
                        ?? 'N/A';
                }
    
                /*
                * ======================================================
                * YIELD AFTER
                * ======================================================
                */
                $yieldAfter = 'N/A';
    
                if (
                    isset($evaluation->crop_yield_after) &&
                    $evaluation->crop_yield_after !== null &&
                    $evaluation->crop_yield_after !== ''
                ) {
    
                    $yieldAfter =
                        $evaluation->crop_yield_after;
                }
    
                /*
                * ======================================================
                * SELLING PRICE
                * ======================================================
                */
                $sellingPrice = 'N/A';
    
                if (
                    isset($evaluation->selling_price) &&
                    $evaluation->selling_price !== null &&
                    $evaluation->selling_price !== ''
                ) {
    
                    $sellingPrice =
                        $evaluation->selling_price;
    
                } elseif (
                    isset($evaluation->selling_price_per_bag) &&
                    $evaluation->selling_price_per_bag !== null &&
                    $evaluation->selling_price_per_bag !== ''
                ) {
    
                    $sellingPrice =
                        $evaluation->selling_price_per_bag;
    
                } elseif (
                    isset($evaluation->price_per_bag) &&
                    $evaluation->price_per_bag !== null &&
                    $evaluation->price_per_bag !== ''
                ) {
    
                    $sellingPrice =
                        $evaluation->price_per_bag;
    
                } elseif (
                    isset($evaluation->selling_price_after) &&
                    $evaluation->selling_price_after !== null &&
                    $evaluation->selling_price_after !== ''
                ) {
    
                    $sellingPrice =
                        $evaluation->selling_price_after;
                }
    
                /*
                * ======================================================
                * FEEDBACK SCORE
                * ======================================================
                */
                $feedbackScore = 'N/A';
    
                if (!empty($evaluation->feedbacks)) {
    
                    foreach (
                        $evaluation->feedbacks as $feedback
                    ) {
    
                        if (!empty($feedback)) {
    
                            /*
                            * Feedback Score
                            */
                            if (
                                isset($feedback->feedback_score) &&
                                $feedback->feedback_score !== ''
                            ) {
    
                                $feedbackScore =
                                    $feedback->feedback_score;
    
                            /*
                            * Rating
                            */
                            } elseif (
                                isset($feedback->rating) &&
                                $feedback->rating !== ''
                            ) {
    
                                $feedbackScore =
                                    $feedback->rating;
    
                            /*
                            * Score
                            */
                            } elseif (
                                isset($feedback->score) &&
                                $feedback->score !== ''
                            ) {
    
                                $feedbackScore =
                                    $feedback->score;
                            }
    
                            /*
                            * Use first feedback.
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
                $effectiveness = 'N/A';
    
                if (
                    isset($evaluation->effectiveness) &&
                    $evaluation->effectiveness !== null &&
                    $evaluation->effectiveness !== ''
                ) {
    
                    $effectiveness =
                        $evaluation->effectiveness;
    
                } elseif (
                    isset($evaluation->effectiveness_label) &&
                    $evaluation->effectiveness_label !== null &&
                    $evaluation->effectiveness_label !== ''
                ) {
    
                    $effectiveness =
                        $evaluation->effectiveness_label;
                }
    
                /*
                * ======================================================
                * WRITE ROW
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
                * B = Program
                */
                $sheet->setCellValue(
                    "B{$row}",
                    $program
                );
    
                /*
                * C = Farm Size
                */
                $sheet->setCellValue(
                    "C{$row}",
                    $farmSize
                );
    
                /*
                * D = Yield After
                */
                $sheet->setCellValue(
                    "D{$row}",
                    $yieldAfter
                );
    
                /*
                * E = Selling Price
                */
                $sheet->setCellValue(
                    "E{$row}",
                    $sellingPrice
                );
    
                /*
                * F = Feedback Score
                */
                $sheet->setCellValue(
                    "F{$row}",
                    $feedbackScore
                );
    
                /*
                * G = Effectiveness
                */
                $sheet->setCellValue(
                    "G{$row}",
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
                    'A3:G' . ($row - 1)
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
                    'A4:G' . ($row - 1)
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
                    'C4:G' . ($row - 1)
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
                range('A', 'G')
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
            * AUTO FILTER
            * ==========================================================
            */
            $lastRow = max(3, $row - 1);
    
            $sheet->setAutoFilter(
                'A3:G' . $lastRow
            );
    
            /*
            * ==========================================================
            * PAGE SETUP
            * ==========================================================
            */
            $sheet->getPageSetup()
                ->setOrientation(
                    \PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE
                );
    
            $sheet->getPageSetup()
                ->setPaperSize(
                    \PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4
                );
    
            $sheet->getPageSetup()
                ->setFitToWidth(1);
    
            $sheet->getPageSetup()
                ->setFitToHeight(0);
    
            $sheet->getSheetView()
                ->setZoomScale(90);
    
            /*
            * ==========================================================
            * FILE NAME
            * ==========================================================
            */
            $fileName =
                'anonymous_evaluation_summary_' .
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
            * WRITE EXCEL
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

            /*
            * Get evaluation together with Feedbacks.
            */
            $evaluation = $this->Evaluations->get($id, [
                'contain' => ['Feedbacks']
            ]);

            /*
            * Default rating.
            */
            $feedbackRating = null;

            /*
            * Get the rating from the related feedback.
            */
            if (!empty($evaluation->feedbacks)) {

                foreach ($evaluation->feedbacks as $feedback) {

                    if (!$feedback) {
                        continue;
                    }

                    /*
                    * Check the possible rating field names.
                    */
                    if (
                        isset($feedback->feedback_score) &&
                        $feedback->feedback_score !== '' &&
                        $feedback->feedback_score !== null
                    ) {
                        $feedbackRating = $feedback->feedback_score;
                    }
                    elseif (
                        isset($feedback->rating) &&
                        $feedback->rating !== '' &&
                        $feedback->rating !== null
                    ) {
                        $feedbackRating = $feedback->rating;
                    }
                    elseif (
                        isset($feedback->score) &&
                        $feedback->score !== '' &&
                        $feedback->score !== null
                    ) {
                        $feedbackRating = $feedback->score;
                    }

                    /*
                    * We only need the first feedback.
                    */
                    break;
                }
            }

            /*
            * Return evaluation data.
            */
            $data = [
                'id' => $evaluation->id,

                'rice_type' => $evaluation->rice_type ?? null,

                'average_yield' => $evaluation->average_yield ?? null,

                'crop_yield_after' => $evaluation->crop_yield_after ?? null,

                'selling_price' => $evaluation->selling_price ?? null,

                'subsidy_received' => $evaluation->subsidy_received ?? null,

                'feedback_rating' => $feedbackRating
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
