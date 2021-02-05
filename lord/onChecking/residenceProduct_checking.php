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
        $('.approveAll').on("click",function(){
            var productId=$(this).prop('id');
            productId=Number(productId.replace(/\D+/g,""));
            $.get('app/approveAll.php',  {productId}, function(data) {
                $('#getApp').html(data);
            });
            $('#product'+productId).remove();
        });

        $('.approveProduct').on("click",function(){
            var productId=$(this).prop('id');
            productId=Number(productId.replace(/\D+/g,""));
            $.get('app/approveProduct.php',  {productId}, function(data) {
                $('#getApp').html(data);
            });
            $('#product'+productId).remove();
        });

        $('.approvePhoto').on("click",function(){
            var productId=$(this).prop('id');
            productId=Number(productId.replace(/\D+/g,""));

            var infoError=check_Message($('#infoError'+productId).val());
            $('#infoError'+productId).val(infoError.message);
            if(infoError.boolError===false){
                infoError=infoError.message;
                $.get('app/approvePhoto.php',  {productId, infoError}, function(data) {
                    $('#getApp').html(data);
                });
                $('#product'+productId).remove();
            }else{
                $('#infoError'+productId).css('backgroundColor',  infoError.color);
            }
        });


        $('.refusingProduct').on("click",function(){
            var productId=$(this).prop('id');
            productId=Number(productId.replace(/\D+/g,""));

            var infoError=check_Message($('#infoError'+productId).val());
            $('#infoError'+productId).val(infoError.message);
            if(infoError.boolError===false){
                infoError=infoError.message;
                $.get('app/refusingProduct.php',  {productId, infoError}, function(data) {
                    $('#getApp').html(data);
                });
                $('#product'+productId).remove();
            }else{
                $('#infoError'+productId).css('backgroundColor',  infoError.color);
            }
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
                    <h1 class="title">Модерация объявлений</h1>
                    <section>
                        <div id="product-box"><?php  $LordResidence->getProduct_onChecking();?></div>
                    </section>
                </main>
            </div>
        </div>
    </div>
</div>
<?php include_once($_SERVER['DOCUMENT_ROOT'].'/root/blocks/footer.php');?>
</body>
</html>