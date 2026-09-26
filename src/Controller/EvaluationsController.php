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
    
     public function initialize(): void
    {
        parent::initialize();

        $this->loadComponent('Flash');

        $this->loadModel('Feedbacks');
    }


    /**
     * ============================================================
     * INDEX
     * ============================================================
     */
    public function index()
{
    /*
     * ============================================================
     * NEW EMPTY EVALUATION ENTITY
     * ============================================================
     */

    $evaluation = $this->Evaluations->newEmptyEntity();


    /*
     * ============================================================
     * QUESTION-BY-QUESTION SURVEY SUMMARY
     * ============================================================
     */

    $questionSummary = $this->getQuestionSummary();


    /*
     * ============================================================
     * OVERALL SEED SUBSIDY EVALUATION
     * 
     * Aggregate queries now accept both standard and legacy labels
     * ============================================================
     */

    $effectiveCount = $this->Evaluations
        ->find()
        ->where([
            'Evaluations.effectiveness_label IN' => [
                'Effective', 
                'Strongly Agree', 
                'Agree'
            ]
        ])
        ->count();


    $moderatelyEffectiveCount = $this->Evaluations
        ->find()
        ->where([
            'Evaluations.effectiveness_label IN' => [
                'Moderately Effective', 
                'Neutral'
            ]
        ])
        ->count();


    $notEffectiveCount = $this->Evaluations
        ->find()
        ->where([
            'Evaluations.effectiveness_label IN' => [
                'Not Effective', 
                'Disagree', 
                'Strongly Disagree'
            ]
        ])
        ->count();


    /*
     * ============================================================
     * TOTAL EVALUATED
     * ============================================================
     */

    $totalEvaluated =
        $effectiveCount +
        $moderatelyEffectiveCount +
        $notEffectiveCount;


    /*
     * ============================================================
     * CALCULATE PERCENTAGES
     * ============================================================
     */

    $effectivePercent = 0;
    $moderatelyEffectivePercent = 0;
    $notEffectivePercent = 0;

    if ($totalEvaluated > 0) {
        $effectivePercent =
            ($effectiveCount / $totalEvaluated) * 100;

        $moderatelyEffectivePercent =
            ($moderatelyEffectiveCount / $totalEvaluated) * 100;

        $notEffectivePercent =
            ($notEffectiveCount / $totalEvaluated) * 100;
    }


    /*
     * ============================================================
     * DETERMINE OVERALL EVALUATION
     * ============================================================
     */

    $overallResult = 'No Evaluation';

    if ($totalEvaluated > 0) {
        if (
            $effectiveCount >= $moderatelyEffectiveCount &&
            $effectiveCount >= $notEffectiveCount
        ) {
            $overallResult = 'Effective';
        } elseif (
            $moderatelyEffectiveCount >= $effectiveCount &&
            $moderatelyEffectiveCount >= $notEffectiveCount
        ) {
            $overallResult = 'Moderately Effective';
        } else {
            $overallResult = 'Not Effective';
        }
    }


    /*
     * ============================================================
     * OVERALL EVALUATION DATA
     * ============================================================
     */

    $overallEvaluation = [
        'effective' =>
            round($effectivePercent, 1),

        'moderately_effective' =>
            round($moderatelyEffectivePercent, 1),

        'not_effective' =>
            round($notEffectivePercent, 1),

        'effective_count' =>
            $effectiveCount,

        'moderately_effective_count' =>
            $moderatelyEffectiveCount,

        'not_effective_count' =>
            $notEffectiveCount,

        'total' =>
            $totalEvaluated,

        'overall' =>
            $overallResult
    ];


    /*
     * ============================================================
     * SEND DATA TO VIEW
     * ============================================================
     */

    $this->set([
        'evaluation' => $evaluation,
        'questionSummary' => $questionSummary,
        'overallEvaluation' => $overallEvaluation
    ]);
}


    /**
     * ============================================================
     * VIEW
     * ============================================================
     */
    public function view($id = null)
    {
        $evaluation = $this->Evaluations->get($id, [
            'contain' => [
                'Farms',
                'Feedbacks',
                'Schedules'
            ]
        ]);

        $this->set(compact('evaluation'));
    }


    /**
     * ============================================================
     * ADD
     * ============================================================
     *
     * Manual evaluation creation.
     *
     * IMPORTANT:
     * effectiveness_label is NOT predicted here.
     */
    public function add()
    {
        $evaluation = $this->Evaluations->newEmptyEntity();

        if ($this->request->is('post')) {

            $evaluation = $this->Evaluations->patchEntity(
                $evaluation,
                $this->request->getData()
            );

            /*
             * Do not predict effectiveness during save.
             */
            $evaluation->effectiveness_label = null;

            if ($this->Evaluations->save($evaluation)) {

                $this->Flash->success(
                    __('The evaluation has been saved. It is pending evaluation.')
                );

                return $this->redirect([
                    'action' => 'index'
                ]);
            }

            $this->Flash->error(
                __('The evaluation could not be saved. Please, try again.')
            );
        }

        $questionSummary = $this->getQuestionSummary();

        $this->set([
            'evaluation' => $evaluation,
            'questionSummary' => $questionSummary
        ]);
    }


    /**
     * ============================================================
     * EDIT
     * ============================================================
     */
    public function edit($id = null)
    {
        $evaluation = $this->Evaluations->get($id, [
            'contain' => []
        ]);

        if ($this->request->is(['patch', 'post', 'put'])) {

            $evaluation = $this->Evaluations->patchEntity(
                $evaluation,
                $this->request->getData()
            );

            /*
             * Do not automatically predict effectiveness.
             *
             * Existing effectiveness_label is preserved when
             * editing an already evaluated record.
             */
            if (
                !$evaluation->isDirty('effectiveness_label')
            ) {
                /*
                 * Leave existing value unchanged.
                 */
            }

            if ($this->Evaluations->save($evaluation)) {

                $this->Flash->success(
                    __('The evaluation has been saved.')
                );

                return $this->redirect([
                    'action' => 'index'
                ]);
            }

            $this->Flash->error(
                __('The evaluation could not be saved. Please, try again.')
            );
        }

        $this->set(compact('evaluation'));
    }


    /**
     * ============================================================
     * DELETE
     * ============================================================
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);

        $evaluation = $this->Evaluations->get($id);

        if ($this->Evaluations->delete($evaluation)) {

            $this->Flash->success(
                __('The evaluation has been deleted.')
            );

        } else {

            $this->Flash->error(
                __('The evaluation could not be deleted. Please, try again.')
            );
        }

        return $this->redirect([
            'action' => 'index'
        ]);
    }


    /**
     * ============================================================
     * DOWNLOAD SUMMARY
     * ============================================================
     *
     * Excel contains:
     *
     * A  = No.
     * B  = Program
     * C  = Farm Size
     * D  = Yield After
     * E  = Selling Price
     * F  = Q1
     * G  = Q2
     * H  = Q3
     * I  = Q4
     * J  = Q5
     * K  = Q6
     * L  = Q7
     * M  = Q8
     * N  = Q9
     * O  = Q10
     * P  = Effectiveness
     * Q  = Feedback Date
     *
     * No old rating or feedback_score column.
     */
   public function downloadSummary()
{
    try {

        /*
         * ==========================================================
         * GET EVALUATIONS
         * ==========================================================
         */

        $evaluations = $this->Evaluations
            ->find()
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

        $sheet->mergeCells('A1:P1');

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

            'E3' => 'Selling Price',

            'F3' => 'Q1',

            'G3' => 'Q2',

            'H3' => 'Q3',

            'I3' => 'Q4',

            'J3' => 'Q5',

            'K3' => 'Q6',

            'L3' => 'Q7',

            'M3' => 'Q8',

            'N3' => 'Q9',

            'O3' => 'Q10',

            'P3' => 'Feedback Date'
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

        $sheet->getStyle('A3:P3')
            ->getFont()
            ->setBold(true);

        $sheet->getStyle('A3:P3')
            ->getAlignment()
            ->setHorizontal(
                Alignment::HORIZONTAL_CENTER
            );

        $sheet->getStyle('A3:P3')
            ->getAlignment()
            ->setVertical(
                Alignment::VERTICAL_CENTER
            );


        /*
         * ==========================================================
         * HEADER FILL
         * ==========================================================
         */

        $sheet->getStyle('A3:P3')
            ->getFill()
            ->setFillType(
                Fill::FILL_SOLID
            );

        $sheet->getStyle('A3:P3')
            ->getFill()
            ->getStartColor()
            ->setARGB('FFFFFF00');

        $sheet->getStyle('A3:P3')
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


        /*
         * ==========================================================
         * LOOP EVALUATIONS
         * ==========================================================
         */

        foreach ($evaluations as $evaluation) {


            /*
             * ======================================================
             * FARM
             * ======================================================
             */

            $farm = null;

            if (
                isset($evaluation->farm) &&
                !empty($evaluation->farm)
            ) {

                $farm =
                    $evaluation->farm;

            } elseif (
                isset($evaluation->farms) &&
                !empty($evaluation->farms)
            ) {

                if (
                    is_array(
                        $evaluation->farms
                    )
                ) {

                    $farm =
                        !empty($evaluation->farms)
                            ? $evaluation->farms[0]
                            : null;

                } else {

                    $farm =
                        $evaluation->farms;
                }
            }


            /*
             * ======================================================
             * SCHEDULE
             * ======================================================
             */

            $schedule = null;

            if (
                isset($evaluation->schedule) &&
                !empty($evaluation->schedule)
            ) {

                $schedule =
                    $evaluation->schedule;

            } elseif (
                isset($evaluation->schedules) &&
                !empty($evaluation->schedules)
            ) {

                if (
                    is_array(
                        $evaluation->schedules
                    )
                ) {

                    $schedule =
                        !empty($evaluation->schedules)
                            ? $evaluation->schedules[0]
                            : null;

                } else {

                    $schedule =
                        $evaluation->schedules;
                }
            }


            /*
             * ======================================================
             * PROGRAM
             * ======================================================
             *
             * Priority:
             *
             * 1. Schedule program_name
             * 2. Schedule program_code
             * 3. Evaluation program_name
             * 4. Evaluation program
             * 5. Seed Subsidy Distribution
             */

            $program = '';

            if (
                $schedule &&
                isset($schedule->program_name) &&
                trim(
                    (string)$schedule->program_name
                ) !== ''
            ) {

                $program =
                    trim(
                        (string)$schedule->program_name
                    );

            } elseif (
                $schedule &&
                isset($schedule->program_code) &&
                trim(
                    (string)$schedule->program_code
                ) !== ''
            ) {

                $program =
                    trim(
                        (string)$schedule->program_code
                    );

            } elseif (
                isset($evaluation->program_name) &&
                trim(
                    (string)$evaluation->program_name
                ) !== ''
            ) {

                $program =
                    trim(
                        (string)$evaluation->program_name
                    );

            } elseif (
                isset($evaluation->program) &&
                trim(
                    (string)$evaluation->program
                ) !== ''
            ) {

                $program =
                    trim(
                        (string)$evaluation->program
                    );

            } else {

                $program =
                    'Seed Subsidy Distribution';
            }


            /*
             * ======================================================
             * FARM SIZE
             * ======================================================
             */

            $farmSize = null;


            if ($farm) {

                $farmSizeFields = [

                    'farm_size',

                    'farm_area',

                    'size',

                    'area'
                ];


                foreach (
                    $farmSizeFields as $field
                ) {

                    if (
                        isset($farm->{$field}) &&
                        $farm->{$field} !== null &&
                        $farm->{$field} !== ''
                    ) {

                        $farmSize =
                            $farm->{$field};

                        break;
                    }
                }
            }


            /*
             * FALLBACK TO EVALUATION
             */

            if (
                $farmSize === null ||
                $farmSize === ''
            ) {

                $evaluationFarmSizeFields = [

                    'farm_size',

                    'farm_area',

                    'farm_size_ha',

                    'farm_area_ha',

                    'area'
                ];


                foreach (
                    $evaluationFarmSizeFields
                    as $field
                ) {

                    if (
                        isset($evaluation->{$field}) &&
                        $evaluation->{$field} !== null &&
                        $evaluation->{$field} !== ''
                    ) {

                        $farmSize =
                            $evaluation->{$field};

                        break;
                    }
                }
            }


            if (
                $farmSize === null ||
                $farmSize === ''
            ) {

                $farmSize =
                    'N/A';
            }


            /*
             * ======================================================
             * YIELD AFTER
             * ======================================================
             */

            $yieldAfter = null;


            $yieldFields = [

                'crop_yield_after',

                'yield_after',

                'crop_yield',

                'yield',

                'crop_yield_bags',

                'yield_after_bags'
            ];


            foreach (
                $yieldFields as $field
            ) {

                if (
                    isset($evaluation->{$field}) &&
                    $evaluation->{$field} !== null &&
                    $evaluation->{$field} !== ''
                ) {

                    $yieldAfter =
                        $evaluation->{$field};

                    break;
                }
            }


            if (
                $yieldAfter === null ||
                $yieldAfter === ''
            ) {

                $yieldAfter =
                    'N/A';
            }


            /*
             * ======================================================
             * SELLING PRICE
             * ======================================================
             */

            $sellingPrice = null;


            $sellingPriceFields = [

                'selling_price',

                'selling_price_per_bag',

                'price_per_bag',

                'selling_price_after',

                'price'
            ];


            foreach (
                $sellingPriceFields as $field
            ) {

                if (
                    isset($evaluation->{$field}) &&
                    $evaluation->{$field} !== null &&
                    $evaluation->{$field} !== ''
                ) {

                    $sellingPrice =
                        $evaluation->{$field};

                    break;
                }
            }


            if (
                $sellingPrice === null ||
                $sellingPrice === ''
            ) {

                $sellingPrice =
                    'N/A';
            }


            /*
             * ======================================================
             * GET FEEDBACKS
             * ======================================================
             */

            $feedbacks = [];


            if (
                isset($evaluation->feedbacks) &&
                !empty($evaluation->feedbacks)
            ) {

                if (
                    is_iterable(
                        $evaluation->feedbacks
                    )
                ) {

                    foreach (
                        $evaluation->feedbacks
                        as $feedback
                    ) {

                        if (!empty($feedback)) {

                            $feedbacks[] =
                                $feedback;
                        }
                    }
                }

            } elseif (
                isset($evaluation->feedback) &&
                !empty($evaluation->feedback)
            ) {

                $feedbacks[] =
                    $evaluation->feedback;
            }


            /*
             * ======================================================
             * ANSWERS
             * ======================================================
             */

            $answers = [];

            $feedbackDate = null;


            foreach (
                $feedbacks as $feedback
            ) {

                if (!$feedback) {
                    continue;
                }


                /*
                 * ==================================================
                 * GET ANSWER JSON
                 * ==================================================
                 */

                $answerData = null;


                if (
                    isset($feedback->answer) &&
                    $feedback->answer !== null &&
                    trim(
                        (string)$feedback->answer
                    ) !== ''
                ) {

                    $answerData =
                        $feedback->answer;
                }


                /*
                 * Try alternative answers field
                 */

                if (
                    $answerData === null &&
                    isset($feedback->answers) &&
                    $feedback->answers !== null &&
                    $feedback->answers !== ''
                ) {

                    $answerData =
                        $feedback->answers;
                }


                /*
                 * ==================================================
                 * DECODE JSON
                 * ==================================================
                 */

                if (
                    $answerData !== null &&
                    is_string($answerData)
                ) {

                    $decoded =
                        json_decode(
                            $answerData,
                            true
                        );


                    if (
                        json_last_error() ===
                        JSON_ERROR_NONE &&
                        is_array($decoded)
                    ) {

                        $answers =
                            array_merge(
                                $answers,
                                $decoded
                            );
                    }

                } elseif (
                    is_array($answerData)
                ) {

                    $answers =
                        array_merge(
                            $answers,
                            $answerData
                        );
                }


                /*
                 * ==================================================
                 * CHECK DIRECT Q1-Q10 FIELDS
                 * ==================================================
                 */

                for (
                    $q = 1;
                    $q <= 10;
                    $q++
                ) {

                    $possibleKeys = [

                        'q' . $q,

                        'Q' . $q,

                        'question' . $q,

                        'Question' . $q,

                        'question_' . $q,

                        'Question_' . $q
                    ];


                    foreach (
                        $possibleKeys as $possibleKey
                    ) {

                        if (
                            isset(
                                $feedback->{$possibleKey}
                            ) &&
                            $feedback->{$possibleKey} !== null &&
                            $feedback->{$possibleKey} !== ''
                        ) {

                            $answers[
                                'q' . $q
                            ] =
                                $feedback->{$possibleKey};

                            break;
                        }
                    }
                }


                /*
                 * ==================================================
                 * FEEDBACK DATE
                 * ==================================================
                 */

                if (
                    $feedbackDate === null &&
                    isset(
                        $feedback->feedback_date
                    ) &&
                    !empty(
                        $feedback->feedback_date
                    )
                ) {

                    $feedbackDate =
                        $feedback->feedback_date;
                }


                if (
                    $feedbackDate === null &&
                    isset(
                        $feedback->created
                    ) &&
                    !empty(
                        $feedback->created
                    )
                ) {

                    $feedbackDate =
                        $feedback->created;
                }


                /*
                 * Stop once answers are found
                 */

                if (!empty($answers)) {
                    break;
                }
            }


            /*
             * ======================================================
             * ALSO CHECK EVALUATION Q1-Q10 FIELDS
             * ======================================================
             */

            for (
                $q = 1;
                $q <= 10;
                $q++
            ) {

                $key =
                    'q' . $q;


                if (
                    isset($answers[$key]) &&
                    $answers[$key] !== null &&
                    $answers[$key] !== ''
                ) {

                    continue;
                }


                $possibleKeys = [

                    $key,

                    'Q' . $q,

                    'question' . $q,

                    'Question' . $q,

                    'question_' . $q,

                    'Question_' . $q
                ];


                foreach (
                    $possibleKeys as $possibleKey
                ) {

                    if (
                        isset(
                            $evaluation->{$possibleKey}
                        ) &&
                        $evaluation->{$possibleKey} !== null &&
                        $evaluation->{$possibleKey} !== ''
                    ) {

                        $answers[$key] =
                            $evaluation->{$possibleKey};

                        break;
                    }
                }
            }


            /*
             * ======================================================
             * FORMAT Q1-Q10
             * ======================================================
             */

            $questionValues = [];


            for (
                $questionNumber = 1;
                $questionNumber <= 10;
                $questionNumber++
            ) {

                $key =
                    'q' . $questionNumber;


                $answer =
                    $answers[$key] ?? null;


                /*
                 * Uppercase key fallback
                 */

                if (
                    ($answer === null || $answer === '') &&
                    isset(
                        $answers[
                            'Q' . $questionNumber
                        ]
                    )
                ) {

                    $answer =
                        $answers[
                            'Q' . $questionNumber
                        ];
                }


                /*
                 * Format answer
                 */

                if (
                    $answer === null ||
                    $answer === ''
                ) {

                    $questionValues[$key] =
                        'N/A';

                } else {

                    $questionValues[$key] =
                        $this->formatAnswerForExport(
                            $answer
                        );
                }
            }


            /*
             * ======================================================
             * FEEDBACK DATE FORMAT
             * ======================================================
             */

            if (
                $feedbackDate !== null &&
                $feedbackDate !== ''
            ) {

                try {

                    if (
                        $feedbackDate instanceof
                        \DateTimeInterface
                    ) {

                        $feedbackDate =
                            $feedbackDate->format(
                                'Y-m-d H:i'
                            );

                    } else {

                        $dateObject =
                            new \DateTime(
                                (string)$feedbackDate
                            );

                        $feedbackDate =
                            $dateObject->format(
                                'Y-m-d H:i'
                            );
                    }

                } catch (\Throwable $dateError) {

                    $feedbackDate =
                        (string)$feedbackDate;
                }

            } else {

                $feedbackDate =
                    'N/A';
            }


            /*
             * ======================================================
             * WRITE ROW
             * ======================================================
             */

            $sheet->setCellValue(
                "A{$row}",
                $number
            );

            $sheet->setCellValue(
                "B{$row}",
                strtoupper(
                    (string)$program
                )
            );

            $sheet->setCellValue(
                "C{$row}",
                $farmSize
            );

            $sheet->setCellValue(
                "D{$row}",
                $yieldAfter
            );

            $sheet->setCellValue(
                "E{$row}",
                $sellingPrice
            );

            $sheet->setCellValue(
                "F{$row}",
                $questionValues['q1']
            );

            $sheet->setCellValue(
                "G{$row}",
                $questionValues['q2']
            );

            $sheet->setCellValue(
                "H{$row}",
                $questionValues['q3']
            );

            $sheet->setCellValue(
                "I{$row}",
                $questionValues['q4']
            );

            $sheet->setCellValue(
                "J{$row}",
                $questionValues['q5']
            );

            $sheet->setCellValue(
                "K{$row}",
                $questionValues['q6']
            );

            $sheet->setCellValue(
                "L{$row}",
                $questionValues['q7']
            );

            $sheet->setCellValue(
                "M{$row}",
                $questionValues['q8']
            );

            $sheet->setCellValue(
                "N{$row}",
                $questionValues['q9']
            );

            $sheet->setCellValue(
                "O{$row}",
                $questionValues['q10']
            );

            $sheet->setCellValue(
                "P{$row}",
                $feedbackDate
            );


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
                'A3:P' . ($row - 1)
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
                'A4:P' . ($row - 1)
            )
                ->getAlignment()
                ->setVertical(
                    Alignment::VERTICAL_CENTER
                );
        }


        /*
         * ==========================================================
         * CENTER COLUMNS
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
                'C4:P' . ($row - 1)
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
            range('A', 'P') as $column
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

        $lastRow =
            max(
                3,
                $row - 1
            );

        $sheet->setAutoFilter(
            'A3:P' . $lastRow
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
            ->setZoomScale(80);


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
         * TEMP FILE
         * ==========================================================
         */

        $tempFile =
            tempnam(
                sys_get_temp_dir(),
                'evaluation_summary_'
            );


        /*
         * ==========================================================
         * WRITE EXCEL
         * ==========================================================
 */

        $writer =
            new Xlsx(
                $spreadsheet
            );

        $writer->save(
            $tempFile
        );


        /*
         * ==========================================================
         * DOWNLOAD
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


    /**
     * ============================================================
     * GET FARMER FEEDBACK RECORDS
     * ============================================================
     *
     * Each valid Q1-Q10 answer becomes one record.
     */
    private function getFeedbackRecords(): array
    {
        $this->loadModel('Feedbacks');

        $feedbacks = $this->Feedbacks
            ->find()
            ->contain([
                'Farmers',
                'Evaluations'
            ])
            ->order([
                'Feedbacks.feedback_date' => 'DESC'
            ])
            ->all()
            ->toArray();


        $questions = $this->getQuestions();

        $records = [];


        foreach ($feedbacks as $feedback) {

            /*
             * ========================================================
             * FARMER INFORMATION
             * ========================================================
             */

            $farmerNo = '-';

            $farmerName = '-';


            if (!empty($feedback->farmer)) {

                $farmerNo =
                    $feedback->farmer->farmer_no
                    ?? '-';

                $farmerName =
                    $feedback->farmer->full_name
                    ?? '';


                if (empty($farmerName)) {

                    $farmerName = trim(
                        ($feedback->farmer->first_name ?? '') .
                        ' ' .
                        ($feedback->farmer->last_name ?? '')
                    );
                }


                if (empty($farmerName)) {
                    $farmerName = '-';
                }
            }


            /*
             * ========================================================
             * DECODE ANSWERS
             * ========================================================
             */

            $answers = [];

            if (
                $feedback->answer !== null &&
                trim((string)$feedback->answer) !== ''
            ) {

                $decoded = json_decode(
                    (string)$feedback->answer,
                    true
                );

                if (is_array($decoded)) {
                    $answers = $decoded;
                }
            }


            /*
             * ========================================================
             * FEEDBACK DATE
             * ========================================================
             */

            $date =
                $feedback->feedback_date
                ?? $feedback->created
                ?? null;


            /*
             * ========================================================
             * CREATE ONE RECORD PER VALID QUESTION
             * ========================================================
             */

            foreach ($questions as $key => $question) {

                if (!array_key_exists($key, $answers)) {
                    continue;
                }


                $rating = $this->normalizeAnswer(
                    $answers[$key]
                );


                if ($rating === null) {
                    continue;
                }


                $records[] = [

                    'farmer_no' =>
                        $farmerNo,

                    'farmer_name' =>
                        $farmerName,

                    'question' =>
                        $question,

                    'rating' =>
                        $rating,

                    'created' =>
                        $date
                ];
            }
        }


        return $records;
    }


    /**
     * ============================================================
     * QUESTION SUMMARY
     * ============================================================
     *
     * Reads Q1-Q10 from:
     *
     * feedbacks.answer
     */
    private function getQuestionSummary(): array
    {
        $questions = $this->getQuestions();


        /*
         * ============================================================
         * INITIALIZE
         * ============================================================
         */

        $summary = [];

        foreach ($questions as $number => $question) {

            $summary[$number] = [

                'question' =>
                    $question,

                'rating_1' =>
                    0,

                'rating_2' =>
                    0,

                'rating_3' =>
                    0,

                'rating_4' =>
                    0,

                'rating_5' =>
                    0,

                'total' =>
                    0,

                'average' =>
                    0,

                'label' =>
                    'No Response'
            ];
        }


        /*
         * ============================================================
         * GET FEEDBACKS
         * ============================================================
         */

        $feedbacks = $this->Feedbacks
            ->find()
            ->select([
                'id',
                'answer'
            ])
            ->where([
                'answer IS NOT' => null
            ])
            ->all();


        /*
         * ============================================================
         * PROCESS ANSWERS
         * ============================================================
         */

        foreach ($feedbacks as $feedback) {

            if (
                $feedback->answer === null ||
                trim((string)$feedback->answer) === ''
            ) {
                continue;
            }


            $answers = json_decode(
                (string)$feedback->answer,
                true
            );


            if (!is_array($answers)) {
                continue;
            }


            foreach ($questions as $number => $question) {

                $key =
                    'q' . $number;


                if (!array_key_exists($key, $answers)) {
                    continue;
                }


                $rating = $this->normalizeAnswer(
                    $answers[$key]
                );


                /*
                 * Skip invalid / N/A answers
                 */

                if ($rating === null) {
                    continue;
                }


                /*
                 * Increment rating count
                 */

                $summary[$number][
                    'rating_' . $rating
                ]++;

                /*
                 * Increment total
                 */

                $summary[$number]['total']++;
            }
        }


        /*
         * ============================================================
         * CALCULATE AVERAGE + LABEL
         * ============================================================
         */

        foreach ($summary as &$item) {

            /*
             * No response
             */

            if ($item['total'] <= 0) {

                $item['average'] = 0;

                $item['label'] =
                    'No Response';

                continue;
            }


            /*
             * Weighted total
             */

            $totalScore =

                ($item['rating_1'] * 1) +

                ($item['rating_2'] * 2) +

                ($item['rating_3'] * 3) +

                ($item['rating_4'] * 4) +

                ($item['rating_5'] * 5);


            /*
             * Average
             */

            $item['average'] =
                round(
                    $totalScore /
                    $item['total'],
                    2
                );


            /*
             * Survey response interpretation
             */

            if ($item['average'] >= 4.21) {

                $item['label'] =
                    'Strongly Agree';

            } elseif ($item['average'] >= 3.41) {

                $item['label'] =
                    'Agree';

            } elseif ($item['average'] >= 2.61) {

                $item['label'] =
                    'Neutral';

            } elseif ($item['average'] >= 1.81) {

                $item['label'] =
                    'Disagree';

            } else {

                $item['label'] =
                    'Strongly Disagree';
            }
        }

        unset($item);


        return array_values(
            $summary
        );
    }


    /**
     * ============================================================
     * SURVEY QUESTIONS
     * ============================================================
     *
     * Q1-Q10
     */
    private function getQuestions(): array
    {
        return [

            1 =>
                'I am satisfied with the service/intervention that I received from the DA.',

            2 =>
                'I spent a reasonable amount of time waiting to receive the seed subsidy.',

            3 =>
                'I received the seed subsidy that I needed and that was promised by the Department of Agriculture, following the prescribed distribution procedures.',

            4 =>
                'The City Agriculture Office was easily accessible and could be approached or contacted regarding the seed subsidy distribution.',

            5 =>
                'I was properly informed about the proper use, benefits, and expected results of the seed subsidy I received, and my feedback was listened to.',

            6 =>
                'I did not have to pay an unreasonable amount of fees to receive the seed subsidy. (Do not rate if the seed subsidy was provided free of charge.)',

            7 =>
                'I believe the distribution of the seed subsidy was fair to all qualified farmer beneficiaries, or "walang palakasan."',

            8 =>
                'I was treated courteously by the staff during the seed subsidy distribution, and they were helpful when I needed assistance.',

            9 =>
                'I received the seed subsidy that I needed, or, if my request was not granted, the reason for the denial was sufficiently explained to me.',

            10 =>
                'I received the seed subsidy within the expected time for its intended purpose.'
        ];
    }


    /**
     * ============================================================
     * NORMALIZE ANSWER
     * ============================================================
     *
     * Returns:
     *
     * 1-5  = valid survey answer
     * null = skipped / N/A / invalid
     */
    private function normalizeAnswer(mixed $value): ?int
    {
        if ($value === null) {
            return null;
        }


        if (is_string($value)) {

            $value = trim($value);


            if (
                $value === '' ||
                strtolower($value) === 'n/a' ||
                strtolower($value) === 'na' ||
                strtolower($value) === 'skip' ||
                strtolower($value) === 'skipped' ||
                strtolower($value) === 'not applicable'
            ) {
                return null;
            }
        }


        if (!is_numeric($value)) {
            return null;
        }


        $rating = (int)$value;


        /*
         * Zero means no response / N/A.
         */

        if ($rating === 0) {
            return null;
        }


        /*
         * Only 1-5 are valid.
         */

        if (
            $rating < 1 ||
            $rating > 5
        ) {
            return null;
        }


        return $rating;
    }


    /**
     * ============================================================
     * FORMAT ANSWER FOR EXCEL
     * ============================================================
     */
    private function formatAnswerForExport(
        mixed $value
    ): string|int
    {
        $rating =
            $this->normalizeAnswer($value);


        if ($rating === null) {
            return 'N/A';
        }


        return $rating;
    }


    /**
     * ============================================================
     * EFFECTIVENESS LABEL
     * ============================================================
     *
     * IMPORTANT:
     *
     * This method DOES NOT predict anything.
     *
     * It only converts a value that has already been assigned
     * later by the evaluation/ML process.
     *
     * 0 = Not Effective
     * 1 = Moderately Effective
     * 2 = Effective
     */
    private function getEffectivenessLabel(
        mixed $value
    ): string
    {
        if (
            $value === null ||
            $value === ''
        ) {
            return 'Pending Evaluation';
        }


        switch ((int)$value) {

            case 0:

                return 'Not Effective';


            case 1:

                return 'Moderately Effective';


            case 2:

                return 'Effective';


            default:

                return 'Pending Evaluation';
        }
    }
}
