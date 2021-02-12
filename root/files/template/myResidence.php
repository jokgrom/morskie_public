<section class="product-box" id="product<?php echo $cell["residenceId"];?>">
    <p class="control-line-product">
        <span><a href="/cabinet/residence/edit/step1.php?id=<?php echo $cell["residenceId"].$UrlAdminStatus;?>">изменить</a></span>
        <span><a href="/cabinet/residence/edit/step2.php?id=<?php echo $cell["residenceId"].$UrlAdminStatus;?>">изменить фото</a></span>
        <?php if($UrlAdminStatus==''){
            echo "<span><a id='publish$cell[residenceId]' class='publish' productId='$cell[residenceId]'>$cell[publication_statusTitle]</a></span>";
        } ?>
        <span><a class="deleteProduct" productId="<?php echo $cell["residenceId"];?>">удалить</a></span>
    </p>
    <div class="product">
        <div class="cell main-box">
            <p><a href="/residence/<?php echo $cell["residenceId"].$UrlAdminStatus;?>" class="product-name" target="_blank"><?php echo $cell["residenceTitle"];?></a></p>
            <p class="product-address"><?php echo $cell["cityTitle"].', '.$cell['suburbTitle']; ?></p>
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
                <li>Разместил</li>
                <li><?php echo $cell["ad_ownerTitle"];?></li>
            </ul>
            <?php
                if($UrlAdminStatus==''){
                    echo "
                    <ul class='cell'>
                        <li>Позиция</li>
                        <li><span id='upPublish$cell[residenceId]'>$cell[residenceDate_actual]</span></li>
                        <li><a class='upPublish' style='font-weight: 600;' productId='$cell[residenceId]'>Поднять в ТОП</a></li>
                    </ul>
                ";}
                $mapImgUrl="https://static-maps.yandex.ru/1.x/?ll=$cell[residenceAddressLongitude],$cell[residenceAddressLatitude]&size=200,200&z=13&l=map&pt=$cell[residenceAddressLongitude],$cell[residenceAddressLatitude],pm2rdm";
            ?>
            <div class="mapImg" style="background-image: url(<?php echo $mapImgUrl;?>)"></div>
        </div>
    </div>
    <p class="infoError"><?php echo $cell[residenceInfoError]; ?></p>
</section>