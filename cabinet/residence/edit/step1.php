<?php
    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/db.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/Checks.php');

    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/CleanForm.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/CleanFormPerson.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/CleanFormProduct.php');

    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/MyResidence.php');

    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/TemplateProduct.php');

    $CleanForm=new CleanForm;
    $CleanFormPerson=new CleanFormPerson;
    $CleanFormProduct=new CleanFormProduct;
    $Checks=new Checks($db);
    $TemplateProduct=new TemplateProduct($db);
    $MyResidence = new MyResidence($db);

    $statusError=$product=[];

    //проверка аутентификации
    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/template/authenticationPerson.php');

    //ищем ошибки в идентификаторе продукта
    list  ($productId, $boolError, $textError)=$CleanFormProduct->id($_GET['id']);
    if($boolError){array_push($statusError, $textError);}

    //его ли это объявление
    list  ($boolError, $textError)=$Checks->lastResidenceId($personId, $productId);
    if($boolError){array_push($statusError, $textError);}


    $UrlAdminStatus=$CleanForm->number($_GET['s']);

    if(!count($statusError)){
        $MyResidence->getInfo($personId, $productId, $UrlAdminStatus);
    }else{
        $statusError=implode(', ', $statusError);
        exit("<div class='modal'><p>Ошибка: '.$statusError.'!</p></div>
                <script  type='text/javascript'>
                    setTimeout(function(){
                        window.location.href = '/cabinet/residence/';
                    }, 500);
                 </script>");
    }

?>
<!DOCTYPE html>
<html lang="ru">
<head><?php require_once($_SERVER['DOCUMENT_ROOT'].'/root/blocks/head.php');?></head>
<script src="/root/js/cleanFormProduct.js<?php echo $updateUrlHash; ?>"></script>
<script src="/root/js/product_edit.js<?php echo $updateUrlHash; ?>"></script>
<script src="https://maps.api.2gis.ru/2.0/loader.js?pkg=full"></script>
<body>
	<div class="page page-editResidence">
		<div class="wrap">
			<?php include_once($_SERVER['DOCUMENT_ROOT'].'/root/blocks/header.php');?>
			<div class="container">
				<div class="content">
					<?php include_once($_SERVER['DOCUMENT_ROOT'].'/root/blocks/leftBar/residenceLeftBar.php');?>
					<main class="main">
						<h1 class="title">Шаг 1/2. Редактирование объявления</h1>
                        <section class="section">
                            <p class="strong"><label for="product-title">Название объявления</label></p>
                            <?php echo $TemplateProduct->title($MyResidence->title); ?>
                        </section>

                        <section class="section characteristic" id="characteristic">
                            <p class="strong">Характеристики номера</p>
                            <?php echo $TemplateProduct->city($MyResidence->city_id); ?>
                            <?php echo $TemplateProduct->suburb($MyResidence->city_id, $MyResidence->suburb_id); ?>
                            <?php echo $TemplateProduct->guest($MyResidence->guest_id); ?>
                            <?php echo $TemplateProduct->typeHousing($MyResidence->typeHousing_id); ?>
                            <?php echo $TemplateProduct->distance($MyResidence->distance_id); ?>
                            <?php echo $TemplateProduct->adOwner($MyResidence->adOwner_id); ?>
                        </section>

                        <section class="section convenience" id="conveniences">
                            <?php echo $TemplateProduct->conveniences($MyResidence->conveniences); ?>
                        </section>

                        <section class="section" id="rules">
                            <p class="strong"><label for="product-rules">Правила и ограничения</label></p>
                            <p>Например: отъезд, приезд, дети с 3х лет, залоговая сумма, паспорт при регистрации, бронирование за 1 день и т.д.</p>
                            <?php echo $TemplateProduct->rules(str_replace('<br />', '', $MyResidence->rules));?>
                        </section>

                        <section class="section" id="description">
                            <p class="strong"><label for="product-description">Описание</label></p>
                            <p>Расскажите например приемущества вашего номера, развлечения по близости, все удобства, когда можно с вами связаться, и любую другую информацию на ваше усмотрение.</p>
                            <?php echo $TemplateProduct->description(str_replace('<br />', '', $MyResidence->description));?>
                        </section>

                        <section class="section" id="address">
                            <p class="strong"><label for="product-address"></label>Адрес</p>
                            <p><?php echo $TemplateProduct->address($MyResidence->address, $MyResidence->addressLatitude, $MyResidence->addressLongitude); ?></p>
                            <p><?php echo $TemplateProduct->map($MyResidence->address, $MyResidence->addressLatitude, $MyResidence->addressLongitude); ?></p>
                        </section>

                        <section class="section" id="contacts">
                            <p class="strong"><label for="product-contacts"></label>Контакты</p>
                            <p>Можете указать: тел.номер, mail, социальные сети, личный сайт</p>
                            <?php echo $TemplateProduct->contacts(str_replace('<br />', '', $MyResidence->contacts));?>
                        </section>

                        <section class="section" id="price">
                            <p class="strong">Цена за номер в сутки</p>
                            <p>Укажите цены на каждый месяц за 1сутки. Если в тот или иной месяц вы не готовы принять гостей, то укажите 0 или не заполняйте поля.</p>
                            <?php echo $TemplateProduct->price($MyResidence->prices); ?>
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