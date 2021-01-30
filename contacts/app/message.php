<?php
    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/db.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/CleanForm.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/CleanFormPerson.php');

    $statusError=[];
    $CleanFormPerson=new CleanFormPerson();

    $message=$_GET['message'];

    list  ($message["typeMessage"], $boolError, $textError)=$CleanFormPerson->typeMessage($message["typeMessage"]);
    if($boolError){array_push($statusError, $textError);}

    list  ($message["message"], $boolError, $textError)=$CleanFormPerson->message($message["message"]);
    if($boolError){array_push($statusError, $textError);}


    switch($message["typeMessage"]){
        case 0:
            $message["typeMessage"]= 'общий';
            break;
        case 1:
            $message["typeMessage"]= 'рекомендация';
            break;
        case 2:
            $message["typeMessage"]= 'жалоба';
            break;
        case 3:
            $message["typeMessage"]= 'помощь по сайту';
            break;
        case 4:
            $message["typeMessage"]= 'реклама';
            break;
        case 5:
            $message["typeMessage"]= 'сотрудничество';
            break;
    }

    //если ошибок нету то отправляем себе письмо
    if(!count($statusError)){
        //
    }else{
        $statusError=implode(', ', $statusError);
        exit('<div class="modal"><p>Ошибка: '.$statusError.'!</p></div>');
    }