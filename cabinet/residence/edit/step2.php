<?php
    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/db.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/Checks.php');

    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/CleanForm.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/CleanFormPerson.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/CleanFormProduct.php');

    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/Product.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/Residence.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/TemplateProduct.php');
	$productId=$_GET['id'];

    $CleanForm=new CleanForm;
    $CleanFormPerson=new CleanFormPerson;
    $CleanFormProduct=new CleanFormProduct;
    $Checks=new Checks($db);
    $Residence = new Residence($db);
    $TemplateProduct=new TemplateProduct($db);

    $statusError=[];
    //проверка аутентификации
    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/template/authenticationPerson.php');

    //ищем ошибки в идентификаторе продукта
    list  ($productId, $boolError, $textError)=$CleanFormProduct->id($_GET['id']);
    if($boolError){array_push($statusError, $textError);}

    //его ли это объявление
    list  ($boolError, $textError)=$Checks->lastResidenceId($personId, $productId);
    if($boolError){array_push($statusError, $textError);}

    $UrlAdminStatus=$CleanForm->number($_GET['s']);

    setcookie('lastEditResidenceId',$productId,time() + (3600*24*365),'/');//добавим ид последнего добавленного товара
?>
<!DOCTYPE html>
<html>
<head><?php require_once($_SERVER['DOCUMENT_ROOT'].'/root/blocks/head.php');?></head>
<script src="/root/js/jquery.form.min.js<?php echo $updateUrlHash; ?>"></script>
<script src="/root/js/product_edit2.js<?php echo $updateUrlHash; ?>"></script>
<script>

</script>
<body>
	<div class="page">
		<div class="wrap">
			<?php include_once($_SERVER['DOCUMENT_ROOT'].'/root/blocks/header.php');?>
			<div class="container">
				<div class="content">
					<aside class="left-bar left-bar-nomer"></aside>
					<main class="main">
						<h1 class="title">Редактирование фотографий</h1>
						<p>Объявление: <span class="color1 strong"><?php echo $Residence->title; ?></span></p>
						<p class="small-font">Ограничение: не более 15 фотографий, формат jpg, jpeg, png и не более 10мб</p>
                        <section class="section">
                            <form name="" method="post" action="#" enctype="multipart/form-data" >
                                <input type="file" name="photo[]"  id="button-selectFiles" multiple accept="image/jpeg, image/png" />
                            </form>
                        </section>
                        <section class="section">
                            <div id="box-img">
                                <?php echo $TemplateProduct->oldPhotoBox($personId, $productId, $UrlAdminStatus); ?>
                            </div>
                        </section>
                        <section>
                            <input id="GO" class="form-button" type="button" productId="<?php echo $productId; ?>" value="сохранить">
                            <div class="info-text"></div>
                        </section>
					</main>
				</div>
			</div>
		</div>
	</div>
	<?php include_once($_SERVER['DOCUMENT_ROOT'].'/root/blocks/footer.php');?>
	<script src="/root/js/drag_and_drop_photo.js"></script>
</body>
</html>