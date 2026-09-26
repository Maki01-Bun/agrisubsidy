
<div class="login-box">
    <div class="card">
        <div class="card-body login-card-body">
            <div class="logo-header d-flex justify-content-center align-items-center mb-4">
                <div class="logo-item">
                    <?= $this->Html->image('da_logo.jpg', [
                        'alt' => 'Santiago City Logo',
                        'class' => 'header-logo'
                    ]) ?>
                </div>

                <!-- AgriSubsidy Logo -->
                <div class="logo-item">
                    <?= $this->Html->image('logos.png', [
                        'alt' => 'AgriSubsidy Logo',
                        'class' => 'header-logo'
                    ]) ?>
                </div>
            </div>
            <p class="login-box-msg">AgriSubsidy</p>
            <center><?php echo $this->Flash->render(); ?></center>
            <?= $this->Form->create(null,['templates'=>['inputContainer' => '{{content}}']]) ?>
            <div class="input-group mb-3">
                <?= $this->Form->control('username',['class'=>'form-control',
                    'placeholder'=>'Username','label'=>false,'required'])?>
                <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-user"></span>
                    </div>
                </div>
            </div>
            <div class="input-group mb-3">
                <?= $this->Form->control('password', [
                    'class' => 'form-control',
                    'placeholder' => 'Password',
                    'label' => false,
                    'required' => true,
                    'id' => 'password'
                ]) ?>

                <div class="input-group-append">
                    <button
                        type="button"
                        class="btn btn-outline-secondary"
                        onclick="togglePassword()"
                        aria-label="Show or hide password"
                    >
                        <i id="passwordIcon" class="fas fa-eye-slash"></i>
                    </button>
                </div>
            </div>
          <div class="row">
            <div class="col-12">
                <button type="submit" class="btn btn-primary btn-block">
                    Sign In
                </button>
            </div>
        </div>
        
        <div class="row mt-2">
            <div class="col-12 text-center">
                <?= $this->Html->link(
                    'Create an Account',
                    ['controller' => 'Users', 'action' => 'register']
                ) ?>
            </div>
        </div>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
<script>
function togglePassword() {
    const password = document.getElementById('password');
    const icon = document.getElementById('passwordIcon');

    if (password.type === 'password') {
        password.type = 'text';

        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    } else {
        password.type = 'password';

        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    }
}
</script>

