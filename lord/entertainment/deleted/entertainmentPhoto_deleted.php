<?php
    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/db.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/CleanForm.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/Checks.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/LordEntertainment.php');

    $Checks = new Checks($db);
    $Checks->authenticationLord();

    $CleanForm=new CleanForm();
    $product["page"]=$CleanForm->number($_GET["page"]);
    $LordEntertainment = new LordEntertainment($db);
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <?php require_once($_SERVER['DOCUMENT_ROOT'].'/root/blocks/head.php');?>
    <meta name="robots" content="noindex, nofollow"/>
</head>
<body>
<div class="page page-lord">
    <div class="wrap">
        <?php include_once($_SERVER['DOCUMENT_ROOT'].'/root/blocks/header_lord.php');?>
        <div class="container">
            <div class="content">
                <div class="left-bar">
                    <?php include_once($_SERVER['DOCUMENT_ROOT'].'/root/blocks/leftBar/lordDeleted.php');?>
                </div>
                <main class="main">
                    <h1 class="title">Удалённые фотографии Развлечений</h1>
                    <section>
                        <div class="photo-block"><?php  $LordEntertainment->getPhoto_deleted();?></div>
                    </section>
                </main>
            </div>
        </div>
    </div>
</div>
<?php include_once($_SERVER['DOCUMENT_ROOT'].'/root/blocks/footer.php');?>
</body>
</html>