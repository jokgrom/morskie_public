<?php
    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/db.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/CleanForm.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/CleanFormPerson.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/CleanFormProduct.php');

    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/LordResidence.php');

    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/Checks.php');
    $Checks=new Checks($db);
    $Checks->authenticationLord();

    $statusError=[];
    $CleanFormProduct= new CleanFormProduct();
    $CleanFormPerson=new CleanFormPerson;
    list  ($productId, $boolError, $textError)=$CleanFormProduct->id($_GET["productId"]);
    if($boolError){array_push($statusError, $textError);}

    list  ($infoError, $boolError, $textError)=$CleanFormPerson->message($_GET["infoError"]);
    if($boolError){array_push($statusError, $textError);}

    //если ошибок нету то продулываем удаление и замену данных
    if(!count($statusError)){
        $LordResidence = new LordResidence($db);
        $LordResidence->refusingProduct($productId, $infoError);
    }else{
        $statusError=implode(', ', $statusError);
        exit('<div class="modal"><p>Ошибка: '.$statusError.'!</p></div>');
    }
