<?php
class Landlord extends Person
{
    public function __construct($db){
        $this->db = $db;
    }
    public function checkExistence_person($phone){
        $stmt = $this->db->prepare("SELECT `id` FROM `person` WHERE `phone` = :phone");
        $stmt->execute([':phone' => $phone]);
        return ($stmt->fetch() ? true : false);
    }

    public function registration($phone,$password,$personIp){
        $time=time();
        $person_identification=md5(md5($time+(int)$phone));
        $person_identification=substr($person_identification, 0, 25);
        $person_password_hash=password_hash($password, PASSWORD_DEFAULT);


        $query = "INSERT INTO `person` (`phone`, `password`, `identification`, `date_registration`, `ip`) VALUES (:phone, :password, :identification, $time, :personIp)";
        $params =[':phone' => $phone,
                ':password' => $person_password_hash,
                ':identification' => $person_identification,
                ':personIp' => $personIp];
        $stmt = $this->db->prepare($query);
        if($stmt->execute($params)){
            setcookie('person_id',$this->db->lastInsertId(),time() + (3600*24*365),'/');
            setcookie('person_identification',$person_identification,time() + (3600*24*365),'/');
            return true;
        }else{
            return false;
        }
    }

    public function authorization($phone, $password){
        $query="SELECT `id`, `password`, `identification` FROM `person` WHERE `phone` = :phone LIMIT 1";
        $params = [':phone' => $phone];
        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        if($cell = $stmt->fetch()){
            if(password_verify($password, $cell['password'])){
                setcookie('person_id',$cell['id'],time() + (3600*24*365),'/');
                setcookie('person_identification',$cell['identification'],time() + (3600*24*365),'/');
                return true;
            }
        }
        return false;
    }

    public function updateInfo($person){
        $query="SELECT phone, password, identification, ip, name, mail FROM person WHERE id = :personId LIMIT 1";
        $params = [':personId' => $person['id']];
        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        $cell = $stmt->fetch();


        //подготовка ip
        $clientIp  = @$_SERVER['HTTP_CLIENT_IP'];
        $forwardIp = @$_SERVER['HTTP_X_FORWARDED_FOR'];
        $remoteIp  = @$_SERVER['REMOTE_ADDR'];
        if(filter_var($clientIp, FILTER_VALIDATE_IP)) $personIp = $clientIp;
        elseif(filter_var($forwardIp, FILTER_VALIDATE_IP)) $personIp = $forwardIp;
        else $personIp = $remoteIp;


        $time=time();
        $query_insert = "INSERT INTO person_edit (`person_id`, `phone`, `identification`, `date_edit`, `ip`, `name`, `mail`)
                        VALUES (:personId, $cell[phone],  '$cell[identification]', $time, '$personIp', '$cell[name]', '$cell[mail]')";
        $stmt_insert = $this->db->prepare($query_insert);
        if($stmt_insert->execute($params)){
            $query_update= "UPDATE person SET name = :name, phone = :phone, mail = :mail  WHERE id=:personId LIMIT 1";
            $params_update =	[':name' => $person['name'],
                ':phone' => $person['phone'],
                ':mail' => $person['mail'],
                ':personId' => $person['id']];
            $stmt_update = $this->db->prepare($query_update);
            return $stmt_update->execute($params_update);
        }
    }

    public function updatePassword($person){
        $query="SELECT password FROM person WHERE id = :personId LIMIT 1";
        $params = [':personId' => $person['id']];
        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        $cell = $stmt->fetch();


        //подготовка ip
        $clientIp  = @$_SERVER['HTTP_CLIENT_IP'];
        $forwardIp = @$_SERVER['HTTP_X_FORWARDED_FOR'];
        $remoteIp  = @$_SERVER['REMOTE_ADDR'];
        if(filter_var($clientIp, FILTER_VALIDATE_IP)) $personIp = $clientIp;
        elseif(filter_var($forwardIp, FILTER_VALIDATE_IP)) $personIp = $forwardIp;
        else $personIp = $remoteIp;

        $time=time();
        $query_insert = "INSERT INTO person_edit (`person_id`, `password`, `date_edit`, `ip`)
                        VALUES (:personId, :password, $time, '$personIp')";
        $params_insert = [':password' => $cell['password'], ':personId' => $person['id']];
        $stmt_insert = $this->db->prepare($query_insert);
        if($stmt_insert->execute($params_insert)){
            $newPassword=password_hash($person['password'], PASSWORD_DEFAULT);
            $query_update= "UPDATE person SET password = :newPassword  WHERE id=:personId LIMIT 1";
            $params_update =[':newPassword' => $newPassword,
                ':personId' => $person['id']];
            $stmt = $this->db->prepare($query_update);
            return $stmt->execute($params_update);
        }
    }


    public function passwordReset(){

    }


    public function getInfo($personId){
        $query="SELECT name, phone, mail, password FROM person WHERE id = :personId LIMIT 1";
        $params = [':personId' => $personId];
        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        if($cell = $stmt->fetch()){
            $this->name=$cell['name'];
            $this->phone=$cell['phone'];
            $this->mail=$cell['mail'];
            $this->password=$cell['password'];
        }
    }
}