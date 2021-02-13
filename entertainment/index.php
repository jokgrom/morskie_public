<?php

    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/db.php');

    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/CleanForm.php');

    //класс по развлечениям

    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/TemplateProduct.php');

    $TemplateProduct=new TemplateProduct($db);
    $CleanForm=new CleanForm();
    $
    $statusError=[];


    //ищём ошибки

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
                        <?php echo $TemplateProduct->getListEntertainment();?>
                    </aside>
                    <main id="main">
                        <aside class="top-bar">
                            <div class="cell"><?php echo $TemplateProduct->city(); ?></div>
                            <div class="cell"><?php echo $TemplateProduct->suburb(); ?></div>
                            <div class="cell"><?php echo $TemplateProduct->title(); ?></div>
                            <div class="cell">
                                <input id="GO" class="filter" type="button" value="найти">
                                <input class="filter reset" type="button" value="сбросить фильтр">
                            </div>
                        </aside>
                        <p><?php //echo $TemplateProduct->html_h1($product); ?></p>
                        <div class="mapImgSearch"><?php if($product["city"]!=0){echo $TemplateProduct->mapSearch($city_id);} ?></div>
                        <div id="product-box"><?php  //$Residence->getAll($product);?></div>
                    </main>
                </div>
            </div>
        </div>
    </div>
    <?php include_once($_SERVER['DOCUMENT_ROOT'].'/root/blocks/footer.php');?>
    </body>
</html>