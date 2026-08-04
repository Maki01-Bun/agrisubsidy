<?php $title = $this->fetch('title'); ?>
<!-- Main Sidebar Container -->
<aside class="main-sidebar elevation-4 sidebar-light-primary">
    <?php
        $image = $this->Html->image('logos.png',['class'=>'brand-image img-circle elevation-3','style'=>'opacity:1;']);
        echo $this->Html->link($image.'<span class="brand-text font-weight-light text-dark">AgriSubsidy</span>','/Dashboard',['class'=>'brand-link',
            'escape'=>false,'style'=>'background-color:#007BFF;']);
    ?>
    <div class="sidebar">
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="info">
                <a href="#" class="d-block">Welcome <?= $auth['username'] ?></a>
            </div>
        </div>
        <div class="user-panel nav nav-pills nav-sidebar flex-column"data-widget="treeview"role="menu"data-accordion="false">
           <div class="nav-item">
               <?php $active = $title == 'Profile' ? 'active' : ''; ?>
               <?= $this->Html->link('<i class="nav-icon fas fa-user-alt"></i>
                   <p>
                        Profile
                   </p>',
                   '/Profile',['class' => 'nav-link py-1 ' . $active,'escape' => false]) ?>
           </div>
        </div>  
        <nav class="mt-2 mb-0">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item">
                    <?php $active = $title=='Dashboard'?'active':'' ?>
                    <?= $this->Html->link('<i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Dashboard
                        </p>','/Dashboard',['class'=>'nav-link '.$active,'escape'=>false]) ?>
                </li>
                <li class="nav-item">
                    <?php $active = $title=='Farmers'?'active':'' ?>
                    <?= $this->Html->link('<i class="nav-icon fas fa-users"></i>
                        <p>
                            Beneficiaries
                        </p>','/Farmers',['class'=>'nav-link '.$active,'escape'=>false]) ?>
                </li>
                 <li class="nav-item">
                    <?php $active = $title=='Farms'?'active':'' ?>
                    <?= $this->Html->link('<i class="nav-icon fas fa-warehouse"></i>
                        <p>
                            Farms
                        </p>','/Farms',['class'=>'nav-link '.$active,'escape'=>false]) ?>
                </li>
                <li class="nav-item">
                    <?php $active = $title=='Records'?'active':'' ?>
                    <?= $this->Html->link('<i class="nav-icon fas fa-scroll"></i>
                        <p>
                            History Records
                        </p>','/Records',['class'=>'nav-link '.$active,'escape'=>false]) ?>
                </li>
                <li class="nav-item">
                    <?php $active = $title=='Programs'?'active':'' ?>
                    <?= $this->Html->link('<i class="nav-icon fas fa-calendar-alt"></i>
                        <p>
                            Programs
                        </p>','/Programs',['class'=>'nav-link '.$active,'escape'=>false]) ?>
                </li>
                <li class="nav-item">
                    <?php $active = $title=='Pests'?'active':'' ?>
                    <?= $this->Html->link('<i class="nav-icon fas fa-bug"></i>
                        <p>
                            Pests
                        </p>','/Pests',['class'=>'nav-link '.$active,'escape'=>false]) ?>
                </li>
                <!-- <li class="nav-item">
                    <?php $active = $title=='Distributions'?'active':'' ?>
                    <?= $this->Html->link('<i class="nav-icon fas fa-user-clock"></i>
                        <p>
                            Distribution History
                        </p>','/Distributions',['class'=>'nav-link '.$active,'escape'=>false]) ?>
                </li> -->
                <li class="nav-item">
                    <?php $active = $title=='Users'?'active':'' ?>
                    <?= $this->Html->link('<i class="nav-icon fas fa-user-cog"></i>
                        <p>
                            Users
                        </p>','/Users',['class'=>'nav-link '.$active,'escape'=>false]) ?>
                </li>
                <li class="nav-item">
                    <?php $active = $title=='Evaluations'?'active':'' ?>
                    <?= $this->Html->link('<i class="nav-icon fas fa-chart-line"></i>
                        <p>
                            Evaluations
                        </p>','/Evaluations',['class'=>'nav-link '.$active,'escape'=>false]) ?>
                </li>
            </ul>
        </nav>
        <!-- logout -->
        <div class="mt-auto px-3 pb-3">
            <div class="sidebar-logout">
             <div class="card bg-danger mb-0">
                    <div class="card-body p-2 text-center">
                        <?= $this->Html->link('<i class="fas fa-sign-out-alt mr-2"></i> 
                        Logout',
                            '/Users/logout',['class' => 'text-white font-weight-bold','escape' => false]
                        ) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
</aside>