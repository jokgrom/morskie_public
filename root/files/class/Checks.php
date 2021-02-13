<?php


class Checks
{
    public $db;

    public function __construct($db){
        $this->db = $db;
    }

    static function authenticationLord(){
        if($_COOKIE['person_id']!=117 OR $_COOKIE['person_identification']!='237ce737ad12b921957e0c76b'){
            exit('<div class="modal"><p>Ошибка Аутентификации!</p></div>
                <script  type="text/javascript">
                    setTimeout(function(){
                        window.location.href = "/";
                    }, 500);
                </script>');
        }
    }

    function authenticationPerson($personId,$personIdentification){
        $query = "SELECT 1 FROM person WHERE id = :personId AND identification=:personIdentification LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':personId' => $personId, ':personIdentification' => $personIdentification]);
        if($stmt->fetch()){
            return array (false, '');
        }else{
            return array (true, 'ошибка Аутентификации№3');
        }
    }

    function suburbOnCity($city,$suburb){
        $query = "SELECT parent_city_id FROM city WHERE id = :suburb LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':suburb' => $suburb]);
        $cell= $stmt->fetch();

        if($cell[parent_city_id]==$city){
            return array (false, '');
        }else{
            return array (true, 'поле "Пригород" не соответствует полю "Город"');
        }
    }



    function lastResidenceId($personId, $productId){
        $query = "SELECT 1 FROM residence WHERE id = :productId AND person_id=:personId LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':personId' => $personId, ':productId' => $productId]);
        if($stmt->fetch()){
            return array (false, '');
        }else{
            return array (true, 'ошибка Аутентификации объявления');
        }
    }

    function lastEntertainmentId($personId, $productId){
        $query = "SELECT 1 FROM entertainment WHERE id = :productId AND person_id=:personId LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':personId' => $personId, ':productId' => $productId]);
        if($stmt->fetch()){
            return array (false, '');
        }else{
            return array (true, 'ошибка Аутентификации объявления');
        }
    }

    function countResidence($personId){
        $query = "SELECT COUNT(id) AS `countProduct`  FROM residence WHERE person_id=:personId";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':personId' => $personId]);
        if($cell=$stmt->fetch()){
            return $cell['countProduct'];
        }else{
            return 100;
        }
    }

    function countEntertainment($personId){
        $query = "SELECT COUNT(id) AS `countProduct`  FROM entertainment WHERE person_id=:personId";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':personId' => $personId]);
        if($cell=$stmt->fetch()){
            return $cell['countProduct'];
        }else{
            return 100;
        }
    }

    function countPhotoResidence($personId, $productId){
        $query = "SELECT COUNT(id) AS `countPhotoResidence`  FROM photo_residence WHERE person_id=:personId AND residence_id=:productId";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':personId' => $personId, ':productId' => $productId]);
        if($cell=$stmt->fetch()){
            return $cell['countPhotoResidence'];
        }else{
            return 100;
        }
    }

    function countPhotoEntertainment($personId, $productId){
        $query = "SELECT COUNT(id) AS `countPhotoEntertainment`  FROM photo_entertainment WHERE person_id=:personId AND product_id=:productId";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':personId' => $personId, ':productId' => $productId]);
        if($cell=$stmt->fetch()){
            return $cell['countPhotoEntertainment'];
        }else{
            return 100;
        }
    }

    function countPersonRegistration(){
        $time=time()-(24 * 60 * 60);
        $query="SELECT COUNT(id) AS `countRegistration` FROM person WHERE date_registration >$time";
        $data =$this->db->query($query);
        if(is_object($data)){
            foreach($data  as $cell) {
                return $cell['countRegistration'];
            }
        }
        return 0;
    }

    function moderationProduct($productId){
        $query = "SELECT _adminStatusPublication FROM residence WHERE id=:productId";
        $params =	[':productId' => $productId];
        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        $cell=$stmt->fetch();
        if($cell['_adminStatusPublication']!=2){
            return true;
        }else{
            return false;
        }
    }




}