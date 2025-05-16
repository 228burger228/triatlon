<?php
header('Content-Type: application/json');

// 1. Настройки (лучше вынести в отдельный config-файл)
$recipientEmail = 'ваш_email@yandex.ru'; // Куда приходить письма
$senderEmail = 'robot@ваш_сайт.ru'; // От кого (должен существовать)
$yandexLogin = 'ваш_логин@yandex.ru'; // Без пароля!

// 2. Проверка данных
$requiredFields = ['name', 'email', 'message'];
foreach ($requiredFields as $field) {
    if (empty($_POST[$field])) {
        echo json_encode(['success' => false, 'message' => 'Заполните все обязательные поля']);
        exit;
    }
}

// 3. Формирование письма
$subject = "Новое сообщение с сайта от {$_POST['name']}";
$message = "
    <h2>Новое сообщение с сайта</h2>
    <p><strong>Имя:</strong> {$_POST['name']}</p>
    <p><strong>Email:</strong> {$_POST['email']}</p>
    <p><strong>Телефон:</strong> {$_POST['phone'] ?? 'Не указан'}</p>
    <p><strong>Сообщение:</strong></p>
    <p>{nl2br($_POST['message'])}</p>
";

// 4. Отправка через mail() (без SMTP пароля)
$headers = [
    'From' => "$senderEmail",
    'Reply-To' => $_POST['email'],
    'Content-Type' => 'text/html; charset=UTF-8',
    'X-Mailer' => 'PHP/' . phpversion()
];

$headersString = implode("\r\n", array_map(
    fn($k, $v) => "$k: $v", 
    array_keys($headers), 
    $headers
));

if (mail($recipientEmail, $subject, $message, $headersString)) {
    echo json_encode(['success' => true, 'message' => 'Сообщение отправлено!']);
} else {
    error_log('Ошибка отправки почты: ' . print_r(error_get_last(), true));
    echo json_encode(['success' => false, 'message' => 'Ошибка сервера при отправке']);
}
?>
