<?php
    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/db.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/CleanForm.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/CleanFormPerson.php');

	$description=$_GET['description'];
    $CleanFormPerson= new CleanFormPerson();
    $statusError=[];

	//ищём ошибки	
	list  ($description, $boolError, $textError)=$CleanFormPerson->description($description);
	if($boolError){array_push($statusError, $textError);}
	unset($boolError, $textError);

	// //если ошибок нету то востанавливаем почту
	if(!count($statusError)){
        exit("<div class='modal'><p>Заявка на восстановления пароля отправлена!</p></div>");
	}else{
        $statusError=implode(', ', $statusError);
        exit("<div class='modal'><p>Ошибка: $statusError</p></div>");
	}