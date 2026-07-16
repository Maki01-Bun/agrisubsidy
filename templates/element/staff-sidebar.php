<?php $title = $this->fetch('title'); ?>
<!-- Main Sidebar Container -->
<aside class="main-sidebar elevation-4 sidebar-light-primary">
    <?php
        $image = $this->Html->image('logos.png',['class'=>'brand-image img-circle elevation-3','style'=>'opacity:0.8;']);
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
                <!-- <li class="nav-item">
                    <?php $active = $title=='Audit Logs'?'active':'' ?>
                    <?= $this->Html->link('<i class="nav-icon fas fa-boxes"></i>
                        <p>
                            Audit Logs
                        </p>','/Audit_Logs',['class'=>'nav-link '.$active,'escape'=>false]) ?>
                </li> -->
                <li class="nav-item">
                    <?php $active = $title=='Farmers'?'active':'' ?>
                    <?= $this->Html->link('<i class="nav-icon fas fa-boxes"></i>
                        <p>
                            Farmers
                        </p>','/Farmers',['class'=>'nav-link '.$active,'escape'=>false]) ?>
                </li>
                <!-- <li class="nav-item">
                    <?php $active = $title=='Categories'?'active':'' ?>
                    <?= $this->Html->link('<i class="nav-icon fas fa-box"></i>
                        <p>
                            Categories
                        </p>','/Categories',['class'=>'nav-link '.$active,'escape'=>false]) ?>
                </li> -->
                <li class="nav-item">
                    <?php $active = $title=='Users'?'active':'' ?>
                    <?= $this->Html->link('<i class="nav-icon fas fa-user-cog"></i>
                        <p>
                            Users
                        </p>','/Users',['class'=>'nav-link '.$active,'escape'=>false]) ?>
                </li>
            </ul>
        </nav>
        <div class="mt-auto px-3 pb-3">
            <div class="sidebar-logout">
             <div class="card bg-danger mb-0">
                 <div class="card-body p-2 text-center">
                     <?= $this->Html->link(
                         '<i class="fas fa-sign-out-alt mr-2"></i> Logout',
                         '/Users/logout',
                         [
                             'class' => 'text-white font-weight-bold',
                             'escape' => false
                         ]
                     ) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</aside>