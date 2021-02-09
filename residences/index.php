<?php

    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/db.php');

    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/CleanForm.php');

    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/Product.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/Residence.php');

    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/TemplateProduct.php');

    $TemplateProduct=new TemplateProduct($db);
    $CleanForm=new CleanForm();
    $Residence = new Residence($db);
    $statusError=[];


    //ищём ошибки
    $product["city"]=$CleanForm->number($_GET["city"]);
    $product["suburb"]=$CleanForm->number($_GET["suburb"]);
    $product["guest"]=$CleanForm->number($_GET["guest"]);
    $product["typeHousing"]=$CleanForm->number($_GET["typeHousing"]);
    $product["distance"]=$CleanForm->number($_GET["distance"]);
    $product["priceFrom"]=$CleanForm->number($_GET["priceFrom"]);
    $product["priceTo"]=$CleanForm->number($_GET["priceTo"]);
    $product["priceMonth"]=$CleanForm->number($_GET["priceMonth"]);
    $product["adOwner"]=$CleanForm->number($_GET["adOwner"]);
    $product["sort"]=$CleanForm->number($_GET["sort"]);

    $product["conveniences"]=preg_replace('/[^\d_]/','', $_GET["conveniences"]);
    $product["conveniences"] = explode("_", $product["conveniences"]);
    $product["conveniences"] = array_diff($product["conveniences"], array(''));
    $product["page"]=$CleanForm->number($_GET["page"]);

    $city_id=($product["suburb"]==0 ? $product["city"] : $product["suburb"]);


?>
<!DOCTYPE html>
<html lang="ru">
    <head><?php require_once($_SERVER['DOCUMENT_ROOT'].'/root/blocks/head.php');?></head>
    <script src="/root/js/cleanFormProduct.js<?php echo $updateUrlHash; ?>"></script>
    <script src="/root/js/residences_find.js<?php echo $updateUrlHash; ?>"></script>
    <script src="https://maps.api.2gis.ru/2.0/loader.js?pkg=full"></script>
    <body class="main page-residences">
    <div class="page">
        <div class="wrap">
            <?php include_once($_SERVER['DOCUMENT_ROOT'].'/root/blocks/header.php');?>
            <div class="container">
                <div class="content">
                    <aside class="left-bar">
                        <?php echo $TemplateProduct->getListConveniencesBox($product["conveniences"]);?>
                    </aside>
                    <main id="main">
                        <aside class="top-bar">
                            <div class="cell"><?php echo $TemplateProduct->city($product["city"]); ?></div>
                            <div class="cell"><?php echo $TemplateProduct->suburb($product["city"], $product["suburb"]); ?></div>
                            <div class="cell"><?php echo $TemplateProduct->guest($product["guest"]); ?></div>
                            <div class="cell"><?php echo $TemplateProduct->typeHousing($product["typeHousing"]); ?></div>
                            <div class="cell"><?php echo $TemplateProduct->distance($product["distance"]); ?></div>
                            <div class="cell">
                                <label><input type="text" placeholder="цена от" class="filter product-price price-from" value="<?php echo $product["priceFrom"]; ?>"></label>
                                -
                                <label><input type="text" placeholder="цена до" class="filter product-price price-to" value="<?php echo $product["priceTo"]; ?>"></label>
                            </div>
                            <div class="cell"><?php echo $TemplateProduct->priceMonth($product["priceMonth"]); ?></div>
                            <div class="cell"><?php echo $TemplateProduct->adOwner($product["adOwner"]); ?></div>
                            <div class="cell"><?php echo $TemplateProduct->sort($product["sort"]); ?></div>
                            <div class="cell">
                                <input id="GO" class="filter" type="button" value="найти">
                                <input class="filter reset" type="button" value="сбросить фильтр">
                            </div>
                        </aside>
                        <p><?php echo $TemplateProduct->html_h1($product); ?></p>
                        <div class="mapImgSearch"><?php if($product["city"]!=0){echo $TemplateProduct->mapSearch($city_id);} ?></div>
                        <div id="product-box"><?php  $Residence->getAll($product);?></div>
                    </main>
                </div>
            </div>
        </div>
    </div>
    <?php include_once($_SERVER['DOCUMENT_ROOT'].'/root/blocks/footer.php');?>
    </body>
</html>