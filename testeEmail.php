<?php
require __DIR__ . '/PHPMailer/src/PHPMailer.php';
require __DIR__ . '/PHPMailer/src/SMTP.php';
require __DIR__ . '/PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer(true);

try {
    // Configuração servidor Gmail
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'oyespanatienda@gmail.com';      // 👉 seu email Gmail
    $mail->Password   = 'icdk rfbi wnhm hhvg';            // 👉 senha de app gerada
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $mail->Port       = 465;

    // Remetente
    $mail->setFrom('SEUEMAIL@gmail.com', 'Seu Nome');

    // Destinatário
    $mail->addAddress('suzyalvessoares@gmail.com', 'Destinatário');

    // Conteúdo
    $mail->isHTML(true);
    $mail->Subject = 'Teste com Gmail e PHPMailer';
    $mail->Body    = 'Este é um <b>teste</b> enviado com PHPMailer pelo Gmail!';
    $mail->AltBody = 'Este é um teste enviado com PHPMailer pelo Gmail!';

    $mail->send();
    echo "✅ Email enviado com sucesso pelo Gmail!";
} catch (Exception $e) {
    echo "❌ Erro ao enviar email: {$mail->ErrorInfo}";
}
