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
                <a href="#" class="d-block">Welcome <?= h($auth['username']) ?></a>
            </div>
        </div>
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column h-100" data-widget="treeview" role="menu" data-accordion="false">
               <?php
                    $announcementActive =
                        $this->request->getParam('controller') === 'Schedules' &&
                        $this->request->getParam('action') === 'announcements'
                            ? 'active'
                            : '';
                    ?>
                    
                    <li class="nav-item">
                        <?= $this->Html->link(
                            '<i class="nav-icon fas fa-bullhorn"></i>
                            <p>Subsidy Announcements</p>',
                            [
                                'controller' => 'Schedules',
                                'action' => 'announcements'
                            ],
                            [
                                'escape' => false,
                                'class' => 'nav-link ' . $announcementActive
                            ]
                        ) ?>
                    </li>
                
                <?php
                $currentController = $this->request->getParam('controller');
                $currentAction = $this->request->getParam('action');
                
                $feedbackActive = (
                    $currentController === 'Feedbacks'
                ) ? 'active' : '';
                ?>
                
                <li class="nav-item">
                    <?= $this->Html->link(
                        '<i class="nav-icon fas fa-user-cog"></i>
                        <p>Feedback</p>',
                        [
                            'controller' => 'Feedbacks',
                            'action' => 'index'
                        ],
                        [
                            'class' => 'nav-link ' . $feedbackActive,
                            'escape' => false
                        ]
                    ) ?>
                </li>
                <li class="nav-item">
                <?= $this->Html->link(
                    '<i class="nav-icon fas fa-user-alt"></i><p>Profile</p>',
                    '/Profile',
                    ['class' => 'nav-link '.($title == 'Profile' ? 'active' : ''),'escape' => false])?>
                </li>
            </ul>
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview">
                <li class="nav-item">
                    <?= $this->Html->link(
                        '<i class="nav-icon fas fa-sign-out-alt"></i><p>Logout</p>',
                        ['controller' => 'Users', 'action' => 'logout'],
                        ['class' => 'nav-link logout-link','escape' => false])?>
                </li>
            </ul>
        </nav>
    </div>
</aside>