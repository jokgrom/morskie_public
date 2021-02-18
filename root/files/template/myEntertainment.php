<section class="product-box" id="product<?php echo $cell["entertainmentId"];?>">
    <p class="control-line-product">
        <span><a href="/cabinet/entertainment/edit/step1.php?id=<?php echo $cell["entertainmentId"].$UrlAdminStatus;?>">изменить</a></span>
        <span><a href="/cabinet/entertainment/edit/step2.php?id=<?php echo $cell["entertainmentId"].$UrlAdminStatus;?>">изменить фото</a></span>
        <?php if($UrlAdminStatus==''){
            echo "<span><a id='publish$cell[entertainmentId]' class='publish' productId='$cell[entertainmentId]'>$cell[publication_statusTitle]</a></span>";
        } ?>
        <span><a class="deleteProduct" productId="<?php echo $cell["entertainmentId"];?>">удалить</a></span>
    </p>
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
            <?php
                if($UrlAdminStatus==''){
                    echo "
                    <ul class='cell'>
                        <li>Позиция</li>
                        <li><span id='upPublish$cell[entertainmentId]'>$cell[entertainmentDate_actual]</span></li>
                        <li><a class='upPublish' style='font-weight: 600;' productId='$cell[entertainmentId]'>Поднять в ТОП</a></li>
                    </ul>
                ";}
                $mapImgUrl="https://static-maps.yandex.ru/1.x/?ll=$cell[entertainmentAddressLongitude],$cell[entertainmentAddressLatitude]&size=200,200&z=13&l=map&pt=$cell[entertainmentAddressLongitude],$cell[entertainmentAddressLatitude],pm2rdm";
            ?>
            <div class="mapImg" style="background-image: url(<?php echo $mapImgUrl;?>)"></div>
        </div>
    </div>
    <?php if($cell["entertainmentInfoError"]!=''){echo '<p class="infoError">'.$cell["entertainmentInfoError"].'</p>';} ?>
</section>