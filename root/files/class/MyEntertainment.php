<?php
class MyEntertainment{

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
//            self::_mail('new_entertainment', $personId);
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

}