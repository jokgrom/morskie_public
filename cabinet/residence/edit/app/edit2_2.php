<?php
    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/db.php');

    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/CleanForm.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/CleanFormPerson.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/CleanFormProduct.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/Checks.php');

    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/MyResidence.php');

    $statusError=[];
    $CleanFormPerson=new CleanFormPerson;
    $CleanFormProduct= new CleanFormProduct();
    $Checks=new Checks($db);
    $MyResidence = new MyResidence($db);


    //проверка аутентификации
    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/template/authenticationPerson.php');

    //проверка принадлежности продукта к человеку
    list  ($productId, $boolError, $textError)=$CleanFormProduct->id($_COOKIE['lastEditResidenceId']);
    if($boolError){array_push($statusError, $textError);}
    if(!count($statusError)){
        list  ($boolError, $textError)=$Checks->lastResidenceId($personId, $productId);
        if($boolError){array_push($statusError, $textError);}
    }



    $input_name='images';
    if (isset($_FILES['images']) AND !count($statusError)) {
        $dir1 = $_SERVER['DOCUMENT_ROOT']."/photo/".$personId;
        $dir2 = $_SERVER['DOCUMENT_ROOT']."/photo/".$personId."/".$productId."/";
        if(is_dir($dir1)){ //проверяем директории, если нету то делаем
            if(!is_dir($dir2)){mkdir($dir2, 0777);}
        }else{
            mkdir($dir1, 0777);
            mkdir($dir2, 0777);
        }
        unset($dir1);

        $files = [];  // Преобразуем массив $_FILES в удобный вид для перебора в foreach.
        $diff = count($_FILES[$input_name]) - count($_FILES[$input_name], COUNT_RECURSIVE);

        if ($diff == 0) {
            $files = array($_FILES[$input_name]);
        } else {
            foreach($_FILES[$input_name] as $k => $l) {
                foreach($l as $i => $v) {
                    $files[$i][$k] = $v;
                }
            }
        }
        $countPhoto=0;
        foreach ($files as $file) {
            if (!empty($file['error']) || empty($file['tmp_name'])) {
                switch (@$file['error']) {
                    case 1:
                    case 2: $statusError.= 'Превышен размер загружаемого файла. '; continue;
                    case 3: $statusError.= 'Файл был получен только частично. '; continue;
                    case 4: $statusError.= 'Файл не был загружен. '; continue;
                    case 6: $statusError.= 'Файл не загружен - отсутствует временная директория. '; continue;
                    case 7: $statusError.= 'Не удалось записать файл на диск. '; continue;
                    case 8: $statusError.= 'PHP-расширение остановило загрузку файла. '; continue;
                    case 9: $statusError.= 'Файл не был загружен - директория не существует. '; continue;
                    case 10: $statusError.= 'Превышен максимально допустимый размер файла. '; continue;
                    case 11: $statusError.= 'Данный тип файла запрещен. '; continue;
                    case 12: $statusError.= 'Ошибка при копировании файла. '; continue;
                    default: $statusError.= 'Файл не был загружен - неизвестная ошибка. '; continue;
                }
            } elseif ($file['tmp_name'] == 'none' || !is_uploaded_file($file['tmp_name'])) {
                $statusError.= 'Не удалось загрузить файл. ';
                continue;
            }else{

                // Получим MIME-тип
                $fi = finfo_open(FILEINFO_MIME_TYPE);// Создадим ресурс FileInfo
                $mime = (string) finfo_file($fi, $file['tmp_name']);
                finfo_close($fi); // Закроем ресурс

                // Проверим MIME-тип
                if (strpos($mime, 'image') === false) { $statusError.= 'Можно загружать только изображения. '; continue;}
                $image = getimagesize($file['tmp_name']); // закинем данные об картинке в переменную
                if($image[2]!=2 and $image[2]!=3){$statusError.= 'Можно загружать изображения формата png, jpg, jpeg. '; continue;}

                // Зададим ограничения для картинок
                $limitBytes  = 1024 * 1024 * 10; //размер
                $limitWidthMax  = 12500; //ширина
                $limitWidthMin  = 200;
                $limitHeightMax = 12500; //высота
                $limitHeightMin = 200;

                $sizeHeight=$image[1]; $sizeWidth=$image[0];
                $sizeBytes=filesize($file['tmp_name']);

                // Проверим нужные параметры
                if ($sizeBytes > $limitBytes) {$statusError.= 'Размер изображения не должен превышать 10 Мбайт. '; continue;}
                if ($sizeHeight > $limitHeightMax) {$statusError.= 'Высота изображения не должна превышать 12500 пикселей. '; continue;}
                if ($sizeHeight < $limitHeightMin) {$statusError.= 'Высота изображения не должна быть меньше 200 пикселей. '; continue;}
                if ($sizeWidth > $limitWidthMax) {$statusError.= 'Ширина изображения не должна превышать 12500 пикселей. '; continue;}
                if ($sizeWidth < $limitWidthMin) {$statusError.= 'Ширина изображения не должна быть меньше 200 пикселей. '; continue;}
                $new_sizeHeight=$new_sizeWidth=0;
                if($sizeHeight>=$sizeWidth){
                    $new_sizeWidth=$sizeWidth*(1200/$sizeHeight);
                    $new_sizeHeight=1200;
                }else{
                    $new_sizeHeight=$sizeHeight*(1200/$sizeWidth);
                    $new_sizeWidth=1200;
                }
                // Сгенерируем новое имя
                $namePhoto=md5_file($file['tmp_name']);
                $namePhoto=rand(0,9).substr($namePhoto,0,10).''.time();

                $image_p = imagecreatetruecolor($new_sizeWidth, $new_sizeHeight);
                $source=($image[2]==2 ? imagecreatefromjpeg($file['tmp_name']) : imagecreatefrompng($file['tmp_name']));
                imagecopyresampled($image_p, $source, 0, 0, 0, 0, $new_sizeWidth, $new_sizeHeight, $sizeWidth, $sizeHeight);

                // Переместим картинку с новым именем и расширением в папку /photo/userId/productId
                if (imagejpeg($image_p, $dir2 . $namePhoto .'.jpg', 79)) {
                    imagedestroy($source);//Чистим память
                    $statusError.= 'Запись изображения на диск успешна. ';
                    $namePhoto.='.jpg';
                    $path="/photo/".$personId."/".$productId."/";
                    $MyResidence->editPhoto2($personId, $productId, $namePhoto, $sizeWidth, $sizeHeight, $sizeBytes, $path);
                    $countPhoto++;
                    continue;

                }else{
                    $statusError.= 'При записи изображения на диск произошла ошибка. '; continue;
                }
            }
        }
        if($countPhoto>=1){
            setcookie('lastResidenceId','',0,'/');//удалим куку
            $MyResidence->_mail('new_photoResidence', $personId);
            exit('<div class="modal"><p>Отправленно на рассмотрение фотографий: '.$countPhoto.'!</p></div>
                <script  type="text/javascript">
                     setTimeout(function(){
                        window.location.href = "/cabinet/residence/";
                    }, 500);
                    </script>');
        }
    }else{
        $statusError=implode(', ', $statusError);
        exit('<div class="modal"><p>Ошибка: '.$statusError.'!</p></div>');
    }
