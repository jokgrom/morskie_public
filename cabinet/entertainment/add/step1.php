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
    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/template/authenticationPerson.php');

    //ограничение по колличеству
    $countProduct=$Checks->countEntertainment($personId);
    if($countProduct>=5){
        exit('<div class="modal"><p>Превышен лимит. Не более 5ти объявлений!</p></div>
            <script  type="text/javascript">
         setTimeout(function(){
            window.location.href = "/cabinet/entertainment/";
        }, 500);
        </script>');
    }


?>
<!DOCTYPE html>
<html lang="ru">
<head><?php require_once($_SERVER['DOCUMENT_ROOT'].'/root/blocks/head.php');?></head>
<script src="/root/js/cleanFormProduct.js<?php echo $updateUrlHash; ?>"></script>
<script src="/root/js/productEntertainment_add.js<?php echo $updateUrlHash; ?>"></script>
<script src="https://maps.api.2gis.ru/2.0/loader.js?pkg=full"></script>
<script src="//code-ya.jivosite.com/widget/xrXHAvphaN" async></script>
<body>
	<div class="page page-addEntertainment">
		<div class="wrap">
			<?php include_once($_SERVER['DOCUMENT_ROOT'].'/root/blocks/header.php');?>
			<div class="container">
				<div class="content">
					<?php include_once($_SERVER['DOCUMENT_ROOT'].'/root/blocks/leftBar/entertainmentLeftBar.php');?>
					<main class="main">
						<h1 class="title">Шаг 1/2. Размещение объявления | Развлечения</h1>
						<section class="section">
							<p class="small">Объявления размещаются бесплатно. Если у вас несколько развлечений, то разместите отдельно каждое объявление к каждому развлечению.</p>
                            <p class="small">Если вдруг возникли трудности, <a href="/contacts/">свяжитесь с нами</a>. Мы разместим объявление за вас!</p>
						</section>

                        <section class="section">
                            <p class="strong"><label for="product-title">Название объявления</label></p>
                            <?php echo $TemplateProduct->title(); ?>
                        </section>

						<section class="section characteristic" id="characteristic">
							<p class="strong">Характеристики</p>
                            <?php echo $TemplateProduct->city(); ?>
                            <?php echo $TemplateProduct->suburb(); ?>
						</section>

						<section class="section" id="listEntertainment">
                            <?php echo $TemplateProduct->getListEntertainment(); ?>
						</section>

						<section class="section" id="description">
							<p class="strong"><label for="product-description">Описание</label></p>
                            <?php echo $TemplateProduct->description(); ?>
						</section>

                        <section class="section" id="price">
                            <p class="strong">Цена</p>
                            <?php echo $TemplateProduct->priceEntertainment(); ?>
                        </section>

						<section class="section" id="address">
							<p class="strong"><label for="product-address"></label>Адрес оказания услуг или место встречи</p>
							<p><?php echo $TemplateProduct->address(); ?></p>
                            <p><?php echo $TemplateProduct->map(); ?></p>
						</section>

						<section class="section" id="contacts">
							<p class="strong"><label for="product-contacts"></label>Контакты</p>
							<p>Можете указать: тел.номер, mail, социальные сети, личный сайт</p>
                            <?php echo $TemplateProduct->contacts(); ?>
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