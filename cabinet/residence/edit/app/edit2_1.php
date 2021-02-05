<?php
    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/db.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/Checks.php');

    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/CleanForm.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/CleanFormPerson.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/CleanFormProduct.php');

    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/MyResidence.php');


    $statusError=[];
    $CleanFormPerson=new CleanFormPerson;
    $CleanFormProduct= new CleanFormProduct();
    $Checks=new Checks($db);

    //проверка аутентификации
    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/template/authenticationPerson.php');


    //ищем ошибки
    list  ($productId, $boolError, $textError)=$CleanFormProduct->id($_GET['productId']);
    if($boolError){array_push($statusError, $textError);}

    //его ли это объявление
    list  ($boolError, $textError)=$Checks->lastResidenceId($personId, $productId);
    if($boolError){array_push($statusError, $textError);}

    //ищем ошибки в id старых фото
    $idOldPhotos=[];
    if(count($_GET['idOldPhotos'])>=1 ){
        foreach($_GET['idOldPhotos']  as $key=>$cell){
            list  ($idOldPhotos[$key], $boolError, $textError)=$CleanFormProduct->id($cell);
            if($boolError){array_push($statusError, $textError);}
        }
    }

    //если ошибок нету то добавляем в бд
    if(!count($statusError)){
        $MyResidence = new MyResidence($db);
        if($MyResidence->editPhoto($personId, $productId, $idOldPhotos)){
            exit('<div class="modal"><p>Изменения сохранены!</p></div>
                <script  type="text/javascript">
                    setTimeout(function(){
                    window.location.href = "/cabinet/residence/";
                }, 1000);
                </script>');
        }
    }else{
        $statusError=implode(', ', $statusError);
        exit('<div class="modal"><p>Ошибка: '.$statusError.'!</p></div>');
    }