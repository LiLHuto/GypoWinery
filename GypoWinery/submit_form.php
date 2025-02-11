<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Composer-rel telepített PHPMailer betöltése

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstname = htmlspecialchars($_POST["firstname"]);
    $lastname = htmlspecialchars($_POST["lastname"]);
    $email = htmlspecialchars($_POST["email"]);
    $message = htmlspecialchars($_POST["message"]);

    $mail = new PHPMailer(true);

    try {
        // SMTP konfiguráció
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Gmail SMTP szerver
        $mail->SMTPAuth = true;
        $mail->Username = 'gypowinery@gmail.com'; // A saját Gmail címed
        $mail->Password = 'ygvy zysu voxa umpo'; // Gmail App Password (nem a normál jelszavad!)
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Feladó és címzett beállítása
        $mail->setFrom($email, "$firstname $lastname");
        $mail->addAddress('gypowinery@gmail.com'); // Címzett email cím

        // E-mail tartalom
        $mail->isHTML(true);
        $mail->Subject = 'Új üzenet az űrlapról';
        $mail->Body = "
            <h2>Kapcsolatfelvételi űrlap üzenete</h2>
            <p><strong>Név:</strong> $firstname $lastname</p>
            <p><strong>Email:</strong> $email</p>
            <p><strong>Üzenet:</strong></p>
            <p>$message</p>
        ";

        // Email elküldése
        $mail->send();
        echo "Sikeresen elküldve!";
    } catch (Exception $e) {
        echo "Hiba történt: {$mail->ErrorInfo}";
    }
} else {
    echo "Hibás kérés!";
}
?>
