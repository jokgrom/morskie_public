<?php
    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/db.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/CleanForm.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/CleanFormPerson.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/Person.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/Landlord.php');


	$person=$_GET['person'];
    $CleanFormPerson= new CleanFormPerson();
    $statusError=[];

	//ищём ошибки
    list  ($person["phone"], $boolError, $textError)=$CleanFormPerson->phone($person["phone"]);
    if($boolError){array_push($statusError, $textError);}

    list  ($person["password"], $boolError, $textError)=$CleanFormPerson->password($person["password"]);
    if($boolError){array_push($statusError, $textError);}
	unset($boolError,$textError);


	//если ошибок нету то авторизируемся
	if(!count($statusError)){
        $Landlord = new Landlord($db);
        if($Landlord->checkExistence_person($person["phone"])){
            if($Landlord->authorization($person["phone"], $person["password"])){
                exit('<script type="text/javascript">
                    setTimeout(function(){
                    window.location.href = "/cabinet/residence/";
                }, 500);
                </script>');
            }else{
                exit('<div class="modal"><p>Неверный пароль!</p></div>');
            }
        }else{
            exit('<div class="modal"><p>Неверный логин!</p></div>');
        }
	}else{
        $statusError=implode(', ', $statusError);
        exit("<div class='modal'><p>Ошибка: $statusError !</p></div>");
	}