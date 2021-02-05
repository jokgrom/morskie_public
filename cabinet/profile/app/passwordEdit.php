<?php
    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/db.php');

    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/CleanForm.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/CleanFormPerson.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/Checks.php');

    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/Person.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/Landlord.php');

    $statusError=[];
    $CleanFormPerson=new CleanFormPerson;
    $Checks=new Checks($db);
    $Landlord=new Landlord($db);

    //проверка аутентификации
    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/template/authenticationPerson.php');



    $person=$_GET['formPassword'];
    $person["id"]=$personId;
    //ищём ошибки
    list  ($person["password"], $boolError, $textError)=$CleanFormPerson->password($person["password"]);
    if($boolError){array_push($statusError, $textError);}

    list  ($person["password2"], $boolError, $textError)=$CleanFormPerson->password($person["password2"]);
    if($boolError){array_push($statusError, $textError);}
    if($person['password']!=$person['password2']){
        array_push($statusError, 'пароли не совпадают');
    }

//если ошибок нету меняем все данные
	if(!count($statusError)){
        if($Landlord->updatePassword($person)){
            exit('<div class="modal"><p>Пароль изменён!</p></div>');
        }
	}else{
        $statusError=implode(', ', $statusError);
		exit('<div class="modal"><p>Ошибка: '.$statusError.'!</p></div>');
	}
