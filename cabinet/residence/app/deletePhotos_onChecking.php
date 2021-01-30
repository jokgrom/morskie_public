<?php
    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/db.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/CleanForm.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/CleanFormPerson.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/CleanFormProduct.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/Checks.php');

    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/MyResidence.php');

    $CleanFormPerson=new CleanFormPerson;
    $CleanFormProduct= new CleanFormProduct();
    $Checks=new Checks($db);
    $MyResidence = new MyResidence($db);
    $statusError=[];

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

    //проверка принадлежности продукта к человеку
    list  ($productId, $boolError, $textError)=$CleanFormProduct->id($_GET["productId"]);
    if($boolError){array_push($statusError, $textError);}
    if(!count($statusError)){
        list  ($boolError, $textError)=$Checks->lastResidenceId($personId, $productId);
        if($boolError){array_push($statusError, $textError);}
    }


    //если ошибок нету то удаляем объявление
    if(!count($statusError)){
        //клонируем в другую таблицу и удаяем оригинальную запись в бд
        $MyResidence->deletePhotos_onChecking($personId, $productId);
    }else{
        $statusError=implode(', ', $statusError);
        exit('<div class="modal"><p>Ошибка: '.$statusError.'!</p></div>');
    }
