<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
            </div>

            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">

                    <!-- Dashboard - NOT CLICKABLE -->
                    <li class="breadcrumb-item">
                        <span>Dashboard</span>
                    </li>

                    <li class="breadcrumb-item active">
                        <?= $this->fetch('title') == 'Dashboard'
                            ? ''
                            : $this->fetch('title') ?>
                    </li>

                </ol>
            </div>
        </div>
    </div>
</section>