<?php

    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/db.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/Checks.php');

    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/CleanForm.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/CleanFormPerson.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/CleanFormProduct.php');

    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/Entertainment.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/MyEntertainment.php');

    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/TemplateProduct.php');

    $Checks=new Checks($db);
    $CleanForm= new CleanForm;
    $CleanFormPerson = new CleanFormPerson;
    $CleanFormProduct = new CleanFormProduct;
    $Entertainment=new Entertainment($db);
    $MyEntertainment=new MyEntertainment($db);
    $TemplateProduct=new TemplateProduct($db);
    $statusError=[];

    //ищём ошибки
    list  ($productId, $boolError, $textError)=$CleanFormProduct->id($_GET['id']);
    if($boolError){array_push($statusError, $textError);}

    $UrlAdminStatus=$CleanForm->number($_GET['s']);

    //если ошибок нету то выводим инфу
    $bool=true;
    if(!count($statusError)){
        if($UrlAdminStatus==3 OR $UrlAdminStatus==1){
            //проверка аутентификации
            list  ($personId, $boolError, $textError)=$CleanFormPerson->personId($_COOKIE['person_id']);
            if($boolError){array_push($statusError, $textError);}

            list  ($personIdentification, $boolError, $textError)=$CleanFormPerson->personIdentification($_COOKIE['person_identification']);
            if($boolError){array_push($statusError, $textError);}
            if(!count($statusError)){
                list  ($boolError, $textError)=$Checks->authenticationPerson($personId, $personIdentification);
                if(!$boolError){
                    $MyEntertainment->getInfo_forMe($productId);
                    $Entertainment= clone $MyEntertainment;
                    $bool=false;
                }
            }
        }
        if($bool){
            $Entertainment->getInfo($productId);
        }
    }else{
        $statusError=implode(', ', $statusError);
        exit("<div class='modal'><p>Ошибка: $statusError!</p></div>
                <script type='text/javascript'>
                setTimeout(function(){
                    window.location.href = '/residences/';
                }, 500);
                </script>");
    }
?>
<!DOCTYPE html>
<html lang="ru">
<head><?php require_once($_SERVER['DOCUMENT_ROOT'].'/root/blocks/head.php');?>
    <style>.fotorama__nav {text-align: center;}</style>
</head>
<script src="https://maps.api.2gis.ru/2.0/loader.js?pkg=full"></script>
<body>
<div class="page page-entertainment">
    <div class="wrap">
        <?php include_once($_SERVER['DOCUMENT_ROOT'].'/root/blocks/header.php');?>
        <div class="container">
            <div class="content">
                <aside class="left-bar">
                    <ul class="cell">
                        <li><a href="#photo">фото</a></li>
                        <li><a href="#description">описание</a></li>
                        <li><a href="#price">цены</a></li>
                        <li><a href="#contacts">контакты</a></li>
                        <li><a href="#address">на карте</a></li>
                        <script>
                            const leftBar=$(".left-bar .cell");
                            $(window).scroll(function(){
                                leftBar.css({"top":$(this).scrollTop()});
                            });
                        </script>
                    </ul>
                </aside>
                <main class="main">
                    <h1 class="title"><?php echo $Entertainment->title; ?></h1>
                    <p><span class="product-address"><?php echo $Entertainment->cityTitle.', '.$Entertainment->suburbTitle; ?> | <span class="product-distance"><?php echo $Entertainment->entertainment; ?></span></span></p>
                    <section class="section section-photo" id="photo">
                        <div class="fotorama"  data-nav="thumbs" data-navposition="bottom" data-transition="crossfade" data-loop="true"  data-maxheight="500" data-width="100%">
                            <?php echo $TemplateProduct->getPhotoEntertainmentBox($productId, $UrlAdminStatus); ?>
                        </div>
                    </section>
                    <section class="section" id="conveniences">
                        <?php echo $TemplateProduct->conveniences2($Entertainment->conveniences); ?>
                    </section>
                    <section class="section" id="description">
                        <p class="strong">Описание</p>
                        <p><?php echo $Entertainment->description; ?></p>
                    </section>
                    <section class="section" id="price">
                        <p class="strong">Цена</p>
                        <p><?php echo $Entertainment->prices; ?></p>
                        <p><small>Внимание! Цены могут отличаться.</small></p>
                    </section>
                    <section class="section" id="contacts">
                        <p class="strong">Контакты</p>
                        <p><small class="color4">Если возникли не совпадения с описанием, то сообщите об этом собственнику или <a href="https://morskie-puti.ru/contacts/" target="_blank">нам</a>! Мы постараемся навести порядок в этом хаосе :) С уважением, "Морские пути".</small></p>
                        <p><?php echo $Entertainment->contacts; ?></p>
                    </section>
                    <section class="section" id="address">
                        <p><span class="strong">Адрес:</span> <?php echo $Entertainment->address;?></p>
                        <?php echo $TemplateProduct->map($Entertainment->address, $Entertainment->addressLatitude, $Entertainment->addressLongitude); ?>
                    </section>
                </main>
            </div>
        </div>
    </div>
</div>
<?php include_once($_SERVER['DOCUMENT_ROOT'].'/root/blocks/footer.php');?>
</body>
</html>