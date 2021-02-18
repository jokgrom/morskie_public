<?php

    class TemplateProduct {
        protected $db;

        function __construct($db=''){
            $this->db = $db;
        }

        static public function photoWH_size($w=1, $h=1){
            if($h>=$w){
                $w=$w*(200/$h);
                $h=200;
            }else{
                $h=$h*(200/$w);
                $w=200;
            }
            return array($w, $h);
        }

        public function title($value='') {
             return '<input type="text" id="product-title" class="product-title" placeholder="Название объявления" value="'.$value.'">';
        }

        public function city($value=''){
            $query = "SELECT id, title FROM city WHERE parent_city_id IS NULL ORDER BY title";
            $data =$this->db->query($query);
            $listCity=($value!='' ? '<option value="0">Город</option>' : '<option selected value="0">Город</option>');
            if(is_object($data)){
                foreach($data  as $cell) {
                    $listCity.=($value==$cell['id'] ? "<option selected value='$cell[id]'>$cell[title]</option>" :
                        "<option value='$cell[id]'>$cell[title]</option>");
                }
            }
            return '<select class="filter filter-city">'.$listCity.'</select>';
        }

        function suburb($city=0, $suburb=''){
            $query="SELECT id, title FROM city WHERE parent_city_id = :city ORDER BY title";
            $stmt = $this->db->prepare($query);
            $stmt->execute([':city' => $city]);
            $listSuburb=($suburb!='' ? '<option value="0">Пригород</option>' : '<option selected value="0">Пригород</option>');
            while($cell = $stmt->fetch()){
                $listSuburb.=($suburb==$cell['id'] ? "<option selected value='$cell[id]'>$cell[title]</option>" :
                    "<option value='$cell[id]'>$cell[title]</option>");
            }
            return '<select class="filter filter-suburb">'.$listSuburb.'</select>';
        }

        function guest($value=''){
            $query = "SELECT id, title FROM guest";
            $data =$this->db->query($query);
            $listGuest=($value!='' ? '<option value="0">Вместительность номера</option>' : '<option selected value="0">Вместительность номера</option>');
            if(is_object($data)){
                foreach($data  as $cell) {
                    $listGuest.=($value==$cell['id'] ? "<option selected value='$cell[id]'>$cell[title]</option>" :
                        "<option value='$cell[id]'>$cell[title]</option>");
                }
            }
            return '<select class="filter filter-guest">'.$listGuest.'</select>';
        }

        function typeHousing($value=''){
            $query = "SELECT id, title FROM type_housing";
            $data =$this->db->query($query);
            $listTypeHousing=($value!='' ? '<option value="0">Тип жилья</option>' : '<option selected value="0">Тип жилья</option>');
            if(is_object($data)){
                foreach($data  as $cell) {
                    $listTypeHousing.=($value==$cell['id'] ? "<option selected value='$cell[id]'>$cell[title]</option>" :
                        "<option value='$cell[id]'>$cell[title]</option>");
                }
            }
            return '<select class="filter filter-typeHousing">'.$listTypeHousing.'</select>';
        }

        function distance($value=''){
            $query = "SELECT id, title FROM distance";
            $data =$this->db->query($query);
            $listDistance=($value!='' ? '<option value="0">До моря</option>' : '<option selected value="0">До моря</option>');
            if(is_object($data)){
                foreach($data  as $cell) {
                    $listDistance.=($value==$cell['id'] ? "<option selected value='$cell[id]'>$cell[title]</option>" :
                        "<option value='$cell[id]'>$cell[title]</option>");
                }
            }
            return '<select class="filter filter-distance">'.$listDistance.'</select>';
        }

        function adOwner($value=''){
            $query = "SELECT id, title FROM ad_owner";
            $data =$this->db->query($query);
            $listAdOwner=($value!='' ? '<option value="0">Разместил</option>' : '<option selected value="0">Разместил</option>');
            if(is_object($data)){
                foreach($data  as $cell) {
                    $listAdOwner.=($value==$cell['id'] ? "<option selected value='$cell[id]'>$cell[title]</option>" :
                        "<option value='$cell[id]'>$cell[title]</option>");
                }
            }
            return '<select class="filter filter-adOwner">'.$listAdOwner.'</select>';
        }

        function conveniences($value=''){
            $value_array=($value!='' ? json_decode($value) : [] );
            $query_heading = "SELECT id,title FROM conveniences WHERE parent_convenience_id IS NULL ORDER BY priority";
            $data_heading =$this->db->query($query_heading);
            $listConveniences='';
            if(is_object($data_heading)) {
                foreach ($data_heading as $cell_heading) {
                    $listConveniences.='<p class="strong">'.$cell_heading["title"].'</p>';
                    $query = "SELECT id,title FROM conveniences WHERE parent_convenience_id=$cell_heading[id] ORDER BY title";
                    $data =$this->db->query($query);
                    if(is_object($data)) {
                        foreach ($data as $cell) {
                            if (in_array($cell['id'], $value_array)) {
                                $listConveniences.="<li><label><input type='checkbox' class='filter-check' checked value='$cell[id]'> $cell[title]</label></li>";
                            }else{
                                $listConveniences.="<li><label><input type='checkbox' class='filter-check' value='$cell[id]'> $cell[title]</label></li>";
                            }
                        }
                    }
                }
            }
            return '<ul class="cell">'.$listConveniences.'</ul>';
        }

        function conveniences2($value=''){
            $value_array=($value!='' ? json_decode($value) : [] );

            $listConveniences='';
                $query = "SELECT id,title FROM conveniences ORDER BY title";
                $data =$this->db->query($query);
                if(is_object($data)) {
                    foreach ($data as $cell) {
                        if (in_array($cell['id'], $value_array)) {
                            $listConveniences.="<li class='cell'>$cell[title]</li>";
                        }
                    }
                }
            return '<ul class="product-description">'.$listConveniences.'</ul>';
        }
        function getListConveniencesBox($values=[]){
            $query_heading = "SELECT id,title FROM conveniences WHERE parent_convenience_id IS NULL ORDER BY priority";
            $data_heading =$this->db->query($query_heading);
            $listConveniences='';
            if(is_object($data_heading)) {
                foreach ($data_heading as $cell_heading) {
                    $listConveniences.='</ul><ul class="cell"><p class="strong">'.$cell_heading["title"].'</p>';
                    $query = "SELECT id,title FROM conveniences WHERE parent_convenience_id=$cell_heading[id] ORDER BY title";
                    $data =$this->db->query($query);
                    if(is_object($data)) {
                        foreach ($data as $cell) {
                            if (in_array($cell['id'], $values)) {
                                $listConveniences.="<li><label><input type='checkbox' class='filter-check' checked value='$cell[id]'> $cell[title]</label></li>";
                            }else{
                                $listConveniences.="<li><label><input type='checkbox' class='filter-check' value='$cell[id]'> $cell[title]</label></li>";
                            }
                        }
                    }
                }
            }
            return substr($listConveniences.'</ul>', 5);
        }

        public function rules($value='') {
            return '<textarea id="product-rules" class="form-textarea product-rules">'.$value.'</textarea>';
        }

        public function description($value="") {
            return '<textarea id="product-description" class="form-textarea product-description">'.$value.'</textarea>';
        }

        public function address($address='', $addressLatitude='', $addressLongitude='') {
            return '<input type="text" placeholder="город, село/посёлок, улица, дом" id="product-address" class="form-address product-address" addressLatitude="'.$addressLatitude.'" addressLongitude="'.$addressLongitude.'" address="'.$address.'" value="'.$address.'">
                    <input type="button" value="найти" class="form-button">';
        }

        public function map($address='', $addressLatitude='', $addressLongitude=''){
            if($addressLatitude=='' OR $addressLongitude=='' OR $address==''){
                $addressLatitude='44.01';
                $addressLongitude='37.01';
                $address='морские пути';
            }
            return "<div id='mapbox' class='mapbox'>
                    <div id='map' class='map'></div>
                    <script type='text/javascript'>
                        var map;
                        DG.then(function () {
                            map = DG.map('map', {
                                center: [$addressLatitude, $addressLongitude],
                                zoom: 13
                            });
                            DG.marker([$addressLatitude, $addressLongitude]).addTo(map).bindPopup('$address');
                        });
                    </script>
                </div>";
        }


        public function price($value='') {
            $value_array=($value!='' ? json_decode($value) : [] );
            $newArray=[];
            if(is_object($value_array)) {
                foreach ($value_array as $key){
                    array_push($newArray, $key);
                }
            }

            return '<ul>
                    <li><label><input type="text" class="product-price" value="'.$newArray[0].'"> январь</label></li>
                    <li><label><input type="text" class="product-price" value="'.$newArray[1].'"> февраль</label></li>
                    <li><label><input type="text" class="product-price" value="'.$newArray[2].'"> март</label></li>
                    <li><label><input type="text" class="product-price" value="'.$newArray[3].'"> апрель</label></li>
                    <li><label><input type="text" class="product-price" value="'.$newArray[4].'"> май</label></li>
                    <li><label><input type="text" class="product-price" value="'.$newArray[5].'"> июнь</label></li>
                    <li><label><input type="text" class="product-price" value="'.$newArray[6].'"> июль</label></li>
                    <li><label><input type="text" class="product-price" value="'.$newArray[7].'"> август</label></li>
                    <li><label><input type="text" class="product-price" value="'.$newArray[8].'"> сентябрь</label></li>
                    <li><label><input type="text" class="product-price" value="'.$newArray[9].'"> октябрь</label></li>
                    <li><label><input type="text" class="product-price" value="'.$newArray[10].'"> ноябрь</label></li>
                    <li><label><input type="text" class="product-price" value="'.$newArray[11].'"> декабрь</label></li>
                </ul>';
        }
        public function priceEntertainment($value='') {
            return '<textarea id="product-price" class="form-textarea productEntertainment-price">'.$value.'</textarea>';
        }

        public function contacts($value='') {
            return '<textarea id="product-contacts" class="form-textarea product-contacts">'.$value.'</textarea>';
        }

        public function oldPhotoBox($personId, $productId, $UrlAdminStatus){
            $photoContent='';
            switch ($UrlAdminStatus){
                case 1:
                    $query = "SELECT id, name, sizeWidth, sizeHeight, path FROM photo_residence WHERE residence_id=:productId AND person_id=:personId ORDER BY priority";
                    break;
                case 3:
                    $query = "SELECT id, name, sizeWidth, sizeHeight, path FROM photo_residence WHERE residence_id=:productId AND person_id=:personId AND _adminStatusPublication!=1 ORDER BY priority";
                    break;
                default:
                    $query = "SELECT id, name, sizeWidth, sizeHeight, path FROM photo_residence WHERE residence_id=:productId AND person_id=:personId AND _adminStatusPublication=2 ORDER BY priority";
                    break;
            }

            $params =	[':personId' => $personId,
                ':productId' => $productId];
            $stmt = $this->db->prepare($query);
            $stmt->execute($params);
            while($cell =  $stmt->fetch()){
                list ($sizeWidth, $sizeHeight)=self::photoWH_size($cell["sizeWidth"], $cell["sizeHeight"]);
                $photoContent.= '<div class="oldBox boxImg" id="oldBox'.$cell["id"].'" draggable="true">
                        <img src="/photo/'.$personId.'/'.$productId.'/'. $cell["name"].'" draggable="true" width="'.$sizeWidth.'" height="'.$sizeHeight.'" class="dragImg img oldImg" id="oldImg'.$cell["id"].'" alt="старое фото">
                        <span id="delete'.$cell["id"].'" class="deletePhoto">x</span>
                    </div>';
            }
            return $photoContent;
        }

        public function oldPhotoEntertainmentBox($personId, $productId, $UrlAdminStatus){
            $photoContent='';
            switch ($UrlAdminStatus){
                case 1:
                    $query = "SELECT id, name, sizeWidth, sizeHeight, path FROM photo_entertainment WHERE product_id=:productId AND person_id=:personId ORDER BY priority";
                    break;
                case 3:
                    $query = "SELECT id, name, sizeWidth, sizeHeight, path FROM photo_entertainment WHERE product_id=:productId AND person_id=:personId AND _adminStatusPublication!=1 ORDER BY priority";
                    break;
                default:
                    $query = "SELECT id, name, sizeWidth, sizeHeight, path FROM photo_entertainment WHERE product_id=:productId AND person_id=:personId AND _adminStatusPublication=2 ORDER BY priority";
                    break;
            }

            $params =	[':personId' => $personId,
                ':productId' => $productId];
            $stmt = $this->db->prepare($query);
            $stmt->execute($params);
            while($cell =  $stmt->fetch()){
                list ($sizeWidth, $sizeHeight)=self::photoWH_size($cell["sizeWidth"], $cell["sizeHeight"]);
                $photoContent.= '<div class="oldBox boxImg" id="oldBox'.$cell["id"].'" draggable="true">
                        <img src="/photoEntertainment/'.$personId.'/'.$productId.'/'. $cell["name"].'" draggable="true" width="'.$sizeWidth.'" height="'.$sizeHeight.'" class="dragImg img oldImg" id="oldImg'.$cell["id"].'" alt="старое фото">
                        <span id="delete'.$cell["id"].'" class="deletePhoto">x</span>
                    </div>';
            }
            return $photoContent;
        }


        public function getPhotoBox($productId, $UrlAdminStatus){
            //совмещаем старые и вновь добавленные фотографии
            $photoContent='';
            switch ($UrlAdminStatus){
                case 1:
                    $query = "SELECT name, path FROM photo_residence WHERE residence_id=:productId ORDER BY priority";
                    break;
                case 3:
                    $query = "SELECT name, path FROM photo_residence WHERE residence_id=:productId AND _adminStatusPublication !=1 ORDER BY priority";
                    break;
                default:
                    $query = "SELECT name, path FROM photo_residence WHERE residence_id=:productId AND _adminStatusPublication =2 ORDER BY priority";
            }
            $params =	[':productId' => $productId];
            $stmt = $this->db->prepare($query);
            $stmt->execute($params);
            while($cell =  $stmt->fetch()){
                $photoContent.='<img src="'.$cell["path"].$cell["name"].'">';
            }
            return $photoContent;
        }


        public function getPhotoEntertainmentBox($productId, $UrlAdminStatus){
            //совмещаем старые и вновь добавленные фотографии
            $photoContent='';
            switch ($UrlAdminStatus){
                case 1:
                    $query = "SELECT name, path FROM photo_entertainment WHERE product_id=:productId ORDER BY priority";
                    break;
                case 3:
                    $query = "SELECT name, path FROM photo_entertainment WHERE product_id=:productId AND _adminStatusPublication !=1 ORDER BY priority";
                    break;
                default:
                    $query = "SELECT name, path FROM photo_entertainment WHERE product_id=:productId AND _adminStatusPublication =2 ORDER BY priority";
            }
            $params =	[':productId' => $productId];
            $stmt = $this->db->prepare($query);
            $stmt->execute($params);
            while($cell =  $stmt->fetch()){
                $photoContent.='<img src="'.$cell["path"].$cell["name"].'">';
            }
            return $photoContent;
        }

        public function priceMonth($priceMonth){
            $selected=[];
            switch ($priceMonth){
                case '1':
                    $selected[1]='selected';
                    break;
                case '2':
                    $selected[2]='selected';
                    break;
                case '3':
                    $selected[3]='selected';
                    break;
                case '4':
                    $selected[4]='selected';
                    break;
                case '5':
                    $selected[5]='selected';
                    break;
                case '6':
                    $selected[6]='selected';
                    break;
                case '7':
                    $selected[7]='selected';
                    break;
                case '8':
                    $selected[8]='selected';
                    break;
                case '9':
                    $selected[9]='selected';
                    break;
                case '10':
                    $selected[10]='selected';
                    break;
                case '11':
                    $selected[11]='selected';
                    break;
                case '12':
                    $selected[12]='selected';
                    break;
                default:
                    $selected[0]='selected';

            }
            return '<select class="filter filter-price">
                <option '.$selected[0].' value="0">Цена в</option>
                <option '.$selected[1].' value="1">в январе</option>
                <option '.$selected[2].' value="2">в феврале</option>
                <option '.$selected[3].' value="3">в марте</option>
                <option '.$selected[4].' value="4">в апреле</option>
                <option '.$selected[5].' value="5">в мае</option>
                <option '.$selected[6].' value="6">в июне</option>
                <option '.$selected[7].' value="7">в июле</option>
                <option '.$selected[8].' value="8">в августе</option>
                <option '.$selected[9].' value="9">в сентябре</option>
                <option '.$selected[10].' value="10">в октябре</option>
                <option '.$selected[11].' value="11">в ноябре</option>
                <option '.$selected[12].' value="12">в декабре</option>
            </select>';
        }

        public function sort($sort=''){
            $selected=[];
            switch ($sort){
                case '1':
                    $selected[1]='selected';
                    break;
                case '2':
                    $selected[2]='selected';
                    break;
                case '3':
                    $selected[3]='selected';
                    break;
                default:
                    $selected[0]='selected';
            }
            return '<select class="filter filter-sort">
                <option '.$selected[0].' value="0">сортировать</option>
                <option '.$selected[1].' value="0">по умолчанию</option>
                <option '.$selected[2].' value="2">по возростанию цены</option>
                <option '.$selected[3].' value="3">по убыванию цены</option>
            </select>';
        }

        function suburb_list($city=0){
            $query="SELECT id, title FROM city WHERE parent_city_id = :city ORDER BY title";
            $stmt = $this->db->prepare($query);
            $stmt->execute([':city' => $city]);
            while($cell = $stmt->fetch()){
                $listSuburb.="<li><a href='/residences/?city=$city&suburb=$cell[id]'>$cell[title]</a></li>";
            }
            return '<ul>'.$listSuburb.'</ul>';
        }


        function html_h1($product){
            $querySelect=[];
            $queryFrom=[];
            $queryWhere=[];
            $h1=[];
            if($product["city"]!=''){
                array_push($querySelect, 'city.title AS `city`');
                array_push($queryFrom, 'city');
                array_push($queryWhere, 'city.id='.$product['city']);
                $h1['city']='г. ';
            }
            if($product["suburb"]!=''){
                array_push($querySelect, 'city2.title AS `suburb`');
                array_push($queryFrom, 'city AS `city2`');
                array_push($queryWhere, 'city2.id='.$product['suburb']);
            }
            if($product["guest"]!=''){
                array_push($querySelect, 'guest.title AS `guest`');
                array_push($queryFrom, 'guest');
                array_push($queryWhere, 'guest.id='.$product['guest']);
                $h1['guest']='вместительность номера: ';
            }
            if($product["typeHousing"]!=''){
                array_push($querySelect, 'type_housing.title AS `typeHousing`');
                array_push($queryFrom, 'type_housing');
                array_push($queryWhere, 'type_housing.id='.$product['typeHousing']);
                $h1['typeHousing']='тип жилья: ';
            }
            if($product["distance"]!=''){
                array_push($querySelect, 'distance.title AS `distance`');
                array_push($queryFrom, 'distance');
                array_push($queryWhere, 'distance.id='.$product['distance']);
                $h1['distance']='до моря: ';
            }
            if($product["priceFrom"]!=''){
                $h1['priceFrom']='цена от: '.$product["priceFrom"];
            }
            if($product["priceTo"]!=''){
                $h1['priceTo']='цена до: '.$product["priceTo"];
            }
            if($product["priceMonth"]!=''){
                switch ($product["priceMonth"]){
                    case '1':
                        $h1['priceMonth']='цена: в январе';
                        break;
                    case '2':
                        $h1['priceMonth']='цена: в феврале';
                        break;
                    case '3':
                        $h1['priceMonth']='цена: в марте';
                        break;
                    case '4':
                        $h1['priceMonth']='цена: в апреле';
                        break;
                    case '5':
                        $h1['priceMonth']='цена: в мае';
                        break;
                    case '6':
                        $h1['priceMonth']='цена: в июне';
                        break;
                    case '7':
                        $h1['priceMonth']='цена: в июле';
                        break;
                    case '8':
                        $h1['priceMonth']='цена: в августе';
                        break;
                    case '9':
                        $h1['priceMonth']='цена: в сентябре';
                        break;
                    case '10':
                        $h1['priceMonth']='цена: в октябре';
                        break;
                    case '11':
                        $h1['priceMonth']='цена: в ноябре';
                        break;
                    case '12':
                        $h1['priceMonth']='цена: в декабре';
                        break;
                }
            }
            if($product["adOwner"]!=''){
                array_push($querySelect, 'ad_owner.title AS `adOwner`');
                array_push($queryFrom, 'ad_owner');
                array_push($queryWhere, 'ad_owner.id='.$product['adOwner']);
                $h1['adOwner']='владелец объявления: ';
            }
            if($product["sort"]!=''){
                if($product["sort"]==2){$h1['sort']='сортировать: по возростанию цены';}
                if($product["sort"]==3){$h1['sort']='сортировать: по убыванию цены';}
            }
            $h1_city=$h1_suburb=$h1_guest=$h1_typeHousing=$h1_distance=$h1_priceFrom=$h1_priceTo=$h1_priceMonth=$h1_adOwner=$h1_sort='';
            if(count($querySelect)>=1){
                $querySelect=join($querySelect, ' , ');
                $queryFrom=join($queryFrom, ' , ');
                $queryWhere=join($queryWhere, ' AND ');

                $query="SELECT $querySelect FROM $queryFrom WHERE $queryWhere";
                $data =$this->db->query($query);
                if(is_object($data)){
                    foreach($data  as $cell) {
                        $h1_city=($h1['city'] ? $h1['city'].$cell['city'].',' : '');
                        $h1_suburb=($cell['suburb'] ? $h1['suburb'].$cell['suburb'].',' : '');
                        $h1_guest=($h1['guest'] ? $h1['guest'].$cell['guest'].',' : '');
                        $h1_typeHousing=($h1['typeHousing'] ? $h1['typeHousing'].$cell['typeHousing'].',' : '');
                        $h1_distance=($h1['distance'] ? $h1['distance'].$cell['distance'].',' : '');
                        $h1_priceFrom=($h1['priceFrom'] ? $h1['priceFrom'].$cell['priceFrom'].',' : '');
                        $h1_priceTo=($h1['priceTo'] ? $h1['priceTo'].$cell['priceTo'].',' : '');
                        $h1_priceMonth=($h1['priceMonth'] ? $h1['priceMonth'].$cell['priceMonth'].',' : '');
                        $h1_adOwner=($h1['adOwner'] ? $h1['adOwner'].$cell['adOwner'].',' : '');
                        $h1_sort=($h1['sort'] ? $h1['sort'].$cell['sort'].',' : '');
                    }
                }
            }
            return "Поиск жилья у моря <span class='h1_city'>$h1_city</span> <span class='h1_suburb'>$h1_suburb</span> ".
                "<span class='h1_guest'>$h1_guest</span> <span class='h1_typeHousing'>$h1_typeHousing</span> <span class='h1_distance'>$h1_distance</span> ".
                "<noindex><span class='h1_priceFrom'>$h1_priceFrom</span> <span class='h1_priceTo'>$h1_priceTo</span></noindex> <span class='h1_priceMonth'>$h1_priceMonth</span> ".
                "<span class='h1_adOwner'>$h1_adOwner</span> <span class='h1_sort'>$h1_sort</span>";
        }

        function mapSearch($city_id){
            $query = "SELECT title ,addressLatitude, addressLongitude FROM city WHERE id = :city_id LIMIT 1";
            $stmt = $this->db->prepare($query);
            $stmt->execute([':city_id' => $city_id]);
            $cell = $stmt->fetch();
            if($cell){
                return self::map($cell['title'], $cell['addressLatitude'],$cell['addressLongitude']);
            }else{
                return false;
            }
        }

        function getListEntertainment($values=''){
            $listEntertainment='';
            $query = "SELECT id,title FROM entertainment_list ORDER BY title";
            $data =$this->db->query($query);
            if(is_object($data)) {
                foreach ($data as $cell) {
                    if ($cell['id']==$values) {
                        $listEntertainment.="<li><label><input type='radio' name='entertainment' class='filter-check' checked value='$cell[id]'> $cell[title]</label></li>";
                    }else{
                        $listEntertainment.="<li><label><input type='radio' name='entertainment' class='filter-check' value='$cell[id]'> $cell[title]</label></li>";
                    }
                }
            }
            return '<ul class="cell">'.$listEntertainment.'</ul>';
        }

    }