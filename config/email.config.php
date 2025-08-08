<?php

// Configuración para el envío de correos con PHPMailer
return [
    'smtp_host'       => 'smtp.gmail.com',       // Servidor SMTP de tu proveedor (ej. Gmail)
    'smtp_auth'       => true,                   // Usar autenticación SMTP
    'smtp_username'   => 'familiabanguera12361@gmail.com',  // Tu dirección de correo electrónico
    'smtp_password'   => 'mzaj iexi idut wpuj', // ¡MUY IMPORTANTE! Usa una contraseña de aplicación, no tu contraseña real
    'smtp_secure'     => PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_SMTPS, // O 'tls'
    'smtp_port'       => 465,                    // Puerto SMTP (587 para TLS, 465 para SMTPS)
    'from_email'      => 'familiabanguera12361@gmail.com',  // El correo que aparecerá como remitente
    'from_name'       => 'Soporte HERMES'        // El nombre que aparecerá como remitente
];
