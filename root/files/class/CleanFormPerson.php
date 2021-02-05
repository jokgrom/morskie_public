<?php


class CleanFormPerson extends CleanForm
{

    function personId($personId){
        $personId=parent::clean($personId);
        $personId=preg_replace('/[^0-9]/','', $personId);
        $personId=mb_substr($personId, 0, 5);
        $personId=(int)($personId);
        if($personId<1){
            return array ($personId, true, 'ошибка Аутентификации№1');
        }else{
            return array ($personId, false, '');
        }
    }

    function personIdentification($personIdentification){
        $personIdentification=parent::clean($personIdentification);
        $personIdentification=preg_replace('/[\W]/','', $personIdentification);
        $personIdentification=mb_substr($personIdentification, 0, 25);
        if(mb_strlen($personIdentification, 'utf8')<3){
            return array ($personIdentification, true, 'ошибка Аутентификации№2');
        }else{
            return array ($personIdentification, false, '');
        }
    }

    function name($name){
        $name=parent::clean($name);
        $name=preg_replace('/[^а-яА-ЯёЁ]/u','', $name);
        $name=mb_substr($name, 0, 12);
        if(mb_strlen($name, 'utf8')<3){
            return array ($name, true, 'не корректное имя');
        }else{
            return array ($name, false, '');
        }
    }

    public function phone($phone){
        $phone=parent::clean($phone);
        $phone=preg_replace('/[^\d]/','', $phone);
        $phone=mb_substr($phone, 0, 11);
        if($phone[0]=='8'){
            $phone[0]='7';
        }elseif($phone[0]=='9' OR $phone[0]!='7'){
            $phone='7'.$phone;
        }
        $phoneLength=mb_strlen($phone, 'utf8');
        if($phoneLength==11){
            return array ($phone, false, '');
        }else{
            return array ($phone, true, 'не корректный номер телефона');
        }
    }

    function password($password){
        $password=parent::clean($password);
        $password=strtolower($password);
        $password=preg_replace('/[\W]/','', $password);
        $password=mb_substr($password, 0, 12);
        if(mb_strlen($password, 'utf8')<3){
            return array ($password, true, 'не корректный пароль');
        }else{
            return array ($password, false, '');
        }
    }

    function mail($email){
        $email=parent::clean($email);
        $email=preg_replace('/[^a-zA-Z0-9_\-\.@]/','', $email);
        $email=mb_substr($email, 0, 22);
        if(filter_var($email, FILTER_VALIDATE_EMAIL)){
            return array ($email, false, '');
        }else{
            return array ($email, true, 'не корректная почта');
        }
    }

    function description($description){
        $description=parent::clean($description);
        $description=preg_replace('/[^a-zA-Zа-яА-ЯёЁ0-9_ \n\-(){}\[\]\/.,;:@!?#№=+]/u','', $description);
        $description=mb_substr($description, 0, 2000);
        if(mb_strlen($description, 'utf8')<3){
            return array ($description, true, 'не корректное описание');
        }else{
            return array ($description, false, '');
        }
    }

    function typeMessage($typeMessage){
        $typeMessage=parent::clean($typeMessage);
        $typeMessage=preg_replace('/[^0-9]/','', $typeMessage);
        $typeMessage=mb_substr($typeMessage, 0, 5);
        $typeMessage=(int)($typeMessage);
        if($typeMessage<1){
            return array ($typeMessage, true, 'не корректный тип сообщения');
        }else{
            return array ($typeMessage, false, '');
        }
    }

    function message($message){
        $message=parent::clean($message);
        $message=preg_replace('/[^a-zA-Zа-яА-ЯёЁ0-9_ \n\-(){}\[\]\/.,;:@!?#№=+]/u','', $message);
        $message=mb_substr($message, 0, 2000);
        if(mb_strlen($message, 'utf8')<3){
            return array ($message, true, 'короткое сообщение');
        }else{
            return array ($message, false, '');
        }
    }


}