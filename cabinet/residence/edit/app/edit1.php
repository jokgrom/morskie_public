<?php
    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/db.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/Checks.php');

    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/CleanForm.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/CleanFormPerson.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/CleanFormProduct.php');

    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/Product.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/MyResidence.php');

    $statusError=[];
    $CleanFormPerson=new CleanFormPerson;
    $CleanFormProduct= new CleanFormProduct();
    $Checks=new Checks($db);

    //проверка аутентификации
    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/template/authenticationPerson.php');


    $product=$_POST['product']; //снять комент потом
    //ищём ошибки
    list  ($product["id"], $boolError, $textError)=$CleanFormProduct->id($product["id"]);
    if($boolError){array_push($statusError, $textError);}

    //его ли это объявление
    list  ($boolError, $textError)=$Checks->lastResidenceId($personId, $product["id"]);
    if($boolError){array_push($statusError, $textError);}

    list  ($product["title"], $boolError, $textError)=$CleanFormProduct->title($product["title"]);
    if($boolError){array_push($statusError, $textError);}

    list  ($product["city"], $boolError, $textError)=$CleanFormProduct->city($product["city"]);
    if($boolError){array_push($statusError, $textError);}

    list  ($product["suburb"], $boolError, $textError)=$CleanFormProduct->suburb($product["suburb"]);
    if($boolError){array_push($statusError, $textError);}

    //проверка на совместимость города и посёлка
    list  ($boolError, $textError)=$Checks->suburbOnCity($product["city"], $product["suburb"]);
    if($boolError){array_push($statusError, $textError);}

    list  ($product["guest"], $boolError, $textError)=$CleanFormProduct->guest($product["guest"]);
    if($boolError){array_push($statusError, $textError);}

    list  ($product["typeHousing"], $boolError, $textError)=$CleanFormProduct->typeHousing($product["typeHousing"]);
    if($boolError){array_push($statusError, $textError);}

    list  ($product["distance"], $boolError, $textError)=$CleanFormProduct->distance($product["distance"]);
    if($boolError){array_push($statusError, $textError);}

    list  ($product["adOwner"], $boolError, $textError)=$CleanFormProduct->adOwner($product["adOwner"]);
    if($boolError){array_push($statusError, $textError);}

    list  ($product["rules"], $boolError, $textError)=$CleanFormProduct->rules($product["rules"]);
    if($boolError){array_push($statusError, $textError);}

    list  ($product["description"], $boolError, $textError)=$CleanFormProduct->description($product["description"]);
    if($boolError){array_push($statusError, $textError);}

    list  ($product["address"], $boolError, $textError)=$CleanFormProduct->address($product["address"]);
    if($boolError){array_push($statusError, $textError);}

    list  ($product["addressLatitude"], $boolError, $textError)=$CleanFormProduct->coordinate($product["addressLatitude"]);
    if($boolError){array_push($statusError, $textError);}

    list  ($product["addressLongitude"], $boolError, $textError)=$CleanFormProduct->coordinate($product["addressLongitude"]);
    if($boolError){array_push($statusError, $textError);}

    list  ($product["contacts"], $boolError, $textError)=$CleanFormProduct->contacts($product["contacts"]);
    if($boolError){array_push($statusError, $textError);}

    //ищем ошибки в удобствах
    $product_conveniences=[];
    if(count($product["conveniences"])>=1 ){
        foreach($product["conveniences"]  as $key=>$cell){
            list  ($product_conveniences[$key], $boolError, $textError)=$CleanFormProduct->conveniences($cell);
            if($boolError){array_push($statusError, $textError);}
        }
    }
    if(count($product_conveniences)<3){
        array_push($statusError, 'выбранно мало удобств(минимум 3)');
    }else{
        $product["conveniences"]=$product_conveniences;
    }

    //ищем ошибки в ценах
    $nanMonth=true;
    $product_price=[];
    if(count($product["price"])==12){
        foreach($product["price"] as $key=>$cell){
            list  ($product_price[$key], $boolError, $textError)=$CleanFormProduct->price($cell);
            if($boolError){
                array_push($statusError, $textError);
            }else{
                if($product_price[$key]!=0){$nanMonth=false;}
            }
        }
        if($nanMonth){
            array_push($statusError, 'не заполнено поле "Цена"(минимум 1)');
        }else{
            $product["price"]=array("1"=>$product_price[0],
                "2"=>$product_price[1],
                "3"=>$product_price[2],
                "4"=>$product_price[3],
                "5"=>$product_price[4],
                "6"=>$product_price[5],
                "7"=>$product_price[6],
                "8"=>$product_price[7],
                "9"=>$product_price[8],
                "10"=>$product_price[9],
                "11"=>$product_price[10],
                "12"=>$product_price[11]);
        }
    }else{
        array_push($statusError, 'не все цены прошли обработку');
    }
    //если ошибок нету то добавляем в бд
    if(!count($statusError)){
        $MyResidence = new MyResidence($db);
        $MyResidence->edit($personId, $product);
    }else{
        $statusError=implode(', ', $statusError);
        exit('<div class="modal"><p>Ошибка: '.$statusError.'!</p></div>');
    }