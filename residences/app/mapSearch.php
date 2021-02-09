<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/db.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/CleanForm.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/CleanFormProduct.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/TemplateProduct.php');


$CleanFormProduct= new CleanFormProduct();
$city_id=$_GET['city_id'];
list  ($city_id, $boolError, $textError)=$CleanFormProduct->city($city_id);
if($boolError){$city_id=0;}

$TemplateProduct=new TemplateProduct($db);
echo $TemplateProduct->mapSearch($city_id);