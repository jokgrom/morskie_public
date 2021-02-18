<section class="product-box" id="product<?php echo $cell["entertainmentId"];?>">
    <div class="product">
        <div class="cell main-box">
            <p><a href="/entertainment/<?php echo $cell["entertainmentId"].$UrlAdminStatus;?>" class="product-name" target="_blank"><?php echo $cell["entertainmentTitle"];?></a> | <?php echo $cell["entertainment_listTitle"];?></p>
            <p class="product-address"><?php echo $cell["cityTitle"].', '.$cell['suburbTitle']; ?></p>
            <ul class="product-description">
                <?php echo $convenienceContent;?>
            </ul>
            <div class="fotorama"  data-nav="thumbs" data-navposition="top" data-transition="crossfade" data-loop="true"  data-maxheight="400" data-width="80%" data-thumbwidth="50px" data-thumbheight="50px">
                <?php echo $photoContent;?>
            </div>
        </div>
        <div class="cell right-box">
            <?php $mapImgUrl="https://static-maps.yandex.ru/1.x/?ll=$cell[entertainmentAddressLongitude],$cell[entertainmentAddressLatitude]&size=200,200&z=13&l=map&pt=$cell[entertainmentAddressLongitude],$cell[entertainmentAddressLatitude],pm2rdm";  ?>
            <div class="mapImg" style="background-image: url(<?php echo $mapImgUrl;?>)"></div>
        </div>
    </div>
</section>