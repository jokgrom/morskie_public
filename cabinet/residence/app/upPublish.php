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
require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/template/authenticationPerson.php');

//проверка принадлежности продукта к человеку
list  ($productId, $boolError, $textError)=$CleanFormProduct->id($_GET["productId"]);
if($boolError){array_push($statusError, $textError);}
if(!count($statusError)){
    list  ($boolError, $textError)=$Checks->lastResidenceId($personId, $productId);
    if($boolError){array_push($statusError, $textError);}
}




//если ошибок нету то изменяем поле публикации
if(!count($statusError)){
    echo $MyResidence->upPublish($personId, $productId);
}else{
    $statusError=implode(', ', $statusError);
    exit('<div class="modal"><p>Ошибка: '.$statusError.'!</p></div>');
}