<section class="product-box" id="product<?php echo $cell["id"];?>">
    <p class="control-line-product">
        <span><a href="/cabinet/entertainment/edit/step2.php?id=<?php echo $cell["id"].$UrlAdminStatus;;?>">изменить фото</a></span>
        <span><a class="deletePhotos" productId="<?php echo $cell["id"];?>">удалить фотографии</a></span>
    </p>
    <div class="myproduct">
        <div class="cell">
            <p><a class="product-name"><?php echo $cell["title"];?></a></p>
            <div class="fotorama"  data-nav="thumbs" data-navposition="top" data-transition="crossfade" data-loop="true"  data-maxheight="400" data-width="80%" data-thumbwidth="50px" data-thumbheight="50px">
                <?php echo $photoContent;?>
            </div>
        </div>
    </div>
</section>