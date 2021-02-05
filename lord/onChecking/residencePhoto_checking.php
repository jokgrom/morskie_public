<?php
    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/db.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/CleanForm.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/Checks.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/LordResidence.php');

    $Checks = new Checks($db);
    $Checks->authenticationLord();

    $CleanForm=new CleanForm();
    $product["page"]=$CleanForm->number($_GET["page"]);
    $LordResidence = new LordResidence($db);
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <?php require_once($_SERVER['DOCUMENT_ROOT'].'/root/blocks/head.php');?>
    <meta name="robots" content="noindex, nofollow"/>
</head>
<script src="/root/js/cleanFormPerson.js<?php echo $updateUrlHash; ?>"></script>
<body>
<script>
    $(function(){
        $('.approve').on("click",function(){
            var photoId=$(this).prop('id');
            photoId=Number(photoId.replace(/\D+/g,""));
            $.get('app/approve_onePhoto.php',  {photoId}, function(data) {
                $('#getApp').html(data);
            });
            $('#photo-box'+photoId).remove();
        });

        $('.refusing').on("click",function(){
            var photoId=$(this).prop('id');
            photoId=Number(photoId.replace(/\D+/g,""));
            $.get('app/refusing_onePhoto.php',  {photoId}, function(data) {
                $('#getApp').html(data);
            });
            $('#photo-box'+photoId).remove();
        });
    });
</script>
<div class="page page-lord">
    <div class="wrap">
        <?php include_once($_SERVER['DOCUMENT_ROOT'].'/root/blocks/header_lord.php');?>
        <div class="container">
            <div class="content">
                <div class="left-bar">
                    <ul>
                        <li><a href="/lord/onChecking/residenceProduct_checking.php">Объявления</a></li>
                        <li><a href="/lord/onChecking/residencePhoto_checking.php">Фотографии</a></li>
                    </ul>
                </div>
                <main class="main">
                    <h1 class="title">Модерация фотографий</h1>
                    <section>
                        <div class="photo-block"><?php  $LordResidence->getPhoto_onChecking();?></div>
                    </section>
                </main>
            </div>
        </div>
    </div>
</div>
<?php include_once($_SERVER['DOCUMENT_ROOT'].'/root/blocks/footer.php');?>
</body>
</html>