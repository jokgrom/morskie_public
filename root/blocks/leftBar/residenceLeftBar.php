<aside class="left-bar left-bar-nomer">
	<ul class="cell">
		<li><a href="#characteristic">характеристики</a></li>
		<li><a href="#convenience">удобства</a></li>
		<li><a href="#rules">правила и ограничения</a></li>
		<li><a href="#description">описание</a></li>
		<li><a href="#address">адрес</a></li>
		<li><a href="#contacts">контакты</a></li>
		<li><a href="#price">цены</a></li>
	</ul>
</aside>
<script>
	const leftBar=$(".left-bar .cell");
	$(window).scroll(function(){
		leftBar.css({"top":$(this).scrollTop()});
	});
</script>