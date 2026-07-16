<div class="register-box">
    <div class="card">  
        <div class="card-body register-card-body">
            <p class="register-box-msg">
                Registration - Step <?= $step ?> of 2
            </p>
            <?= $this->Flash->render() ?>
            <?php if ($step == 1): ?>
                <?= $this->Form->create(null, ['templates' => ['inputContainer' => '{{content}}']]) ?>
                <div class="col-md-12 mb-3">
                    <?= $this->Form->control('farmer_no', ['class' => 'form-control',
                    'placeholder' => 'Farmer Number','label' => false,'required']) ?>
                </div>
                <div class="col-md-12 mb-3">
                    <?= $this->Form->control('first_name', ['class' => 'form-control',
                    'placeholder' => 'First Name','label' => false,'required']) ?>
                </div>

                <div class="col-md-12 mb-3"><?= $this->Form->control('middle_name', ['class' => 'form-control',
                    'placeholder' => 'Middle Name','label' => false]) ?>
                </div>
                <div class="col-md-12 mb-3">
                    <?= $this->Form->control('last_name', ['class' => 'form-control',
                    'placeholder' => 'Last Name','label' => false,'required'
                    ]) ?>
                </div>
                <div class="col-md-12 mb-3"><?= $this->Form->control('gender', ['class' => 'form-control',
                    'label' => false,'options'=>$this->Option->gender()]) ?>
                </div>
                <div class="col-md-12 mb-3"><?= $this->Form->control('contact_no', ['class' => 'form-control',
                    'placeholder' => 'Contact Number','maxlength' => 11,'label' => false]) ?>
                </div>
                <div class="col-md-12 mb-3">
                    <?= $this->Form->control('address', ['class' => 'form-control','placeholder' => 'Address','label' => false]) ?>
                </div>
                <div class="col-md-12 mb-3">
                    <label class="form-label">Birthdate</label>
                    <?= $this->Form->control('birthdate', ['class' => 'form-control','type' => 'date','label' => false,
                    'max' => date('Y-m-d', strtotime('-18 years'))]) ?>
                </div>
                <div class="row mb-3">
                    <div class="col-6">
                        <?= $this->Html->link('Cancel',['controller' => 'Users', 'action' => 'login'],
                        ['class' => 'btn btn-secondary btn-block']) ?>
                    </div> 
                    <div class="col-6">
                        <button type="submit" class="btn btn-primary btn-block">
                            Next
                        </button>
                    </div>
                </div>
                <?= $this->Form->end() ?>
            <?php else: ?>
                <?= $this->Form->create($user, ['templates' => ['inputContainer' => '{{content}}']]) ?>
                <div class="input-group mb-3">
                    <?= $this->Form->control('username', ['class' => 'form-control',
                    'placeholder' => 'Username','label' => false,'required']) ?>
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-user"></span>
                        </div>
                    </div>
                </div>
                <div class="input-group mb-3">
                    <?= $this->Form->control('password', [
                        'type' => 'password',
                        'class' => 'form-control',
                        'id' => 'password',
                        'placeholder' => 'Password',
                        'label' => false,
                        'required' => true
                    ]) ?>

                    <div class="input-group-append">
                        <button type="button" class="btn btn-outline-secondary" onclick="password.type=password.type=='password'?'text':'password'">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>
                <div class="input-group mb-3">
                    <?= $this->Form->control('confirm_password', ['type' => 'password','class' => 'form-control','placeholder' => 'Confirm Password','minlength' => 8,
                        'label' => false,'required' => true,'id' => 'confirm_password'
                    ]) ?>
                    <div class="input-group-append">
                        <button type="button" class="btn btn-outline-secondary" onclick="confirm_password.type=confirm_password.type=='password'?'text':'password'">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>
                <div class="input-group mb-3">
                    <?= $this->Form->hidden('role', ['value' => 'farmer']) ?>
                </div>
                <button type="submit" class="btn btn-success btn-block">Register</button>
                <?= $this->Form->end() ?>
            <?php endif; ?>
        </div>
    </div>
</div>