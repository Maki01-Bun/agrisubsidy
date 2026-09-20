<div class="analytics-page">

    <div class="analytics-title">
        <i class="fas fa-chart-line"></i>
        AgriSubsidy Data Analytics
    </div>
    <!-- MODEL SUMMARY -->
    <div class="row g-3">
        <!-- STATUS -->
        <div class="col-lg-6 col-md-6">
            <div class="model-card">
                <div class="model-icon">
                    <i class="fas fa-brain"></i>
                </div>
                <div>
                    <div class="model-label">
                        Model Status
                    </div>
                    <div class="model-value text-success">
                        Active
                    </div>
                    <small>
                        Random Forest Classifier
                    </small>
                </div>
            </div>
        </div>
        <!-- DATA USED -->
        <div class="col-lg-6 col-md-6">
            <div class="model-card">
                <div class="model-icon">
                    <i class="fas fa-database"></i>
                </div>
                <div>
                    <div class="model-label">
                        Data Used
                    </div>
                    <div class="model-value">
                        <?= number_format($totalEvaluations) ?>
                    </div>
                    <small>
                        Evaluation Records
                    </small>
                </div>
            </div>
        </div>
    </div>
 <!-- =========================================================
     SUBSIDY RECEIVED RECORDS
========================================================= -->

<div class="card subsidy-received-card mt-3">

    <!-- =====================================================
         HEADER
    ====================================================== -->

    <div class="subsidy-received-header">

        <div class="subsidy-received-header-inner">

            <!-- TITLE -->

            <div class="subsidy-received-title">

                <h3>
                    Beneficiaries Received the Subsidy
                </h3>

            </div>


            <!-- FILTER + DOWNLOAD -->

            <div class="subsidy-received-tools">

                <!-- LOCATION FILTER -->

                <div class="subsidy-location-filter">

                    <select
                        id="receivedLocationFilter"
                        class="form-control"
                    >

                        <option value="ALL">
                            All Locations
                        </option>

                        <?php
                        $receivedLocations =
                            $receivedLocations ?? [];
                        ?>

                        <?php foreach (
                            $receivedLocations
                            as $location
                        ): ?>

                            <option
                                value="<?= h($location) ?>"
                            >
                                <?= h($location) ?>
                            </option>

                        <?php endforeach; ?>

                    </select>

                </div>


                <!-- DOWNLOAD -->

                <button
                    type="button"
                    id="downloadReceivedSubsidy"
                    class="btn subsidy-download-btn"
                >
                    Download
                </button>

            </div>

        </div>

    </div>


    <!-- =====================================================
         BODY
    ====================================================== -->

    <div class="subsidy-received-body">

        <?php
        $receivedRecords =
            $receivedRecords ?? [];
        ?>


        <?php if (!empty($receivedRecords)): ?>


            <!-- =================================================
                 RECORD COUNT
            ================================================== -->

            <div
                class="received-record-count"
                id="receivedRecordCount"
            >
                Showing
                <strong>
                    <?= count($receivedRecords) ?>
                </strong>
                received subsidy record(s)
            </div>


            <!-- =================================================
                 TABLE
            ================================================== -->

            <div class="table-responsive received-table-wrapper">

                <table
                    id="receivedSubsidyTable"
                    class="table subsidy-received-table mb-0"
                >

                    <thead>

                        <tr>

                            <th>#</th>

                            <th>
                                LGU RSBSA Number
                            </th>

                            <th>
                                Farmer Name
                            </th>

                            <th>
                                Distribution Code
                            </th>

                            <th>
                                Subsidy Item
                            </th>

                            <th>
                                Quantity (tons)
                            </th>

                            <th>
                                Location
                            </th>

                            <th>
                                Distribution Date
                            </th>

                            <th>
                                Distribution Time
                            </th>

                            <th>
                                Received Date
                            </th>

                            <th>
                                Status
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        <?php foreach (
                            $receivedRecords
                            as $index => $record
                        ): ?>

                            <?php

                            $location =
                                trim(
                                    (string)(
                                        $record[
                                            'barangay'
                                        ] ?? 'N/A'
                                    )
                                );

                            if (
                                $location === ''
                            ) {

                                $location =
                                    'N/A';
                            }

                            ?>

                            <tr
                                data-location="<?= h($location) ?>"
                            >

                                <!-- NUMBER -->

                                <td
                                    class="row-number"
                                >
                                    <?= $index + 1 ?>
                                </td>


                                <!-- FARMER NO -->

                                <td>

                                    <strong
                                        class="farmer-number"
                                    >
                                        <?= h(
                                            $record[
                                                'farmer_no'
                                            ] ?? '-'
                                        ) ?>
                                    </strong>

                                </td>


                                <!-- FARMER NAME -->

                                <td>

                                    <strong
                                        class="farmer-name"
                                    >
                                        <?= h(
                                            $record[
                                                'farmer_name'
                                            ] ?? '-'
                                        ) ?>
                                    </strong>

                                </td>


                                <!-- DISTRIBUTION CODE -->

                                <td>

                                    <span
                                        class="program-badge"
                                    >
                                        <?= h(
                                            $record[
                                                'program_code'
                                            ] ?? '-'
                                        ) ?>
                                    </span>

                                </td>


                                <!-- SUBSIDY ITEM -->

                                <td>

                                    <?= h(
                                        $record[
                                            'subsidy_item'
                                        ]
                                        ?? 'Seed Subsidy'
                                    ) ?>

                                </td>


                                <!-- QUANTITY -->

                                <td>

                                    <span
                                        class="quantity-value"
                                    >
                                        <?= h(
                                            $record[
                                                'quantity'
                                            ] ?? '0.00'
                                        ) ?>
                                    </span>

                                </td>


                                <!-- LOCATION -->

                                <td>

                                    <span
                                        class="location-value"
                                    >
                                        <?= h(
                                            $location
                                        ) ?>
                                    </span>

                                </td>


                                <!-- DISTRIBUTION DATE -->

                                <td>

                                    <?= h(
                                        $record[
                                            'distribution_date'
                                        ] ?? '-'
                                    ) ?>

                                </td>


                                <!-- DISTRIBUTION TIME -->

                                <td>

                                    <?= h(
                                        $record[
                                            'distribution_time'
                                        ] ?? '-'
                                    ) ?>

                                </td>


                                <!-- RECEIVED DATE -->

                                <td>

                                    <?= h(
                                        $record[
                                            'received_date'
                                        ] ?? '-'
                                    ) ?>

                                </td>


                                <!-- STATUS -->

                                <td>

                                    <span
                                        class="received-status"
                                    >
                                        <?= h(
                                            $record[
                                                'status'
                                            ] ?? 'Received'
                                        ) ?>
                                    </span>

                                </td>

                            </tr>

                        <?php endforeach; ?>

                    </tbody>

                </table>

            </div>


            <!-- =================================================
                 FILTER EMPTY STATE
            ================================================== -->

            <div
                id="receivedNoFilterResult"
                class="received-filter-empty"
                style="display:none;"
            >

                <h5>
                    No Records Found
                </h5>

                <p>
                    No received subsidy records were found
                    for the selected location.
                </p>

            </div>


        <?php else: ?>


            <!-- =================================================
                 EMPTY STATE
            ================================================== -->

            <div
                class="subsidy-received-empty"
            >

                <h5>
                    No Subsidy Records Yet
                </h5>

                <p>
                    Farmers who receive the subsidy
                    will appear here.
                </p>

            </div>


        <?php endif; ?>

    </div>

</div>
        <!-- PROGRAM EFFECTIVENESS TABLE -->
    <div class="table-responsive mt-3">
        <table class="table table-hover mb-0">
            <thead style="background:#07851f; color:white;">
                <tr>
                    <th class="text-center">Rank</th>
                    <th class="text-center">Program Code</th>
                    <th class="text-center">Program Name</th>
                    <th class="text-center">Feedback<br>Responses</th>
                    <th class="text-center">Average Feedback<br>Rating</th>
                    <th class="text-center">Performance</th>
                    <th class="text-center">Evaluation Results</th>
                </tr>
            </thead>
    
            <tbody>
                <?php if (!empty($programEffectiveness)): ?>
    
                    <?php foreach ($programEffectiveness as $index => $program): ?>
    
                        <?php
                        $rank = $index + 1;
    
                        $programCode = $program['program_code'] ?? 'N/A';
                        $programName = $program['program_name'] ?? 'N/A';
    
                        $totalFeedbacks = (int)($program['total_feedbacks'] ?? 0);
                        $averageRating = (float)($program['effectiveness_rating'] ?? 0);
    
                        $effectiveCount =
                            (int)($program['effective_count'] ?? 0);
    
                        $moderatelyEffectiveCount =
                            (int)($program['moderately_effective_count'] ?? 0);
    
                        $notEffectiveCount =
                            (int)($program['not_effective_count'] ?? 0);
    
                        $isMostEffective =
                            !empty($mostEffectiveProgram) &&
                            ($mostEffectiveProgram['program_code'] ?? '') === $programCode;
    
                        $ratingPercent = ($averageRating / 5) * 100;
                        ?>
    
                        <tr>
    
                            <!-- RANK -->
                            <td class="text-center align-middle">
                                <?php if ($isMostEffective): ?>
    
                                    <span class="badge badge-success"
                                          style="font-size:14px; padding:7px 10px;">
                                        <i class="fas fa-trophy"></i>
                                        #<?= $rank ?>
                                    </span>
    
                                <?php else: ?>
    
                                    <strong>#<?= $rank ?></strong>
    
                                <?php endif; ?>
                            </td>
    
                            <!-- PROGRAM CODE -->
                            <td class="align-middle">
                                <strong>
                                    <?= h($programCode) ?>
                                </strong>
                            </td>
    
                            <!-- PROGRAM NAME -->
                            <td class="align-middle">
                                <strong>
                                    <?= h($programName) ?>
                                </strong>
                            </td>
    
                            <!-- FEEDBACK RESPONSES -->
                            <td class="text-center align-middle">
                                <?= $totalFeedbacks ?>
                            </td>
    
                            <!-- AVERAGE FEEDBACK RATING -->
                            <td class="text-center align-middle">
    
                                <div style="font-size:20px; font-weight:bold;">
                                    <?= number_format($averageRating, 2) ?>
                                    / 5.00
                                </div>
    
                                <div style="margin-top:5px; font-size:20px;">
    
                                    <?php
                                    $fullStars = floor($averageRating);
                                    $hasHalfStar = ($averageRating - $fullStars) >= 0.5;
                                    $emptyStars = 5 - $fullStars - ($hasHalfStar ? 1 : 0);
                                    ?>
    
                                    <?php for ($i = 0; $i < $fullStars; $i++): ?>
                                        <i class="fas fa-star"></i>
                                    <?php endfor; ?>
    
                                    <?php if ($hasHalfStar): ?>
                                        <i class="fas fa-star-half-alt"></i>
                                    <?php endif; ?>
    
                                    <?php for ($i = 0; $i < $emptyStars; $i++): ?>
                                        <i class="far fa-star"></i>
                                    <?php endfor; ?>
    
                                </div>
    
                            </td>
    
                            <!-- PERFORMANCE -->
                            <td class="text-center align-middle">
    
                                <?php if ($isMostEffective): ?>
    
                                    <span class="badge badge-success"
                                          style="font-size:13px; padding:7px 10px;">
                                        <i class="fas fa-trophy"></i>
                                        Most Effective
                                    </span>
    
                                <?php elseif ($averageRating >= 4.00): ?>
    
                                    <span class="badge badge-success">
                                        Highly Rated
                                    </span>
    
                                <?php elseif ($averageRating >= 3.00): ?>
    
                                    <span class="badge badge-warning">
                                        Moderately Rated
                                    </span>
    
                                <?php else: ?>
    
                                    <span class="badge badge-danger">
                                        Low Rated
                                    </span>
    
                                <?php endif; ?>
    
                            </td>
    
                            <!-- EVALUATION RESULTS -->
                            <td class="text-center align-middle">
    
                                <div class="mb-1">
    
                                    <span class="badge badge-success">
                                        Effective:
                                        <?= $effectiveCount ?>
                                    </span>
    
                                    <span class="badge badge-warning">
                                        Moderate:
                                        <?= $moderatelyEffectiveCount ?>
                                    </span>
    
                                </div>
    
                                <div>
    
                                    <span class="badge badge-danger">
                                        Not Effective:
                                        <?= $notEffectiveCount ?>
                                    </span>
    
                                </div>
    
                            </td>
    
                        </tr>
    
                    <?php endforeach; ?>
    
                <?php else: ?>
    
                    <tr>
                        <td colspan="7"
                            class="text-center text-muted py-4">
    
                            <i class="fas fa-info-circle"></i>
                            No program evaluation data available.
    
                        </td>
                    </tr>
    
                <?php endif; ?>
    
            </tbody>
        </table>
    </div>
</div>

<script>

document.addEventListener(
    'DOMContentLoaded',
    function () {

        /*
         * =========================================================
         * ELEMENTS
         * =========================================================
         */

        const locationFilter =
            document.getElementById(
                'receivedLocationFilter'
            );


        const downloadButton =
            document.getElementById(
                'downloadReceivedSubsidy'
            );


        const table =
            document.getElementById(
                'receivedSubsidyTable'
            );


        const visibleCount =
            document.getElementById(
                'receivedVisibleCount'
            );


        const noResult =
            document.getElementById(
                'receivedNoFilterResult'
            );


        const activeLocation =
            document.getElementById(
                'receivedActiveLocation'
            );


        const activeLocationText =
            document.getElementById(
                'receivedActiveLocationText'
            );


        /*
         * =========================================================
         * MAKE SURE REQUIRED ELEMENTS EXIST
         * =========================================================
         */

        if (
            !locationFilter ||
            !table
        ) {
            return;
        }


        /*
         * =========================================================
         * GET TABLE ROWS
         * =========================================================
         */

        const rows =
            Array.from(
                table.querySelectorAll(
                    'tbody tr'
                )
            );


        /*
         * =========================================================
         * UPDATE ROW NUMBERS
         * =========================================================
         */

        function updateRowNumbers(
            visibleRows
        ) {

            visibleRows.forEach(
                function (
                    row,
                    index
                ) {

                    const numberCell =
                        row.querySelector(
                            '.row-number'
                        );


                    if (
                        numberCell
                    ) {

                        numberCell.textContent =
                            index + 1;

                    }

                }
            );

        }


        /*
         * =========================================================
         * FILTER RECORDS
         * =========================================================
         */

        function filterRecords() {

            const selectedLocation =
                locationFilter.value;


            let visibleRows = [];


            rows.forEach(
                function (
                    row
                ) {

                    const rowLocation =
                        (
                            row.dataset.location
                            || ''
                        ).trim();


                    const selected =
                        (
                            selectedLocation
                            || ''
                        ).trim();


                    const shouldShow =
                        selected === 'ALL'
                        ||
                        rowLocation.toLowerCase()
                        ===
                        selected.toLowerCase();


                    if (
                        shouldShow
                    ) {

                        row.style.display =
                            '';

                        visibleRows.push(
                            row
                        );

                    } else {

                        row.style.display =
                            'none';

                    }

                }
            );


            /*
             * =====================================================
             * UPDATE ROW NUMBERS
             * =====================================================
             */

            updateRowNumbers(
                visibleRows
            );


            /*
             * =====================================================
             * UPDATE VISIBLE COUNT
             * =====================================================
             */

            if (
                visibleCount
            ) {

                visibleCount.textContent =
                    visibleRows.length;

            }


            /*
             * =====================================================
             * ACTIVE LOCATION
             * =====================================================
             */

            if (
                activeLocation
            ) {

                if (
                    selectedLocation === 'ALL'
                ) {

                    activeLocation.style.display =
                        'none';

                } else {

                    activeLocation.style.display =
                        'inline-flex';

                }

            }


            if (
                activeLocationText
            ) {

                activeLocationText.textContent =
                    selectedLocation === 'ALL'
                        ? ''
                        : selectedLocation;

            }


            /*
             * =====================================================
             * EMPTY FILTER RESULT
             * =====================================================
             */

            if (
                noResult
            ) {

                if (
                    visibleRows.length === 0
                ) {

                    noResult.style.display =
                        'block';

                } else {

                    noResult.style.display =
                        'none';

                }

            }

        }


        /*
         * =========================================================
         * LOCATION FILTER CHANGE
         * =========================================================
         */

        locationFilter.addEventListener(
            'change',
            function () {

                filterRecords();

            }
        );


        /*
         * =========================================================
         * DOWNLOAD EXCEL
         * =========================================================
         */

        if (
            downloadButton
        ) {

            downloadButton.addEventListener(
                'click',
                function () {

                    const selectedLocation =
                        locationFilter.value;


                    /*
                     * =================================================
                     * CHECK VISIBLE RECORDS
                     * =================================================
                     */

                    const visibleRows =
                        rows.filter(
                            function (
                                row
                            ) {

                                return (
                                    row.style.display
                                    !==
                                    'none'
                                );

                            }
                        );


                    if (
                        visibleRows.length === 0
                    ) {

                        alert(
                            'There are no received subsidy records to download for the selected location.'
                        );

                        return;

                    }


                    /*
                     * =================================================
                     * DISABLE BUTTON WHILE DOWNLOADING
                     * =================================================
                     */

                    const originalHTML =
                        downloadButton.innerHTML;


                    downloadButton.disabled =
                        true;


                    downloadButton.innerHTML =
                        '<i class="fas fa-spinner fa-spin mr-1"></i> Generating...';


                    /*
                     * =================================================
                     * BUILD DOWNLOAD URL
                     * =================================================
                     *
                     * The actual Excel file is generated by
                     * PhpSpreadsheet in the RecordsController.
                     *
                     */

                    const baseUrl =
                        '<?= $this->Url->build([
                            "controller" => "Records",
                            "action" => "downloadReceivedSubsidyExcel"
                        ]) ?>';


                    const url =
                        baseUrl +
                        '?location=' +
                        encodeURIComponent(
                            selectedLocation
                        );


                    /*
                     * =================================================
                     * DOWNLOAD FILE
                     * =================================================
                     */

                    window.location.href =
                        url;


                    /*
                     * =================================================
                     * RESTORE BUTTON
                     * =================================================
                     */

                    setTimeout(
                        function () {

                            downloadButton.disabled =
                                false;

                            downloadButton.innerHTML =
                                originalHTML;

                        },
                        1500
                    );

                }
            );

        }


        /*
         * =========================================================
         * INITIAL FILTER
         * =========================================================
         */

        filterRecords();

    }
);

</script>