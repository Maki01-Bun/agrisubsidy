<div class="col-12">
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title text-dark">Users</h3>
            
            <div class="card-tools">
                <?= $this->Html->link('<i class="fas fa-plus"></i>','',
                    ['id'=>'add','data-toggle'=>'tooltip','data-placement'=>'bottom','title'=>'Add User','escape'=>false]) ?>
            </div>
        </div>
        <div class="card-body">
            <table id="users-table" class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
<div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="users-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h4 id="modal-title"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?= $this->Form->create($user,['id'=>'users-form']) ?>
            <div class="modal-body">
                <div class="form-group">
                    <label for="username">Username</label>
                    <?= $this->Form->control('username',['class'=>'form-control','label'=>false]) ?>
                    <label for="email">Email</label>
                    <?= $this->Form->control('email',['class'=>'form-control','label'=>false]) ?>
                        <label for="password">Password</label>
                    <div style="position:relative;">
                        <?= $this->Form->control('password', [
                            'class' => 'form-control',
                            'label' => false,
                            'id' => 'password',
                            'type' => 'password',
                            'required' => true,
                            'pattern' => '(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z\d]).{8,}',
                            'title' => 'Password must be at least 8 characters and contain at least one uppercase letter, one lowercase letter, one number, and one special character.'
                        ]) ?>

                        <i class="fas fa-eye"
                        onclick="let p=document.getElementById('password'); p.type=p.type==='password'?'text':'password'; this.classList.toggle('fa-eye-slash');"
                        style="position:absolute; right:12px; top:50%; transform:translateY(-50%); cursor:pointer;">
                        </i>
                    </div>
                    <div id="password-rule" style="font-size:13px; margin-top:5px;">
                        Password must contain:
                        <ul style="margin:4px 0 0 18px; padding:0;">
                            <li>At least 8 characters</li>
                            <li>One uppercase letter</li>
                            <li>One lowercase letter</li>
                            <li>One number</li>
                            <li>One special character</li>
                        </ul>
                    </div>
                    <div id="role-field" style="display: none;">

                        <label for="role">Role</label>
                    
                        <?= $this->Form->control('role', [
                            'class' => 'form-control',
                            'options' => $this->Option->roles(),
                            'label' => false,
                            'id' => 'role'
                        ]) ?>
                    
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <?= $this->Form->control('id',['type'=>'hidden','label'=>false]) ?>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
<script>

$(document).ready(function () {

    /*
     * ADD USER
     */
    $('#add').on('click', function (e) {

        e.preventDefault();

        $('#modal-title').text('Add User');

        $('#users-form')[0].reset();

        // Show role when adding
        $('#role-field').show();

        $('#users-modal').modal('show');

    });


    /*
     * EDIT USER
     *
     * Put this inside your existing edit/user-row click
     * after loading the user's data.
     */
    $(document).on('click', '.edit-user', function () {

        $('#modal-title').text('Edit User');

        // Hide role when editing
        $('#role-field').hide();

        $('#users-modal').modal('show');

    });

});

</script>