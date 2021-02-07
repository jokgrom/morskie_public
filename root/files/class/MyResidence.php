<?php


class MyResidence{

    public $title, $city_id, $suburb_id, $guest_id,
        $typeHousing_id, $distance_id, $adOwner_id, $rules,
        $description, $addressLatitude, $addressLongitude, $address,
        $contacts, $conveniences, $prices;
    public $cityTitle, $suburbTitle, $guestTitle, $type_housingTitle,
        $distanceTitle, $ad_ownerTitle;

    public function __construct($db){
        $this->db = $db;
    }

    public static function _mail($type, $personId){
        switch($type){
            case 'new_residence':
                $title='Морские пути - Новое жильё';
                $subject='new residence';
                break;
            case 'new_photoResidence':
                $title='Морские пути - Новое фото';
                $subject='new photo residence';
                break;
            case 'edit_residence':
                $title='Морские пути - редактирование жилья';
                $subject='edit residence';
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
            $queryWhere.=' AND photo_residence._adminStatusPublication = '.$adminStatusPublication;
        }
        if($no_adminStatusPublication!=''){
            $queryWhere.=' AND photo_residence._adminStatusPublication != '.$no_adminStatusPublication;
        }
        $photoContent='';
        $query = "SELECT name, path FROM photo_residence WHERE residence_id=:productId $queryWhere ORDER BY priority";
        $params =	[':productId' => $productId];
        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        while($cell =  $stmt->fetch()){
            $photoContent.='<img src="'.$cell["path"].$cell["name"].'">';
        }
        return $photoContent;
    }


    protected function _getConvenience($residenceConveniences){
        $residenceConveniences=mb_substr($residenceConveniences,1,-1);
        $query = "SELECT id, title FROM conveniences WHERE id IN ( $residenceConveniences)";
        $data =$this->db->query($query);
        $convenienceContent='';
        if(is_object($data)){
            foreach($data  as $cell) {
                $convenienceContent.='<li class="cell">'.$cell["title"].'</li>';
            }
        }
        return $convenienceContent;
    }

    protected function _getPrice($residencePrice){
        $priceList=json_decode($residencePrice);
        $monthArray=["январь", "февраль", "март", "апрель", "май", "июнь", "июль", "август", "сентябрь", "октябрь", "ноябрь", "декабрь"];
        $key=date('n');
        $priceContent='';
        for($i=0; $i<=4; $i++){
            $price=($priceList->$key>50 ? $priceList->$key.' &#8381;' :'—' );
            $priceContent.='<li>'.$monthArray[$key-1].': '.$price.'</li>';
            $key=$key+1;
            if($key>=12){$key=1;}
        }
        return $priceContent;
    }


    public function add($personId, $product){
        $product['rules']=nl2br($product['rules']);
        $product['description']=nl2br($product['description']);
        $product['contacts']=nl2br($product['contacts']);
        $product['conveniences']=json_encode($product['conveniences']);
        $product['price']=json_encode($product['price']);

        $query = "INSERT INTO residence 
                    (date_edit, date_actual, person_id, title, 
                     city_id, suburb_id, guest_id, typeHousing_id, 
                     distance_id, adOwner_id, rules, 
                     description, addressLatitude, addressLongitude, 
                     address, contacts, conveniences, prices) 
                    VALUES (NOW(), NOW(), :person_id, :title, 
                            :city_id, :suburb_id, :guest_id, :typeHousing_id, 
                            :distance_id, :adOwner_id, :rules, 
                            :description, :addressLatitude, :addressLongitude, 
                            :address, :contacts, :conveniences, :prices)";
        $params =	[':person_id' => $personId,
            ':title' => $product['title'],
            ':city_id' => $product['city'],
            ':suburb_id' => $product['suburb'],
            ':guest_id' => $product['guest'],
            ':typeHousing_id' => $product['typeHousing'],
            ':distance_id' => $product['distance'],
            ':adOwner_id' => $product['adOwner'],
            ':rules' => $product['rules'],
            ':description' => $product['description'],
            ':addressLatitude' => $product['addressLatitude'],
            ':addressLongitude' => $product['addressLongitude'],
            ':address' => $product['address'],
            ':contacts' => $product['contacts'],
            ':conveniences' => $product['conveniences'],
            ':prices' => $product['price']];

        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        $lastInsertId=$this->db->lastInsertId();
        setcookie('lastResidenceId',$lastInsertId,time() + (3600*24*365),'/');//добавим ид последнего добавленного товара

        //добавим в таблицу на модерацию
        $query_is_edit = "INSERT INTO residence_edit 
                            (residence_id, date_edit, date_actual, person_id, title, 
                             city_id, suburb_id, guest_id, typeHousing_id, 
                             distance_id, adOwner_id, rules, 
                             description, addressLatitude, addressLongitude, 
                             address, contacts, conveniences, prices) 
                            VALUES ($lastInsertId, NOW(), NOW(), :person_id, :title, 
                                    :city_id, :suburb_id, :guest_id, :typeHousing_id, 
                                    :distance_id, :adOwner_id, :rules, 
                                    :description, :addressLatitude, :addressLongitude, 
                                    :address, :contacts, :conveniences, :prices)";
        $stmt_is_edit = $this->db->prepare($query_is_edit);
        if($stmt_is_edit->execute($params)){
            self::_mail('new_residence', $personId);
            exit('<div class="modal"><p>Объявление создано, осталось добавить фотографии!</p></div>
                    <script  type="text/javascript">
                     setTimeout(function(){
                        window.location.href = "/cabinet/residence/add/step2.php";
                    }, 500);
                    </script>');
        }
    }


    public function addPhoto($personId, $productId, $namePhoto, $sizeWidth, $sizeHeight, $sizeBytes, $path, $priority){
        $date_added=time();
        $query="INSERT INTO photo_residence (date_added, name, person_id, residence_id, sizeWidth, sizeHeight, sizeBytes, path, priority) 
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
        if($publish!=''){$queryWhere=' AND residence.publicationStatus_id='.$publish;}
        $query = "SELECT residence.id AS `residenceId`,
                        DATE_FORMAT(residence.date_actual, '%d.%m.%y %k:%i') AS `residenceDate_actual`,
                        publication_status.title AS `publication_statusTitle`,
                        residence.person_id AS `residencePerson_id`,
                        residence.title AS `residenceTitle`,
                        city.title AS `cityTitle`, 
                        city2.title AS `suburbTitle`, 
                        guest.title AS `guestTitle`, 
                        type_housing.title AS `type_housingTitle`, 
                        distance.title AS `distanceTitle`, 
                        ad_owner.title AS `ad_ownerTitle`,
                        residence.rules AS `residenceRules`, 
                        residence.description AS `residenceDescription`, 
                        residence.addressLatitude AS `residenceAddressLatitude`, 
                        residence.addressLongitude AS `residenceAddressLongitude`,
                        residence.address AS `residenceAddress`, 
                        residence.contacts AS `residenceContacts`,
                        residence.conveniences AS `residenceConveniences`,
                        residence.prices AS `residencePrices`
                FROM residence 
                INNER JOIN city ON residence.city_id=city.id
                INNER JOIN city AS `city2` ON residence.suburb_id=city2.id 
                INNER JOIN guest ON residence.guest_id=guest.id
                INNER JOIN type_housing ON residence.typeHousing_id=type_housing.id
                INNER JOIN distance ON residence.distance_id=distance.id
                INNER JOIN ad_owner ON residence.adOwner_id=ad_owner.id
                INNER JOIN publication_status ON residence.publicationStatus_id=publication_status.id
                WHERE residence.person_id=:personId AND residence._adminStatusPublication=2 $queryWhere ORDER BY residence.date_actual DESC";
        $params =	[':personId' => $personId];
        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        while($cell =  $stmt->fetch()){
            $photoContent=self::_getPhoto($cell["residenceId"],2);
            $convenienceContent=self::_getConvenience($cell['residenceConveniences']);
            $priceContent=self::_getPrice($cell['residencePrices']);
            include($_SERVER['DOCUMENT_ROOT'].'/root/files/template/myResidence.php');
        }
    }



    //на модерации объявления и фотографии
    public function getProduct_onChecking($personId){
        $UrlAdminStatus='&s=3';
        $queryWhere=' AND residence_edit._adminStatusPublication=3';
        //выведем те у которых текст на редактировании и все фото в объявлении кроме тех кто отклонён
        $query = "SELECT residence_edit.residence_id AS `residenceId`,
                        publication_status.title AS `publication_statusTitle`,
                        residence_edit.person_id AS `residencePerson_id`,
                        residence_edit.title AS `residenceTitle`,
                        city.title AS `cityTitle`,
                        city2.title AS `suburbTitle`,
                        guest.title AS `guestTitle`,
                        type_housing.title AS `type_housingTitle`,
                        distance.title AS `distanceTitle`,
                        ad_owner.title AS `ad_ownerTitle`,
                        residence_edit.rules AS `residenceRules`,
                        residence_edit.description AS `residenceDescription`,
                        residence_edit.addressLatitude AS `residenceAddressLatitude`,
                        residence_edit.addressLongitude AS `residenceAddressLongitude`,
                        residence_edit.address AS `residenceAddress`,
                        residence_edit.contacts AS `residenceContacts`,
                        residence_edit.conveniences AS `residenceConveniences`,
                        residence_edit.prices AS `residencePrices`
                FROM residence_edit
                INNER JOIN city ON residence_edit.city_id=city.id
                INNER JOIN city AS `city2` ON residence_edit.suburb_id=city2.id
                INNER JOIN guest ON residence_edit.guest_id=guest.id
                INNER JOIN type_housing ON residence_edit.typeHousing_id=type_housing.id
                INNER JOIN distance ON residence_edit.distance_id=distance.id
                INNER JOIN ad_owner ON residence_edit.adOwner_id=ad_owner.id
                INNER JOIN publication_status ON residence_edit.publicationStatus_id=publication_status.id
                WHERE residence_edit.person_id=:personId $queryWhere ORDER BY residence_edit.date_actual DESC";
        $params =	[':personId' => $personId];
        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        $productId_array=[];
        $bool=true;
        while($cell =  $stmt->fetch()){
            if($bool){
                echo '<h1 class="title">Изменения на модерации</h1>';
                $bool=false;
            }
            array_push($productId_array, $cell["residenceId"]);
            $photoContent=self::_getPhoto($cell["residenceId"], '', 1);
            $convenienceContent=self::_getConvenience($cell['residenceConveniences']);
            $priceContent=self::_getPrice($cell['residencePrices']);
            include($_SERVER['DOCUMENT_ROOT'].'/root/files/template/myResidence.php');
        }

        //Выведем фото которое на модерации так как сам продукт не на модерации
        //определим ид не выведенных объявлений и вытащим их фотографии
        $query2="SELECT residence_id FROM photo_residence WHERE _adminStatusPublication=3 AND person_id=:personId GROUP BY residence_id";
        $stmt2 = $this->db->prepare($query2);
        $stmt2->execute($params);
        $productId_array_photoEdit=[];
        while($cell =  $stmt2->fetch()){
            array_push($productId_array_photoEdit, $cell["residence_id"]);
        }
        $productId_newArray=array_diff($productId_array_photoEdit, $productId_array); //расхождение массивов
        $list_productId=implode(', ', $productId_newArray);
        if($list_productId==''){ return false;}

        //выведем те у которых только фото на редактировании
        echo '<h2 class="title">Фотографии на модерации</h2>';
        $query = "SELECT id , title FROM residence WHERE person_id=:personId AND id IN ($list_productId) ORDER BY date_actual DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        while($cell =  $stmt->fetch()){
            $photoContent=self::_getPhoto($cell["id"], '3');
            include($_SERVER['DOCUMENT_ROOT'].'/root/files/template/myResidencePhoto.php');
        }
    }

    //отклонённые объявления и фотографии
    public function getProduct_refusing($personId){
        $UrlAdminStatus='&s=1';
        $queryWhere=' AND residence_edit._adminStatusPublication=1';
        //выведем те у которых текст на редактировании и все фото в объявлении
        $query = "SELECT residence_edit.residence_id AS `residenceId`,
                        publication_status.title AS `publication_statusTitle`,
                        residence_edit.person_id AS `residencePerson_id`,
                        residence_edit.title AS `residenceTitle`,
                        city.title AS `cityTitle`,
                        city2.title AS `suburbTitle`,
                        guest.title AS `guestTitle`,
                        type_housing.title AS `type_housingTitle`,
                        distance.title AS `distanceTitle`,
                        ad_owner.title AS `ad_ownerTitle`,
                        residence_edit.rules AS `residenceRules`,
                        residence_edit.description AS `residenceDescription`,
                        residence_edit.addressLatitude AS `residenceAddressLatitude`,
                        residence_edit.addressLongitude AS `residenceAddressLongitude`,
                        residence_edit.address AS `residenceAddress`,
                        residence_edit.contacts AS `residenceContacts`,
                        residence_edit.conveniences AS `residenceConveniences`,
                        residence_edit.prices AS `residencePrices`,
                        residence_edit.infoError AS `residenceInfoError`
                FROM residence_edit
                INNER JOIN city ON residence_edit.city_id=city.id
                INNER JOIN city AS `city2` ON residence_edit.suburb_id=city2.id
                INNER JOIN guest ON residence_edit.guest_id=guest.id
                INNER JOIN type_housing ON residence_edit.typeHousing_id=type_housing.id
                INNER JOIN distance ON residence_edit.distance_id=distance.id
                INNER JOIN ad_owner ON residence_edit.adOwner_id=ad_owner.id
                INNER JOIN publication_status ON residence_edit.publicationStatus_id=publication_status.id
                WHERE residence_edit.person_id=:personId $queryWhere ORDER BY residence_edit.date_actual DESC";
        $params =	[':personId' => $personId];
        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        $productId_array=[];
        $bool=true;
        while($cell =  $stmt->fetch()){
            if($bool){
                echo '<h1 class="title">Отклонённые изменения</h1>';
                $bool=false;
            }
            array_push($productId_array, $cell["residenceId"]);
            $photoContent=self::_getPhoto($cell["residenceId"]);
            $convenienceContent=self::_getConvenience($cell['residenceConveniences']);
            $priceContent=self::_getPrice($cell['residencePrices']);
            include($_SERVER['DOCUMENT_ROOT'].'/root/files/template/myResidence.php');
        }


        //удалим общие элементы и вытащим запрос по откланённым фотографиям
        $query2="SELECT residence_id FROM photo_residence WHERE _adminStatusPublication=1 AND person_id=:personId GROUP BY residence_id";
        $stmt2 = $this->db->prepare($query2);
        $stmt2->execute($params);
        $productId_array_photoEdit=[];
        while($cell =  $stmt2->fetch()){
            array_push($productId_array_photoEdit, $cell["residence_id"]);
        }

        //выведем те у которых только фото отклонены
        $productId_newArray=array_diff($productId_array_photoEdit, $productId_array); //расхождение массивов
        $list_productId=implode(', ', $productId_newArray);
        if($list_productId==''){ return false;}

        echo '<h2 class="title">Отклонённые фотографии</h2>';
        echo $list_productId;
        $query = "SELECT id , title FROM residence WHERE person_id=:personId AND id IN ($list_productId) ORDER BY date_actual DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        while($cell =  $stmt->fetch()){
            $photoContent=self::_getPhoto($cell["id"], '1');
            include($_SERVER['DOCUMENT_ROOT'].'/root/files/template/myResidencePhoto.php');
        }
    }


    public function publish($personId, $productId){ //опубликовать или снять с публикации
        $query="SELECT publicationStatus_id FROM residence WHERE id=:productId AND person_id=:personId LIMIT 1";
        $params =	[':personId' => $personId,
            ':productId' => $productId];
        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        $cell =  $stmt->fetch();
        $status_id=($cell['publicationStatus_id']=='2'?1:2);

        $query_update = "UPDATE residence SET publicationStatus_id = $status_id WHERE id=:productId AND person_id=:personId LIMIT 1";
        $stmt_update = $this->db->prepare($query_update);
        $stmt_update->execute($params);
        unset($query_update, $stmt_update);

        //если есть таблица на редактировании то изменим и у неё статус
        $query_update = "UPDATE residence_edit SET publicationStatus_id = $status_id WHERE residence_id=:productId AND person_id=:personId ORDER BY date_edit DESC LIMIT 1 ";
        $stmt_update = $this->db->prepare($query_update);
        $stmt_update->execute($params);
        unset($query_update, $stmt_update);

        $query="SELECT publication_status.title AS `publication_statusTitle` 
                    FROM residence 
                    INNER JOIN publication_status ON residence.publicationStatus_id=publication_status.id
                    WHERE residence.id=:productId AND residence.person_id=:personId LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        $cell =  $stmt->fetch();
        return $cell['publication_statusTitle'];
    }


    public function upPublish($personId, $productId){ //опубликовать или снять с публикации
        $query_update = "UPDATE residence SET date_actual = NOW() WHERE id=:productId AND person_id=:personId LIMIT 1";
        $params =	[':personId' => $personId,
            ':productId' => $productId];
        $stmt_update = $this->db->prepare($query_update);
        $stmt_update->execute($params);
        unset($query_update, $stmt_update);

        $query="SELECT DATE_FORMAT(date_actual, '%d.%m.%y %k:%i') AS `residenceDate_actual`
                    FROM residence WHERE id=:productId AND person_id=:personId LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        $cell =  $stmt->fetch();
        return $cell['residenceDate_actual'];
    }



    public function edit($personId, $product){
        //сперва удалим существующее объявление, потом занесём новое(изменённое)
        $query_deleteProduct = "DELETE FROM residence_edit WHERE residence_id=:productId AND person_id=:personId LIMIT 1";
        $params =	[':personId' => $personId, ':productId' => $product['id']];
        $stmt_deleteProduct = $this->db->prepare($query_deleteProduct);
        $stmt_deleteProduct->execute($params);

        $product['rules']=nl2br($product['rules']);
        $product['description']=nl2br($product['description']);
        $product['contacts']=nl2br($product['contacts']);
        $product['conveniences']=json_encode($product['conveniences']);
        $product['price']=json_encode($product['price']);

        $query = "INSERT INTO residence_edit 
                    (residence_id, date_edit, date_actual, person_id, title, 
                     city_id, suburb_id, guest_id, typeHousing_id, 
                     distance_id, adOwner_id, rules, 
                     description, addressLatitude, addressLongitude, 
                     address, contacts, conveniences, prices) 
                    VALUES (:residence_id, NOW(), NOW(), :person_id, :title, 
                            :city_id, :suburb_id, :guest_id, :typeHousing_id, 
                            :distance_id, :adOwner_id, :rules, 
                            :description, :addressLatitude, :addressLongitude, 
                            :address, :contacts, :conveniences, :prices)";
        $params =	[':person_id' => $personId,
            ':residence_id' => $product['id'],
            ':title' => $product['title'],
            ':city_id' => $product['city'],
            ':suburb_id' => $product['suburb'],
            ':guest_id' => $product['guest'],
            ':typeHousing_id' => $product['typeHousing'],
            ':distance_id' => $product['distance'],
            ':adOwner_id' => $product['adOwner'],
            ':rules' => $product['rules'],
            ':description' => $product['description'],
            ':addressLatitude' => $product['addressLatitude'],
            ':addressLongitude' => $product['addressLongitude'],
            ':address' => $product['address'],
            ':contacts' => $product['contacts'],
            ':conveniences' => $product['conveniences'],
            ':prices' => $product['price']];

        $stmt = $this->db->prepare($query);
        if($stmt->execute($params)){
            self::_mail('edit_residence', $personId);
            exit('<div class="modal"><p>бъявление отправленно на рассмотрение!</p></div>
                    <script  type="text/javascript">
                     setTimeout(function(){
                        window.location.href = "/cabinet/residence/";
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
                $query_update = "UPDATE photo_residence SET priority = $k WHERE id=$v  AND person_id=:personId AND residence_id=:productId LIMIT 1";
                $stmt = $this->db->prepare($query_update);
                if($stmt->execute($params)){
                    $bool=true;
                }
            }
        }

        $query = "SELECT id FROM photo_residence WHERE person_id=:personId AND residence_id=:productId";
        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        while($cell =  $stmt->fetch()){
            if(!in_array($cell['id'],$idOldPhotos)){//если есть удалённый элемент
                $query_clone="INSERT photo_residence_delete SELECT * FROM photo_residence WHERE id=$cell[id] AND residence_id=:productId AND person_id=:personId ";
                $stmt_clonePhoto = $this->db->prepare($query_clone);
                if($stmt_clonePhoto->execute($params)){
                    $query_deletePhoto = "DELETE FROM photo_residence WHERE id=$cell[id] AND residence_id=:productId AND person_id=:personId";
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
         $query="INSERT INTO photo_residence (date_added, name, person_id, residence_id, sizeWidth, sizeHeight, sizeBytes, path, priority)
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
        $query_clonePhoto="INSERT photo_residence_delete SELECT * FROM photo_residence WHERE residence_id=:productId AND person_id=:personId";
        $params =	[':personId' => $personId,
            ':productId' => $productId];
        $stmt_clonePhoto = $this->db->prepare($query_clonePhoto);
        if($stmt_clonePhoto->execute($params)){
            //удаляем фоки из бд
            $query_deletePhoto = "DELETE FROM photo_residence WHERE residence_id=:productId AND person_id=:personId";
            $stmt_deletePhoto = $this->db->prepare($query_deletePhoto);
            $stmt_deletePhoto->execute($params);
        }

        //клонируем объявление
        $query_cloneProduct="INSERT residence_delete SELECT * FROM residence WHERE id=:productId AND person_id=:personId";
        $stmt_cloneProduct = $this->db->prepare($query_cloneProduct);
        if($stmt_cloneProduct->execute($params)){
            //удаляем объявление из бд
            $query_deleteProduct = "DELETE FROM residence WHERE id=:productId AND person_id=:personId";
            $stmt_deleteProduct = $this->db->prepare($query_deleteProduct);
            $stmt_deleteProduct->execute($params);

            //удаляем объявление из редактируемых
            $query_deleteProduct = "DELETE FROM residence_edit WHERE residence_id=:productId AND person_id=:personId";
            $stmt_deleteProduct = $this->db->prepare($query_deleteProduct);
            $stmt_deleteProduct->execute($params);
        }
    }


    public function deleteProduct_edit($personId, $productId){
        $query="SELECT 1 FROM residence WHERE id=:productId AND person_id=:personId AND _adminStatusPublication=2";
        $params =	[':personId' => $personId,
            ':productId' => $productId];
        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        if($stmt->fetch()){ //если есть активное объявление, то удалим редактируемую таблицу и все не активные фотки, в БД кинем только фотки не активные
            //клонируем фотки
            $query_clonePhoto="INSERT photo_residence_delete SELECT * FROM photo_residence WHERE residence_id=:productId AND person_id=:personId AND _adminStatusPublication!=2";
            $stmt_clonePhoto = $this->db->prepare($query_clonePhoto);
            if($stmt_clonePhoto->execute($params)){
                //удаляем фоки из бд
                $query_deletePhoto = "DELETE FROM photo_residence WHERE residence_id=:productId AND person_id=:personId AND _adminStatusPublication!=2";
                $stmt_deletePhoto = $this->db->prepare($query_deletePhoto);
                $stmt_deletePhoto->execute($params);
            }
            //удаляем объявление из редактируемых
            $query_deleteProduct = "DELETE FROM residence_edit WHERE residence_id=:productId AND person_id=:personId";
            $stmt_deleteProduct = $this->db->prepare($query_deleteProduct);
            $stmt_deleteProduct->execute($params);
        }else{
            //если само объявление не активно, то удалим основное, редактируемое и все фотки, при этом все фотки занесём в БД
            //клонируем фотки
            $query_clonePhoto="INSERT photo_residence_delete SELECT * FROM photo_residence WHERE residence_id=:productId AND person_id=:personId";
            $stmt_clonePhoto = $this->db->prepare($query_clonePhoto);
            if($stmt_clonePhoto->execute($params)){
                //удаляем фоки из бд
                $query_deletePhoto = "DELETE FROM photo_residence WHERE residence_id=:productId AND person_id=:personId";
                $stmt_deletePhoto = $this->db->prepare($query_deletePhoto);
                $stmt_deletePhoto->execute($params);

                //удаляем объявление из бд
                $query_deleteProduct = "DELETE FROM residence WHERE id=:productId AND person_id=:personId";
                $stmt_deleteProduct = $this->db->prepare($query_deleteProduct);
                $stmt_deleteProduct->execute($params);

                //удаляем объявление из редактируемых
                $query_deleteProduct = "DELETE FROM residence_edit WHERE residence_id=:productId AND person_id=:personId";
                $stmt_deleteProduct = $this->db->prepare($query_deleteProduct);
                $stmt_deleteProduct->execute($params);
            }
        }
    }

    public function deletePhotos_onChecking($personId, $productId){
        //клонируем фотки
        $query_clonePhoto="INSERT photo_residence_delete SELECT * FROM photo_residence WHERE residence_id=:productId AND person_id=:personId AND _adminStatusPublication=3";
        $params =	[':personId' => $personId,
            ':productId' => $productId];
        $stmt_clonePhoto = $this->db->prepare($query_clonePhoto);
        if($stmt_clonePhoto->execute($params)){
            //удаляем фоки из бд
            $query_deletePhoto = "DELETE FROM photo_residence WHERE residence_id=:productId AND person_id=:personId AND _adminStatusPublication=3";
            $stmt_deletePhoto = $this->db->prepare($query_deletePhoto);
            $stmt_deletePhoto->execute($params);
        }
    }

    public function deletePhotos_refusing($personId, $productId){
        //клонируем фотки
        $query_clonePhoto="INSERT photo_residence_delete SELECT * FROM photo_residence WHERE residence_id=:productId AND person_id=:personId AND _adminStatusPublication=1";
        $params =	[':personId' => $personId,
            ':productId' => $productId];
        $stmt_clonePhoto = $this->db->prepare($query_clonePhoto);
        if($stmt_clonePhoto->execute($params)){
            //удаляем фоки из бд
            $query_deletePhoto = "DELETE FROM photo_residence WHERE residence_id=:productId AND person_id=:personId AND _adminStatusPublication=1";
            $stmt_deletePhoto = $this->db->prepare($query_deletePhoto);
            $stmt_deletePhoto->execute($params);
        }
    }


    public function getInfo($personId, $productId, $UrlAdminStatus){
        if($UrlAdminStatus!=3 AND $UrlAdminStatus!=1){
            $query = "SELECT _adminStatusPublication, title, city_id, suburb_id, guest_id,
                             typeHousing_id, distance_id, adOwner_id, rules, 
                             description, addressLatitude, addressLongitude, address, 
                             contacts, conveniences, prices 
                    FROM residence WHERE person_id=:personId  AND id=:productId LIMIT 1";
        }else{
            $query = "SELECT _adminStatusPublication, title, city_id, suburb_id, guest_id,
                             typeHousing_id, distance_id, adOwner_id, rules, 
                             description, addressLatitude, addressLongitude, address, 
                             contacts, conveniences, prices 
                    FROM residence_edit WHERE person_id=:personId  AND residence_id=:productId LIMIT 1";
        }
        $params =	[':personId' => $personId,
            ':productId' => $productId];
        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        $cell =  $stmt->fetch();

        $this->title=$cell['title'];
        $this->city_id=$cell['city_id'];
        $this->suburb_id=$cell['suburb_id'];
        $this->guest_id=$cell['guest_id'];

        $this->typeHousing_id=$cell['typeHousing_id'];
        $this->distance_id=$cell['distance_id'];
        $this->adOwner_id=$cell['adOwner_id'];
        $this->rules=$cell['rules'];

        $this->description=$cell['description'];
        $this->addressLatitude=$cell['addressLatitude'];
        $this->addressLongitude=$cell['addressLongitude'];
        $this->address=$cell['address'];

        $this->contacts=$cell['contacts'];
        $this->conveniences=$cell['conveniences'];
        $this->prices=$cell['prices'];
    }

    public function getInfo_forMe($productId){
        $query = "SELECT residence_edit.title AS `residenceTitle`, 
                    city.title AS `cityTitle`,
                    city2.title AS `suburbTitle`, 
                    guest.title AS `guestTitle`,
                    type_housing.title AS `type_housingTitle`,
                    distance.title AS `distanceTitle`, 
                    ad_owner.title AS `ad_ownerTitle`,
                    residence_edit.rules AS `residenceRules`,   
                    residence_edit.description AS `residenceDescription`, 
                    residence_edit.addressLatitude AS `residenceAddressLatitude`, 
                    residence_edit.addressLongitude AS `residenceAddressLongitude`, 
                    residence_edit.address AS `residenceAddress`,      
                    residence_edit.contacts AS `residenceContacts`, 
                    residence_edit.conveniences AS `residenceConveniences`, 
                    residence_edit.prices  AS `residencePrices`
                FROM residence_edit 
                INNER JOIN city ON residence_edit.city_id=city.id
                INNER JOIN city AS `city2` ON residence_edit.suburb_id=city2.id 
                INNER JOIN guest ON residence_edit.guest_id=guest.id
                INNER JOIN type_housing ON residence_edit.typeHousing_id=type_housing.id
                INNER JOIN distance ON residence_edit.distance_id=distance.id
                INNER JOIN ad_owner ON residence_edit.adOwner_id=ad_owner.id
                WHERE residence_edit.residence_id=:productId LIMIT 1";
        $params =	[':productId' => $productId];
        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        $cell =  $stmt->fetch();

        $this->title=$cell['residenceTitle'];
        $this->cityTitle=$cell['cityTitle'];
        $this->suburbTitle=$cell['suburbTitle'];
        $this->guestTitle=$cell['guestTitle'];

        $this->type_housingTitle=$cell['type_housingTitle'];
        $this->distanceTitle=$cell['distanceTitle'];
        $this->ad_ownerTitle=$cell['ad_ownerTitle'];
        $this->rules=$cell['residenceRules'];

        $this->description=$cell['residenceDescription'];
        $this->addressLatitude=$cell['residenceAddressLatitude'];
        $this->addressLongitude=$cell['residenceAddressLongitude'];
        $this->address=$cell['residenceAddress'];

        $this->contacts=$cell['residenceContacts'];
        $this->conveniences=$cell['residenceConveniences'];
        $this->prices=$cell['residencePrices'];
    }
}