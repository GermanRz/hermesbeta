document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('form[action="olvido-contrasena"]');
    if (form) {
        form.addEventListener('submit', function (e) {
            e.preventDefault();

            const emailInput = document.getElementById('email');
            if (!emailInput.value) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Por favor, introduce tu correo electrónico.',
                });
                return;
            }

            const data = new FormData();
            data.append('emailRecuperacion', emailInput.value);

            fetch('ajax/usuarios.ajax.php', {
                method: 'POST',
                body: data
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Revisa tu correo!',
                        text: data.message,
                    }).then(() => {
                        window.location.href = 'login';
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message,
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error del sistema',
                    text: 'No se pudo procesar la solicitud. Inténtalo de nuevo más tarde.',
                });
            });
        });
    }
});