<section class="product-box" id="product<?php echo $cell["entertainmentId"];?>">
    <p style="margin:0 0 0 1%;">№:<?php echo $cell["entertainmentId"];?></p>
    <div class="product">
        <div class="cell main-box">
            <?php echo "<p><a href='/entertainment/$cell[entertainmentId]' class='product-name $colorError[entertainmentTitle]' target='_blank'>$cell[entertainmentTitle]</a></p>"; ?>
            <p class="product-address <?php echo $colorError['cityTitle'].' '.$colorError['suburbTitle'];?>"><?php echo $cell["cityTitle"].', '.$cell['suburbTitle']; ?></p>
            <div class="fotorama"  data-nav="thumbs" data-navposition="top" data-transition="crossfade" data-loop="true"  data-maxheight="400" data-width="80%" data-thumbwidth="50px" data-thumbheight="50px">
                <?php echo $photoContent;?>
            </div>
        </div>
        <div class="cell right-box">
            <?php echo "<p class='$colorError[entertainment_listTitle]'>$cell[entertainment_listTitle]</p>" ?>
            <?php $mapImgUrl="https://static-maps.yandex.ru/1.x/?ll=$cell[entertainmentAddressLongitude],$cell[entertainmentAddressLatitude]&size=200,200&z=13&l=map&pt=$cell[entertainmentAddressLongitude],$cell[entertainmentAddressLatitude],pm2rdm";  ?>
            <div class="mapImg" style="background-image: url(<?php echo $mapImgUrl;?>)"></div>
        </div>
    </div>

    <div class="description">
        <p>Описание:</p>
        <?php echo "<p class='$colorError[entertainmentDescription]'>$cell[entertainmentDescription]</p>" ?>
    </div>
    <div class="description">
        <p>Цена:</p>
        <?php echo "<p class='$colorError[entertainmentPrices]'>$cell[entertainmentPrices]</p>" ?>
    </div>
    <div class="contacts">
        <p>Контакты:</p>
        <?php echo "<p class='$colorError[entertainmentContacts]'>$cell[entertainmentContacts]</p>" ?>
    </div>
    <p class="control-line-product">
        <span><a class="approveAll color7" id="approveAll<?php echo $cell["entertainmentId"];?>">[<span class="color7">объявление</span>|<span class="color7">фото</span>]</a></span>
        <span><a class="approveProduct" id="approveProduct<?php echo $cell["entertainmentId"];?>">[<span class="color7">объявление</span>]</a></span>
        <span><a class="refusingProduct color2" id="refusingProduct<?php echo $cell["entertainmentId"];?>">[<span class="color4 strong">объявление</span>]</a></span>
    </p>
    <div class="control-infoError"><textarea name="" id="infoError<?php echo $cell["entertainmentId"];?>" class="form-textarea" cols="30" rows="10"></textarea></div>
</section>