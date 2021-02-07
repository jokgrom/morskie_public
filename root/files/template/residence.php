<section class="product-box" id="product<?php echo $cell["residenceId"];?>">
    <div class="product">
        <div class="cell main-box">
            <p><a href="/residence/<?php echo $cell["residenceId"];?>" class="product-name" target="_blank"><?php echo $cell["residenceTitle"];?></a></p>
            <p><a class="product-address"><?php echo $cell["cityTitle"].', '.$cell['suburbTitle']; ?></a></p>
            <ul class="product-description">
                <?php echo $convenienceContent;?>
            </ul>
            <div class="fotorama"  data-nav="thumbs" data-navposition="top" data-transition="crossfade" data-loop="true"  data-maxheight="400" data-width="80%" data-thumbwidth="50px" data-thumbheight="50px">
                 <?php echo $photoContent;?>
            </div>
        </div>
        <div class="cell right-box">
            <ul class="cell">
                <li>Тип жилья</li>
                <li><?php echo $cell["type_housingTitle"];?></li>
            </ul>
            <ul class="cell">
                <li>Вместительность</li>
                <li><?php echo $cell["guestTitle"];?></li>
            </ul>
            <ul class="cell">
                <li>До моря </li>
                <li><?php echo $cell["distanceTitle"]?></li>
            </ul>
            <ul class="cell">
                <li>Цены за номер</li>
                <li><?php print_r($priceContent);?></li>
            </ul>
            <ul class="cell">
                <li>Владелец объявления</li>
                <li><?php echo $cell["ad_ownerTitle"];?></li>
            </ul>
            <p><a href="/residence/<?php echo $cell["residenceId"];?>" target="_blank">Посмотреть описание</a></p>
        </div>
    </div>
</section>