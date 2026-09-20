<!-- ============================================================
     EVALUATIONS
============================================================ -->

<div class="col-12">

    <div class="card card-primary shadow-sm">

        <!-- ====================================================
             CARD HEADER
        ===================================================== -->

        <div class="card-header d-flex align-items-center justify-content-between">

            <h3 class="card-title text-dark mb-0">
                <i class="fas fa-clipboard-check mr-2"></i>
                Evaluations
            </h3>

            <div class="card-tools evaluation-card-tools">

                <!-- Download Button -->
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
        ===================================================== -->

        <div class="card-body">

            <div class="table-responsive">

                <table
                    id="evaluations-table"
                    class="table table-bordered table-hover w-100 evaluation-summary-table"
                >

                    <thead>

                        <tr>

                            <th>
                                <i class="fas fa-layer-group mr-1"></i>
                                Program Name
                            </th>

                            <th class="text-center">
                                <i class="fas fa-ruler-combined mr-1"></i>
                                Farm Size (ha)
                            </th>

                            <th class="text-center">
                                <i class="fas fa-seedling mr-1"></i>
                                Yield After (tons/ha)
                            </th>

                            <th class="text-center">
                                <i class="fas fa-chart-line mr-1"></i>
                                Effectiveness Label
                            </th>

                            <th class="text-center">
                                <i class="fas fa-cogs mr-1"></i>
                                Action
                            </th>

                        </tr>

                    </thead>

                </table>

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
                        Distribution of farmer responses for each survey question
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


                    <!-- =================================================
                         RATING 1
                    ================================================== -->

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


                    <!-- =================================================
                         RATING 2
                    ================================================== -->

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


                    <!-- =================================================
                         RATING 3
                    ================================================== -->

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


                    <!-- =================================================
                         RATING 4
                    ================================================== -->

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


                    <!-- =================================================
                         RATING 5
                    ================================================== -->

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

                                <?php if (!empty($questionSummary)): ?>

                                    <?php foreach ($questionSummary as $index => $question): ?>

                                        <?php

                                        /*
                                         * ==================================================
                                         * QUESTION NUMBER
                                         * ==================================================
                                         */

                                        $questionNumber = $index + 1;


                                        /*
                                         * ==================================================
                                         * AVERAGE
                                         * ==================================================
                                         */

                                        $average = (float)(
                                            $question['average'] ?? 0
                                        );


                                        /*
                                         * ==================================================
                                         * LABEL
                                         * ==================================================
                                         */

                                        $label = $question['label']
                                            ?? 'No Response';


                                        /*
                                         * ==================================================
                                         * BADGE
                                         * ==================================================
                                         */

                                        switch ($label) {

                                            case 'Strongly Agree':

                                                $badge = 'badge-success';
                                                $labelIcon = 'fa-thumbs-up';

                                                break;

                                            case 'Agree':

                                                $badge = 'badge-primary';
                                                $labelIcon = 'fa-check';

                                                break;

                                            case 'Neutral':

                                                $badge = 'badge-warning';
                                                $labelIcon = 'fa-minus';

                                                break;

                                            case 'Disagree':

                                                $badge = 'badge-danger';
                                                $labelIcon = 'fa-times';

                                                break;

                                            case 'Strongly Disagree':

                                                $badge = 'badge-danger';
                                                $labelIcon = 'fa-thumbs-down';

                                                break;

                                            default:

                                                $badge = 'badge-secondary';
                                                $labelIcon = 'fa-minus-circle';

                                                break;
                                        }


                                        /*
                                         * ==================================================
                                         * TOTAL RESPONSES
                                         * ==================================================
                                         */

                                        $totalResponses = (int)(
                                            $question['total'] ?? 0
                                        );


                                        /*
                                         * ==================================================
                                         * AVERAGE PERCENTAGE
                                         * ==================================================
                                         */

                                        $averagePercent = 0;

                                        if ($average > 0) {

                                            $averagePercent = min(
                                                100,
                                                ($average / 5) * 100
                                            );

                                        }

                                        ?>


                                        <tr>


                                            <!-- =============================================
                                                 QUESTION
                                            ============================================== -->

                                            <td class="question-cell">

                                                <div class="question-content">

                                                    <div class="question-number">

                                                        <?= $questionNumber ?>

                                                    </div>


                                                    <div class="question-icon">

                                                        <i class="fas fa-question"></i>

                                                    </div>


                                                    <div class="question-text">

                                                        <?= h(
                                                            $question['question'] ?? ''
                                                        ) ?>

                                                    </div>

                                                </div>


                                                <div class="question-response-count">

                                                    <i class="fas fa-users mr-1"></i>

                                                    <?= number_format(
                                                        $totalResponses
                                                    ) ?>

                                                    response<?= $totalResponses == 1 ? '' : 's' ?>

                                                </div>

                                            </td>


                                            <!-- =============================================
                                                 RATING 1
                                            ============================================== -->

                                            <td class="text-center rating-cell">

                                                <span class="rating-count rating-count-1">

                                                    <?= (int)(
                                                        $question['rating_1'] ?? 0
                                                    ) ?>

                                                </span>

                                            </td>


                                            <!-- =============================================
                                                 RATING 2
                                            ============================================== -->

                                            <td class="text-center rating-cell">

                                                <span class="rating-count rating-count-2">

                                                    <?= (int)(
                                                        $question['rating_2'] ?? 0
                                                    ) ?>

                                                </span>

                                            </td>


                                            <!-- =============================================
                                                 RATING 3
                                            ============================================== -->

                                            <td class="text-center rating-cell">

                                                <span class="rating-count rating-count-3">

                                                    <?= (int)(
                                                        $question['rating_3'] ?? 0
                                                    ) ?>

                                                </span>

                                            </td>


                                            <!-- =============================================
                                                 RATING 4
                                            ============================================== -->

                                            <td class="text-center rating-cell">

                                                <span class="rating-count rating-count-4">

                                                    <?= (int)(
                                                        $question['rating_4'] ?? 0
                                                    ) ?>

                                                </span>

                                            </td>


                                            <!-- =============================================
                                                 RATING 5
                                            ============================================== -->

                                            <td class="text-center rating-cell">

                                                <span class="rating-count rating-count-5">

                                                    <?= (int)(
                                                        $question['rating_5'] ?? 0
                                                    ) ?>

                                                </span>

                                            </td>


                                            <!-- =============================================
                                                 AVERAGE
                                            ============================================== -->

                                            <td class="text-center">

                                                <div class="average-wrapper">


                                                    <div class="average-main">

                                                        <span class="average-value">

                                                            <?= number_format(
                                                                $average,
                                                                2
                                                            ) ?>

                                                        </span>

                                                        <i class="fas fa-star average-star"></i>

                                                    </div>


                                                    <?php if ($average > 0): ?>

                                                        <div class="average-progress">

                                                            <div
                                                                class="average-progress-bar"
                                                                style="width: <?= $averagePercent ?>%;"
                                                            ></div>

                                                        </div>

                                                        <div class="average-scale">
                                                            <?= number_format($averagePercent, 0) ?>% of maximum
                                                        </div>

                                                    <?php else: ?>

                                                        <div class="average-no-response">
                                                            No rating
                                                        </div>

                                                    <?php endif; ?>

                                                </div>

                                            </td>


                                            <!-- =============================================
                                                 RESULT
                                            ============================================== -->

                                            <td class="text-center">

                                                <span
                                                    class="feedback-label <?= h($badge) ?>"
                                                >

                                                    <i
                                                        class="fas <?= h($labelIcon) ?> mr-1"
                                                    ></i>

                                                    <?= h($label) ?>

                                                </span>

                                            </td>


                                        </tr>


                                    <?php endforeach; ?>


                                <?php else: ?>


                                    <!-- =============================================
                                         EMPTY STATE
                                    ============================================== -->

                                    <tr>

                                        <td
                                            colspan="8"
                                            class="feedback-empty-state"
                                        >

                                            <div class="empty-feedback-icon">

                                                <i class="fas fa-comment-slash"></i>

                                            </div>


                                            <div class="empty-feedback-title">

                                                No Feedback Responses

                                            </div>


                                            <div class="empty-feedback-text">

                                                No farmer feedback responses are available yet.

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