<?php
$to = "info@mos-propusk-24.ru";


$name = $_POST['name'];
$phone = $_POST['phone'];
$subject = $_POST['subj'];






/* Для отправки HTML-почты вы можете установить шапку Content-type. */
$headers= "MIME-Version: 1.0\r\n";
$headers .= "Content-type: text/html; charset=utf-8\r\n";

/* дополнительные шапки */
$headers .= "From: Пропуска МКАД, ТТК, СК -  RWIFamily <info@mos-propusk-24.ru>\r\n";





$message = "Имя пославшего письмо: $name\nТелефон:$phone";
$ml=mail($to, $subject, $message, $headers);
echo '<center><b>Сообщение успешно отправлено!</b></center>';
exit;
?>