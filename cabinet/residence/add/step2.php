<?php
    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/db.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/Checks.php');

    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/CleanForm.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/CleanFormPerson.php');

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
    if($_COOKIE['lastResidenceId']==''){
        exit('<script  type="text/javascript">
             setTimeout(function(){
            window.location.href = "/cabinet/residence/";
        }, 500);
        </script>');
    }

?>
<!DOCTYPE html>
<html lang="ru">
<head><?php require_once($_SERVER['DOCUMENT_ROOT'].'/root/blocks/head.php');?></head>
<script src="/root/js/product_add2.js<?php echo $updateUrlHash; ?>"></script>
<body>
	<div class="page page-addResidence">
		<div class="wrap">
			<?php include_once($_SERVER['DOCUMENT_ROOT'].'/root/blocks/header.php');?>
			<div class="container">
				<div class="content">
					<aside class="left-bar left-bar-nomer"></aside>
					<main class="main">
						<h1 class="title">Шаг 2/2. Размещение бесплатного объявления</h1>
                        <p class="small">Объявление было создано, осталось добавить фотографии. Не более 15 фотографий и формата jpg, jpeg, png.</p>
						<section class="section">
                            <form name="" method="post" action="#" enctype="multipart/form-data" >
                                <input type="file" name="photo[]"  id="button-selectFiles" multiple accept="image/jpeg, image/png" />
                            </form>
						</section>
						<section class="section">
							<div id="box-img"></div>
						</section>
						<section>
							<input id="GO" class="form-button" type="button" value="сохранить">
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