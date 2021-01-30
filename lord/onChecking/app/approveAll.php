<?php
    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/db.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/CleanForm.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/CleanFormProduct.php');

    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/LordResidence.php');


    $statusError=[];
    $CleanFormProduct= new CleanFormProduct();
    list  ($productId, $boolError, $textError)=$CleanFormProduct->id($_GET["productId"]);
    if($boolError){array_push($statusError, $textError);}

    //если ошибок нету то продулываем удаление и замену данных
    if(!count($statusError)){
        $LordResidence = new LordResidence($db);
        $LordResidence->approveAll($productId);
    }else{
        $statusError=implode(', ', $statusError);
        exit('<div class="modal"><p>Ошибка: '.$statusError.'!</p></div>');
    }
