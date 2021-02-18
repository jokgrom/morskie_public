<?php
    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/db.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/CleanForm.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/CleanFormProduct.php');

    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/Entertainment.php');


    $CleanForm=new CleanForm();
    $CleanFormProduct= new CleanFormProduct();
    $Entertainment = new Entertainment($db);

    $product=$_GET['product'];
    //очищаем
    list  ($product["title"], $boolError, $textError)=$CleanFormProduct->title($product["title"]);
    if($boolError){$product["title"]='';}
    $product["city"]=$CleanForm->number($product["city"]);
    $product["suburb"]=$CleanForm->number($product["suburb"]);
    $product["listEntertainment"]=$CleanForm->number($product["listEntertainment"]);


    $Entertainment->getAll($product);