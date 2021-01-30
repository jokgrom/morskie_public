<?php
     class Residence {
         public $title, $city_id, $suburb_id, $guest_id,
             $typeHousing_id, $distance_id, $adOwner_id, $rules,
             $description, $addressLatitude, $addressLongitude, $address,
             $contacts, $conveniences, $prices;
         public $cityTitle, $suburbTitle, $guestTitle, $type_housingTitle,
             $distanceTitle, $ad_ownerTitle;


         public function __construct($db){
              $this->db = $db;
         }


         protected function _getConvenience($residenceConveniences){
             $residenceConveniences=substr($residenceConveniences,1,-1);
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
             for($i=1; $i<=5; $i++){
                 $price=($priceList->$i>50 ? $priceList->$i.' &#8381;' :'—' );
                 $priceContent.='<li>'.$monthArray[$key-1].': '.$price.'</li>';
                 $key=$key+1;
                 if($key>12){$key=1;}
             }
             return $priceContent;
         }


         protected function _getPhoto($productId){
             //совмещаем старые и вновь добавленные фотографии
             $photoContent='';
             $query = "SELECT name, path FROM photo_residence WHERE residence_id=:productId AND _adminStatusPublication =2";
             $params =	[':productId' => $productId];
             $stmt = $this->db->prepare($query);
             $stmt->execute($params);
             while($cell =  $stmt->fetch()){
                 $photoContent.='<img src="'.$cell["path"].$cell["name"].'">';
             }
             return $photoContent;
         }


         public function getAll($product=[]){
             $queryWhere=[];
             if($product['city']!=0){array_push($queryWhere, 'residence.city_id='.$product['city']);}
             if($product['suburb']!=0){array_push($queryWhere, 'residence.suburb_id='.$product['suburb']);}
             if($product['guest']!=0){array_push($queryWhere, 'residence.guest_id='.$product['guest']);}
             if($product['typeHousing']!=0){array_push($queryWhere, 'residence.typeHousing_id='.$product['typeHousing']);}
             if($product['distance']!=0){array_push($queryWhere, 'residence.distance_id='.$product['distance']);}
             if($product['adOwner']!=0){array_push($queryWhere, 'residence.adOwner_id='.$product['adOwner']);}
             if(count($product['conveniences'])>=1){
                 function joinConveniences($cell){
                    return " JSON_CONTAINS(residence.conveniences, '$cell')";
                 }
                 array_push($queryWhere, implode(" AND ", array_map('joinConveniences', $product['conveniences'])));
             }

             $month=($product['priceMonth']!=0 ? $product['priceMonth'] : date("n"));
             if($product['priceFrom']!=0){
                 array_push($queryWhere, " JSON_EXTRACT(prices, '$.".$month."')>=".$product['priceFrom']);
             }
             if($product['priceTo']!=0){
                 array_push($queryWhere, " JSON_EXTRACT(prices, '$.".$month."')<=".$product['priceTo']);
             }
             switch ($product['sort']){
                 case 2:
                     $queryOrderBy=" ORDER BY JSON_EXTRACT(prices, '$.".$month."')";
                     break;
                 case 3:
                     $queryOrderBy=" ORDER BY JSON_EXTRACT(prices, '$.".$month."') DESC";
                     break;
                 default:
                     $queryOrderBy=' ORDER BY residence.id';
             }
             $queryGroupBy=' GROUP BY residence.id';

             $queryCOUNT="SELECT COUNT(id) AS `countProduct` FROM residence WHERE _adminStatusPublication !=3";
             $query = "SELECT residence.id AS `residenceId`,
                            residence._adminStatusPublication AS `residence_adminStatusPublication`,
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
                    INNER JOIN publication_status ON residence.publicationStatus_id=publication_status.id AND residence.publicationStatus_id=2
                    INNER JOIN photo_residence ON photo_residence.residence_id=residence.id AND photo_residence._adminStatusPublication=2 
                    WHERE residence._adminStatusPublication =2";

             if(count($queryWhere)>=1){
                 $queryWhere=join($queryWhere, ' AND ');
                 $query.=' AND '.$queryWhere;
                 $queryCOUNT.=' AND '.$queryWhere;
             }

             $maxCountProduct=15;
             if($product['page']!=0){
                 $from=$product['page']*$maxCountProduct-$maxCountProduct;
                 $queryLimit=' LIMIT '.$from.','.$maxCountProduct;
             }else{
                 $queryLimit=' LIMIT 0,'.$maxCountProduct;
             }

             $query.=$queryGroupBy.$queryOrderBy;
             $query.=$queryLimit;
             $data =$this->db->query($query);
             if(is_object($data)){
                 foreach($data  as $cell) {
                     $photoContent=self::_getPhoto($cell["residenceId"]);
                     $convenienceContent=self::_getConvenience($cell['residenceConveniences']);
                     $priceContent=self::_getPrice($cell['residencePrices']);

                     include($_SERVER['DOCUMENT_ROOT'].'/root/files/template/residence.php');
                 }
             }

             //вывод пагинации
             $countProduct=1;
             $dataCOUNT =$this->db->query($queryCOUNT);
             if(is_object($dataCOUNT)){
                 foreach($dataCOUNT  as $cell) {
                     $countProduct=$cell['countProduct'];
                 }
             }
             $countPage=ceil($countProduct/$maxCountProduct);
             $paginationBack=($product['page']-1>=1 ? '<span><a href="/residences/?page='.($product['page']-1).'">← Пред.</a></span>' :'');
             $paginationForward=($product['page']+1<=$countPage ? '<span><a href="/residences/?page='.($product['page']+1).'">След. →</a></span>' : '');
             $pageFirst=($product['page']-5>1 ? $product['page']-5 : 1);
             $pageLast=($product['page']+5<$countPage ? $product['page']+5 : $countPage);
             $pagination='';
             for($i=$pageFirst; $i<=$pageLast; $i++){
                 if($i==$product['page']){
                     $pagination.='<span><a href="/residences/?page='.$i.'" class="pagination-main-page">'.$i.'</a></span>';
                 }else{
                     $pagination.='<a href="/residences/?page='.$i.'">'.$i.'</a></span>';
                 }
             }

             $pagination=($countProduct>$maxCountProduct ? $paginationBack.$pagination.$paginationForward : '');
             echo "<div class='pagination'>$pagination</div>";
         }


         public function getInfo($productId){
             $query = "SELECT residence.title AS `residenceTitle`, 
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
                    residence.prices  AS `residencePrices`
                FROM residence 
                INNER JOIN city ON residence.city_id=city.id
                INNER JOIN city AS `city2` ON residence.suburb_id=city2.id 
                INNER JOIN guest ON residence.guest_id=guest.id
                INNER JOIN type_housing ON residence.typeHousing_id=type_housing.id
                INNER JOIN distance ON residence.distance_id=distance.id
                INNER JOIN ad_owner ON residence.adOwner_id=ad_owner.id
                WHERE residence.id=:productId AND _adminStatusPublication=2 LIMIT 1";
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