<!DOCTYPE html>
<html lang="ru">
<head><?php require_once($_SERVER['DOCUMENT_ROOT'].'/root/blocks/head.php');?></head>
<script src="/root/js/cleanFormPerson.js<?php echo $updateUrlHash; ?>"></script>
<script src="/root/js/passwordReset.js<?php echo $updateUrlHash; ?>"></script>
<body>
	<div class="page page-passwordReset">
		<div class="wrap">
			<?php include_once($_SERVER['DOCUMENT_ROOT'].'/root/blocks/header.php');?>
			<div class="container">
				<div class="content">
					<?php include_once($_SERVER['DOCUMENT_ROOT'].'/root/blocks/leftBar/passwordResetLeftBar.php');?>
					<main class="main">
						<h1 class="title">Восстановление пароля</h1>
						<section class="section">
							<ul>
								<li><label for="person-mail">Почта</label><input id="person-mail" class="form-person" type="text" placeholder="email"></li>
								<li><input class="form-button" id="password-reset1" type="button" value="Восстановить"></li>
								<li><span class="info-text"></span></li>
							</ul>
						</section>
						<section>
                            <ul>
                                <li><label for="person-description">Номер телефона или мессенджер</label></li>
                                <li><textarea class="form-textarea" id="person-description"></textarea></li>
                                <li><input class="form-button" id="password-reset2" type="button" value="Восстановить"></li>
                                <li><span class="info-text2"></span></li>
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