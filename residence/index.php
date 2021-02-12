<?php
    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/db.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/Checks.php');

    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/CleanForm.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/CleanFormPerson.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/CleanFormProduct.php');

    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/Product.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/Residence.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/MyResidence.php');

    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/TemplateProduct.php');

    $Checks=new Checks($db);
    $CleanForm= new CleanForm;
    $CleanFormPerson = new CleanFormPerson;
    $CleanFormProduct = new CleanFormProduct;
    $Residence=new Residence($db);
    $MyResidence=new MyResidence($db);
    $TemplateProduct=new TemplateProduct($db);

    $statusError=[];

    //ищем ошибки
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
                    $MyResidence->getInfo_forMe($productId);
                    $Residence= clone $MyResidence;
                    $bool=false;
                }
            }
        }
        if($bool){
            $Residence->getInfo($productId);
        }

        //определим цены
        $prices_object=($Residence->prices!='' ? json_decode($Residence->prices) : [] );
        $prices=[];
        if(is_object($prices_object)) {
            foreach ($prices_object as $key){
                array_push($prices, $key);
            }
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
<div class="page page-residence">
    <div class="wrap">
        <?php include_once($_SERVER['DOCUMENT_ROOT'].'/root/blocks/header.php');?>
        <div class="container">
            <div class="content">
                <aside class="left-bar">
                    <ul class="cell">
                        <li><a href="#photo">фото</a></li>
                        <li><a href="#conveniences">удобства</a></li>
                        <li><a href="#price">цены</a></li>
                        <li><a href="#rules">правила</a></li>
                        <li><a href="#description">описание</a></li>
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
                    <h1 class="title"><?php echo $Residence->title; ?></h1>
                    <p><span class="product-address"><?php echo $Residence->cityTitle.', '.$Residence->suburbTitle; ?> | <span class="product-distance"> до моря <?php echo $Residence->distanceTitle; ?></span></span></p>
                    <section class="section section-photo" id="photo">
                        <div class="fotorama"  data-nav="thumbs" data-navposition="bottom" data-transition="crossfade" data-loop="true"  data-maxheight="500" data-width="100%">
                            <?php echo $TemplateProduct->getPhotoBox($productId, $UrlAdminStatus); ?>
                        </div>
                    </section>
                    <section class="section" id="conveniences">
                        <?php echo $TemplateProduct->conveniences2($Residence->conveniences); ?>
                    </section>
                    <section class="section" id="price">
                        <p class="strong">Цена за номер в сутки</p>
                        <div  class="table-price ">
                            <div class="cell"><p class="th">январь</p><p class="td"><?php echo $prices[0];?> &#8381;</p></div>
                            <div class="cell"><p class="th">февраль</p><p class="td"><?php echo $prices[1];?> &#8381;</p></div>
                            <div class="cell"><p class="th">март</p><p class="td"><?php echo $prices[2];?> &#8381;</p></div>
                            <div class="cell"><p class="th">апрель</p><p class="td"><?php echo $prices[3];?> &#8381;</p></div>
                            <div class="cell"><p class="th">май</p><p class="td"><?php echo $prices[4];?> &#8381;</p></div>
                            <div class="cell"><p class="th">июнь</p><p class="td"><?php echo $prices[5];?> &#8381;</p></div>
                            <div class="cell"><p class="th">июль</p><p class="td"><?php echo $prices[6];?> &#8381;</p></div>
                            <div class="cell"><p class="th">август</p><p class="td"><?php echo $prices[7];?> &#8381;</p></div>
                            <div class="cell"><p class="th">сентябрь</p><p class="td"><?php echo $prices[8];?> &#8381;</p></div>
                            <div class="cell"><p class="th">октябрь</p><p class="td"><?php echo $prices[9];?> &#8381;</p></div>
                            <div class="cell"><p class="th">ноябрь</p><p class="td"><?php echo $prices[10];?> &#8381;</p></div>
                            <div class="cell"><p class="th">декабрь</p><p class="td"><?php echo $prices[11];?> &#8381;</p></div>
                        </div>
                        <p><small>Внимание! Цены могут отличаться. Уточните цену у собственника.</small></p>
                    </section>
                    <section class="section" id="rules">
                        <p class="strong">Правила и ограничения</p>
                        <p><?php echo $Residence->rules; ?></p>
                    </section>
                    <section class="section" id="description">
                        <p class="strong">Описание</p>
                        <p>Тип жилья: <?php echo $Residence->type_housingTitle; ?></p>
                        <p>Вместительность: <?php echo $Residence->guestTitle; ?></p>
                        <p><?php echo $Residence->description; ?></p>
                    </section>
                    <section class="section" id="contacts">
                        <p class="strong">Контакты</p>
                        <p><small class="color4">Если возникли не совпадения с описанием, то сообщите об этом собственнику или <a href="https://morskie-puti.ru/contacts/" target="_blank">нам</a>! Мы постараемся навести порядок в этом хаосе :) С уважением, "Морские пути".</small></p>
                        <p><?php echo $Residence->contacts; ?></p>
                    </section>
                    <section class="section" id="address">
                        <p><span class="strong">Адрес:</span> <?php echo $Residence->address;?></p>
                        <?php echo $TemplateProduct->map($Residence->address, $Residence->addressLatitude, $Residence->addressLongitude); ?>
                    </section>
                </main>
            </div>
        </div>
    </div>
</div>
<?php include_once($_SERVER['DOCUMENT_ROOT'].'/root/blocks/footer.php');?>
</body>
</html>