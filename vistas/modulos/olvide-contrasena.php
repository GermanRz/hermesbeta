<?php
$recuperacion = new ControladorRecuperacion();
?>

<div class="login-box">
    <div class="login-logo">
        <img src="vistas/img/Logo/android-chrome-192x192.png" alt="HERMES Logo" class="img-fluid mb-3">
        <br>
    </div>
    
    <div class="card">
        <div class="card-body login-card-body">
            <p class="login-box-msg">Recuperar Contraseña</p>

            <form action="" method="post">
                <!-- Nombre de Usuario -->
                <div class="input-group mb-3">
                    <input type="text" class="form-control" placeholder="Nombre de Usuario" name="nombreUsuario" required>
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-user"></span>
                        </div>
                    </div>
                </div>
                
                <!-- Correo Electrónico -->
                <div class="input-group mb-3">
                    <input type="email" class="form-control" placeholder="Correo Electrónico" name="correoElectronico" required>
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-envelope"></span>
                        </div>
                    </div>
                </div>
                
                <!-- Nueva Contraseña -->
                <div class="input-group mb-3">
                    <input type="password" class="form-control" placeholder="Nueva Contraseña" name="nuevaContrasena" id="nuevaContrasena" required minlength="6">
                    <div class="input-group-append">
                        <div class="input-group-text" onclick="togglePassword('nuevaContrasena', 'toggleIcon1')" style="cursor: pointer;">
                            <span class="fas fa-eye-slash" id="toggleIcon1"></span>
                        </div>
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                </div>
                
                <!-- Confirmar Contraseña -->
                <div class="input-group mb-3">
                    <input type="password" class="form-control" placeholder="Confirmar Contraseña" name="confirmarContrasena" id="confirmarContrasena" required minlength="6">
                    <div class="input-group-append">
                        <div class="input-group-text" onclick="togglePassword('confirmarContrasena', 'toggleIcon2')" style="cursor: pointer;">
                            <span class="fas fa-eye-slash" id="toggleIcon2"></span>
                        </div>
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                </div>
                
                <!-- Captcha -->
                <div class="form-group">
                    <label for="captcha" class="text-center d-block">
                        <strong>Resuelve la suma: 5 + 3 = ?</strong>
                    </label>
                    <div class="input-group">
                        <input type="number" class="form-control text-center" placeholder="Escribe el resultado" name="captcha" id="captcha" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-calculator"></span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-12">
                        <button type="submit" class="btn btn-success btn-block">Cambiar Contraseña</button>
                    </div>
                </div>
                
                <?php
                $recuperacion->ctrRecuperarContrasena();
                ?>
            </form>

            <p class="mt-3 mb-1">
                <a href="index.php">Volver al inicio</a>
            </p>
            
        </div>
    </div>
</div>

<script>
function togglePassword(fieldId, iconId) {
    const field = document.getElementById(fieldId);
    const icon = document.getElementById(iconId);
    
    if (field.type === "password") {
        field.type = "text";
        icon.classList.remove("fa-eye-slash");
        icon.classList.add("fa-eye");
    } else {
        field.type = "password";
        icon.classList.remove("fa-eye");
        icon.classList.add("fa-eye-slash");
    }
}

// Validación en tiempo real para confirmar contraseña
document.addEventListener('DOMContentLoaded', function() {
    const nuevaContrasena = document.getElementById('nuevaContrasena');
    const confirmarContrasena = document.getElementById('confirmarContrasena');
    
    if(nuevaContrasena && confirmarContrasena) {
        confirmarContrasena.addEventListener('input', function() {
            if(nuevaContrasena.value !== confirmarContrasena.value) {
                confirmarContrasena.setCustomValidity('Las contraseñas no coinciden');
            } else {
                confirmarContrasena.setCustomValidity('');
            }
        });
    }
});
</script>