<?php
    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/db.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/Checks.php');

    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/CleanForm.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/CleanFormPerson.php');

    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/MyResidence.php');


    $CleanFormPerson=new CleanFormPerson;
    $Checks = new Checks($db);
    $MyResidence = new MyResidence($db);
    $statusError=[];

    //проверка аутентификации
    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/template/authenticationPerson.php');
?>
<!DOCTYPE html>
<html lang="ru">
<head><?php require_once($_SERVER['DOCUMENT_ROOT'].'/root/blocks/head.php');?></head>
<script src="/root/js/controlProduct.js<?php echo $updateUrlHash; ?>"></script>
<body>
<div class="page page-noPublish myResidence">
    <div class="wrap">
        <?php include_once($_SERVER['DOCUMENT_ROOT'].'/root/blocks/header.php');?>
        <div class="container">
            <div class="content">
                <?php include_once($_SERVER['DOCUMENT_ROOT'].'/root/blocks/leftBar/cabinetLeftBar.php');?>
                <main class="main">
                    <h1 class="title">Жильё | Не опубликованные объявления</h1>
                    <?php  $MyResidence->getProduct($personId,1); ?>
                </main>
            </div>
        </div>
    </div>
</div>
<?php include_once($_SERVER['DOCUMENT_ROOT'].'/root/blocks/footer.php');?>
</body>
</html>