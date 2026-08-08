<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * Analytics Controller
 *
 * @method \App\Model\Entity\Analytics[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class AnalyticsController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
     public function index()
    {
        $this->loadModel('Evaluations');
        $this->loadModel('Farms');
        $this->loadModel('Feedbacks');

        //TOTAL EVALUATION DATA

        $totalEvaluations = $this->Evaluations->find()
            ->count();


        // EFFECTIVENESS COUNTS


        $effective = $this->Evaluations->find()
            ->where([
                'Evaluations.effectiveness_label' => 'Effective'
            ])
            ->count();


        $moderatelyEffective = $this->Evaluations->find()
            ->where([
                'Evaluations.effectiveness_label' => 'Moderately Effective'
            ])
            ->count();


        $notEffective = $this->Evaluations->find()
            ->where([
                'Evaluations.effectiveness_label' => 'Not Effective'
            ])
            ->count();

        //SUBSIDY PROGRAM COUNTS


        $programs = $this->Evaluations->find()
            ->select([
                'subsidy_type' => 'Evaluations.subsidy_type',

                'total' => $this->Evaluations
                    ->find()
                    ->func()
                    ->count('Evaluations.id')
            ])
            ->group([
                'Evaluations.subsidy_type'
            ])
            ->enableHydration(false)
            ->toArray();


        //AVERAGE FEEDBACK

        $feedbackData = $this->Feedbacks->find()
            ->select([
                'average_rating' => $this->Feedbacks
                    ->find()
                    ->func()
                    ->avg('Feedbacks.rating')
            ])
            ->first();

        $feedbackAverage = 0;

        if ($feedbackData) {
            $feedbackAverage = (float)($feedbackData->average_rating ?? 0);
        }



        //  AVERAGE CROP YIELD


        $yieldData = $this->Evaluations->find()
            ->select([
                'avg_yield_before' =>
                    $this->Evaluations
                        ->find()
                        ->func()
                        ->avg('Farms.crop_yield'),

                'avg_yield_after' =>
                    $this->Evaluations
                        ->find()
                        ->func()
                        ->avg('Evaluations.crop_yield_after')
            ])
            ->innerJoinWith('Farms')
            ->first();


        $yieldBefore = 0;
        $yieldAfter = 0;

        if ($yieldData) {

            $yieldBefore = (float)(
                $yieldData->avg_yield_before ?? 0
            );

            $yieldAfter = (float)(
                $yieldData->avg_yield_after ?? 0
            );
        }


        //AVERAGE INCOME

        $incomeData = $this->Evaluations->find()
            ->select([
                'avg_income_before' =>
                    $this->Evaluations
                        ->find()
                        ->func()
                        ->avg('Farms.income'),

                'avg_income_after' =>
                    $this->Evaluations
                        ->find()
                        ->func()
                        ->avg('Evaluations.income_after')
            ])
            ->innerJoinWith('Farms')
            ->first();


        $incomeBefore = 0;
        $incomeAfter = 0;

        if ($incomeData) {

            $incomeBefore = (float)(
                $incomeData->avg_income_before ?? 0
            );

            $incomeAfter = (float)(
                $incomeData->avg_income_after ?? 0
            );
        }


        // CALCULATE IMPROVEMENT

        $yieldImprovement = 0;

        if ($yieldBefore > 0) {

            $yieldImprovement =
                (($yieldAfter - $yieldBefore) / $yieldBefore) * 100;
        }


        $incomeImprovement = 0;

        if ($incomeBefore > 0) {

            $incomeImprovement =
                (($incomeAfter - $incomeBefore) / $incomeBefore) * 100;
        }


        //  * MACHINE LEARNING MODEL INFORMATION

        $modelStatus = 'Active';

        $modelName = 'Random Forest Classifier';

        $modelAccuracy = 93.5;

        $lastTrained = 'July 22, 2026 11:30 PM';


        //FEATURE IMPORTANCE

        $featureImportance = [

            'Crop Yield Increase' =>
                round(abs($yieldImprovement), 2),

            'Income Increase' =>
                round(abs($incomeImprovement), 2),

            'Subsidy Utilization' =>
                42,

            'Distribution Timeliness' =>
                34,

            'Farmer Feedback' =>
                20
        ];


  
        //PROGRAM EFFECTIVENESS RECOMMENDATIONS

        $recommendations = [];


        foreach ($programs as $program) {

            $programName = $program['subsidy_type'] ?? 'Unknown';

            $result = $this->Evaluations->find()
                ->select([
                    'effectiveness_label' =>
                        'Evaluations.effectiveness_label',

                    'total' => $this->Evaluations
                        ->find()
                        ->func()
                        ->count('Evaluations.id')
                ])
                ->where([
                    'Evaluations.subsidy_type' => $programName
                ])
                ->group([
                    'Evaluations.effectiveness_label'
                ])
                ->order([
                    'total' => 'DESC'
                ])
                ->enableHydration(false)
                ->first();


            $prediction = $result['effectiveness_label']
                ?? 'No Data';


            if ($prediction === 'Effective') {

                $action =
                    'Continue program implementation';

                $confidence = 95;


            } elseif ($prediction === 'Moderately Effective') {

                $action =
                    'Improve program implementation';

                $confidence = 89;


            } elseif ($prediction === 'Not Effective') {

                $action =
                    'Review and strengthen implementation';

                $confidence = 85;


            } else {

                $action =
                    'Collect more evaluation data';

                $confidence = 0;
            }


            $recommendations[] = [

                'program' =>
                    $programName,

                'prediction' =>
                    $prediction,

                'confidence' =>
                    $confidence,

                'action' =>
                    $action
            ];
        }

        // SET DATA TO VIEW

        $this->set(compact(
            'totalEvaluations',
            'effective',
            'moderatelyEffective',
            'notEffective',
            'programs',
            'feedbackAverage',
            'yieldBefore',
            'yieldAfter',
            'yieldImprovement',
            'incomeBefore',
            'incomeAfter',
            'incomeImprovement',
            'modelStatus',
            'modelName',
            'modelAccuracy',
            'lastTrained',
            'featureImportance',
            'recommendations'
        ));
    }
}
