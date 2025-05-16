<?php
header('Content-Type: application/json');

// 1. Настройки (лучше вынести в отдельный config-файл)
$yourEmail = 'derser21136@gmail.com'; // Куда приходить письма
$senderEmail = 'robot@https://228burger228.github.io/triatlon/'; // От кого (должен существовать)

// 2. Проверка данных
if (empty($_POST['name']) || empty($_POST['email']) || empty($_POST['message'])) {
    echo json_encode(['success' => false, 'message' => 'Заполните обязательные поля']);
    exit;
}

// 3. Формирование письма
$subject = "Новое сообщение с сайта от {$_POST['name']}";
$message = "
    <h2>Новое сообщение с сайта</h2>
    <p><strong>Имя:</strong> {$_POST['name']}</p>
    <p><strong>Email:</strong> {$_POST['email']}</p>
    <p><strong>Телефон:</strong> {$_POST['phone'] ?? 'Не указан'}</p>
    <p><strong>Сообщение:</strong></p>
    <p>{nl2br(htmlspecialchars($_POST['message']))}</p>
";

// 4. Отправка через Gmail SMTP с PHPMailer
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'PHPMailer/src/Exception.php';

$mail = new PHPMailer\PHPMailer\PHPMailer(true);

try {
    // Настройки SMTP
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'derser21136@gmail.com'; // Ваш Gmail
    $mail->Password = 'uyrp jjcc ockq suvp'; // Пароль приложения (не основной!)
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;
    $mail->SMTPSecure = 'tls'; // Шифрование
    
    // От кого
    $mail->setFrom($senderEmail, 'Site Robot');
    $mail->addReplyTo($_POST['email'], $_POST['name']);
    
    // Кому
    $mail->addAddress($yourEmail);
    
    // Содержимое
    $mail->isHTML(true);
    $mail->Subject = $subject;
    $mail->Body = $message;
    $mail->AltBody = strip_tags($message);
    
    $mail->send();
    echo json_encode(['success' => true, 'message' => 'Сообщение отправлено!']);
} catch (Exception $e) {
    error_log('Mailer Error: '.$mail->ErrorInfo);
    echo json_encode(['success' => false, 'message' => 'Ошибка при отправке']);
}
?>

// Проверка на ботов
if (empty($_POST['name']) || empty($_POST['message'])) {
    die(json_encode(['success' => false, 'message' => 'Заполните все поля']));
}

// Проверка email
if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
    die(json_encode(['success' => false, 'message' => 'Некорректный email']));
}
