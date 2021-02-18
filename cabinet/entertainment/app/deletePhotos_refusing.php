<?php
    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/db.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/CleanForm.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/CleanFormPerson.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/CleanFormProduct.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/Checks.php');

    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/MyEntertainment.php');

    $CleanFormPerson=new CleanFormPerson;
    $CleanFormProduct= new CleanFormProduct();
    $Checks=new Checks($db);
    $MyEntertainment = new MyEntertainment($db);
    $statusError=[];

    //проверка аутентификации
    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/template/authenticationPerson.php');

    //проверка принадлежности продукта к человеку
    list  ($productId, $boolError, $textError)=$CleanFormProduct->id($_GET["productId"]);
    if($boolError){array_push($statusError, $textError);}
    if(!count($statusError)){
        list  ($boolError, $textError)=$Checks->lastEntertainmentId($personId, $productId);
        if($boolError){array_push($statusError, $textError);}
    }


    //если ошибок нету то удаляем объявление
    if(!count($statusError)){
        //клонируем в другую таблицу и удаяем оригинальную запись в бд
        $MyEntertainment->deletePhotos_refusing($personId, $productId);
    }else{
        $statusError=implode(', ', $statusError);
        exit('<div class="modal"><p>Ошибка: '.$statusError.'!</p></div>');
    }
