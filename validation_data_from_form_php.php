<?php
// Data from forms
$model =  $phone = $name = $mail = $form3 = '';

if(isset($_POST['model'])){$model = test_input($_POST['model']);}
if(isset($_POST['name'])){ $name  = test_input($_POST['name']);}
if(isset($_POST['phone'])){$phone = test_input($_POST['phone']);}
if(isset($_POST['email'])){$email = test_input($_POST['email']);}
if(isset($_POST['form3dickaunt'])){$form3 = test_input($_POST['form3dickaunt']);}

$error = 0;

function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}


if(!preg_match("/^[0-9 -]{7,16}+$/", $phone)){
  echo ("<center><h3>Телефон задан в неверном формате</h3></center>");
  $error = 1;
}

if(!empty($name)){
	if (!preg_match('/^[а-яА-ЯёЁ ]{2,20}$/ui', $name)) {
	  echo ("<center><h3>Введите имя русскими буквами</h3></center>");
	  $error = 1;
	}
}

if(!empty($email)){
	if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
	  echo ("<center><h3>Не верный формат мэйла</h3></center>");
	  $error = 1;
	}
}

?>
