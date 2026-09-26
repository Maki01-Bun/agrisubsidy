<!-- ============================================================
     SEED SUBSIDY EVALUATION
============================================================ -->

<div class="col-12">

    <div class="row">

        <!-- ====================================================
             OVERALL SEED SUBSIDY EVALUATION
        ==================================================== -->

        <div class="col-12 mb-3">

            <div class="card card-primary shadow-sm h-100">

                <!-- ====================================================
                     CARD HEADER
                ==================================================== -->

                <div
                    class="card-header d-flex align-items-center justify-content-between"
                >

                        <h3 class="card-title text-dark mb-0">
                            <i class="fas fa-chart-pie mr-2 text-primary"></i>
                            Overall Seed Subsidy Evaluation
                        </h3>


                    <!-- ====================================================
                         DOWNLOAD BUTTON
                    ==================================================== -->

                    <div class="card-tools evaluation-card-tools">

                        <a
                            href="<?= $this->Url->build([
                                'controller' => 'Evaluations',
                                'action' => 'downloadSummary'
                            ]) ?>"
                            class="btn btn-success evaluation-download-btn"
                            title="Download Evaluation Summary"
                        >

                            <i class="fas fa-file-excel mr-1"></i>

                            <span>
                                Download Evaluation Summary
                            </span>

                        </a>

                    </div>

                </div>


                <!-- ====================================================
                     CARD BODY
                ==================================================== -->

                <div class="card-body">

                    <!-- =================================================
                         DESCRIPTION
                    ================================================== -->

                    <div
                        class="text-muted mb-4"
                        style="font-size: 0.9rem;"
                    >

                        <i class="fas fa-info-circle mr-1"></i>

                        Distribution of effectiveness classifications
                        among all evaluated farmers.

                    </div>


                    <!-- =================================================
                         SUMMARY ROW
                    ================================================== -->

                    <div class="row">


                        <!-- =============================================
                             EFFECTIVE
                        ============================================== -->

                        <div class="col-lg-4 col-md-6 mb-3">

                            <div
                                class="card border-0 shadow-sm h-100"
                                style="background: #f8fff9;"
                            >

                                <div class="card-body">

                                    <div
                                        class="d-flex justify-content-between align-items-center mb-2"
                                    >

                                        <div>

                                            <i
                                                class="fas fa-check-circle text-success mr-1"
                                            ></i>

                                            <strong>
                                                Effective
                                            </strong>

                                        </div>


                                        <strong class="text-success">

                                            <?= number_format(
                                                (float)(
                                                    $overallEvaluation['effective']
                                                    ?? 0
                                                ),
                                                1
                                            ) ?>%

                                        </strong>

                                    </div>


                                    <div
                                        class="progress"
                                        style="height: 10px;"
                                    >

                                        <div
                                            class="progress-bar bg-success"
                                            role="progressbar"
                                            style="
                                                width:
                                                <?= min(
                                                    100,
                                                    max(
                                                        0,
                                                        (float)(
                                                            $overallEvaluation['effective']
                                                            ?? 0
                                                        )
                                                    )
                                                ) ?>%;
                                            "
                                        ></div>

                                    </div>


                                    <div
                                        class="text-muted mt-2"
                                        style="font-size: 0.78rem;"
                                    >

                                        <i class="fas fa-users mr-1"></i>

                                        <?= number_format(
                                            (int)(
                                                $overallEvaluation[
                                                    'effective_count'
                                                ]
                                                ?? 0
                                            )
                                        ) ?>

                                        evaluation<?= (
                                            (int)(
                                                $overallEvaluation[
                                                    'effective_count'
                                                ]
                                                ?? 0
                                            ) === 1
                                        ) ? '' : 's' ?>

                                    </div>

                                </div>

                            </div>

                        </div>



                        <!-- =============================================
                             MODERATELY EFFECTIVE
                        ============================================== -->

                        <div class="col-lg-4 col-md-6 mb-3">

                            <div
                                class="card border-0 shadow-sm h-100"
                                style="background: #fffdf5;"
                            >

                                <div class="card-body">

                                    <div
                                        class="d-flex justify-content-between align-items-center mb-2"
                                    >

                                        <div>

                                            <i
                                                class="fas fa-minus-circle text-warning mr-1"
                                            ></i>

                                            <strong>
                                                Moderately Effective
                                            </strong>

                                        </div>


                                        <strong class="text-warning">

                                            <?= number_format(
                                                (float)(
                                                    $overallEvaluation[
                                                        'moderately_effective'
                                                    ]
                                                    ?? 0
                                                ),
                                                1
                                            ) ?>%

                                        </strong>

                                    </div>


                                    <div
                                        class="progress"
                                        style="height: 10px;"
                                    >

                                        <div
                                            class="progress-bar bg-warning"
                                            role="progressbar"
                                            style="
                                                width:
                                                <?= min(
                                                    100,
                                                    max(
                                                        0,
                                                        (float)(
                                                            $overallEvaluation[
                                                                'moderately_effective'
                                                            ]
                                                            ?? 0
                                                        )
                                                    )
                                                ) ?>%;
                                            "
                                        ></div>

                                    </div>


                                    <div
                                        class="text-muted mt-2"
                                        style="font-size: 0.78rem;"
                                    >

                                        <i class="fas fa-users mr-1"></i>

                                        <?= number_format(
                                            (int)(
                                                $overallEvaluation[
                                                    'moderately_effective_count'
                                                ]
                                                ?? 0
                                            )
                                        ) ?>

                                        evaluation<?= (
                                            (int)(
                                                $overallEvaluation[
                                                    'moderately_effective_count'
                                                ]
                                                ?? 0
                                            ) === 1
                                        ) ? '' : 's' ?>

                                    </div>

                                </div>

                            </div>

                        </div>



                        <!-- =============================================
                             NOT EFFECTIVE
                        ============================================== -->

                        <div class="col-lg-4 col-md-6 mb-3">

                            <div
                                class="card border-0 shadow-sm h-100"
                                style="background: #fff8f8;"
                            >

                                <div class="card-body">

                                    <div
                                        class="d-flex justify-content-between align-items-center mb-2"
                                    >

                                        <div>

                                            <i
                                                class="fas fa-times-circle text-danger mr-1"
                                            ></i>

                                            <strong>
                                                Not Effective
                                            </strong>

                                        </div>


                                        <strong class="text-danger">

                                            <?= number_format(
                                                (float)(
                                                    $overallEvaluation[
                                                        'not_effective'
                                                    ]
                                                    ?? 0
                                                ),
                                                1
                                            ) ?>%

                                        </strong>

                                    </div>


                                    <div
                                        class="progress"
                                        style="height: 10px;"
                                    >

                                        <div
                                            class="progress-bar bg-danger"
                                            role="progressbar"
                                            style="
                                                width:
                                                <?= min(
                                                    100,
                                                    max(
                                                        0,
                                                        (float)(
                                                            $overallEvaluation[
                                                                'not_effective'
                                                            ]
                                                            ?? 0
                                                        )
                                                    )
                                                ) ?>%;
                                            "
                                        ></div>

                                    </div>


                                    <div
                                        class="text-muted mt-2"
                                        style="font-size: 0.78rem;"
                                    >

                                        <i class="fas fa-users mr-1"></i>

                                        <?= number_format(
                                            (int)(
                                                $overallEvaluation[
                                                    'not_effective_count'
                                                ]
                                                ?? 0
                                            )
                                        ) ?>

                                        evaluation<?= (
                                            (int)(
                                                $overallEvaluation[
                                                    'not_effective_count'
                                                ]
                                                ?? 0
                                            ) === 1
                                        ) ? '' : 's' ?>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>


                    <!-- =================================================
                         DIVIDER
                    ================================================== -->

                    <hr>


                    <!-- =================================================
                         OVERALL RESULT
                    ================================================== -->

                    <div class="text-center mt-4">


                        <div
                            class="text-muted text-uppercase"
                            style="
                                font-size: 0.75rem;
                                font-weight: 700;
                                letter-spacing: 0.6px;
                            "
                        >

                            Overall Evaluation

                        </div>


                        <?php

                        $overallResult =
                            $overallEvaluation['overall']
                            ?? 'No Evaluation';


                        $overallBadge =
                            'badge-secondary';


                        $overallIcon =
                            'fa-minus-circle';


                        if (
                            $overallResult ===
                            'Effective'
                        ) {

                            $overallBadge =
                                'badge-success';

                            $overallIcon =
                                'fa-check-circle';

                        } elseif (
                            $overallResult ===
                            'Moderately Effective'
                        ) {

                            $overallBadge =
                                'badge-warning';

                            $overallIcon =
                                'fa-minus-circle';

                        } elseif (
                            $overallResult ===
                            'Not Effective'
                        ) {

                            $overallBadge =
                                'badge-danger';

                            $overallIcon =
                                'fa-times-circle';
                        }

                        ?>


                        <div class="mt-2">

                            <span
                                class="badge <?= h(
                                    $overallBadge
                                ) ?>"
                                style="
                                    font-size: 1rem;
                                    padding: 0.65rem 1.1rem;
                                "
                            >

                                <i
                                    class="fas <?= h(
                                        $overallIcon
                                    ) ?> mr-1"
                                ></i>

                                <?= h(
                                    $overallResult
                                ) ?>

                            </span>

                        </div>

                    </div>


                    <!-- =================================================
                         TOTAL EVALUATED
                    ================================================== -->

                    <div
                        class="text-center text-muted mt-4"
                        style="font-size: 0.85rem;"
                    >

                        <i class="fas fa-users mr-1"></i>

                        <?= number_format(
                            (int)(
                                $overallEvaluation['total']
                                ?? 0
                            )
                        ) ?>

                        farmers evaluated

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>



<!-- ============================================================
     FEEDBACK SUMMARY BY QUESTION
============================================================ -->

<div class="col-12 mt-3">

    <div class="card card-primary shadow-sm feedback-summary-card">


        <!-- ====================================================
             CARD HEADER
        ===================================================== -->

        <div class="card-header feedback-summary-header">

            <div class="feedback-header-content">

                <div class="feedback-header-icon">

                    <i class="fas fa-chart-bar"></i>

                </div>


                <div>

                    <h3 class="card-title mb-1">

                        Feedback Summary by Question

                    </h3>

                    <br>

                    <div class="feedback-header-subtitle">

                        <i class="fas fa-info-circle mr-1"></i>

                        Distribution of farmer responses for each
                        survey question

                    </div>

                </div>

            </div>

        </div>


        <!-- ====================================================
             CARD BODY
        ===================================================== -->

        <div class="card-body feedback-summary-body">


            <!-- =================================================
                 RATING SCALE
            ================================================== -->

            <div class="feedback-scale-wrapper">

                <div class="feedback-scale-header">

                    <div class="feedback-scale-title">

                        <i class="fas fa-sliders-h mr-2"></i>

                        <span>
                            Rating Scale
                        </span>

                    </div>


                    <div class="feedback-scale-description">

                        Higher ratings indicate stronger agreement.

                    </div>

                </div>


                <div class="feedback-rating-scale">


                    <!-- RATING 1 -->

                    <div class="rating-scale-item rating-one">

                        <div class="rating-number">
                            1
                        </div>

                        <div class="rating-scale-content">

                            <div class="rating-label">
                                Strongly Disagree
                            </div>

                        </div>

                    </div>


                    <!-- RATING 2 -->

                    <div class="rating-scale-item rating-two">

                        <div class="rating-number">
                            2
                        </div>

                        <div class="rating-scale-content">

                            <div class="rating-label">
                                Disagree
                            </div>

                        </div>

                    </div>


                    <!-- RATING 3 -->

                    <div class="rating-scale-item rating-three">

                        <div class="rating-number">
                            3
                        </div>

                        <div class="rating-scale-content">

                            <div class="rating-label">
                                Neutral
                            </div>

                        </div>

                    </div>


                    <!-- RATING 4 -->

                    <div class="rating-scale-item rating-four">

                        <div class="rating-number">
                            4
                        </div>

                        <div class="rating-scale-content">

                            <div class="rating-label">
                                Agree
                            </div>

                        </div>

                    </div>


                    <!-- RATING 5 -->

                    <div class="rating-scale-item rating-five">

                        <div class="rating-number">
                            5
                        </div>

                        <div class="rating-scale-content">

                            <div class="rating-label">
                                Strongly Agree
                            </div>

                        </div>

                    </div>

                </div>

            </div>


            <!-- =================================================
                 SUMMARY TABLE
            ================================================== -->

            <div class="feedback-table-wrapper">

                <div class="table-responsive">

                    <table
                        id="feedback-summary-table"
                        class="table table-hover feedback-summary-table"
                    >

                        <thead>

                            <tr>

                                <th class="question-column">
                                    Question
                                </th>

                                <th class="rating-column text-center">
                                    <span class="table-rating-header rating-header-1">
                                        1
                                    </span>
                                </th>

                                <th class="rating-column text-center">
                                    <span class="table-rating-header rating-header-2">
                                        2
                                    </span>
                                </th>

                                <th class="rating-column text-center">
                                    <span class="table-rating-header rating-header-3">
                                        3
                                    </span>
                                </th>

                                <th class="rating-column text-center">
                                    <span class="table-rating-header rating-header-4">
                                        4
                                    </span>
                                </th>

                                <th class="rating-column text-center">
                                    <span class="table-rating-header rating-header-5">
                                        5
                                    </span>
                                </th>

                                <th class="average-column text-center">
                                    Average
                                </th>

                                <th class="label-column text-center">
                                    Result
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            <?php

                            /*
                             * =========================================================
                             * FIXED QUESTION LIST
                             * =========================================================
                             */

                            $surveyQuestions = [

                                [
                                    'number' => 1,
                                    'code' => 'Overall',
                                    'english' =>
                                        'I am satisfied with the service/intervention that I received from the DA.',
                                    'tagalog' => ''
                                ],

                                [
                                    'number' => 2,
                                    'code' => 'SQD1',
                                    'english' =>
                                        'I spent a reasonable amount of time waiting to receive the seed subsidy.',
                                    'tagalog' =>
                                        'Makatuwiran ang haba ng oras na hinintay ko bago ko natanggap ang subsidy.'
                                ],

                                [
                                    'number' => 3,
                                    'code' => 'SQD2',
                                    'english' =>
                                        'I received the seed subsidy that I needed and that was promised by the Department of Agriculture, following the prescribed distribution procedures.',
                                    'tagalog' =>
                                        'Natatanggap ko ang subsidiya sa binhi na kailangan ko at ipinangako ng Department of Agriculture, alinsunod sa itinakdang proseso ng pamamahagi.'
                                ],

                                [
                                    'number' => 4,
                                    'code' => 'SQD3',
                                    'english' =>
                                        'The City Agriculture Office was easily accessible and could be approached or contacted regarding the seed subsidy distribution.',
                                    'tagalog' =>
                                        'Madaling puntahan o makontak ang City Agriculture Office tungkol sa pamamahagi ng subsidiya sa binhi.'
                                ],

                                [
                                    'number' => 5,
                                    'code' => 'SQD4',
                                    'english' =>
                                        'I was properly informed about the proper use, benefits, and expected results of the seed subsidy I received, and my feedback was listened to.',
                                    'tagalog' =>
                                        'Naipaliwanag sa akin nang maayos ang tamang paggamit, mga benepisyo, at inaasahang resulta ng subsidiya sa binhi na aking natanggap, at pinakinggan ang aking mga komento o suhestiyon.'
                                ],

                                [
                                    'number' => 6,
                                    'code' => 'SQD5',
                                    'english' =>
                                        'I did not have to pay an unreasonable amount of fees to receive the seed subsidy. (Do not rate if the seed subsidy was provided free of charge.)',
                                    'tagalog' =>
                                        'Hindi ako nagbayad ng hindi makatuwirang halaga upang matanggap ang subsidiya sa binhi. (Huwag sagutan kung ang subsidiya sa binhi ay ibinigay nang libre.)'
                                ],

                                [
                                    'number' => 7,
                                    'code' => 'SQD6',
                                    'english' =>
                                        'I believe the distribution of the seed subsidy was fair to all qualified farmer beneficiaries, or "walang palakasan."',
                                    'tagalog' =>
                                        'Naniniwala ako na naging patas ang pamamahagi ng subsidiya sa binhi sa lahat ng kwalipikadong benepisyaryong magsasaka, o "walang palakasan."'
                                ],

                                [
                                    'number' => 8,
                                    'code' => 'SQD7',
                                    'english' =>
                                        'I was treated courteously by the staff during the seed subsidy distribution, and they were helpful when I needed assistance.',
                                    'tagalog' =>
                                        'Magalang akong pinakitunguhan ng mga kawani sa panahon ng pamamahagi ng subsidiya sa binhi, at tinulungan nila ako nang kailangan ko ng kanilang tulong.'
                                ],

                                [
                                    'number' => 9,
                                    'code' => 'SQD8',
                                    'english' =>
                                        'I received the seed subsidy that I needed, or, if my request was not granted, the reason for the denial was sufficiently explained to me.',
                                    'tagalog' =>
                                        'Natatanggap ko ang subsidiya sa binhi na kailangan ko, o kung hindi naibigay ang aking kahilingan, ipinaliwanag sa akin nang maayos ang dahilan kung bakit ito hindi ipinagkaloob.'
                                ],

                                [
                                    'number' => 10,
                                    'code' => 'TDD1',
                                    'english' =>
                                        'I received the seed subsidy within the expected time for its intended purpose.',
                                    'tagalog' =>
                                        'Natanggap ko ang subsidiya sa binhi sa itinakdang oras na kinakailangan para sa layunin nito.'
                                ]

                            ];


                            /*
                             * =========================================================
                             * DISPLAY QUESTIONS
                             * =========================================================
                             */

                            foreach (
                                $surveyQuestions
                                as $index => $surveyQuestion
                            ):

                                $question =
                                    $questionSummary[$index]
                                    ?? [];


                                /*
                                 * QUESTION NUMBER
                                 */

                                $questionNumber =
                                    $surveyQuestion['number'];


                                /*
                                 * AVERAGE
                                 */

                                $average =
                                    (float)(
                                        $question['average']
                                        ?? 0
                                    );


                                /*
                                 * LABEL
                                 */

                                $label =
                                    $question['label']
                                    ?? 'No Response';


                                /*
                                 * BADGE
                                 */

                                switch ($label) {

                                    case 'Strongly Agree':

                                        $badge =
                                            'badge-success';

                                        $labelIcon =
                                            'fa-thumbs-up';

                                        break;


                                    case 'Agree':

                                        $badge =
                                            'badge-primary';

                                        $labelIcon =
                                            'fa-check';

                                        break;


                                    case 'Neutral':

                                        $badge =
                                            'badge-warning';

                                        $labelIcon =
                                            'fa-minus';

                                        break;


                                    case 'Disagree':

                                        $badge =
                                            'badge-danger';

                                        $labelIcon =
                                            'fa-times';

                                        break;


                                    case 'Strongly Disagree':

                                        $badge =
                                            'badge-danger';

                                        $labelIcon =
                                            'fa-thumbs-down';

                                        break;


                                    default:

                                        $badge =
                                            'badge-secondary';

                                        $labelIcon =
                                            'fa-minus-circle';

                                        break;
                                }


                                /*
                                 * TOTAL RESPONSES
                                 */

                                $totalResponses =
                                    (int)(
                                        $question['total']
                                        ?? 0
                                    );


                                /*
                                 * AVERAGE PERCENTAGE
                                 */

                                $averagePercent = 0;

                                if ($average > 0) {

                                    $averagePercent =
                                        min(
                                            100,
                                            ($average / 5) * 100
                                        );

                                }

                            ?>


                                <tr>


                                    <!-- =====================================
                                         QUESTION
                                    ====================================== -->

                                    <td class="question-cell">

                                        <div class="question-content">

                                            <div class="question-number">

                                                <?= $questionNumber ?>

                                            </div>


                                            <div class="question-icon">

                                                <i class="fas fa-question"></i>

                                            </div>


                                            <div class="question-text">

                                                <div class="question-code">

                                                    <?= h(
                                                        $surveyQuestion['code']
                                                    ) ?>

                                                </div>


                                                <div class="question-english">

                                                    <?= h(
                                                        $surveyQuestion['english']
                                                    ) ?>

                                                </div>


                                                <?php if (
                                                    !empty(
                                                        $surveyQuestion['tagalog']
                                                    )
                                                ): ?>

                                                    <div
                                                        class="question-tagalog text-muted"
                                                        style="
                                                            font-size: 0.82rem;
                                                            line-height: 1.4;
                                                            margin-top: 5px;
                                                        "
                                                    >

                                                        <?= h(
                                                            $surveyQuestion['tagalog']
                                                        ) ?>

                                                    </div>

                                                <?php endif; ?>

                                            </div>

                                        </div>


                                        <div class="question-response-count">

                                            <i class="fas fa-users mr-1"></i>

                                            <?= number_format(
                                                $totalResponses
                                            ) ?>

                                            response<?= (
                                                $totalResponses == 1
                                            ) ? '' : 's' ?>

                                        </div>

                                    </td>


                                    <!-- RATING 1 -->

                                    <td class="text-center rating-cell">

                                        <span class="rating-count rating-count-1">

                                            <?= (int)(
                                                $question['rating_1']
                                                ?? 0
                                            ) ?>

                                        </span>

                                    </td>


                                    <!-- RATING 2 -->

                                    <td class="text-center rating-cell">

                                        <span class="rating-count rating-count-2">

                                            <?= (int)(
                                                $question['rating_2']
                                                ?? 0
                                            ) ?>

                                        </span>

                                    </td>


                                    <!-- RATING 3 -->

                                    <td class="text-center rating-cell">

                                        <span class="rating-count rating-count-3">

                                            <?= (int)(
                                                $question['rating_3']
                                                ?? 0
                                            ) ?>

                                        </span>

                                    </td>


                                    <!-- RATING 4 -->

                                    <td class="text-center rating-cell">

                                        <span class="rating-count rating-count-4">

                                            <?= (int)(
                                                $question['rating_4']
                                                ?? 0
                                            ) ?>

                                        </span>

                                    </td>


                                    <!-- RATING 5 -->

                                    <td class="text-center rating-cell">

                                        <span class="rating-count rating-count-5">

                                            <?= (int)(
                                                $question['rating_5']
                                                ?? 0
                                            ) ?>

                                        </span>

                                    </td>


                                    <!-- AVERAGE -->

                                    <td class="text-center">

                                        <div class="average-wrapper">

                                            <div class="average-main">

                                                <span class="average-value">

                                                    <?= number_format(
                                                        $average,
                                                        2
                                                    ) ?>

                                                </span>

                                                <i
                                                    class="fas fa-star average-star"
                                                ></i>

                                            </div>


                                            <?php if ($average > 0): ?>

                                                <div class="average-progress">

                                                    <div
                                                        class="average-progress-bar"
                                                        style="
                                                            width:
                                                            <?= $averagePercent ?>%;
                                                        "
                                                    ></div>

                                                </div>


                                                <div class="average-scale">

                                                    <?= number_format(
                                                        $averagePercent,
                                                        0
                                                    ) ?>% of maximum

                                                </div>

                                            <?php else: ?>

                                                <div class="average-no-response">

                                                    No rating

                                                </div>

                                            <?php endif; ?>

                                        </div>

                                    </td>


                                    <!-- RESULT -->

                                    <td class="text-center">

                                        <span
                                            class="feedback-label <?= h(
                                                $badge
                                            ) ?>"
                                        >

                                            <i
                                                class="fas <?= h(
                                                    $labelIcon
                                                ) ?> mr-1"
                                            ></i>

                                            <?= h($label) ?>

                                        </span>

                                    </td>

                                </tr>


                            <?php endforeach; ?>


                            <?php if (empty($questionSummary)): ?>

                                <tr>

                                    <td
                                        colspan="8"
                                        class="feedback-empty-state"
                                    >

                                        <div class="empty-feedback-icon">

                                            <i
                                                class="fas fa-comment-slash"
                                            ></i>

                                        </div>


                                        <div class="empty-feedback-title">

                                            No Feedback Responses

                                        </div>


                                        <div class="empty-feedback-text">

                                            No farmer feedback responses
                                            are available yet.

                                        </div>

                                    </td>

                                </tr>

                            <?php endif; ?>

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </div>

</div>