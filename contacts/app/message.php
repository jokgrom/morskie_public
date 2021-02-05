<?php
    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/db.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/CleanForm.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/CleanFormPerson.php');

    $statusError=[];
    $CleanFormPerson=new CleanFormPerson();

    $formMessage=$_GET['message'];

    list  ($typeMessage, $boolError, $textError)=$CleanFormPerson->typeMessage($formMessage["typeMessage"]);
    if($boolError){array_push($statusError, $textError);}

    list  ($message, $boolError, $textError)=$CleanFormPerson->message($formMessage["message"]);
    if($boolError){array_push($statusError, $textError);}
    $message=nl2br($message);

    switch($typeMessage){
        case 0:
            $typeMessage= 'Общий';
            break;
        case 1:
            $typeMessage= 'Рекомендация';
            break;
        case 2:
            $typeMessage= 'Жалоба';
            break;
        case 3:
            $typeMessage= 'Помощь по сайту';
            break;
        case 4:
            $typeMessage= 'Реклама';
            break;
        case 5:
            $typeMessage= 'Сотрудничество';
            break;
        default:
            $typeMessage='Оповещение';
    }

    //если ошибок нету то отправляем себе письмо
    if(!count($statusError)){
        $clientIp  = @$_SERVER['HTTP_CLIENT_IP'];
        $forwardIp = @$_SERVER['HTTP_X_FORWARDED_FOR'];
        $remoteIp  = @$_SERVER['REMOTE_ADDR'];
        if(filter_var($clientIp, FILTER_VALIDATE_IP)) $personIp = $clientIp;
        elseif(filter_var($forwardIp, FILTER_VALIDATE_IP)) $personIp = $forwardIp;
        else $personIp = $remoteIp;

        $date=date("d.m.y G:i");
        $headers = "From: Морские пути <robot@morskie-puti.ru>\r\nContent-type: text/html; charset=utf-8 \r\n";
        $message= '
		 	<html>
			    <head>
		 	   <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		 	        <title>Морские пути - '.$typeMessage.'</title>
		 	    </head>
		 	    <body>
		 	        <p>дата: '. $date .', ip:'.$personIp.'</p>
		 	        <p>'.$message.'</p>
		 	    </body>
		 	</html>';

        if(mail('jokgrom@yandex.ru', $typeMessage, $message, $headers)){
            exit('<div class="modal"><p>Письмо отправленно!</p></div>
                <script  type="text/javascript">
                 $(".info-text").html("Письмо отправленно!");
                </script>');
        }else{
            exit('<div class="modal"><p>Возникли технические не поладки! Воспользуйтесь другим видом связи.</p></div>');
        }
    }else{
        $statusError=implode(', ', $statusError);
        exit('<div class="modal"><p>Ошибка: '.$statusError.'!</p></div>');
    }