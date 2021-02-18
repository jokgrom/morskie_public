<?php


class Entertainment{
    public $title, $city_id, $suburb_id,
        $entertainment, $description, $prices,
        $addressLatitude, $addressLongitude, $address, $contacts;
    public $cityTitle, $suburbTitle;

    public function __construct($db){
        $this->db = $db;
    }

    protected function _getPhoto($productId){
        //совмещаем старые и вновь добавленные фотографии
        $photoContent='';
        $query = "SELECT name, path FROM photo_entertainment WHERE product_id=:productId AND _adminStatusPublication =2";
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
        if($product['title']!=''){array_push($queryWhere, 'entertainment.title LIKE "%'.$product['title'].'%"');}
        if($product['city']!=0){array_push($queryWhere, 'entertainment.city_id='.$product['city']);}
        if($product['suburb']!=0){array_push($queryWhere, 'entertainment.suburb_id='.$product['suburb']);}
        if($product['listEntertainment']!=0){array_push($queryWhere, 'entertainment.entertainment='.$product['listEntertainment']);}

        $queryGroupBy=' GROUP BY entertainment.id';

        $queryCOUNT="SELECT COUNT(DISTINCT entertainment.id) AS `countProduct` FROM entertainment 
                INNER JOIN photo_entertainment ON photo_entertainment.product_id=entertainment.id AND photo_entertainment._adminStatusPublication=2
                WHERE entertainment._adminStatusPublication =2 AND entertainment.publicationStatus_id=2";

        $query = "SELECT entertainment.id AS `entertainmentId`,
                            entertainment._adminStatusPublication AS `entertainment_adminStatusPublication`,
                            publication_status.title AS `publication_statusTitle`,
                            entertainment.person_id AS `entertainmentPerson_id`,
                            entertainment.title AS `entertainmentTitle`,
                            city.title AS `cityTitle`, 
                            city2.title AS `suburbTitle`, 
                            entertainment_list.title AS `entertainment_listTitle`, 
                            entertainment.description AS `entertainDescription`,
                            entertainment.prices AS `entertainmentPrices`, 
                            entertainment.addressLatitude AS `entertainmentAddressLatitude`, 
                            entertainment.addressLongitude AS `entertainmentAddressLongitude`,
                            entertainment.address AS `entertainmentAddress`, 
                            entertainment.contacts AS `entertainmentContacts`
                    FROM entertainment 
                    INNER JOIN city ON entertainment.city_id=city.id
                    INNER JOIN city AS `city2` ON entertainment.suburb_id=city2.id 
                    INNER JOIN entertainment_list ON entertainment_list.id=entertainment.entertainment                        
                    INNER JOIN publication_status ON entertainment.publicationStatus_id=publication_status.id AND entertainment.publicationStatus_id=2
                    INNER JOIN photo_entertainment ON photo_entertainment.product_id=entertainment.id AND photo_entertainment._adminStatusPublication=2 
                    WHERE entertainment._adminStatusPublication =2";

        if(count($queryWhere)>=1){
            $queryWhere=join($queryWhere, ' AND ');
            $query.=' AND '.$queryWhere;
            $queryCOUNT.=' AND '.$queryWhere;
        }

        $maxCountProduct=10;
        if($product['page']!=0){
            $from=$product['page']*$maxCountProduct-$maxCountProduct;
            $queryLimit=' LIMIT '.$from.','.$maxCountProduct;
        }else{
            $queryLimit=' LIMIT 0,'.$maxCountProduct;
        }

        $query.=$queryGroupBy.$queryLimit;
        $data =$this->db->query($query);
        if(is_object($data)){
            foreach($data  as $cell) {
                $photoContent=self::_getPhoto($cell["entertainmentId"]);
                include($_SERVER['DOCUMENT_ROOT'].'/root/files/template/entertainment.php');
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
        $paginationBack=($product['page']-1>=1 ? '<a href="/entertainments/?page='.($product['page']-1).'">← Пред.</a>' :'');
        if($product['page']==0){
            $paginationForward='<a href="/entertainments/?page=2">След. →</a>';
        }else{
            $paginationForward=($product['page']+1<=$countPage ? '<a href="/entertainments/?page='.($product['page']+1).'">След. →</a>' : '');
        }
        $pageFirst=($product['page']-5>1 ? $product['page']-5 : 1);
        $pageLast=($product['page']+5<$countPage ? $product['page']+5 : $countPage);
        $pagination='';
        for($i=$pageFirst; $i<=$pageLast; $i++){
            if($i==$product['page']){
                $pagination.='<a href="/entertainments/?page='.$i.'" class="pagination-main-page">'.$i.'</a>';
            }else{
                $pagination.='<a href="/entertainments/?page='.$i.'">'.$i.'</a></span>';
            }
        }

        $pagination=($countProduct>$maxCountProduct ? $paginationBack.$pagination.$paginationForward : '');
        echo "<div class='pagination'>$pagination</div>";
    }


    public function getInfo($productId){
        $query = "SELECT entertainment.title AS `entertainmentTitle`, 
                    city.title AS `cityTitle`,
                    city2.title AS `suburbTitle`, 
                    entertainment.entertainment AS `entertainment_list`,
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
                INNER JOIN entertainment_list ON entertainment.entertainment=entertainment_list.id
                WHERE entertainment.id=:productId AND _adminStatusPublication=2 LIMIT 1";
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