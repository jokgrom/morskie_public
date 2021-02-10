<?php
    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/db.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/CleanForm.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/CleanFormProduct.php');

    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/Product.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/Residence.php');


    $CleanForm=new CleanForm();
    $CleanFormProduct= new CleanFormProduct();
    $Residence = new Residence($db);

    $product=$_GET['product'];
    //очищаем
    list  ($product["title"], $boolError, $textError)=$CleanFormProduct->title($product["title"]);
    if($boolError){$product["title"]='';}
    $product["city"]=$CleanForm->number($product["city"]);
    $product["suburb"]=$CleanForm->number($product["suburb"]);
    $product["guest"]=$CleanForm->number($product["guest"]);
    $product["typeHousing"]=$CleanForm->number($product["typeHousing"]);
    $product["distance"]=$CleanForm->number($product["distance"]);
    $product["priceFrom"]=$CleanForm->number($product["priceFrom"]);
    $product["priceTo"]=$CleanForm->number($product["priceTo"]);
    $product["priceMonth"]=$CleanForm->number($product["priceMonth"]);
    $product["adOwner"]=$CleanForm->number($product["adOwner"]);
    $product["sort"]=$CleanForm->number($product["sort"]);

    $conveniences=[];
    if(count($product["conveniences"])>=1){
        foreach ($product["conveniences"] as $key){
                array_push($conveniences, $CleanForm->number($key));
        }
    }
    $product["conveniences"]=$conveniences;
    $Residence->getAll($product);