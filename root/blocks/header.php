<?php
    if($_COOKIE['person_id']!=''){
        $url_navigation='/cabinet/residence/add/step1.php';
        $url_navigation2='/cabinet/residence/';
    }else{
        $url_navigation='/cabinet/registration.php';
        $url_navigation2='/cabinet/authorization.php';
    }
?>
<header class="header">
	<p class="version">Betta версия <?php echo $BettaV;?></p>
	<div class="logo">
		<p>Отдых и жильё на море без посредников!</p>
		<!-- <p>Морские пути</p> -->
	</div>
	<nav>
		<ul class="navigation">
			<li class="cell"><a href="/residences/">Жильё</a></li>
			<li class="cell"><a href="/contacts/">Контакты</a></li>
			<li class="cell"><a href="<?php echo $url_navigation; ?>">Разместить объявление</a></li>
			<li class="cell"><a href="<?php echo $url_navigation2; ?>">Личный кабинет</a></li>
		</ul>
	</nav>
</header>