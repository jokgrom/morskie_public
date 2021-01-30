<?php
    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/db.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/CleanForm.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/CleanFormPerson.php');

    $personEmail=$_GET['personEmail'];
    $CleanFormPerson= new CleanFormPerson();
    $statusError=[];

	//ищём ошибки	
	list  ($personEmail, $boolError, $textError)=$CleanFormPerson->mail($personEmail);
	if($boolError){array_push($statusError, $textError);}
	unset($boolError, $textError);

	// //если ошибок нету то востанавливаем почту
	if(!count($statusError)){
        exit("<div class='modal'><p>На указанный mail отправленно письмо с информацией по восстановлению пароля!</p></div>");
	}else{
        $statusError=implode(', ', $statusError);
        exit("<div class='modal'><p>Ошибка: $statusError!</p></div>");
	}