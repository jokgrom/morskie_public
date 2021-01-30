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
    list  ($personId, $boolError, $textError)=$CleanFormPerson->personId($_COOKIE['person_id']);
    if($boolError){array_push($statusError, $textError);}

    list  ($personIdentification, $boolError, $textError)=$CleanFormPerson->personIdentification($_COOKIE['person_identification']);
    if($boolError){array_push($statusError, $textError);}
    if(!count($statusError)){
        list  ($boolError, $textError)=$Checks->authenticationPerson($personId, $personIdentification);
        if($boolError){array_push($statusError, $textError);}
    }
    unset($personIdentification);
    if(count($statusError)){
        exit('<div class="modal"><p>Ошибка Аутентификации!</p></div>
            <script  type="text/javascript">
             setTimeout(function(){
                window.location.href = "/cabinet/authorization.php";
            }, 500);
            </script>');
    }



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
