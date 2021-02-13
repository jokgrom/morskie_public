<?php
    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/db.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/CleanForm.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/CleanFormPerson.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/CleanFormProduct.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/Checks.php');

    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/MyEntertainment.php');

    $statusError=[];
    $CleanFormPerson=new CleanFormPerson;
    $CleanFormProduct= new CleanFormProduct();
    $Checks=new Checks($db);

    //проверка аутентификации
    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/template/authenticationPerson.php');

    //ограничение по колличеству
    $countProduct=$Checks->countEntertainment($personId);
    if($countProduct>=5){
        array_push($statusError, "Превышен лимит. Не более 5ти объявлений");
    }


   $product=$_POST['product']; //снять комент потом
    //ищём ошибки
    list  ($product["title"], $boolError, $textError)=$CleanFormProduct->title($product["title"]);
    if($boolError){array_push($statusError, $textError);}

    list  ($product["city"], $boolError, $textError)=$CleanFormProduct->city($product["city"]);
    if($boolError){array_push($statusError, $textError);}

    list  ($product["suburb"], $boolError, $textError)=$CleanFormProduct->suburb($product["suburb"]);
    if($boolError){array_push($statusError, $textError);}

    //проверка на совместимость города и посёлка
    list  ($boolError, $textError)=$Checks->suburbOnCity($product["city"], $product["suburb"]);
    if($boolError){array_push($statusError, $textError);}

    list  ($product["description"], $boolError, $textError)=$CleanFormProduct->description($product["description"]);
    if($boolError){array_push($statusError, $textError);}

    list  ($product["entertainmentPrice"], $boolError, $textError)=$CleanFormProduct->entertainmentPrice($product["entertainmentPrice"]);
    if($boolError){array_push($statusError, $textError);}

    list  ($product["address"], $boolError, $textError)=$CleanFormProduct->address($product["address"]);
    if($boolError){array_push($statusError, $textError);}

    list  ($product["addressLatitude"], $boolError, $textError)=$CleanFormProduct->coordinate($product["addressLatitude"]);
    if($boolError){array_push($statusError, $textError);}

    list  ($product["addressLongitude"], $boolError, $textError)=$CleanFormProduct->coordinate($product["addressLongitude"]);
    if($boolError){array_push($statusError, $textError);}

    list  ($product["contacts"], $boolError, $textError)=$CleanFormProduct->contacts($product["contacts"]);
    if($boolError){array_push($statusError, $textError);}

    list  ($product["listEntertainment"], $boolError, $textError)=$CleanFormProduct->listEntertainment($product["listEntertainment"]);
    if($boolError){array_push($statusError, $textError);}

    //если ошибок нету то добавляем в бд
    if(!count($statusError)){
        $MyEntertainment = new MyEntertainment($db);
        $MyEntertainment->add($personId, $product);
    }else{
        $statusError=implode(', ', $statusError);
        echo '<p>Ошибка: $statusError!</p>';
        exit("<div class='modal'><p>Ошибка: $statusError!</p></div>");
    }