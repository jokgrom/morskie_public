<!DOCTYPE html>
<html lang="ru">
<head><?php require_once($_SERVER['DOCUMENT_ROOT'].'/root/blocks/head.php');?></head>
<script src="/root/js/cleanFormPerson.js<?php echo $updateUrlHash; ?>"></script>
<script src="/root/js/contacts.js<?php echo $updateUrlHash; ?>"></script>
<script src="//code-ya.jivosite.com/widget/xrXHAvphaN" async></script>
<body class="page-contacts">
	<div class="page page-contacts">
		<div class="wrap">
			<?php include_once($_SERVER['DOCUMENT_ROOT'].'/root/blocks/header.php');?>
			<div class="container">
				<div class="content">
					<?php include_once($_SERVER['DOCUMENT_ROOT'].'/root/blocks/leftBar/contactsLeftBar.php');?>
					<main class="main">
						<h1 class="title">Напишите нам письмо</h1>
                        <section class="section">
                            <ul>
                                <li>
                                    <label for="person-typeMessage">тип письма</label>
                                    <select id="person-typeMessage" class="form-person">
                                        <option class="strong" selected value="0">общий</option>
                                        <option value="1">рекомендация</option>
                                        <option value="2">жалоба</option>
                                        <option value="3">помощь по сайту</option>
                                        <option value="4">реклама</option>
                                        <option value="5">сотрудничество</option>
                                    </select>
                                </li>
                                <li><label for="person-message">письмо</label><textarea id="person-message" class="form-textarea"></textarea></li>
                                <li><input id ="GO" class="form-button" type="button" value="Написать письмо"></li>
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