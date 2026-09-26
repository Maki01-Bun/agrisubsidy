<div class="container mt-4">

    <!-- =========================================================
         MAIN CARD
    ========================================================== -->

    <div class="card shadow">

        <!-- =====================================================
             CARD HEADER
        ====================================================== -->

        <div class="card-header text-white"
             style="background:#4E944F !important;">

            <h4 class="mb-0">

                <i class="fas fa-clipboard-check me-2"></i>

                Subsidy Effectiveness Evaluation Survey

            </h4>

        </div>


        <!-- =====================================================
             FORM START
        ====================================================== -->

        <?= $this->Form->create(null, [
            'url' => [
                'controller' => 'Feedbacks',
                'action' => 'survey'
            ],
            'id' => 'evaluations-form'
        ]) ?>


        <div class="card-body">


            <!-- =================================================
                 BASIC INFORMATION
            ================================================== -->

            <h5 class="text-success mb-3">

                <i class="fas fa-user me-2"></i>

                Basic Information

            </h5>


            <div class="row">

                <!-- =================================================
                     FARMER NAME
                ================================================== -->

                <div class="col-md-3 mb-3">

                    <label for="farmer_name">
                        Farmer Name
                    </label>

                    <input
                        type="text"
                        id="farmer_name"
                        name="farmer_name"
                        class="form-control"
                        value="<?= h($farmerName) ?>"
                        readonly
                    >

                </div>


                <!-- =================================================
                     SCHEDULE
                ================================================== -->

                <div class="col-md-3 mb-3">

                    <label for="schedule_id">
                        Schedule
                    </label>

                    <?= $this->Form->control('schedule_id', [

                        'type' => 'select',

                        'options' => $schedules,

                        'empty' => '-- Select Schedule --',

                        'class' => 'form-control',

                        'label' => false,

                        'id' => 'schedule_id'

                    ]) ?>

                </div>


                <!-- =================================================
                     FARM
                ================================================== -->

                <div class="col-md-3 mb-3">

                    <label for="farm_id">
                        Farm
                    </label>

                    <?= $this->Form->control('farm_id', [

                        'options' => $farms,

                        'empty' => '-- Select Farm --',

                        'class' => 'form-control',

                        'label' => false,

                        'id' => 'farm_id'

                    ]) ?>

                </div>


                <!-- =================================================
                     RICE TYPE
                ================================================== -->

                <div class="col-md-3 mb-3">

                    <label for="rice_type">
                        Rice Type
                    </label>

                    <?= $this->Form->control('rice_type', [

                        'options' => $rice_type,

                        'empty' => '-- Select Rice Type --',

                        'class' => 'form-control',

                        'label' => false,

                        'id' => 'rice_type'

                    ]) ?>

                </div>

            </div>


            <!-- =================================================
                 YIELD / SUBSIDY INFORMATION
            ================================================== -->

            <div class="row">


                <!-- =================================================
                     AVERAGE YIELD
                ================================================== -->

                <div class="col-md-3 mb-3">

                    <label for="average_yield">

                        Average Yield (tons/ha)

                    </label>

                    <?= $this->Form->control('average_yield', [

                        'class' => 'form-control',

                        'label' => false,

                        'id' => 'average_yield',

                        'readonly' => true,

                        'placeholder' =>
                            'Automatically calculated'

                    ]) ?>

                </div>


                <!-- =================================================
                     CROP YIELD AFTER
                ================================================== -->

                <div class="col-md-3 mb-3">

                    <label for="crop_yield_after">

                        Crop Yield After (tons/ha)

                    </label>

                    <?= $this->Form->control('crop_yield_after', [

                        'class' => 'form-control',

                        'label' => false,

                        'id' => 'crop_yield_after',

                        'type' => 'number',

                        'step' => '0.01',

                        'min' => '0'

                    ]) ?>

                </div>


                <!-- =================================================
                     SUBSIDY RECEIVED
                ================================================== -->

                <div class="col-md-3 mb-3">

                    <label for="subsidy_received">

                        Subsidy Received

                    </label>

                    <?= $this->Form->control('subsidy_received', [

                        'type' => 'select',

                        'options' => [

                            'Yes' => 'Yes',

                            'No' => 'No'

                        ],

                        'empty' =>
                            'Select Yes or No',

                        'class' => 'form-control',

                        'label' => false,

                        'id' => 'subsidy_received'

                    ]) ?>

                </div>


                <!-- =================================================
                     SELLING PRICE
                ================================================== -->

                <div class="col-md-3 mb-3">

                    <label for="selling_price">

                        Selling Price (₱/kg)

                    </label>

                    <?= $this->Form->control('selling_price', [

                        'class' => 'form-control',

                        'label' => false,

                        'id' => 'selling_price',

                        'type' => 'number',

                        'step' => '0.01',

                        'min' => '0'

                    ]) ?>

                </div>

            </div>


            <hr>


            <!-- =================================================
                 QUESTIONNAIRE TITLE
            ================================================== -->

            <h5 class="text-success mb-3">

                <i class="fas fa-list-check me-2"></i>

                Survey Questionnaire

            </h5>


            <p class="text-muted mb-3">

                Please put a check mark (✓) on the column that best
                corresponds to your response to each statement.

            </p>


            <!-- =================================================
                 QUESTIONNAIRE TABLE
            ================================================== -->

            <div class="table-responsive">

                <table
                    class="table table-bordered align-middle"
                    style="min-width:1000px;"
                >


                    <!-- =================================================
                         TABLE HEADER
                    ================================================== -->

                    <thead
                        class="text-center"
                        style="background:#4E944F;color:white;"
                    >

                        <tr>

                            <th
                                style="
                                    width:48%;
                                    vertical-align:middle;
                                "
                            >

                                DIMENSIONS / STATEMENTS

                            </th>


                            <th
                                style="
                                    width:10%;
                                    vertical-align:middle;
                                "
                            >

                                1

                                <br>

                                <small>
                                    Strongly Disagree
                                </small>

                            </th>


                            <th
                                style="
                                    width:10%;
                                    vertical-align:middle;
                                "
                            >

                                2

                                <br>

                                <small>
                                    Disagree
                                </small>

                            </th>


                            <th
                                style="
                                    width:10%;
                                    vertical-align:middle;
                                "
                            >

                                3

                                <br>

                                <small>
                                    Neutral
                                </small>

                            </th>


                            <th
                                style="
                                    width:10%;
                                    vertical-align:middle;
                                "
                            >

                                4

                                <br>

                                <small>
                                    Agree
                                </small>

                            </th>


                            <th
                                style="
                                    width:12%;
                                    vertical-align:middle;
                                "
                            >

                                5

                                <br>

                                <small>
                                    Strongly Agree
                                </small>

                            </th>

                        </tr>

                    </thead>
                    <tbody>
                    
                        <!-- =================================================
                             OVERALL
                        ================================================== -->
                    
                        <tr>
                    
                            <td>
                    
                                <div class="fw-bold mb-1">
                                    OVERALL
                                </div>
                    
                                <div>
                                    I am satisfied with the
                                    service/intervention that I
                                    received from the DA.
                                </div>
                    
                            </td>
                    
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                    
                                <td class="text-center">
                    
                                    <input
                                        type="radio"
                                        name="q1"
                                        value="<?= $i ?>"
                                        required
                                        style="
                                            width:20px;
                                            height:20px;
                                        "
                                    >
                    
                                </td>
                    
                            <?php endfor; ?>
                    
                        </tr>
                    
                    
                        <!-- =================================================
                             SERVICE QUALITY DIMENSION
                        ================================================== -->
                    
                        <tr>
                    
                            <td
                                colspan="6"
                                style="
                                    background:#f1f8f2;
                                    font-weight:bold;
                                "
                            >
                    
                                Service Quality Dimension
                    
                            </td>
                    
                        </tr>
                    
                    
                        <!-- =================================================
                             SQD1
                        ================================================== -->
                    
                        <tr>
                    
                            <td>
                    
                                <strong>SQD1.</strong>
                    
                                I spent a reasonable amount of time waiting
                                to receive the seed subsidy.
                    
                                <div
                                    class="text-muted mt-1"
                                    style="
                                        font-size:0.82rem;
                                        line-height:1.35;
                                    "
                                >
                                    Makatuwiran ang haba ng oras na hinintay ko
                                    bago ko natanggap ang subsidy.
                                </div>
                    
                            </td>
                    
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                    
                                <td class="text-center">
                    
                                    <input
                                        type="radio"
                                        name="q2"
                                        value="<?= $i ?>"
                                        required
                                        style="
                                            width:20px;
                                            height:20px;
                                        "
                                    >
                    
                                </td>
                    
                            <?php endfor; ?>
                    
                        </tr>
                    
                    
                        <!-- =================================================
                             SQD2
                        ================================================== -->
                    
                        <tr>
                    
                            <td>
                    
                                <strong>SQD2.</strong>
                    
                                I received the seed subsidy that I needed
                                and that was promised by the Department of
                                Agriculture, following the prescribed
                                distribution procedures.
                    
                                <div
                                    class="text-muted mt-1"
                                    style="
                                        font-size:0.82rem;
                                        line-height:1.35;
                                    "
                                >
                                    Natatanggap ko ang subsidiya sa binhi na
                                    kailangan ko at ipinangako ng Department
                                    of Agriculture, alinsunod sa itinakdang
                                    proseso ng pamamahagi.
                                </div>
                    
                            </td>
                    
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                    
                                <td class="text-center">
                    
                                    <input
                                        type="radio"
                                        name="q3"
                                        value="<?= $i ?>"
                                        required
                                        style="
                                            width:20px;
                                            height:20px;
                                        "
                                    >
                    
                                </td>
                    
                            <?php endfor; ?>
                    
                        </tr>
                    
                    
                        <!-- =================================================
                             SQD3
                        ================================================== -->
                    
                        <tr>
                    
                            <td>
                    
                                <strong>SQD3.</strong>
                    
                                The City Agriculture Office was easily
                                accessible and could be approached or contacted
                                regarding the seed subsidy distribution.
                    
                                <div
                                    class="text-muted mt-1"
                                    style="
                                        font-size:0.82rem;
                                        line-height:1.35;
                                    "
                                >
                                    Madaling puntahan o makontak ang City
                                    Agriculture Office tungkol sa pamamahagi
                                    ng subsidiya sa binhi.
                                </div>
                    
                            </td>
                    
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                    
                                <td class="text-center">
                    
                                    <input
                                        type="radio"
                                        name="q4"
                                        value="<?= $i ?>"
                                        required
                                        style="
                                            width:20px;
                                            height:20px;
                                        "
                                    >
                    
                                </td>
                    
                            <?php endfor; ?>
                    
                        </tr>
                    
                    
                        <!-- =================================================
                             SQD4
                        ================================================== -->
                    
                        <tr>
                    
                            <td>
                    
                                <strong>SQD4.</strong>
                    
                                I was properly informed about the proper use,
                                benefits, and expected results of the seed subsidy
                                I received, and my feedback was listened to.
                    
                                <div
                                    class="text-muted mt-1"
                                    style="
                                        font-size:0.82rem;
                                        line-height:1.35;
                                    "
                                >
                                    Naipaliwanag sa akin nang maayos ang tamang
                                    paggamit, mga benepisyo, at inaasahang resulta
                                    ng subsidiya sa binhi na aking natanggap,
                                    at pinakinggan ang aking mga komento o
                                    suhestiyon.
                                </div>
                    
                            </td>
                    
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                    
                                <td class="text-center">
                    
                                    <input
                                        type="radio"
                                        name="q5"
                                        value="<?= $i ?>"
                                        required
                                        style="
                                            width:20px;
                                            height:20px;
                                        "
                                    >
                    
                                </td>
                    
                            <?php endfor; ?>
                    
                        </tr>
                    
                    
                        <!-- =================================================
                             SQD5
                        ================================================== -->
                    
                        <tr>
                    
                            <td>
                    
                                <strong>SQD5.</strong>
                    
                                I did not have to pay an unreasonable amount
                                of fees to receive the seed subsidy.
                    
                                <div
                                    class="text-muted mt-1"
                                    style="
                                        font-size:0.82rem;
                                        line-height:1.35;
                                    "
                                >
                                    Hindi ako nagbayad ng hindi makatuwirang halaga
                                    upang matanggap ang subsidiya sa binhi.
                                </div>
                    
                                <div
                                    class="text-muted mt-1"
                                    style="
                                        font-size:0.76rem;
                                        line-height:1.3;
                                        font-style:italic;
                                    "
                                >
                                    (Do not rate if the seed subsidy was provided
                                    free of charge.)
                                    (Huwag sagutan kung ang subsidiya sa binhi ay
                                    ibinigay nang libre.)
                                </div>
                    
                            </td>
                    
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                    
                                <td class="text-center">
                    
                                    <input
                                        type="radio"
                                        name="q6"
                                        value="<?= $i ?>"
                                        required
                                        style="
                                            width:20px;
                                            height:20px;
                                        "
                                    >
                    
                                </td>
                    
                            <?php endfor; ?>
                    
                        </tr>
                    
                    
                        <!-- =================================================
                             SQD6
                        ================================================== -->
                    
                        <tr>
                    
                            <td>
                    
                                <strong>SQD6.</strong>
                    
                                I believe the distribution of the seed subsidy
                                was fair to all qualified farmer beneficiaries,
                                or "walang palakasan."
                    
                                <div
                                    class="text-muted mt-1"
                                    style="
                                        font-size:0.82rem;
                                        line-height:1.35;
                                    "
                                >
                                    Naniniwala ako na naging patas ang pamamahagi
                                    ng subsidiya sa binhi sa lahat ng kwalipikadong
                                    benepisyaryong magsasaka, o "walang palakasan."
                                </div>
                    
                            </td>
                    
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                    
                                <td class="text-center">
                    
                                    <input
                                        type="radio"
                                        name="q7"
                                        value="<?= $i ?>"
                                        required
                                        style="
                                            width:20px;
                                            height:20px;
                                        "
                                    >
                    
                                </td>
                    
                            <?php endfor; ?>
                    
                        </tr>
                    
                    
                        <!-- =================================================
                             SQD7
                        ================================================== -->
                    
                        <tr>
                    
                            <td>
                    
                                <strong>SQD7.</strong>
                    
                                I was treated courteously by the staff during
                                the seed subsidy distribution, and they were
                                helpful when I needed assistance.
                    
                                <div
                                    class="text-muted mt-1"
                                    style="
                                        font-size:0.82rem;
                                        line-height:1.35;
                                    "
                                >
                                    Magalang akong pinakitunguhan ng mga kawani
                                    sa panahon ng pamamahagi ng subsidiya sa binhi,
                                    at tinulungan nila ako nang kailangan ko ng
                                    kanilang tulong.
                                </div>
                    
                            </td>
                    
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                    
                                <td class="text-center">
                    
                                    <input
                                        type="radio"
                                        name="q8"
                                        value="<?= $i ?>"
                                        required
                                        style="
                                            width:20px;
                                            height:20px;
                                        "
                                    >
                    
                                </td>
                    
                            <?php endfor; ?>
                    
                        </tr>
                    
                    
                        <!-- =================================================
                             SQD8
                        ================================================== -->
                    
                        <tr>
                    
                            <td>
                    
                                <strong>SQD8.</strong>
                    
                                I received the seed subsidy that I needed,
                                or, if my request was not granted, the reason
                                for the denial was sufficiently explained to me.
                    
                                <div
                                    class="text-muted mt-1"
                                    style="
                                        font-size:0.82rem;
                                        line-height:1.35;
                                    "
                                >
                                    Natatanggap ko ang subsidiya sa binhi na
                                    kailangan ko, o kung hindi naibigay ang aking
                                    kahilingan, ipinaliwanag sa akin nang maayos
                                    ang dahilan kung bakit ito hindi ipinagkaloob.
                                </div>
                    
                            </td>
                    
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                    
                                <td class="text-center">
                    
                                    <input
                                        type="radio"
                                        name="q9"
                                        value="<?= $i ?>"
                                        required
                                        style="
                                            width:20px;
                                            height:20px;
                                        "
                                    >
                    
                                </td>
                    
                            <?php endfor; ?>
                    
                        </tr>
                    
                    
                        <!-- =================================================
                             TIMELINESS OF DELIVERY
                        ================================================== -->
                    
                        <tr>
                    
                            <td
                                colspan="6"
                                style="
                                    background:#f1f8f2;
                                    font-weight:bold;
                                "
                            >
                    
                                Timeliness of Delivery Dimension
                    
                            </td>
                    
                        </tr>
                    
                    
                        <!-- =================================================
                             TDD1
                        ================================================== -->
                    
                        <tr>
                    
                            <td>
                    
                                <strong>TDD1.</strong>
                    
                                I received the seed subsidy within the expected
                                time for its intended purpose.
                    
                                <div
                                    class="text-muted mt-1"
                                    style="
                                        font-size:0.82rem;
                                        line-height:1.35;
                                    "
                                >
                                    Natanggap ko ang subsidiya sa binhi sa
                                    itinakdang oras na kinakailangan para sa
                                    layunin nito.
                                </div>
                    
                            </td>
                    
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                    
                                <td class="text-center">
                    
                                    <input
                                        type="radio"
                                        name="q10"
                                        value="<?= $i ?>"
                                        required
                                        style="
                                            width:20px;
                                            height:20px;
                                        "
                                    >
                    
                                </td>
                    
                            <?php endfor; ?>
                    
                        </tr>
                    
                    </tbody>


                </table>

            </div>


            <!-- =================================================
                 ADDITIONAL COMMENTS
            ================================================== -->

            <hr>

            <div class="mb-4">

                <label class="fw-bold">

                    Suggestions on how we can further improve
                    our services:

                </label>

                <?= $this->Form->textarea('comment', [

                    'class' => 'form-control',

                    'rows' => 4,

                    'placeholder' =>
                        'Share your comments or suggestions...'

                ]) ?>

            </div>


        </div>


        <!-- =====================================================
             CARD FOOTER
        ====================================================== -->

        <div class="card-footer bg-white text-end">

            <?= $this->Form->hidden('id') ?>

            <button
                type="submit"
                class="btn btn-success btn-lg px-5 rounded-pill"
            >

                <i class="fas fa-paper-plane me-2"></i>

                Submit Evaluation

            </button>

        </div>


        <?= $this->Form->end() ?>

    </div>

</div>


<!-- =============================================================
     SUCCESS MODAL
============================================================== -->

<div
    class="modal fade"
    id="successModal"
    tabindex="-1"
    aria-hidden="true"
>

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content">


            <!-- =================================================
                 HEADER
            ================================================== -->

            <div class="modal-header bg-success text-white">

                <h5 class="modal-title">

                    <i class="fas fa-check-circle me-2"></i>

                    Submission Successful

                </h5>

            </div>


            <!-- =================================================
                 BODY
            ================================================== -->

            <div class="modal-body text-center">

                <i
                    class="fas fa-check-circle text-success"
                    style="font-size:70px;"
                >
                </i>

                <h4 class="mt-3">
                    Thank you!
                </h4>

                <p class="mb-0">

                    Your feedback has been submitted successfully.

                </p>

            </div>


            <!-- =================================================
                 FOOTER
            ================================================== -->

            <div class="modal-footer">

                <button
                    type="button"
                    class="btn btn-success"
                    id="successOk"
                >

                    OK

                </button>

            </div>

        </div>

    </div>

</div>


<!-- =============================================================
     SUCCESS MODAL SCRIPT
============================================================== -->

<?php if ($this->request->getQuery('submitted')): ?>

<script>

$(document).ready(function () {

    $('#successModal').modal('show');

    $('#successOk').on('click', function () {

        window.location.href =
            "<?= $this->Url->build([
                'controller' => 'Schedules',
                'action' => 'announcements'
            ]) ?>";

    });

});

</script>

<?php endif; ?>


<!-- =============================================================
     FEEDBACK SURVEY JAVASCRIPT
============================================================== -->

<script>

$(document).ready(function () {

    // =========================================================
    // FARM SIZES
    // =========================================================

    const farmSizes = <?= json_encode(
        $farmSizes ?? [],
        JSON_NUMERIC_CHECK
    ) ?>;


    // =========================================================
    // SCHEDULE DATA FROM PHP
    // =========================================================

    const rawSchedules = <?= json_encode(
        $schedules ?? [],
        JSON_UNESCAPED_UNICODE
    ) ?>;


    // =========================================================
    // ELEMENTS
    // =========================================================

    const farmSelect =
        $('#farm_id');

    const scheduleSelect =
        $('#schedule_id');

    const riceTypeSelect =
        $('#rice_type');

    const averageYieldInput =
        $('#average_yield');


    // =========================================================
    // NORMALIZED SCHEDULE LIST
    //
    // This supports BOTH:
    //
    // 1. GLOBAL:
    //
    // {
    //     "31": "SD-1 - Seed Subsidy...",
    //     "32": "SD-2 - Seed Subsidy..."
    // }
    //
    // AND OLD NESTED:
    //
    // {
    //     "1": {
    //         "31": "SD-1...",
    //         "32": "SD-2..."
    //     },
    //     "2": {
    //         "31": "SD-1..."
    //     }
    // }
    //
    // =========================================================

    const schedules = {};


    // =========================================================
    // FUNCTION: ADD SCHEDULE
    // =========================================================

    function addSchedule(
        scheduleId,
        scheduleValue
    ) {

        if (
            scheduleId === null ||
            scheduleId === undefined ||
            scheduleId === ''
        ) {

            return;
        }


        // -----------------------------------------------------
        // If value is an object, try to get useful label
        // -----------------------------------------------------

        if (
            typeof scheduleValue === 'object' &&
            scheduleValue !== null
        ) {

            let label = '';


            // ---------------------------------------------
            // Possible label properties
            // ---------------------------------------------

            if (
                scheduleValue.label !== undefined
            ) {

                label =
                    scheduleValue.label;

            } else if (
                scheduleValue.program_code !== undefined
            ) {

                label =
                    scheduleValue.program_code;

                if (
                    scheduleValue.program_name
                ) {

                    label +=
                        ' - ' +
                        scheduleValue.program_name;
                }

            } else if (
                scheduleValue.programCode !== undefined
            ) {

                label =
                    scheduleValue.programCode;

            } else if (
                scheduleValue.name !== undefined
            ) {

                label =
                    scheduleValue.name;
            }


            // ---------------------------------------------
            // Only use object if a label was found
            // ---------------------------------------------

            if (
                label !== ''
            ) {

                schedules[
                    String(scheduleId)
                ] = String(label);
            }

            return;
        }


        // -----------------------------------------------------
        // Normal string value
        // -----------------------------------------------------

        schedules[
            String(scheduleId)
        ] = String(scheduleValue);

    }


    // =========================================================
    // NORMALIZE PHP SCHEDULE DATA
    // =========================================================

    if (
        rawSchedules &&
        typeof rawSchedules === 'object'
    ) {

        Object.keys(rawSchedules).forEach(
            function (key) {

                const value =
                    rawSchedules[key];


                // =================================================
                // GLOBAL SCHEDULE
                //
                // key = schedule ID
                // value = label
                // =================================================

                if (
                    typeof value === 'string' ||
                    typeof value === 'number'
                ) {

                    addSchedule(
                        key,
                        value
                    );

                    return;
                }


                // =================================================
                // OBJECT
                //
                // Could be:
                //
                // schedule object
                //
                // OR
                //
                // old farm => schedules structure
                // =================================================

                if (
                    typeof value === 'object' &&
                    value !== null
                ) {


                    // -------------------------------------------------
                    // Check if this is an actual schedule object
                    // -------------------------------------------------

                    if (
                        value.program_code !== undefined ||
                        value.programCode !== undefined ||
                        value.label !== undefined ||
                        value.name !== undefined
                    ) {

                        addSchedule(
                            key,
                            value
                        );

                        return;
                    }


                    // -------------------------------------------------
                    // Otherwise assume old nested structure
                    // -------------------------------------------------

                    Object.keys(value).forEach(
                        function (nestedScheduleId) {

                            const nestedValue =
                                value[
                                    nestedScheduleId
                                ];


                            // -----------------------------------------
                            // Add nested schedule
                            // -----------------------------------------

                            if (
                                typeof nestedValue === 'string' ||
                                typeof nestedValue === 'number'
                            ) {

                                addSchedule(
                                    nestedScheduleId,
                                    nestedValue
                                );

                            }

                            else if (
                                typeof nestedValue === 'object' &&
                                nestedValue !== null
                            ) {

                                addSchedule(
                                    nestedScheduleId,
                                    nestedValue
                                );

                            }

                        }
                    );

                }

            }
        );

    }


    // =========================================================
    // LOAD SCHEDULES
    // =========================================================

    function loadSchedules() {

        const currentSchedule =
            scheduleSelect.val();


        // -----------------------------------------------------
        // Clear only when initially loading.
        //
        // This function is NOT called when Farm changes.
        // -----------------------------------------------------

        scheduleSelect.empty();


        // -----------------------------------------------------
        // Default option
        // -----------------------------------------------------

        scheduleSelect.append(
            $('<option>', {
                value: '',
                text: '-- Select Completed Schedule --'
            })
        );


        // -----------------------------------------------------
        // No schedules
        // -----------------------------------------------------

        if (
            Object.keys(schedules).length === 0
        ) {

            return;
        }


        // -----------------------------------------------------
        // Add schedules
        // -----------------------------------------------------

        Object.keys(schedules).forEach(
            function (scheduleId) {

                const scheduleLabel =
                    schedules[
                        scheduleId
                    ];


                // -------------------------------------------------
                // NEVER allow [object Object]
                // -------------------------------------------------

                if (
                    typeof scheduleLabel === 'object'
                ) {

                    return;
                }


                scheduleSelect.append(
                    $('<option>', {
                        value: scheduleId,
                        text: String(scheduleLabel)
                    })
                );

            }
        );


        // -----------------------------------------------------
        // Restore previous selection if it still exists
        // -----------------------------------------------------

        if (
            currentSchedule &&
            schedules[
                String(currentSchedule)
            ] !== undefined
        ) {

            scheduleSelect.val(
                String(currentSchedule)
            );

        }

    }


    // =========================================================
    // CALCULATE AVERAGE YIELD
    //
    // 0 = HYBRID
    //     Farm Size × 5.5
    //
    // 1 = INBRED
    //     Farm Size × 6
    // =========================================================

    function calculateAverageYield() {

        const farmId =
            String(
                farmSelect.val() || ''
            );

        const riceType =
            String(
                riceTypeSelect.val() || ''
            );


        // -----------------------------------------------------
        // FARM REQUIRED
        // -----------------------------------------------------

        if (
            farmId === ''
        ) {

            averageYieldInput.val('');

            return;
        }


        // -----------------------------------------------------
        // RICE TYPE REQUIRED
        // -----------------------------------------------------

        if (
            riceType === ''
        ) {

            averageYieldInput.val('');

            return;
        }


        // -----------------------------------------------------
        // GET FARM SIZE
        // -----------------------------------------------------

        const farmSize =
            parseFloat(
                farmSizes[farmId]
            );


        // -----------------------------------------------------
        // INVALID FARM SIZE
        // -----------------------------------------------------

        if (
            isNaN(farmSize) ||
            farmSize <= 0
        ) {

            averageYieldInput.val('');

            return;
        }


        // -----------------------------------------------------
        // RATE
        // -----------------------------------------------------

        let yieldRate = 0;


        // =====================================================
        // 0 = HYBRID
        // =====================================================

        if (
            riceType === '0'
        ) {

            yieldRate = 5.5;
        }


        // =====================================================
        // 1 = INBRED
        // =====================================================

        else if (
            riceType === '1'
        ) {

            yieldRate = 6.0;
        }


        // =====================================================
        // INVALID
        // =====================================================

        else {

            averageYieldInput.val('');

            return;
        }


        // =====================================================
        // CALCULATE
        // =====================================================

        const averageYield =
            farmSize * yieldRate;


        // =====================================================
        // DISPLAY
        // =====================================================

        averageYieldInput.val(
            averageYield.toFixed(2)
        );

    }


    // =========================================================
    // FARM CHANGE
    //
    // IMPORTANT:
    //
    // DO NOT reload schedules.
    // DO NOT clear schedule.
    // DO NOT make AJAX request.
    // =========================================================

    farmSelect.on(
        'change',
        function () {

            calculateAverageYield();

        }
    );


    // =========================================================
    // RICE TYPE CHANGE
    // =========================================================

    riceTypeSelect.on(
        'change',
        function () {

            calculateAverageYield();

        }
    );


    // =========================================================
    // SCHEDULE CHANGE
    // =========================================================

    scheduleSelect.on(
        'change',
        function () {

            // Nothing else should change.
            //
            // Selecting a schedule does NOT:
            // - change Farm
            // - remove Farm
            // - change Rice Type
            // - recalculate Farm size

        }
    );


    // =========================================================
    // FORM SUBMIT
    // =========================================================

    $('#surveyForm').on(
        'submit',
        function (event) {

            const farmId =
                String(
                    farmSelect.val() || ''
                );

            const scheduleId =
                String(
                    scheduleSelect.val() || ''
                );

            const riceType =
                String(
                    riceTypeSelect.val() || ''
                );


            // =================================================
            // FARM
            // =================================================

            if (
                farmId === ''
            ) {

                event.preventDefault();

                alert(
                    'Please select a farm.'
                );

                farmSelect.focus();

                return false;
            }


            // =================================================
            // SCHEDULE
            // =================================================

            if (
                scheduleId === ''
            ) {

                event.preventDefault();

                alert(
                    'Please select a completed distribution schedule.'
                );

                scheduleSelect.focus();

                return false;
            }


            // =================================================
            // VERIFY SCHEDULE
            // =================================================

            if (
                schedules[
                    scheduleId
                ] === undefined
            ) {

                event.preventDefault();

                alert(
                    'Invalid or unavailable distribution schedule.'
                );

                scheduleSelect.focus();

                return false;
            }


            // =================================================
            // RICE TYPE
            // =================================================

            if (
                riceType !== '0' &&
                riceType !== '1'
            ) {

                event.preventDefault();

                alert(
                    'Please select a valid rice type.'
                );

                riceTypeSelect.focus();

                return false;
            }


            // =================================================
            // RECALCULATE
            // =================================================

            calculateAverageYield();


            // =================================================
            // CHECK AVERAGE YIELD
            // =================================================

            const averageYield =
                parseFloat(
                    averageYieldInput.val()
                );


            if (
                isNaN(averageYield) ||
                averageYield <= 0
            ) {

                event.preventDefault();

                alert(
                    'Unable to calculate average yield. Please select a valid farm and rice type.'
                );

                return false;
            }


            // =================================================
            // ALLOW SUBMISSION
            // =================================================

            return true;

        }
    );


    // =========================================================
    // INITIALIZE SCHEDULES
    // =========================================================

    loadSchedules();


    // =========================================================
    // INITIALIZE AVERAGE YIELD
    // =========================================================

    calculateAverageYield();

});

</script>