<?php

include_once('root/files/class/db.php');
include_once('root/files/class/TemplateProduct.php');

include_once('root/files/class/Product.php');
include_once('root/files/class/Residence.php');

$Residence = new Residence($db);
$TemplateProduct=new TemplateProduct($db);
?>
<!DOCTYPE html>
<html lang="ru">
<head><?php include('root/blocks/head.php');?></head>
<script>
    setTimeout(function(){
        window.location.href = "/residences/";
    }, 30000);
</script>
<body>
<div class="page page-main">
    <div class="wrap">
        <?php include('root/blocks/header.php');?>
        <div class="container">
            <div class="content">
                <main class="main">
                    <h1 class="title">Отдых на морских курортах по доступным ценам!</h1>
                    <p>Поможем выбрать лучшее жилье для комфортного отдыха. Посуточная аренда жилья в Анапе, Геленджике, Новороссийске, Сочи, Темрюке и Туапсе.</p>
                     <section class="suburb-list">
                        <div class="cell">
                            <p class="strong"><a href="/residences/?city=1">Анапа</a></p>
                            <?php echo $TemplateProduct->suburb_list(1); ?>
                        </div>
                        <div class="cell">
                            <p class="strong"><a href="/residences/?city=2">Геленджик</a></p>
                            <?php echo $TemplateProduct->suburb_list(2); ?>
                        </div>
                        <div class="cell">
                            <p class="strong"><a href="/residences/?city=2">Новороссийск</a></p>
                            <?php echo $TemplateProduct->suburb_list(3); ?>
                        </div>
                        <div class="cell">
                            <p class="strong"><a href="/residences/?city=2">Сочи</a></p>
                            <?php echo $TemplateProduct->suburb_list(4); ?>
                        </div>
                        <div class="cell">
                            <p class="strong"><a href="/residences/?city=2">Темрюк</a></p>
                            <?php echo $TemplateProduct->suburb_list(5); ?>
                        </div>
                        <div class="cell">
                            <p class="strong"><a href="/residences/?city=2">Туапсе</a></p>
                            <?php echo $TemplateProduct->suburb_list(6); ?>
                        </div>
                    </section>
                    <p>В объявлениях вы можете получить контактные данные владельцев объявлений и
                        связаться напрямую для согласования всех вопросов, касающихся бронирования, предоплаты и нюансов отдыха.
                        Сайт morskie-puti.ru не выполняет функций посредника и не получает комиссионные от объектов размещения.
                        </p>
                    <br>
                    <p>Мы делаем всё, чтобы вы могли с лёгкостью найти для себя комфортный вариант проживания.
                        Ежедневно добавляются новые объявления, улучшается и расширяется сервис.
                        Вы полюбите наш сервис! <a href="/contacts/">Мы всегда на связи и готовы прийти на помощь!</a></p>
                    <br>
                    <p class="strong" style="text-align:center">Запомните! Отдых начинается здесь и сейчас!</p>
                </main>
            </div>
        </div>
    </div>
</div>
<?php include('root/blocks/footer.php');?>
</body>
</html>