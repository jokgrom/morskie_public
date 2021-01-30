<?php
    require_once('class/db.php');
    require_once('class/CleanForm.php');
    require_once('class/CleanFormProduct.php');
    require_once('class/TemplateProduct.php');


    $CleanFormProduct= new CleanFormProduct();
    $product["city"]=$_GET['city'];
    list  ($product["city"], $boolError, $textError)=$CleanFormProduct->city($product["city"]);
    if($boolError){$product["city"]=0;}

    $TemplateProduct=new TemplateProduct($db);
    echo $TemplateProduct->suburb($product["city"]);