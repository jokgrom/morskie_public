<?php
list  ($personId, $boolError, $textError)=$CleanFormPerson->personId($_COOKIE['person_id']);
if($boolError){array_push($statusError, $textError);}

list  ($personIdentification, $boolError, $textError)=$CleanFormPerson->personIdentification($_COOKIE['person_identification']);
if($boolError){array_push($statusError, $textError);}
if(!count($statusError)){
    list  ($boolError, $textError)=$Checks->authenticationPerson($personId, $personIdentification);
    if($boolError){array_push($statusError, $textError);}
}
unset($personIdentification);
if(count($statusError)){
    exit('<div class="modal"><p>Ошибка Аутентификации!</p></div>
                <script  type="text/javascript">
                 setTimeout(function(){
                    window.location.href = "/cabinet/authorization.php";
                }, 500);
                </script>');
}