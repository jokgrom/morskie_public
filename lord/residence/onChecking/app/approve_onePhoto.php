<?php
    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/db.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/CleanForm.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/CleanFormProduct.php');

    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/LordResidence.php');

    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/Checks.php');
    $Checks=new Checks($db);
    $Checks->authenticationLord();

    $statusError=[];
    $CleanFormProduct= new CleanFormProduct();
    list  ($photoId, $boolError, $textError)=$CleanFormProduct->id($_GET["photoId"]);
    if($boolError){array_push($statusError, $textError);}

    //если ошибок нету то меняем статус на одобренно
    if(!count($statusError)){
        $LordResidence = new LordResidence($db);
        $LordResidence->approve_onePhoto($photoId);
    }else{
        $statusError=implode(', ', $statusError);
        exit('<div class="modal"><p>Ошибка: '.$statusError.'!</p></div>');
    }
