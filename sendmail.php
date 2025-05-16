<?php
header('Content-Type: application/json');
echo json_encode(['success' => true, 'message' => 'PHP работает!']);
exit;
?>

<?php
header('Content-Type: application/json');

// Ваши данные
$yourEmail = 'derser21136yandex.ru'; // Замените на ваш реальный Яндекс-email
$subject = 'Новое сообщение с сайта от ' . $_POST['name'];

// Собираем данные из формы
$name = $_POST['name'];
$email = $_POST['email'];
$phone = $_POST['phone'] ?? 'Не указан';
$message = $_POST['message'];

// Формируем тело письма
$body = "
<h2>Новое сообщение с сайта</h2>
<p><strong>Имя:</strong> $name</p>
<p><strong>Email:</strong> $email</p>
<p><strong>Телефон:</strong> $phone</p>
<p><strong>Сообщение:</strong></p>
<p>$message</p>
";

// Для отправки HTML-письма должен быть установлен заголовок Content-type
$headers  = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
$headers .= 'From: ' . $name . ' <' . $email . '>' . "\r\n";

// Отправляем письмо
$mailSent = mail($yourEmail, $subject, $body, $headers);

// Отправляем ответ клиенту
if ($mailSent) {
    echo json_encode(['success' => true, 'message' => 'Сообщение успешно отправлено!']);
} else {
    echo json_encode(['success' => false, 'message' => 'Ошибка при отправке сообщения.']);
}
?>
