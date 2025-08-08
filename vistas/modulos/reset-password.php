<?php
$token = $_GET['token'] ?? '';

if(empty($token)){
    echo '<script>window.location = "login";</script>';
    exit();
}

// Aquí podrías validar el token contra la BD antes de mostrar el formulario
// Por ahora, asumimos que es válido si existe.

?>

<div class="login-box">
    <div class="login-logo">
        <img src="vistas/img/Logo/android-chrome-192x192.png" alt="HERMES Logo" class="img-fluid mb-3">
    </div>
    <div class="card">
        <div class="card-body login-card-body">
            <p class="login-box-msg">Establece tu nueva contraseña</p>

            <form method="post">
                <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">

                <!-- Nueva Contraseña -->
                <div class="input-group mb-3">
                  <input type="password" class="form-control" placeholder="Nueva contraseña" name="nuevaPassword" id="nuevaPassword" required>
                  <div class="input-group-append">
                    <div class="input-group-text">
                      <span class="fas fa-eye-slash" id="toggleIconNueva" style="cursor: pointer;"></span>
                    </div>
                  </div>
                </div>

                <!-- Confirmar Contraseña -->
                <div class="input-group mb-3">
                  <input type="password" class="form-control" placeholder="Confirmar contraseña" name="confirmarPassword" id="confirmarPassword" required>
                  <div class="input-group-append">
                    <div class="input-group-text">
                      <span class="fas fa-eye-slash" id="toggleIconConfirmar" style="cursor: pointer;"></span>
                    </div>
                  </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary btn-block">Cambiar Contraseña</button>
                    </div>
                </div>
            </form>

            <?php
                $resetPassword = new ControladorUsuarios();
                $resetPassword->ctrResetPassword();
            ?>

        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const toggleIconNueva = document.getElementById('toggleIconNueva');
    const nuevaPassword = document.getElementById('nuevaPassword');

    const toggleIconConfirmar = document.getElementById('toggleIconConfirmar');
    const confirmarPassword = document.getElementById('confirmarPassword');

    function togglePassword(input, icon) {
        const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
        input.setAttribute('type', type);
        icon.classList.toggle('fa-eye');
        icon.classList.toggle('fa-eye-slash');
    }

    toggleIconNueva.addEventListener('click', function () {
        togglePassword(nuevaPassword, this);
    });

    toggleIconConfirmar.addEventListener('click', function () {
        togglePassword(confirmarPassword, this);
    });
});
</script>
