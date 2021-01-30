<?php
    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/db.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/Checks.php');

    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/CleanForm.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/CleanFormPerson.php');

    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/MyResidence.php');


    $CleanFormPerson=new CleanFormPerson;
    $Checks=new Checks($db);
    $MyResidence = new MyResidence($db);
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
?>
<!DOCTYPE html>
<html lang="ru">
<head><?php require_once($_SERVER['DOCUMENT_ROOT'].'/root/blocks/head.php');?></head>
<script>
		$(function(){
		    const formMain=$('.main');

            formMain.on( "click", ".publish", function() {
				var productId= $(this).attr('productId');
				$.get('app/publish.php',  {productId: productId}, function(data) {
                    $('#publish'+productId).html(data);
				});
			});

            formMain.on( "click", ".deleteProduct", function() {
				var productId= $(this).attr('productId');
				$.get('app/deleteProduct.php',  {productId: productId}, function(data) {
					$('#getApp').html(data);
				});
				$('#product'+productId).remove();
			});

		})

</script>
<body>
	<div class="page page-publish myResidence">
		<div class="wrap">
			<?php include_once($_SERVER['DOCUMENT_ROOT'].'/root/blocks/header.php');?>
			<div class="container">
				<div class="content">
					<?php include_once($_SERVER['DOCUMENT_ROOT'].'/root/blocks/leftBar/cabinetLeftBar.php');?>
					<main class="main">
						<h1 class="title">Опубликованные объявления</h1>
						<section class="section">
							<p class="small">Размещайте объявления о посуточной сдаче жилья без посредников в нашем сервисе абсолютно бесплатно. Мы не берём плату за размещения или продления объявлений. Все клиенты связываются с вами на прямую. <a href="/cabinet/residence/add/step1.php">Разместить объявление</a>
                                <br>
                            Объявления публикуется только с фотографиями.</p>
						</section>
						<?php  $MyResidence->getProduct($personId, 2); ?>
					</main>	
				</div>
			</div>
		</div>
	</div>
	<?php include_once($_SERVER['DOCUMENT_ROOT'].'/root/blocks/footer.php');?>
</body>
</html>