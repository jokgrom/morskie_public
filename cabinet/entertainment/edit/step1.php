<?php
    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/db.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/Checks.php');

    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/CleanForm.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/CleanFormPerson.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/CleanFormProduct.php');

    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/MyEntertainment.php');

    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/TemplateProduct.php');

    $CleanForm=new CleanForm;
    $CleanFormPerson=new CleanFormPerson;
    $CleanFormProduct=new CleanFormProduct;
    $Checks=new Checks($db);
    $TemplateProduct=new TemplateProduct($db);
    $MyEntertainment = new MyEntertainment($db);

    $statusError=$product=[];

    //проверка аутентификации
    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/template/authenticationPerson.php');

    //ищем ошибки в идентификаторе продукта
    list  ($productId, $boolError, $textError)=$CleanFormProduct->id($_GET['id']);
    if($boolError){array_push($statusError, $textError);}

    //его ли это объявление
    list  ($boolError, $textError)=$Checks->lastEntertainmentId($personId, $productId);
    if($boolError){array_push($statusError, $textError);}


    $UrlAdminStatus=$CleanForm->number($_GET['s']);

    if(!count($statusError)){
        $MyEntertainment->getInfo($personId, $productId, $UrlAdminStatus);
    }else{
        $statusError=implode(', ', $statusError);
        exit("<div class='modal'><p>Ошибка: '.$statusError.'!</p></div>
                <script  type='text/javascript'>
                    setTimeout(function(){
                        window.location.href = '/cabinet/entertainment/';
                    }, 500);
                 </script>");
    }

?>
<!DOCTYPE html>
<html lang="ru">
<head><?php require_once($_SERVER['DOCUMENT_ROOT'].'/root/blocks/head.php');?></head>
<script src="/root/js/cleanFormProduct.js<?php echo $updateUrlHash; ?>"></script>
<script src="/root/js/productEntertainment_edit.js<?php echo $updateUrlHash; ?>"></script>
<script src="https://maps.api.2gis.ru/2.0/loader.js?pkg=full"></script>
<body>
	<div class="page page-editEntertainment">
		<div class="wrap">
			<?php include_once($_SERVER['DOCUMENT_ROOT'].'/root/blocks/header.php');?>
			<div class="container">
				<div class="content">
					<?php include_once($_SERVER['DOCUMENT_ROOT'].'/root/blocks/leftBar/entertainmentLeftBar.php');?>
					<main class="main">
						<h1 class="title">Шаг 1/2. Редактирование объявления | Развлечения</h1>
                        <section class="section">
                            <p class="strong"><label for="product-title">Название объявления</label></p>
                            <?php echo $TemplateProduct->title($MyEntertainment->title); ?>
                        </section>

                        <section class="section characteristic" id="characteristic">
                            <p class="strong">Характеристики</p>
                            <?php echo $TemplateProduct->city($MyEntertainment->city_id); ?>
                            <?php echo $TemplateProduct->suburb($MyEntertainment->city_id,$MyEntertainment->suburb_id); ?>
                        </section>

                        <section class="section convenience" id="conveniences">
                            <?php echo $TemplateProduct->getListEntertainment($MyEntertainment->entertainment); ?>
                        </section>

                        <section class="section" id="description">
                            <p class="strong"><label for="product-description">Описание</label></p>
                            <?php echo $TemplateProduct->description(str_replace('<br />', '', $MyEntertainment->description)); ?>
                        </section>


                        <section class="section" id="price">
                            <p class="strong">Цена</p>
                            <?php echo $TemplateProduct->priceEntertainment(str_replace('<br />', '', $MyEntertainment->prices)); ?>
                        </section>

                        <section class="section" id="address">
                            <p class="strong"><label for="product-address"></label>Адрес</p>
                            <p><?php echo $TemplateProduct->address($MyEntertainment->address, $MyEntertainment->addressLatitude, $MyEntertainment->addressLongitude); ?></p>
                            <p><?php echo $TemplateProduct->map($MyEntertainment->address, $MyEntertainment->addressLatitude, $MyEntertainment->addressLongitude); ?></p>
                        </section>

                        <section class="section" id="contacts">
                            <p class="strong"><label for="product-contacts"></label>Контакты</p>
                            <p>Можете указать: тел.номер, mail, социальные сети, личный сайт</p>
                            <?php echo $TemplateProduct->contacts(str_replace('<br />', '', $MyEntertainment->contacts));?>
                        </section>

                        <section>
                            <p><input id="GO" class="form-button" type="button" productId="<?php echo $productId; ?>" value="сохранить изменения"></p>
                            <p><span class="info-text"></span></p>
                        </section>
					</main>
				</div>
			</div>
		</div>
	</div>
	<?php include_once($_SERVER['DOCUMENT_ROOT'].'/root/blocks/footer.php');?>
</body>
</html>