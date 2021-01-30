<?php
    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/db.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/TemplateProduct.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/Checks.php');

    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/CleanForm.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/CleanFormPerson.php');

    $TemplateProduct=new TemplateProduct($db);
    $CleanFormPerson=new CleanFormPerson;
    $Checks=new Checks($db);
    $statusError=[];

    //проверка аутентификации
    list  ($personId, $boolError, $textError)=$CleanFormPerson->personId($_COOKIE['person_id']);
    if($boolError){array_push($statusError, $textError);}

    list  ($personIdentification, $boolError, $textError)=$CleanFormPerson->personIdentification($_COOKIE['person_identification']);
    if($boolError){array_push($statusError, $textError);}
    if(!count($statusError)){
        list  ($boolError, $textError)=$Checks->authenticationPerson($personId, $personIdentification);
        if($boolError){array_push($statusError, $textError);}
    }
    unset($personIdentification);
    if(count($statusError)){
        exit('<div class="modal"><p>Ошибка Аутентификации!</p></div>
            <script  type="text/javascript">
             setTimeout(function(){
                window.location.href = "/cabinet/authorization.php";
            }, 500);
            </script>');
    }

    //ограничение по колличеству
    $countProduct=$Checks->countResidence($personId);
    if($countProduct>=5){
        exit('<div class="modal"><p>Превышен лимит. Не более 5ти объявлений!</p></div>
            <script  type="text/javascript">
         setTimeout(function(){
            window.location.href = "/cabinet/residence/";
        }, 500);
        </script>');
    }


?>
<!DOCTYPE html>
<html lang="ru">
<head><?php require_once($_SERVER['DOCUMENT_ROOT'].'/root/blocks/head.php');?></head>
<script src="/root/js/cleanFormProduct.js<?php echo $updateUrlHash; ?>"></script>
<script src="/root/js/product_add.js<?php echo $updateUrlHash; ?>"></script>
<script src="https://maps.api.2gis.ru/2.0/loader.js?pkg=full"></script>

<body>
	<div class="page page-addResidence">
		<div class="wrap">
			<?php include_once($_SERVER['DOCUMENT_ROOT'].'/root/blocks/header.php');?>
			<div class="container">
				<div class="content">
					<?php include_once($_SERVER['DOCUMENT_ROOT'].'/root/blocks/leftBar/residenceLeftBar.php');?>
					<main class="main">
						<h1 class="title">Шаг 1/2. Размещение бесплатного объявления</h1>
						<section class="section">
							<p class="small">Объявления размещаются бесплатно. Если у вас несколько номеров, то разместите отдельно каждое объявление к каждому номеру.</p>
                            <p class="small">Если вдруг возникли трудности, <a href="/contacts/">свяжитесь с нами</a>. Мы разместим объявление за вас!</p>
						</section>

                        <section class="section">
                            <p class="strong"><label for="product-title">Название объявления</label></p>
                            <?php echo $TemplateProduct->title(); ?>
                        </section>

						<section class="section characteristic" id="characteristic">
							<p class="strong">Характеристики номера</p>
                            <?php echo $TemplateProduct->city(); ?>
                            <?php echo $TemplateProduct->suburb(); ?>
                            <?php echo $TemplateProduct->guest(); ?>
                            <?php echo $TemplateProduct->typeHousing(); ?>
                            <?php echo $TemplateProduct->distance(); ?>
                            <?php echo $TemplateProduct->adOwner(); ?>
						</section>

						<section class="section convenience" id="conveniences">
                            <?php echo $TemplateProduct->conveniences(); ?>
						</section>

						<section class="section" id="rules">
							<p class="strong"><label for="product-rules">Правила и ограничения</label></p>
							<p>Например: отъезд, приезд, дети с 3х лет, залоговая сумма, паспорт при регистрации, бронирование за 1 день и т.д.</p>
                            <?php echo $TemplateProduct->rules(); ?>
						</section>

						<section class="section" id="description">
							<p class="strong"><label for="product-description">Описание</label></p>
							<p>Расскажите например приемущества вашего номера, развлечения по близости, все удобства, когда можно с вами связаться, и любую другую информацию на ваше усмотрение.</p>
                            <?php echo $TemplateProduct->description(); ?>
						</section>

						<section class="section" id="address">
							<p class="strong"><label for="product-address"></label>Адрес</p>
							<p><?php echo $TemplateProduct->address(); ?></p>
                            <p><?php echo $TemplateProduct->map(); ?></p>
						</section>

						<section class="section" id="contacts">
							<p class="strong"><label for="product-contacts"></label>Контакты</p>
							<p>Можете указать: тел.номер, mail, социальные сети, личный сайт</p>
                            <?php echo $TemplateProduct->contacts(); ?>
						</section>

						<section class="section" id="price">
							<p class="strong">Цена за номер в сутки</p>
							<p>Укажите цены на каждый месяц за 1сутки. Если в тот или иной месяц вы не готовы принять гостей, то укажите 0 или не заполняйте поля.</p>
                            <?php echo $TemplateProduct->price(); ?>
						</section>

						<section>
							<p><input id="GO" class="form-button" type="button" value="сохранить и продолжить"></p>
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