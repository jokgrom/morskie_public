<?php


class CleanFormProduct extends CleanForm
{
    public function id($id){
        $id=parent::clean($id);
        $id=preg_replace('/[^0-9]/','', $id);
        $id=mb_substr($id, 0, 6);
        $id=(int)($id);
        if($id<1){
            return array ($id, true, 'не корректный идентификатор Объявления');
        }else{
            return array ($id, false, '');
        }
    }

    function title($title){
        $title=parent::clean($title);
        $title=preg_replace('/[^a-zA-Zа-яА-ЯёЁ0-9_ \-«»]/u','', $title);
        $title=mb_substr($title, 0, 60);
        if(mb_strlen($title, 'utf8')<3){
            return array ($title, true, 'не заполнено поле "Название объявления"');
        }else{
            return array ($title, false, '');
        }
    }

    public function city($city){
        $city=parent::clean($city);
        $city=preg_replace('/[^0-9]/','', $city);
        $city=mb_substr($city, 0, 6);
        $city=(int)($city);
        if($city<1){
            return array ($city, true, 'не корректный идентификатор поля "Город"');
        }else{
            return array ($city, false, '');
        }
    }

    public function suburb($suburb){
        $suburb=parent::clean($suburb);
        $suburb=preg_replace('/[^0-9]/','', $suburb);
        $suburb=mb_substr($suburb, 0, 6);
        $suburb=(int)($suburb);
        if($suburb<1){
            return array ($suburb, true, 'не корректный идентификатор поля "Пригород"');
        }else{
            return array ($suburb, false, '');
        }
    }

    public function guest($guest){
        $guest=parent::clean($guest);
        $guest=preg_replace('/[^0-9]/','', $guest);
        $guest=mb_substr($guest, 0, 6);
        $guest=(int)($guest);
        if($guest<1){
            return array ($guest, true, 'не корректный идентификатор поля "Вместительность номера"');
        }else{
            return array ($guest, false, '');
        }
    }

    public function typeHousing($typeHousing){
        $typeHousing=parent::clean($typeHousing);
        $typeHousing=preg_replace('/[^0-9]/','', $typeHousing);
        $typeHousing=mb_substr($typeHousing, 0, 6);
        $typeHousing=(int)($typeHousing);
        if($typeHousing<1){
            return array ($typeHousing, true, 'не корректный идентификатор поля "Тип жилья"');
        }else{
            return array ($typeHousing, false, '');
        }
    }

    public function distance($distance){
        $distance=parent::clean($distance);
        $distance=preg_replace('/[^0-9]/','', $distance);
        $distance=mb_substr($distance, 0, 6);
        $distance=(int)($distance);
        if($distance<1){
            return array ($distance, true, 'не корректный идентификатор поля "До моря"');
        }else{
            return array ($distance, false, '');
        }
    }

    public function adOwner($adOwner){
        $adOwner=parent::clean($adOwner);
        $adOwner=preg_replace('/[^0-9]/','', $adOwner);
        $adOwner=mb_substr($adOwner, 0, 6);
        $adOwner=(int)($adOwner);
        if($adOwner<1){
            return array ($adOwner, true, 'не корректный идентификатор поля "Владелец объявления"');
        }else{
            return array ($adOwner, false, '');
        }
    }

    function rules($rules){
        $rules=parent::clean($rules);
        $rules=preg_replace('/[^a-zA-Zа-яА-ЯёЁ0-9_ \n\-(){}\[\]\/.,;:@!?#№=+«»]/u','', $rules);
        $rules=mb_substr($rules, 0, 2000);
        if(mb_strlen($rules, 'utf8')<3){
            return array ($rules, true, 'не заполнено поле "Правила и ограничения"');
        }else{
            return array ($rules, false, '');
        }
    }

    function description($description){
        $description=parent::clean($description);
        $description=preg_replace('/[^a-zA-Zа-яА-ЯёЁ0-9_ \n\-(){}\[\]\/.,;:@!?#№=+«»]/u','', $description);
        $description=mb_substr($description, 0, 2000);
        if(mb_strlen($description, 'utf8')<3){
            return array ($description, true, 'не заполнено поле "Описание"');
        }else{
            return array ($description, false, '');
        }
    }

    function address($address){
        $address=parent::clean($address);
        $address=preg_replace('/[^a-zA-Zа-яА-ЯёЁ0-9_ \-(){}\[\]\/.,;:@!?#№=+«»]/u','', $address);
        $address=mb_substr($address, 0, 2000);
        if(mb_strlen($address, 'utf8')<3){
            return array ($address, true, 'не корректный адрес');
        }else{
            return array ($address, false, '');
        }
    }

    function coordinate($coordinate){
        $coordinate=parent::clean($coordinate);
        $coordinate=preg_replace('/[^0-9,.]/','', $coordinate);
        $coordinate=mb_substr($coordinate, 0, 10);
        $coordinate=(float)($coordinate);
        if($coordinate<35){
            return array ($coordinate, true, 'не верная координата адреса');
        }else{
            return array ($coordinate, false, '');
        }
    }

    function contacts($contacts){
        $contacts=parent::clean($contacts);
        $contacts=preg_replace('/[^a-zA-Zа-яА-ЯёЁ0-9_ \n\-(){}\[\]\/.,;:@!?#№=+«»]/u','', $contacts);
        $contacts=mb_substr($contacts, 0, 2000);
        if(mb_strlen($contacts, 'utf8')<3){
            return array ($contacts, true, 'не заполнено поле "Контакты"');
        }else{
            return array ($contacts, false, '');
        }
    }

    function conveniences($conveniences){
        $conveniences=parent::clean($conveniences);
        $conveniences=preg_replace('/[^0-9]/','', $conveniences);
        $conveniences=mb_substr($conveniences, 0, 3);
        $conveniences=(int)($conveniences);
        if($conveniences<=0){
            return array ($conveniences, true, 'не корректное значение поля "Удобства"');
        }else{
            return array ($conveniences, false, '');
        }
    }

    function price($price){
        $price=parent::clean($price);
        $price=preg_replace('/[^0-9]/','', $price);
        $price=mb_substr($price, 0, 6);
        $price=(int)($price);
        if($price<50 AND $price!=0){
            return array ($price, true, 'не корректная цена');
        }else{
            return array ($price, false, '');
        }
    }


}