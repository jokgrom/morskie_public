<?php
    if($_COOKIE['person_id']!=''){
        $url_navigation='/cabinet/residence/add/step1.php';
        $url_navigation2='/cabinet/residence/';
        $url_navigation3='/cabinet/entertainment/add/step1.php';
    }else{
        $url_navigation='/cabinet/registration.php';
        $url_navigation2='/cabinet/authorization.php';
        $url_navigation3=$url_navigation;
    }
?>
<header class="header">
	<div class="added">
        <p><a href="<?php echo $url_navigation; ?>">Разместить жилье </a></p>
        <p><a href="<?php echo $url_navigation3; ?>">Разместить развлечения </a></p>
    </div>
	<div class="welcome"><p>Отдых и жильё на море без посредников!</p></div>
	<nav>
		<ul class="navigation">
            <li class="cell"><a href="/residences/">Жильё</a></li>
            <li class="cell"><a href="/entertainments/">Развлечения</a></li>
			<li class="cell"><a href="/contacts/">Контакты</a></li>
			<li class="cell"><a href="<?php echo $url_navigation2; ?>">Личный кабинет</a></li>
		</ul>
	</nav>
</header>