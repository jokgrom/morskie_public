<?php
    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/db.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/Checks.php');

    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/CleanForm.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/CleanFormPerson.php');

    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/Person.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/Landlord.php');


	$person=$_GET['person'];
    $CleanFormPerson= new CleanFormPerson();
    $Checks=new Checks($db);
    $statusError=[];
//    $person["phone"]=89999999915;
//    $person["password"]=555;
   // $person["password2"]='55d5';

	//ищём ошибки
	list  ($person["phone"], $boolError, $textError)=$CleanFormPerson->phone($person["phone"]);
	if($boolError){array_push($statusError, $textError);}

	list  ($person["password"], $boolError, $textError)=$CleanFormPerson->password($person["password"]);
	if($boolError){array_push($statusError, $textError);}

	list  ($person["password2"], $boolError, $textError)=$CleanFormPerson->password($person["password2"]);
	if($boolError){array_push($statusError, $textError);}
	if($person['password']!=$person['password2']){
		array_push($statusError, 'пароли не совпадают');
	}

	//подготовка ip
	$clientIp  = @$_SERVER['HTTP_CLIENT_IP'];
	$forwardIp = @$_SERVER['HTTP_X_FORWARDED_FOR'];
	$remoteIp  = @$_SERVER['REMOTE_ADDR'];
	if(filter_var($clientIp, FILTER_VALIDATE_IP)) $personIp = $clientIp;
	elseif(filter_var($forwardIp, FILTER_VALIDATE_IP)) $personIp = $forwardIp;
	else $personIp = $remoteIp;
	if($personIp==''){
		array_push($statusError, 'не корректный IP');
	}
	unset($boolError, $textError, $clientIp, $forwardIp, $remoteIp);

	//проверяем лимит в день
    $countPersonRegistration=$Checks->countPersonRegistration();
    if($countPersonRegistration>=50){
        array_push($statusError, 'Превышен лимит регистраций');
    }

//	если ошибок нету то регистрируемся
	if(!count($statusError)){
		$Landlord = new Landlord($db);
        if($Landlord->checkExistence_person($person["phone"])){
            exit('<div class="modal"><p>Номер занят!</p></div>');
        }else{
            if($Landlord->registration($person["phone"], $person["password"], $personIp)){
                exit('<script type="text/javascript">
		            setTimeout(function(){
		            window.location.href = "/cabinet/residence/";
		        }, 500);
		        </script>');
            }else{
                exit('<div class="modal"><p>Ошибка при регистрации!</p></div>');
            }
        }
	}else{
        $statusError=implode(', ', $statusError);
        exit("<div class='modal'><p>Ошибка: $statusError!</p></div>");
	}
