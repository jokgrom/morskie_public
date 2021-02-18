<?php
    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/db.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/Checks.php');
    $Checks=new Checks($db);
    $Checks->authenticationLord();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <?php require_once($_SERVER['DOCUMENT_ROOT'].'/root/blocks/head.php');?>
    <meta name="robots" content="noindex, nofollow"/>
</head>
<body>
<div class="page">
    <div class="wrap">
        <?php include_once($_SERVER['DOCUMENT_ROOT'].'/root/blocks/header_lord.php');?>
        <div class="container">
            <div class="content">
                <div class="left-bar">
                    <ul>
                        <li><a href="/lord/residence/onChecking/residenceProduct_checking.php">Жильё</a></li>
                        <li><a href="/lord/entertainment/onChecking/entertainmentProduct_checking.php">Развлечения</a></li>
                    </ul>
                </div>
                <main class="main">
                    <h1 class="title">Информация</h1>
                    <p>В разработке</p>
                </main>
            </div>
        </div>
    </div>
</div>
<?php include_once($_SERVER['DOCUMENT_ROOT'].'/root/blocks/footer.php');?>
</body>
</html>