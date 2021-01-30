<!DOCTYPE html>
<html lang="ru">
<head><?php require_once($_SERVER['DOCUMENT_ROOT'].'/root/blocks/head.php');?></head>
<script src="/root/js/cleanFormPerson.js<?php echo $updateUrlHash; ?>"></script>
<script src="/root/js/authorization.js<?php echo $updateUrlHash; ?>"></script>
<body>
	<div class="page page-authorization">
		<div class="wrap">
			<?php include_once($_SERVER['DOCUMENT_ROOT'].'/root/blocks/header.php');?>
			<div class="container">
				<div class="content">
					<?php include_once($_SERVER['DOCUMENT_ROOT'].'/root/blocks/leftBar/authorizationLeftBar.php');?>
					<main class="main">
						<h1 class="title">Авторизация</h1>
						<section class="section">
							<ul>
								<li><label for="person-phone">тел.номер</label><input id="person-phone" class="form-person" type="text" placeholder="телефон"></li>
								<li><label for="person-password">пароль</label><input id="person-password" class="form-person" type="text" placeholder="пароль"></li>
								<li><input id="authorization" class="form-button" type="button" value="Войти"> <a href="passwordReset.php">Забыли пароль?</a></li>
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