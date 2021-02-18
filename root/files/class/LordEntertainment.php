<?php


class LordEntertainment
{
    public function __construct($db){
        $this->db = $db;
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

    public function getProduct_onChecking(){
        //выведем те у которых текст на редактировании
        $colorError=[];
        $color='bgColor1';
        $query_edit = "SELECT entertainment_edit.entertainment_id AS `entertainmentId`,
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
                WHERE entertainment_edit._adminStatusPublication=3 ORDER BY entertainment_edit.date_actual DESC LIMIT 10";
        $data_edit=$this->db->query($query_edit);
        if(is_object($data_edit)){
            foreach($data_edit  as $cell) {
                $entertainmentId=$cell[entertainmentId];
                $query = "SELECT entertainment.id AS `entertainmentId`,
                        entertainment._adminStatusPublication AS `adminStatusPublication`,
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
                WHERE entertainment.id=$entertainmentId";
                $data=$this->db->query($query);

                if(is_object($data)){
                    foreach($data  as $cell_origin) {
                        if($cell_origin['adminStatusPublication']==2){
                            if($cell['entertainmentTitle']!=$cell_origin['entertainmentTitle']){ $colorError['entertainmentTitle']=$color;}
                            if($cell['cityTitle']!=$cell_origin['cityTitle']){ $colorError['cityTitle']=$color;}
                            if($cell['suburbTitle']!=$cell_origin['suburbTitle']){ $colorError['suburbTitle']=$color;}
                            if($cell['entertainment_listTitle']!=$cell_origin['entertainment_listTitle']){ $colorError['entertainment_listTitle']=$color;}
                            if($cell['entertainmentDescription']!=$cell_origin['entertainmentDescription']){ $colorError['entertainmentDescription']=$color;}
                            if($cell['entertainmentPrices']!=$cell_origin['entertainmentPrices']){ $colorError['entertainmentPrices']=$color;}
                            if($cell['entertainmentContacts']!=$cell_origin['entertainmentContacts']){ $colorError['entertainmentContacts']=$color;}

                        }
                        $photoContent=self::_getPhoto($cell["entertainmentId"], '', 1);
                        include($_SERVER['DOCUMENT_ROOT'].'/root/files/template/LordEntertainment.php');
                    }
                }
            }
        }
    }

    public function approveAll($productId){
        $text='';
        $query = "SELECT * FROM entertainment_edit WHERE entertainment_id=:productId LIMIT 1";
        $params =	[':productId' => $productId];
        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        $cell =  $stmt->fetch();
        //изменим оригинал, поставим ему статус одобренно
        $query_update = "UPDATE entertainment 
            SET publicationStatus_id = $cell[publicationStatus_id],
                _adminStatusPublication = 2,
                date_edit = NOW(),
                date_actual = NOW(),
                person_id = $cell[person_id],
                title = '$cell[title]',
                city_id = $cell[city_id],
                suburb_id = $cell[suburb_id],
                entertainment= $cell[entertainment],
                description = '$cell[description]',
                prices='$cell[prices]',
                addressLatitude = '$cell[addressLatitude]',
                addressLongitude = '$cell[addressLongitude]',
                address = '$cell[address]',
                contacts = '$cell[contacts]'
            WHERE id=$cell[entertainment_id] LIMIT 1";
        if($this->db->query($query_update)){
            //удалим все имеющиеся дубли из редактируемых
            $query_delete = "DELETE FROM entertainment_edit WHERE entertainment_id=$cell[entertainment_id] LIMIT 1";
            if(!$this->db->query($query_delete)){
                $text.= '<div class="modal"><p>ошибка при удалении копий!</p></div>';
            }
            //перезапишем статус фотографиям
            $query_update = "UPDATE photo_entertainment SET _adminStatusPublication = 2 WHERE product_id=$cell[entertainment_id]";
            $this->db->query($query_update);
        }else{
            echo '<div class="modal"><p>ошибка при изменении!</p></div>';
        }
        echo $text;
        unset($query_update, $stmt_update);
    }

    public function refusingProduct($productId, $infoError){
        $text='';
        //перезапишем статус о объявления
        $query_update = "UPDATE entertainment_edit SET _adminStatusPublication = 1, infoError='$infoError' WHERE entertainment_id=$productId LIMIT 1";
        if(!$this->db->query($query_update)){
            $text.= '<div class="modal"><p>ошибка при перезаписи статуса у объявления на 1!</p></div>';
        }
        echo $text;
    }

    public function approveProduct($productId){
        $text='';
        $query = "SELECT * FROM entertainment_edit WHERE entertainment_id=:productId LIMIT 1";
        $params =	[':productId' => $productId];
        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        $cell =  $stmt->fetch();

        //изменим оригинал, поставим ему статус одобренно
        $query_update = "UPDATE entertainment 
            SET publicationStatus_id = $cell[publicationStatus_id],
                _adminStatusPublication = 2,
                date_edit = NOW(),
                date_actual = NOW(),
                person_id = $cell[person_id],
                title = '$cell[title]',
                city_id = $cell[city_id],
                suburb_id = $cell[suburb_id],
                entertainment = '$cell[entertainment]',
                description = '$cell[description]',
                prices = '$cell[prices]',
                addressLatitude = '$cell[addressLatitude]',
                addressLongitude = '$cell[addressLongitude]',
                address = '$cell[address]',
                contacts = '$cell[contacts]'
            WHERE id=$cell[entertainment_id] LIMIT 1";
        if($this->db->query($query_update)){
            //удалим все имеющиеся дубли из редактируемых
            $query_delete = "DELETE FROM entertainment_edit WHERE entertainment_id=$cell[entertainment_id] LIMIT 1";
            if(!$this->db->query($query_delete)){
                $text.= '<div class="modal"><p>ошибка при удалении копий!</p></div>';
            }
        }else{
            echo '<div class="modal"><p>ошибка при изменении!</p></div>';
        }
        echo $text;
        unset($query_update, $stmt_update);
    }

    public function approve_onePhoto($photoId){
        $textError='';
//        перезапишем статус фотографиям
        $query_update = "UPDATE photo_entertainment SET _adminStatusPublication = 2 WHERE id=$photoId";
        if(!$this->db->query($query_update)){
            $textError.= '<div class="modal"><p>ошибка при перезаписи статуса фотографий на 2!</p></div>';
        }
        echo $textError;
    }

    public function refusing_onePhoto($photoId){
        $textError='';
//        перезапишем статус фотографиям
        $query_update = "UPDATE photo_entertainment SET _adminStatusPublication = 1 WHERE id=$photoId";
        if(!$this->db->query($query_update)){
            $textError.= '<div class="modal"><p>ошибка при перезаписи статуса фотографий на 1!</p></div>';
        }
        echo $textError;
    }

    public function getPhoto_onChecking(){
        //вывести фотки у которых статус на администрировании
        $query="SELECT product_id, id, sizeWidth, sizeHeight, path, name FROM photo_entertainment WHERE _adminStatusPublication=3 ORDER BY product_id";
        $entertainment_id='';
        $data=$this->db->query($query);
        if(is_object($data)){
            foreach ($data as $cell){
                if($entertainment_id!=$cell["product_id"]){
                    $entertainment_id=$cell["product_id"];
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
                $photoContentHeader='<div class="photoContentHeader"><p>IDp='.$cell["product_id"].' ID='.$cell["id"].'</p></div>';
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
        $query = "SELECT entertainment_id, person_id, title FROM entertainment_edit WHERE _adminStatusPublication=1 ORDER BY date_actual DESC";
        $data=$this->db->query($query);
        if(is_object($data)){
            foreach($data  as $cell) {
                echo '<p>IDp='.$cell["entertainment_id"].', personId='.$cell["person_id"].', название: '.$cell["title"].'</p>';
            }
        }
    }

    public function getPhoto_refusing(){
        //вывести фотки у которых статус на администрировании
        $query="SELECT 	product_id, id, path, name FROM photo_entertainment WHERE _adminStatusPublication=1 ORDER BY  product_id";
        $data=$this->db->query($query);
        if(is_object($data)){
            foreach ($data as $cell){
                echo '<p>IDp='.$cell["product_id"].',  ID='.$cell["id"].',  имя: '.$cell["name"].',  путь: '.$cell["path"].'</p>';
            }
        }
    }

    //Страница удалённые
    public function getProduct_deleted(){
        //выведем те у которых текст на редактировании
        $query = "SELECT id, title FROM entertainment_delete ORDER BY date_actual DESC";
        $data=$this->db->query($query);
        if(is_object($data)){
            foreach($data  as $cell) {
                echo '<p>IDp='.$cell["id"].', название: '.$cell["title"].'</p>';
            }
        }
    }

    public function getPhoto_deleted(){
        //вывести фотки у которых статус на администрировании
        $query="SELECT product_id, id, path, name FROM photo_entertainment_delete ORDER BY date_added DESC";
        $data=$this->db->query($query);
        if(is_object($data)){
            foreach ($data as $cell){
                echo '<p>IDp='.$cell["product_id"].',  ID='.$cell["id"].',  имя: '.$cell["name"].',  путь: '.$cell["path"].'</p>';
            }
        }
    }

}