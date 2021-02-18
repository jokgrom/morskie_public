<?php
class MyEntertainment{
    public $title, $city_id, $suburb_id,
        $entertainment, $description, $prices,
        $addressLatitude, $addressLongitude, $address, $contacts;
    public $cityTitle, $suburbTitle;

    public function __construct($db){
        $this->db = $db;
    }

    public static function _mail($type, $personId){
        switch($type){
            case 'new_entertainment':
                $title='Новое развлечение';
                $subject='new Entertainment';
                break;
            case 'new_photoEntertainment':
                $title='Морские пути - Новое фото';
                $subject='new photo Entertainment';
                break;
            case 'edit_entertainment':
                $title='Морские пути - редактирование развлечения';
                $subject='edit entertainment';
                break;
            default:
                $title='Морские пути';
                $subject='no type';
                break;
        }
        $date=date("d.m.y G:i");
        $headers = "From: Морские пути <robot@morskie-puti.ru>\r\nContent-type: text/html; charset=utf-8 \r\n";
        $message= '
		 	<html>
			    <head>
		 	   <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		 	        <title>$title</title>
		 	    </head>
		 	    <body>
		 	        <p>дата: '. $date .'</p>
		 	        <p>id пользователя: '.$personId.'</p>
		 	    </body> 
		 	</html>';
        mail('jokgrom@yandex.ru', $subject, $message, $headers);
    }

    protected function _getPhoto($productId, $adminStatusPublication='', $no_adminStatusPublication=''){
        //совмещаем старые и вновь добавленные фотографии
        $queryWhere='';
        if($adminStatusPublication!=''){
            $queryWhere.=' AND photo_entertainment._adminStatusPublication = '.$adminStatusPublication;
        }
        if($no_adminStatusPublication!=''){
            $queryWhere.=' AND photo_entertainment._adminStatusPublication != '.$no_adminStatusPublication;
        }
        $photoContent='';
        $query = "SELECT name, path FROM photo_entertainment WHERE product_id=:productId $queryWhere ORDER BY priority";
        $params =	[':productId' => $productId];
        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        while($cell =  $stmt->fetch()){
            $photoContent.='<img src="'.$cell["path"].$cell["name"].'">';
        }
        return $photoContent;
    }

    public function add($personId, $product){
        $product['description']=nl2br($product['description']);
        $product['entertainmentPrice']=nl2br($product['entertainmentPrice']);
        $product['contacts']=nl2br($product['contacts']);

        $query = "INSERT INTO entertainment 
                        (date_edit, date_actual, person_id, title, 
                         city_id, suburb_id, entertainment, description, 
                         prices, addressLatitude, addressLongitude, 
                         address, contacts) 
                        VALUES (NOW(), NOW(), :person_id, :title, 
                                :city_id, :suburb_id, :listEntertainment, :description, 
                                :entertainmentPrice, :addressLatitude, :addressLongitude, 
                                :address, :contacts)";
        $params =	[':person_id' => $personId,
            ':title' => $product['title'],
            ':city_id' => $product['city'],
            ':suburb_id' => $product['suburb'],
            ':listEntertainment' => $product['listEntertainment'],
            ':description' => $product['description'],
            ':entertainmentPrice' => $product['entertainmentPrice'],
            ':addressLatitude' => $product['addressLatitude'],
            ':addressLongitude' => $product['addressLongitude'],
            ':address' => $product['address'],
            ':contacts' => $product['contacts']];
        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        $lastInsertId=$this->db->lastInsertId();
        setcookie('lastEntertainmentId',$lastInsertId,time() + (3600*24*365),'/');//добавим ид последнего добавленного товара

        //добавим в таблицу на модерацию
        $query_is_edit = "INSERT INTO entertainment_edit 
                        (entertainment_id, date_edit, date_actual, person_id, title, 
                         city_id, suburb_id, entertainment, description, 
                         prices, addressLatitude, addressLongitude, 
                         address, contacts) 
                        VALUES ($lastInsertId, NOW(), NOW(), :person_id, :title, 
                                :city_id, :suburb_id, :listEntertainment, :description, 
                                :entertainmentPrice, :addressLatitude, :addressLongitude, 
                                :address, :contacts)";
        $stmt_is_edit = $this->db->prepare($query_is_edit);
        if($stmt_is_edit->execute($params)){
            self::_mail('new_entertainment', $personId);
            exit('<div class="modal"><p>Объявление создано, осталось добавить фотографии!</p></div>
                    <script  type="text/javascript">
                     setTimeout(function(){
                        window.location.href = "/cabinet/entertainment/add/step2.php"; 
                    }, 500);
                    </script>');
        }
    }

    public function addPhoto($personId, $productId, $namePhoto, $sizeWidth, $sizeHeight, $sizeBytes, $path, $priority){
        $date_added=time();
        $query="INSERT INTO photo_entertainment (date_added, name, person_id, product_id, sizeWidth, sizeHeight, sizeBytes, path, priority) 
                        VALUES ($date_added, :namePhoto, :personId, :productId, :sizeWidth, :sizeHeight, :sizeBytes, :path, :priority) ";
        $params =	[':personId' => $personId,
            ':productId' => $productId,
            ':namePhoto' => $namePhoto,
            ':sizeWidth' => $sizeWidth,
            ':sizeHeight' => $sizeHeight,
            ':sizeBytes' => $sizeBytes,
            ':path' => $path,
            ':priority' => $priority];
        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
    }

    //опубликованные и не опубликованные объявления
    public function getProduct($personId, $publish=''){
        $UrlAdminStatus='';
        $queryWhere='';
        if($publish!=''){$queryWhere=' AND entertainment.publicationStatus_id='.$publish;}
        $query = "SELECT entertainment.id AS `entertainmentId`,
                        DATE_FORMAT(entertainment.date_actual, '%d.%m.%y %k:%i') AS `entertainmentDate_actual`,
                        publication_status.title AS `publication_statusTitle`,
                        entertainment.person_id AS `entertainmentPerson_id`,
                        entertainment.title AS `entertainmentTitle`,
                        city.title AS `cityTitle`, 
                        city2.title AS `suburbTitle`, 
                        entertainment_list.title AS `entertainment_listTitle`, 
                        entertainment.description AS `entertainmentDescription`, 
                        entertainment.prices AS `entertainmentPrices`,
                        entertainment.addressLatitude AS `entertainmentAddressLatitude`, 
                        entertainment.addressLongitude AS `entertainmentAddressLongitude`,
                        entertainment.address AS `entertainmentAddress`, 
                        entertainment.contacts AS `entertainmentContacts`
                FROM entertainment 
                INNER JOIN city ON entertainment.city_id=city.id
                INNER JOIN city AS `city2` ON entertainment.suburb_id=city2.id 
                INNER JOIN entertainment_list ON entertainment_list.id=entertainment.entertainment 
                INNER JOIN publication_status ON entertainment.publicationStatus_id=publication_status.id
                WHERE entertainment.person_id=:personId AND entertainment._adminStatusPublication=2 $queryWhere ORDER BY entertainment.date_actual DESC";
        $params =	[':personId' => $personId];
        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        while($cell =  $stmt->fetch()){
            $photoContent=self::_getPhoto($cell["entertainmentId"],2);
            include($_SERVER['DOCUMENT_ROOT'].'/root/files/template/myEntertainment.php');
        }
    }


    //на модерации объявления и фотографии
    public function getProduct_onChecking($personId){
        $UrlAdminStatus='&s=3';
        $queryWhere=' AND entertainment_edit._adminStatusPublication=3';
        //выведем те у которых текст на редактировании и все фото в объявлении кроме тех кто отклонён
        $query = "SELECT entertainment_edit.entertainment_id AS `entertainmentId`,
                        DATE_FORMAT(entertainment_edit.date_actual, '%d.%m.%y %k:%i') AS `entertainmentDate_actual`,
                        publication_status.title AS `publication_statusTitle`,
                        entertainment_edit.person_id AS `entertainmentPerson_id`,
                        entertainment_edit.title AS `entertainmentTitle`,
                        city.title AS `cityTitle`, 
                        city2.title AS `suburbTitle`, 
                        entertainment_list.title AS `entertainment_listTitle`, 
                        entertainment_edit.description AS `entertainmentDescription`, 
                        entertainment_edit.prices AS `entertainmentPrices`,
                        entertainment_edit.addressLatitude AS `entertainmentAddressLatitude`, 
                        entertainment_edit.addressLongitude AS `entertainmentAddressLongitude`,
                        entertainment_edit.address AS `entertainmentAddress`, 
                        entertainment_edit.contacts AS `entertainmentContacts`
                FROM entertainment_edit 
                INNER JOIN city ON entertainment_edit.city_id=city.id
                INNER JOIN city AS `city2` ON entertainment_edit.suburb_id=city2.id 
                INNER JOIN entertainment_list ON entertainment_list.id=entertainment_edit.entertainment 
                INNER JOIN publication_status ON entertainment_edit.publicationStatus_id=publication_status.id
                WHERE entertainment_edit.person_id=:personId $queryWhere ORDER BY entertainment_edit.date_actual DESC";
        $params =	[':personId' => $personId];
        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        $productId_array=[];
        $bool=true;
        while($cell =  $stmt->fetch()){
            if($bool){
                echo '<h1 class="title">Развлечения | Изменения на модерации</h1>';
                $bool=false;
            }
            array_push($productId_array, $cell["entertainmentId"]);
            $photoContent=self::_getPhoto($cell["entertainmentId"], '', 1);
            include($_SERVER['DOCUMENT_ROOT'].'/root/files/template/myEntertainment.php');
        }

        //Выведем фото которое на модерации так как сам продукт не на модерации
        //определим ид не выведенных объявлений и вытащим их фотографии
        $query2="SELECT product_id FROM photo_entertainment WHERE _adminStatusPublication=3 AND person_id=:personId GROUP BY product_id";
        $stmt2 = $this->db->prepare($query2);
        $stmt2->execute($params);
        $productId_array_photoEdit=[];
        while($cell =  $stmt2->fetch()){
            array_push($productId_array_photoEdit, $cell["product_id"]);
        }
        $productId_newArray=array_diff($productId_array_photoEdit, $productId_array); //расхождение массивов
        $list_productId=implode(', ', $productId_newArray);
        if($list_productId==''){ return false;}

        //выведем те у которых только фото на редактировании
        echo '<h2 class="title">Развлечения | Фотографии на модерации</h2>';
        $query = "SELECT id , title FROM entertainment WHERE person_id=:personId AND id IN ($list_productId) ORDER BY date_actual DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        while($cell =  $stmt->fetch()){
            $photoContent=self::_getPhoto($cell["id"], '3');
            include($_SERVER['DOCUMENT_ROOT'].'/root/files/template/myEntertainmentPhoto.php');
        }
    }

    //отклонённые объявления и фотографии
    public function getProduct_refusing($personId){
        $UrlAdminStatus='&s=1';
        $queryWhere=' AND entertainment_edit._adminStatusPublication=1';
        //выведем те у которых текст на редактировании и все фото в объявлении
        $query = "SELECT entertainment_edit.entertainment_id AS `entertainmentId`,
                        DATE_FORMAT(entertainment_edit.date_actual, '%d.%m.%y %k:%i') AS `entertainmentDate_actual`,
                        publication_status.title AS `publication_statusTitle`,
                        entertainment_edit.person_id AS `entertainmentPerson_id`,
                        entertainment_edit.title AS `entertainmentTitle`,
                        city.title AS `cityTitle`, 
                        city2.title AS `suburbTitle`, 
                        entertainment_list.title AS `entertainment_listTitle`, 
                        entertainment_edit.description AS `entertainmentDescription`, 
                        entertainment_edit.prices AS `entertainmentPrices`,
                        entertainment_edit.addressLatitude AS `entertainmentAddressLatitude`, 
                        entertainment_edit.addressLongitude AS `entertainmentAddressLongitude`,
                        entertainment_edit.address AS `entertainmentAddress`, 
                        entertainment_edit.contacts AS `entertainmentContacts`,
                        entertainment_edit.infoError AS `entertainmentInfoError`
                FROM entertainment_edit 
                INNER JOIN city ON entertainment_edit.city_id=city.id
                INNER JOIN city AS `city2` ON entertainment_edit.suburb_id=city2.id 
                INNER JOIN entertainment_list ON entertainment_list.id=entertainment_edit.entertainment 
                INNER JOIN publication_status ON entertainment_edit.publicationStatus_id=publication_status.id
                WHERE entertainment_edit.person_id=:personId $queryWhere ORDER BY entertainment_edit.date_actual DESC";
        $params =	[':personId' => $personId];
        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        $productId_array=[];
        $bool=true;
        while($cell =  $stmt->fetch()){
            if($bool){
                echo '<h1 class="title">Развлечения | Отклонённые изменения</h1>';
                $bool=false;
            }
            array_push($productId_array, $cell["entertainmentId"]);
            $photoContent=self::_getPhoto($cell["entertainmentId"]);
            include($_SERVER['DOCUMENT_ROOT'].'/root/files/template/myEntertainment.php');
        }


        //удалим общие элементы и вытащим запрос по откланённым фотографиям
        $query2="SELECT product_id FROM photo_entertainment WHERE _adminStatusPublication=1 AND person_id=:personId GROUP BY product_id";
        $stmt2 = $this->db->prepare($query2);
        $stmt2->execute($params);
        $productId_array_photoEdit=[];
        while($cell =  $stmt2->fetch()){
            array_push($productId_array_photoEdit, $cell["product_id"]);
        }

        //выведем те у которых только фото отклонены
        $productId_newArray=array_diff($productId_array_photoEdit, $productId_array); //расхождение массивов
        $list_productId=implode(', ', $productId_newArray);
        if($list_productId==''){ return false;}

        echo '<h2 class="title">Развлечения | Отклонённые фотографии</h2>';
        $query = "SELECT id , title FROM entertainment WHERE person_id=:personId AND id IN ($list_productId) ORDER BY date_actual DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        while($cell =  $stmt->fetch()){
            $photoContent=self::_getPhoto($cell["id"], '1');
            include($_SERVER['DOCUMENT_ROOT'].'/root/files/template/myEntertainmentPhoto.php');
        }
    }

    public function publish($personId, $productId){ //опубликовать или снять с публикации
        $query="SELECT publicationStatus_id FROM entertainment WHERE id=:productId AND person_id=:personId LIMIT 1";
        $params =	[':personId' => $personId,
            ':productId' => $productId];
        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        $cell =  $stmt->fetch();
        $status_id=($cell['publicationStatus_id']=='2'?1:2);

        $query_update = "UPDATE entertainment SET publicationStatus_id = $status_id WHERE id=:productId AND person_id=:personId LIMIT 1";
        $stmt_update = $this->db->prepare($query_update);
        $stmt_update->execute($params);
        unset($query_update, $stmt_update);

        //если есть таблица на редактировании то изменим и у неё статус
        $query_update = "UPDATE entertainment_edit SET publicationStatus_id = $status_id WHERE entertainment_id=:productId AND person_id=:personId ORDER BY date_edit DESC LIMIT 1 ";
        $stmt_update = $this->db->prepare($query_update);
        $stmt_update->execute($params);
        unset($query_update, $stmt_update);

        $query="SELECT publication_status.title AS `publication_statusTitle` 
                    FROM entertainment 
                    INNER JOIN publication_status ON entertainment.publicationStatus_id=publication_status.id
                    WHERE entertainment.id=:productId AND entertainment.person_id=:personId LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        $cell =  $stmt->fetch();
        return $cell['publication_statusTitle'];
    }

    public function upPublish($personId, $productId){ //опубликовать или снять с публикации
        $query_update = "UPDATE entertainment SET date_actual = NOW() WHERE id=:productId AND person_id=:personId LIMIT 1";
        $params =	[':personId' => $personId,
            ':productId' => $productId];
        $stmt_update = $this->db->prepare($query_update);
        $stmt_update->execute($params);
        unset($query_update, $stmt_update);

        $query="SELECT DATE_FORMAT(date_actual, '%d.%m.%y %k:%i') AS `entertainmentDate_actual`
                    FROM entertainment WHERE id=:productId AND person_id=:personId LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        $cell =  $stmt->fetch();
        return $cell['entertainmentDate_actual'];
    }

    public function edit($personId, $product){
        //сперва удалим существующее объявление, потом занесём новое(изменённое)
        $query_deleteProduct = "DELETE FROM entertainment_edit WHERE entertainment_id=:productId AND person_id=:personId LIMIT 1";
        $params =	[':personId' => $personId, ':productId' => $product['id']];
        $stmt_deleteProduct = $this->db->prepare($query_deleteProduct);
        $stmt_deleteProduct->execute($params);

        $product['description']=nl2br($product['description']);
        $product['prices']=nl2br($product['prices']);
        $product['contacts']=nl2br($product['contacts']);
        $query = "INSERT INTO entertainment_edit 
                    (entertainment_id, date_edit, date_actual, person_id, title, 
                     city_id, suburb_id, entertainment, description, 
                     prices, addressLatitude, addressLongitude, 
                     address, contacts) 
                    VALUES (:entertainment_id, NOW(), NOW(), :person_id, :title, 
                            :city_id, :suburb_id, :listEntertainment, :description, 
                            :entertainmentPrice, :addressLatitude, :addressLongitude, 
                            :address, :contacts)";
        $params =	[':person_id' => $personId,
            ':entertainment_id' => $product['id'],
            ':title' => $product['title'],
            ':city_id' => $product['city'],
            ':suburb_id' => $product['suburb'],
            ':listEntertainment' => $product['listEntertainment'],
            ':description' => $product['description'],
            ':entertainmentPrice' => $product['entertainmentPrice'],
            ':addressLatitude' => $product['addressLatitude'],
            ':addressLongitude' => $product['addressLongitude'],
            ':address' => $product['address'],
            ':contacts' => $product['contacts']];
        $stmt = $this->db->prepare($query);
        if($stmt->execute($params)){
            self::_mail('edit_entertainment', $personId);
            exit('<div class="modal"><p>Объявление отправленно на рассмотрение!</p></div>
                    <script  type="text/javascript">
                     setTimeout(function(){
                        window.location.href = "/cabinet/entertainment/";
                    }, 500);
                    </script>');
        }else{
            exit('<div class="modal"><p>Ошибка запроса!</p></div>');
        }
    }

    public function editPhoto($personId, $productId, $idOldPhotos){
        //изменим порядок фотографий
        $params =	[':personId' => $personId,
            ':productId' => $productId];
        $bool=false;
        if(count($idOldPhotos)>=1){
            foreach ($idOldPhotos as $k => $v) {
                $query_update = "UPDATE photo_entertainment SET priority = $k WHERE id=$v  AND person_id=:personId AND product_id=:productId LIMIT 1";
                $stmt = $this->db->prepare($query_update);
                if($stmt->execute($params)){
                    $bool=true;
                }
            }
        }

        $query = "SELECT id FROM photo_entertainment WHERE person_id=:personId AND product_id=:productId";
        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        while($cell =  $stmt->fetch()){
            if(!in_array($cell['id'],$idOldPhotos)){//если есть удалённый элемент
                $query_clone="INSERT photo_entertainment_delete SELECT * FROM photo_entertainment WHERE id=$cell[id] AND product_id=:productId AND person_id=:personId ";
                $stmt_clonePhoto = $this->db->prepare($query_clone);
                if($stmt_clonePhoto->execute($params)){
                    $query_deletePhoto = "DELETE FROM photo_entertainment WHERE id=$cell[id] AND product_id=:productId AND person_id=:personId";
                    $stmt_deletePhoto = $this->db->prepare($query_deletePhoto);
                    $stmt_deletePhoto->execute($params);
                    $bool=true;
                }
            }
        }
        return  $bool;
    }

    public function editPhoto2($personId, $productId, $namePhoto, $sizeWidth, $sizeHeight, $sizeBytes, $path){
        $date_added=time();
        $priority=100;
        $query="INSERT INTO photo_entertainment (date_added, name, person_id, product_id, sizeWidth, sizeHeight, sizeBytes, path, priority)
                VALUES ($date_added, :namePhoto, :personId, :productId, :sizeWidth, :sizeHeight, :sizeBytes, :path, $priority) ";
        $params =	[':personId' => $personId,
            ':productId' => $productId,
            ':namePhoto' => $namePhoto,
            ':sizeWidth' => $sizeWidth,
            ':sizeHeight' => $sizeHeight,
            ':sizeBytes' => $sizeBytes,
            ':path' => $path];
        $stmt = $this->db->prepare($query);
        return $stmt->execute($params);
    }


    public function deleteProduct($personId, $productId){
        //клонируем фотки
        $query_clonePhoto="INSERT photo_entertainment_delete SELECT * FROM photo_entertainment WHERE product_id=:productId AND person_id=:personId";
        $params =	[':personId' => $personId,
            ':productId' => $productId];
        $stmt_clonePhoto = $this->db->prepare($query_clonePhoto);
        if($stmt_clonePhoto->execute($params)){
            //удаляем фоки из бд
            $query_deletePhoto = "DELETE FROM photo_entertainment WHERE product_id=:productId AND person_id=:personId";
            $stmt_deletePhoto = $this->db->prepare($query_deletePhoto);
            $stmt_deletePhoto->execute($params);
        }

        //клонируем объявление
        $query_cloneProduct="INSERT entertainment_delete SELECT * FROM entertainment WHERE id=:productId AND person_id=:personId";
        $stmt_cloneProduct = $this->db->prepare($query_cloneProduct);
        if($stmt_cloneProduct->execute($params)){
            //удаляем объявление из бд
            $query_deleteProduct = "DELETE FROM entertainment WHERE id=:productId AND person_id=:personId";
            $stmt_deleteProduct = $this->db->prepare($query_deleteProduct);
            $stmt_deleteProduct->execute($params);

            //удаляем объявление из редактируемых
            $query_deleteProduct = "DELETE FROM entertainment_edit WHERE entertainment_id=:productId AND person_id=:personId";
            $stmt_deleteProduct = $this->db->prepare($query_deleteProduct);
            $stmt_deleteProduct->execute($params);
        }
    }


    public function deleteProduct_edit($personId, $productId){
        $query="SELECT 1 FROM entertainment WHERE id=:productId AND person_id=:personId AND _adminStatusPublication=2";
        $params =	[':personId' => $personId,
            ':productId' => $productId];
        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        if($stmt->fetch()){ //если есть активное объявление, то удалим редактируемую таблицу и все не активные фотки, в БД кинем только фотки не активные
            //клонируем фотки
            $query_clonePhoto="INSERT photo_entertainment_delete SELECT * FROM photo_entertainment WHERE product_id=:productId AND person_id=:personId AND _adminStatusPublication!=2";
            $stmt_clonePhoto = $this->db->prepare($query_clonePhoto);
            if($stmt_clonePhoto->execute($params)){
                //удаляем фоки из бд
                $query_deletePhoto = "DELETE FROM photo_entertainment WHERE product_id=:productId AND person_id=:personId AND _adminStatusPublication!=2";
                $stmt_deletePhoto = $this->db->prepare($query_deletePhoto);
                $stmt_deletePhoto->execute($params);
            }
            //удаляем объявление из редактируемых
            $query_deleteProduct = "DELETE FROM entertainment_edit WHERE entertainment_id=:productId AND person_id=:personId";
            $stmt_deleteProduct = $this->db->prepare($query_deleteProduct);
            $stmt_deleteProduct->execute($params);
        }else{
            //если само объявление не активно, то удалим основное, редактируемое и все фотки, при этом все фотки занесём в БД
            //клонируем фотки
            $query_clonePhoto="INSERT photo_entertainment_delete SELECT * FROM photo_entertainment WHERE product_id=:productId AND person_id=:personId";
            $stmt_clonePhoto = $this->db->prepare($query_clonePhoto);
            if($stmt_clonePhoto->execute($params)){
                //удаляем фоки из бд
                $query_deletePhoto = "DELETE FROM photo_entertainment WHERE product_id=:productId AND person_id=:personId";
                $stmt_deletePhoto = $this->db->prepare($query_deletePhoto);
                $stmt_deletePhoto->execute($params);

                //удаляем объявление из бд
                $query_deleteProduct = "DELETE FROM entertainment WHERE id=:productId AND person_id=:personId";
                $stmt_deleteProduct = $this->db->prepare($query_deleteProduct);
                $stmt_deleteProduct->execute($params);

                //удаляем объявление из редактируемых
                $query_deleteProduct = "DELETE FROM entertainment_edit WHERE entertainment_id=:productId AND person_id=:personId";
                $stmt_deleteProduct = $this->db->prepare($query_deleteProduct);
                $stmt_deleteProduct->execute($params);
            }
        }
    }


    public function deletePhotos_onChecking($personId, $productId){
        //клонируем фотки
        $query_clonePhoto="INSERT photo_entertainment_delete SELECT * FROM photo_entertainment WHERE product_id=:productId AND person_id=:personId AND _adminStatusPublication=3";
        $params =	[':personId' => $personId,
            ':productId' => $productId];
        $stmt_clonePhoto = $this->db->prepare($query_clonePhoto);
        if($stmt_clonePhoto->execute($params)){
            //удаляем фоки из бд
            $query_deletePhoto = "DELETE FROM photo_entertainment WHERE product_id=:productId AND person_id=:personId AND _adminStatusPublication=3";
            $stmt_deletePhoto = $this->db->prepare($query_deletePhoto);
            $stmt_deletePhoto->execute($params);
        }
    }

    public function deletePhotos_refusing($personId, $productId){
        //клонируем фотки
        $query_clonePhoto="INSERT photo_entertainment_delete SELECT * FROM photo_entertainment WHERE product_id=:productId AND person_id=:personId AND _adminStatusPublication=1";
        $params =	[':personId' => $personId,
            ':productId' => $productId];
        $stmt_clonePhoto = $this->db->prepare($query_clonePhoto);
        if($stmt_clonePhoto->execute($params)){
            //удаляем фоки из бд
            $query_deletePhoto = "DELETE FROM photo_entertainment WHERE product_id=:productId AND person_id=:personId AND _adminStatusPublication=1";
            $stmt_deletePhoto = $this->db->prepare($query_deletePhoto);
            $stmt_deletePhoto->execute($params);
        }
    }

    public function getInfo($personId, $productId, $UrlAdminStatus){
        if($UrlAdminStatus!=3 AND $UrlAdminStatus!=1){
            $query = "SELECT _adminStatusPublication, title, city_id, suburb_id, 
                            entertainment, description, prices, 
                            addressLatitude, addressLongitude, address, contacts
                    FROM entertainment WHERE person_id=:personId  AND id=:productId LIMIT 1";
        }else{
            $query = "SELECT _adminStatusPublication, title, city_id, suburb_id, 
                            entertainment, description, prices, 
                            addressLatitude, addressLongitude, address, contacts
                    FROM entertainment_edit WHERE person_id=:personId  AND entertainment_id=:productId LIMIT 1";
        }
        $params =	[':personId' => $personId,
            ':productId' => $productId];
        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        $cell =  $stmt->fetch();

        $this->title=$cell['title'];
        $this->city_id=$cell['city_id'];
        $this->suburb_id=$cell['suburb_id'];

        $this->entertainment=$cell['entertainment'];
        $this->description=$cell['description'];
        $this->prices=$cell['prices'];

        $this->addressLatitude=$cell['addressLatitude'];
        $this->addressLongitude=$cell['addressLongitude'];
        $this->address=$cell['address'];
        $this->contacts=$cell['contacts'];
    }

    public function getInfo_forMe($productId){
        $query = "SELECT entertainment_edit.title AS `entertainmentTitle`, 
                    city.title AS `cityTitle`,
                    city2.title AS `suburbTitle`, 
                    entertainment.entertainment AS `entertainment_list`,
                    entertainment_list.title AS `entertainment_listTitle`,
                    entertainment_edit.description AS `entertainmentDescription`,
                    entertainment_edit.prices AS `entertainmentPrices`, 
                    entertainment_edit.addressLatitude AS `entertainmentAddressLatitude`, 
                    entertainment_edit.addressLongitude AS `entertainmentAddressLongitude`, 
                    entertainment_edit.address AS `entertainmentAddress`,      
                    entertainment_edit.contacts AS `entertainmentContacts`
                FROM entertainment_edit 
                INNER JOIN city ON entertainment_edit.city_id=city.id
                INNER JOIN city AS `city2` ON entertainment_edit.suburb_id=city2.id 
                INNER JOIN entertainment_list ON entertainment.entertainment=entertainment_list.id
                WHERE entertainment_edit.entertainment_id=:productId LIMIT 1";
        $params =	[':productId' => $productId];
        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        $cell =  $stmt->fetch();

        $this->title=$cell['entertainmentTitle'];
        $this->cityTitle=$cell['cityTitle'];
        $this->suburbTitle=$cell['suburbTitle'];

        $this->entertainment=$cell['entertainment_listTitle'];
        $this->description=$cell['entertainmentDescription'];
        $this->prices=$cell['entertainmentPrices'];

        $this->addressLatitude=$cell['entertainmentAddressLatitude'];
        $this->addressLongitude=$cell['entertainmentAddressLongitude'];
        $this->address=$cell['entertainmentAddress'];
        $this->contacts=$cell['entertainmentContacts'];
    }
}