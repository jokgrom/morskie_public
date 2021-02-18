<?php
    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/db.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/CleanForm.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/Entertainment.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/TemplateProduct.php');

    $TemplateProduct=new TemplateProduct($db);
    $CleanForm=new CleanForm();
    $Entertainment = new Entertainment($db);
?>
<!DOCTYPE html>
<html lang="ru">
    <head><?php require_once($_SERVER['DOCUMENT_ROOT'].'/root/blocks/head.php');?></head>
    <script src="/root/js/cleanFormProduct.js<?php echo $updateUrlHash; ?>"></script>
    <script src="/root/js/entertainment_find.js<?php echo $updateUrlHash; ?>"></script>
    <script src="https://maps.api.2gis.ru/2.0/loader.js?pkg=full"></script>
    <body class="main page-entertainment">
    <div class="page">
        <div class="wrap">
            <?php include_once($_SERVER['DOCUMENT_ROOT'].'/root/blocks/header.php');?>
            <div class="container">
                <div class="content">
                    <aside class="left-bar">
                        <p>Развлечения</p>
                        <?php echo $TemplateProduct->getListEntertainment(); ?>
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
                        <div class="mapImgSearch"></div>
                        <div id="product-box"><?php  $Entertainment->getAll();?></div>
                    </main>
                </div>
            </div>
        </div>
    </div>
    <?php include_once($_SERVER['DOCUMENT_ROOT'].'/root/blocks/footer.php');?>
    </body>
</html>