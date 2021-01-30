<?php
    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/db.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/Checks.php');

    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/CleanForm.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/CleanFormPerson.php');

    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/Person.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/Landlord.php');

    $CleanFormPerson=new CleanFormPerson;
    $Checks=new Checks($db);
    $Landlord=new Landlord($db);
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

    $Landlord->getInfo($personId);
?>
<!DOCTYPE html>
<html lang="ru">
<head><?php require_once($_SERVER['DOCUMENT_ROOT'].'/root/blocks/head.php');?></head>
<script src="/root/js/cleanFormPerson.js<?php echo $updateUrlHash; ?>"></script>
<script src="/root/js/profile.js<?php echo $updateUrlHash; ?>"></script>
<body>
	<div class="page page-profile myResidence">
		<div class="wrap">
			<?php include_once($_SERVER['DOCUMENT_ROOT'].'/root/blocks/header.php');?>
			<div class="container">
				<div class="content">
					<?php include_once($_SERVER['DOCUMENT_ROOT'].'/root/blocks/leftBar/cabinetLeftBar.php');?>
					<main class="main main-cabinet">
						<h1 class="title">Мой профиль</h1>
                        <div class="blocks">
    						<section class="section">
    							<ul>
    								<li><label for="person-name">имя</label><input id="person-name" class="form-person" type="text" placeholder="имя" value="<?php echo $Landlord->name;?>"></li>
    								<li><label for="person-phone">тел.номер</label><input id="person-phone" class="form-person" type="text" placeholder="тел.номер" value="<?php echo '+'.$Landlord->phone;?>"></li>
    								<li><label for="person-mail">почта</label><input id="person-mail" class="form-person" type="text" placeholder="mail" value="<?php echo $Landlord->mail;?>"></li>
                                    <li><input id="profile-edit" class="form-button" type="button" value="Изменить"></li>
                                </ul>
                                <br>
                                <ul>
                                    <li><label for="person-password">пароль</label><input id="person-password" class="form-person" type="text" placeholder="пароль"></li>
                                    <li><label for="person-password2">повторите пароль</label><input id="person-password2" class="form-person" type="text" placeholder="повторите пароль"></li>
                                    <li><input id="password-edit" class="form-button" type="button" value="Изменить пароль"></li>
                                    <li><div class="info-text"></div></li>
    							</ul>
    						</section>
                        </div>
					</main>
				</div>
			</div>
		</div>
	</div>
	<?php include_once($_SERVER['DOCUMENT_ROOT'].'/root/blocks/footer.php');?>
</body>
</html>