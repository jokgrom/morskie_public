<!DOCTYPE html>
<html lang="ru">
<head><?php require_once($_SERVER['DOCUMENT_ROOT'].'/root/blocks/head.php');?></head>
<script src="/root/js/cleanFormPerson.js<?php echo $updateUrlHash; ?>"></script>
<script src="/root/js/registration.js<?php echo $updateUrlHash; ?>"></script>
<body>
	<div class="page page-registration">
		<div class="wrap">
			<?php include_once($_SERVER['DOCUMENT_ROOT'].'/root/blocks/header.php');?>
			<div class="container">
				<div class="content">
					<?php include_once($_SERVER['DOCUMENT_ROOT'].'/root/blocks/leftBar/registrationLeftBar.php');?>
					<main class="main">
						<h1 class="title">Регистрация</h1>
                        <p>После регистрации вы сможете размещать объявления о сдачи жилья на побережье "Черного моря" бесплатно. Никаких комиссионных, продление объявления бесплатно, публикация минимум на 1 год! Поднятие в ТОП бесплатно!</p>
						<section class="section">
							<ul>
								<li><label for="person-phone">телефон</label><input  id="person-phone" class="form-person" type="text" placeholder="телефон"></li>
								<li><label for="person-password">пароль</label><input id="person-password" class="form-person" type="text" placeholder="пароль" autocomplete="off"></li>
								<li><label for="person-password2">пароль</label><input id="person-password2" class="form-person" type="text" placeholder="пароль ещё раз" autocomplete="off"></li>
								<li><label><a href="Personal_data_processing_policy.pdf" target="_blank">Регистрируясь, вы тем самым даёте созласие на обработку персональных данных</a></label></li>
                                <li><input id="registration" class="form-button" type="button" value="Зарегистрироваться"></li>
								<li><div class="info-text"></div></li>
							</ul>
						</section>
					</main>	
				</div>
			</div>
		</div>
	</div>
	<?php include_once($_SERVER['DOCUMENT_ROOT'].'/root/blocks/footer.php');?>
</body>
</html>