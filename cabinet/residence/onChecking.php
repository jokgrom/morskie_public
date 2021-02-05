<?php
    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/db.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/Checks.php');

    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/CleanForm.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/CleanFormPerson.php');

    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/MyResidence.php');


    $CleanFormPerson=new CleanFormPerson;
    $Checks=new Checks($db);
    $MyResidence = new MyResidence($db);
    $statusError=[];

    //проверка аутентификации
    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/template/authenticationPerson.php');
?>
<!DOCTYPE html>
<html lang="ru">
<head><?php require_once($_SERVER['DOCUMENT_ROOT'].'/root/blocks/head.php');?></head>
<script>
    $(function(){
        const formMain=$('.main');

        formMain.on( "click", ".deleteProduct", function() {
            var productId= $(this).attr('productId');
            $.get('app/deleteProduct_edit.php',  {productId: productId}, function(data) {
                $('#getApp').html(data);
            });
            $('#product'+productId).remove();
        });

        formMain.on( "click", ".deletePhotos", function() {
            var productId= $(this).attr('productId');
            $.get('app/deletePhotos_onChecking.php',  {productId: productId}, function(data) {
                $('#getApp').html(data);
            });
            $('#product'+productId).remove();
        });

    })
</script>
<body>
<div class="page page-onChecking myResidence">
    <div class="wrap">
        <?php include_once($_SERVER['DOCUMENT_ROOT'].'/root/blocks/header.php');?>
        <div class="container">
            <div class="content">
                <?php include_once($_SERVER['DOCUMENT_ROOT'].'/root/blocks/leftBar/cabinetLeftBar.php');?>
                <main class="main">
                    <?php  $MyResidence->getProduct_onChecking($personId); ?>
                </main>
            </div>
        </div>
    </div>
</div>
<?php include_once($_SERVER['DOCUMENT_ROOT'].'/root/blocks/footer.php');?>
</body>
</html>