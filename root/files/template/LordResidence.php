<section class="product-box" id="product<?php echo $cell["residenceId"];?>">
    <p style="margin:0 0 0 1%;">№:<?php echo $cell["residenceId"];?></p>
    <div class="product">
        <div class="cell main-box">
            <?php echo "<p><a href='/residence/$cell[residenceId]' class='product-name $colorError[residenceTitle]' target='_blank'>$cell[residenceTitle]</a></p>"; ?>
            <p class="product-address <?php echo $colorError['cityTitle'].' '.$colorError['suburbTitle'];?>"><?php echo $cell["cityTitle"].', '.$cell['suburbTitle']; ?></p>
            <ul class="product-description <?php echo $colorError['residenceConveniences'];?>">
                <?php echo $convenienceContent;?>
            </ul>
            <div class="fotorama"  data-nav="thumbs" data-navposition="top" data-transition="crossfade" data-loop="true"  data-maxheight="400" data-width="80%" data-thumbwidth="50px" data-thumbheight="50px">
                 <?php echo $photoContent;?>
            </div>
        </div>
        <div class="cell right-box">
            <ul class="cell">
                <li>Тип жилья</li>
                 <?php echo "<li class='$colorError[type_housingTitle]'>$cell[type_housingTitle]</li>";?>
            </ul>
            <ul class="cell">
                <li>Вместительность</li>
                <?php echo "<li class='$colorError[guestTitle]'>$cell[guestTitle]</li>";?>
            </ul>
            <ul class="cell  <?php echo $colorError[distanceTitle]; ?>">
                <li>До моря </li>
                <?php echo "<li class='$colorError[distanceTitle]'>$cell[distanceTitle]</li>";?>
            </ul>
            <ul class="cell <?php echo $colorError[residencePrices]; ?>">
                <li>Цены за номер</li>
                <li><?php print_r($priceContent);?></li>
            </ul>
            <ul class="cell">
                <li>Разместил</li>
                <?php echo "<li class='$colorError[ad_ownerTitle]'>$cell[ad_ownerTitle]</li>";?>
            </ul>
            <?php
                $mapImgUrl="https://static-maps.yandex.ru/1.x/?ll=$cell[residenceAddressLongitude],$cell[residenceAddressLatitude]&size=200,200&z=13&l=map&pt=$cell[residenceAddressLongitude],$cell[residenceAddressLatitude],pm2rdm";
            ?>
            <div class="mapImg" style="background-image: url(<?php echo $mapImgUrl;?>)"></div>
        </div>
    </div>
    <div class="rules">
        <p>Правила:</p>
        <?php echo "<p class='$colorError[residenceRules]'>$cell[residenceRules]</p>" ?>
    </div>
    <div class="description">
        <p>Описание:</p>
        <?php echo "<p class='$colorError[residenceDescription]'>$cell[residenceDescription]</p>" ?>
    </div>
    <div class="contacts">
        <p>Контакты:</p>
        <?php echo "<p class='$colorError[residenceContacts]'>$cell[residenceContacts]</p>" ?>
    </div>
    <p class="control-line-product">
        <span><a class="approveAll color7" id="approveAll<?php echo $cell["residenceId"];?>">[<span class="color7">объявление</span>|<span class="color7">фото</span>]</a></span>
        <span><a class="approveProduct" id="approveProduct<?php echo $cell["residenceId"];?>">[<span class="color7">объявление</span>]</a></span>
        <span><a class="refusingProduct color2" id="refusingProduct<?php echo $cell["residenceId"];?>">[<span class="color4 strong">объявление</span>]</a></span>
    </p>
    <div class="control-infoError"><textarea name="" id="infoError<?php echo $cell["residenceId"];?>" class="form-textarea" cols="30" rows="10"></textarea></div>
</section>
