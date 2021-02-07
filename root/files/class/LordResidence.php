<?php


class LordResidence
{
    public function __construct($db){
        $this->db = $db;
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


    public function getProduct_onChecking(){
        //выведем те у которых текст на редактировании
        $colorError=[];
        $color='bgColor1';
        $query_edit = "SELECT residence_edit.residence_id AS `residenceId`,
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
                WHERE residence_edit._adminStatusPublication=3 ORDER BY residence_edit.date_actual DESC LIMIT 10";
        $data_edit=$this->db->query($query_edit);
        if(is_object($data_edit)){
            foreach($data_edit  as $cell) {
                $residenceId=$cell[residenceId];
                $query = "SELECT residence.id AS `residenceId`,
                        residence._adminStatusPublication AS `adminStatusPublication`,
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
                WHERE residence.id=$residenceId";
                $data=$this->db->query($query);

                if(is_object($data)){
                    foreach($data  as $cell_origin) {
                        if($cell_origin['adminStatusPublication']==2){
                            if($cell['residenceTitle']!=$cell_origin['residenceTitle']){ $colorError['residenceTitle']=$color;}
                            if($cell['cityTitle']!=$cell_origin['cityTitle']){ $colorError['cityTitle']=$color;}
                            if($cell['suburbTitle']!=$cell_origin['suburbTitle']){ $colorError['suburbTitle']=$color;}
                            if($cell['guestTitle']!=$cell_origin['guestTitle']){ $colorError['guestTitle']=$color;}
                            if($cell['type_housingTitle']!=$cell_origin['type_housingTitle']){ $colorError['type_housingTitle']=$color;}
                            if($cell['distanceTitle']!=$cell_origin['distanceTitle']){ $colorError['distanceTitle']=$color;}
                            if($cell['ad_ownerTitle']!=$cell_origin['ad_ownerTitle']){ $colorError['ad_ownerTitle']=$color;}
                            if($cell['residenceRules']!=$cell_origin['residenceRules']){ $colorError['residenceRules']=$color;}
                            if($cell['residenceDescription']!=$cell_origin['residenceDescription']){ $colorError['residenceDescription']=$color;}
                            if($cell['residenceContacts']!=$cell_origin['residenceContacts']){ $colorError['residenceContacts']=$color;}
                            if($cell['residenceConveniences']!=$cell_origin['residenceConveniences']){ $colorError['residenceConveniences']=$color;}
                            if($cell['residencePrices']!=$cell_origin['residencePrices']){ $colorError['residencePrices']=$color;}
                        }
                        $photoContent=self::_getPhoto($cell["residenceId"], '', 1);
                        $convenienceContent=self::_getConvenience($cell['residenceConveniences']);
                        $priceContent=self::_getPrice($cell['residencePrices']);
                        include($_SERVER['DOCUMENT_ROOT'].'/root/files/template/LordResidence.php');
                    }
                }
            }
        }
    }


    public function approveAll($productId){
        $text='';
        $query = "SELECT * FROM residence_edit WHERE residence_id=:productId LIMIT 1";
        $params =	[':productId' => $productId];
        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        $cell =  $stmt->fetch();

        //изменим оригинал, поставим ему статус одобренно
        $query_update = "UPDATE residence 
            SET publicationStatus_id = $cell[publicationStatus_id],
                _adminStatusPublication = 2,
                date_edit = NOW(),
                date_actual = NOW(),
                person_id = $cell[person_id],
                title = '$cell[title]',
                city_id = $cell[city_id],
                suburb_id = $cell[suburb_id],
                guest_id = $cell[guest_id],
                typeHousing_id = $cell[typeHousing_id],
                distance_id = $cell[distance_id],
                adOwner_id = $cell[adOwner_id],
                rules = '$cell[rules]',
                description = '$cell[description]',
                addressLatitude = '$cell[addressLatitude]',
                addressLongitude = '$cell[addressLongitude]',
                address = '$cell[address]',
                contacts = '$cell[contacts]',
                conveniences='$cell[conveniences]',
                prices='$cell[prices]'
            WHERE id=$cell[residence_id] LIMIT 1";
        if($this->db->query($query_update)){
            //удалим все имеющиеся дубли из редактируемых
            $query_delete = "DELETE FROM residence_edit WHERE residence_id=$cell[residence_id] LIMIT 1";
            if(!$this->db->query($query_delete)){
                $text.= '<div class="modal"><p>ошибка при удалении копий!</p></div>';
            }
            //перезапишем статус фотографиям
            $query_update = "UPDATE photo_residence SET _adminStatusPublication = 2 WHERE residence_id=$cell[residence_id]";
            $this->db->query($query_update);
        }else{
            echo '<div class="modal"><p>ошибка при изменении!</p></div>';
        }
        echo $text;
        unset($query_update, $stmt_update);
    }

    public function approveProduct($productId){
        $text='';
        $query = "SELECT * FROM residence_edit WHERE residence_id=:productId LIMIT 1";
        $params =	[':productId' => $productId];
        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        $cell =  $stmt->fetch();

        //изменим оригинал, поставим ему статус одобренно
        $query_update = "UPDATE residence 
            SET publicationStatus_id = $cell[publicationStatus_id],
                _adminStatusPublication = 2,
                date_edit = NOW(),
                date_actual = NOW(),
                person_id = $cell[person_id],
                title = '$cell[title]',
                city_id = $cell[city_id],
                suburb_id = $cell[suburb_id],
                guest_id = $cell[guest_id],
                typeHousing_id = $cell[typeHousing_id],
                distance_id = $cell[distance_id],
                adOwner_id = $cell[adOwner_id],
                rules = '$cell[rules]',
                description = '$cell[description]',
                addressLatitude = '$cell[addressLatitude]',
                addressLongitude = '$cell[addressLongitude]',
                address = '$cell[address]',
                contacts = '$cell[contacts]',
                conveniences='$cell[conveniences]',
                prices='$cell[prices]'
            WHERE id=$cell[residence_id] LIMIT 1";
        if($this->db->query($query_update)){
            //удалим все имеющиеся дубли из редактируемых
            $query_delete = "DELETE FROM residence_edit WHERE residence_id=$cell[residence_id] LIMIT 1";
            if(!$this->db->query($query_delete)){
                $text.= '<div class="modal"><p>ошибка при удалении копий!</p></div>';
            }
        }else{
            echo '<div class="modal"><p>ошибка при изменении!</p></div>';
        }
        echo $text;
        unset($query_update, $stmt_update);
    }

    public function refusingProduct($productId, $infoError){
        $text='';
        //перезапишем статус о объявления
        $query_update = "UPDATE residence_edit SET _adminStatusPublication = 1, infoError='$infoError' WHERE residence_id=$productId LIMIT 1";
        if(!$this->db->query($query_update)){
            $text.= '<div class="modal"><p>ошибка при перезаписи статуса у объявления на 1!</p></div>';
        }
        echo $text;
    }


    public function approve_onePhoto($photoId){
        $textError='';
//        перезапишем статус фотографиям
        $query_update = "UPDATE photo_residence SET _adminStatusPublication = 2 WHERE id=$photoId";
        if(!$this->db->query($query_update)){
            $textError.= '<div class="modal"><p>ошибка при перезаписи статуса фотографий на 2!</p></div>';
        }
        echo $textError;
    }

    public function refusing_onePhoto($photoId){
        $textError='';
//        перезапишем статус фотографиям
        $query_update = "UPDATE photo_residence SET _adminStatusPublication = 1 WHERE id=$photoId";
        if(!$this->db->query($query_update)){

            $textError.= '<div class="modal"><p>ошибка при перезаписи статуса фотографий на 1!</p></div>';
        }
        echo $textError;
    }

    public function getPhoto_onChecking(){
        //вывести фотки у которых статус на администрировании
        $query="SELECT residence_id, id, sizeWidth, sizeHeight, path, name FROM photo_residence WHERE _adminStatusPublication=3 ORDER BY residence_id";
        $residence_id='';
        $data=$this->db->query($query);
        if(is_object($data)){
           foreach ($data as $cell){
               if($residence_id!=$cell["residence_id"]){
                   $residence_id=$cell["residence_id"];
                   echo '<div class="photo-box" style="width: 100%"></div>';
               }
               $h=$cell['sizeHeight'];
               $w=$cell['sizeWidth'];
               if($h>=$w){
                   $w=$w*(300/$h);
                   $h=300;
               }else{
                   $h=$h*(300/$w);
                   $w=300;
               }
               $photoContentHeader='<div class="photoContentHeader"><p>IDp='.$cell["residence_id"].' ID='.$cell["id"].'</p></div>';
               $photoContent='<div class="photoContent"><img src="'.$cell["path"].$cell["name"].'" width="'.$w.'" height="'.$h.'"></div>';
               $photoContentFooter='<div class="photoContentFooter"><p><span><a class="approve" id="approve'.$cell["id"].'">одобрить</a></span> '.
                   '<span><a class="refusing" id="refusing'.$cell["id"].'">отклонить</a></span></p></div>';
               echo '<div class="photo-box" id="photo-box'.$cell["id"].'">'.$photoContentHeader.$photoContent.$photoContentFooter.'</div>';

           }
        }
    }


    //страница отклоненные
    public function getProduct_refusing(){
        //выведем те у которых текст на редактировании
        $query = "SELECT residence_id, person_id, title FROM residence_edit WHERE _adminStatusPublication=1 ORDER BY date_actual DESC";
        $data=$this->db->query($query);
        if(is_object($data)){
            foreach($data  as $cell) {
                echo '<p>IDp='.$cell["residence_id"].', personId='.$cell["person_id"].', название: '.$cell["title"].'</p>';
            }
        }
    }

    public function getPhoto_refusing(){
        //вывести фотки у которых статус на администрировании
        $query="SELECT residence_id, id, path, name FROM photo_residence WHERE _adminStatusPublication=1 ORDER BY residence_id";
        $residence_id='';
        $data=$this->db->query($query);
        if(is_object($data)){
            foreach ($data as $cell){
                echo '<p>IDp='.$cell["residence_id"].',  ID='.$cell["id"].',  имя: '.$cell["name"].',  путь: '.$cell["path"].'</p>';
            }
        }
    }

    //Страница удалённые
    public function getProduct_deleted(){
        //выведем те у которых текст на редактировании
        $query = "SELECT id, title FROM residence_delete ORDER BY date_actual DESC";
        $data=$this->db->query($query);
        if(is_object($data)){
            foreach($data  as $cell) {
               echo '<p>IDp='.$cell["id"].', название: '.$cell["title"].'</p>';
            }
        }
    }

    public function getPhoto_deleted(){
        //вывести фотки у которых статус на администрировании
        $query="SELECT residence_id, id, path, name FROM photo_residence_delete ORDER BY date_actual DESC";
        $data=$this->db->query($query);
        if(is_object($data)){
            foreach ($data as $cell){
                echo '<p>IDp='.$cell["residence_id"].',  ID='.$cell["id"].',  имя: '.$cell["name"].',  путь: '.$cell["path"].'</p>';
            }
        }
    }
}