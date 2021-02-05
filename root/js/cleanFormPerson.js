const regular_personPhone=/[^\d]/g;
const regular_personPassword=/[\W]/;
const regular_personNumber=/[^0-9]/g;
const regular_personMail=/[^a-zA-Z0-9_\-.@]/g;
const regularTest_personMail=/^([A-Za-z0-9_\-.])+@([A-Za-z0-9_\-.])+\.([A-Za-z]{2,4})$/;
const regular_personText=/[^a-zA-Zа-яА-ЯёЁ0-9_ \n\-(){}\[\]\/.,;:@!?#№=+]/g;
const regular_personName_ru=/[^а-яА-ЯёЁ]/g;
const colorError='#fcc', colorNoError='#fff';

function check_personPhone(personPhone){
    personPhone=personPhone.replace(regular_personPhone, '');
    personPhone=personPhone.substr(0, 11);
    // personPhone=parseInt(personPhone);
    if(personPhone[0]==8){
        personPhone=personPhone.substr(1,11);
        personPhone='7'+personPhone;
    }else if(personPhone[0]==9 || personPhone[0]!=7){
        personPhone='7'+personPhone;
    }
    if(personPhone.toString().length==11){
        return {phone: '+'+personPhone, color : colorNoError, boolError: false, textError : ''};
    }else{
        return {phone: '+'+personPhone, color : colorError, boolError: true, textError : 'не корректный номер телефона'};
    }
}

function check_personPassword(personPassword){
    personPassword=personPassword.toLowerCase();
    personPassword=personPassword.replace(regular_personPassword, '');
    personPassword=personPassword.substr(0, 12);
    if(personPassword.toString().length<3){
        return {password: personPassword, color: colorError, boolError: true, textError: 'не корректный пароль'};
    }else{
        return {password: personPassword, color: colorNoError, boolError: false, textError: ''};
    }
}

function check_personPassword2(personPassword, personPassword2){
    personPassword2=personPassword2.toLowerCase();
    personPassword2=personPassword2.replace(regular_personPassword, '');
    personPassword2=personPassword2.substr(0, 12);
    if(personPassword===personPassword2){
        return {password: personPassword2, color: colorNoError, boolError: false, textError: ''};
    }else{
        return {password: personPassword2, color: colorError, boolError: true, textError: 'пароли не совпадают'};
    }
}

function check_personMail(personMail){
    personMail=personMail.replace(regular_personMail,'');
    personMail=personMail.substr(0, 22);
    if(regularTest_personMail.test(personMail)){
        return {mail: personMail, color : colorNoError, boolError: false, textError : ''};
    }else{
        return {mail: personMail, color : colorError, boolError: true, textError : 'не корректная почта'};
    }
}

function check_personDescription(personDescription){
    personDescription=personDescription.replace(regular_personText,'');
    personDescription=personDescription.substr(0, 2000);
    if(personDescription.toString().length<3){
        return {description: personDescription, color : colorError, boolError: true, textError : 'слишком короткий текст'};
    }else{
        return {description: personDescription, color : colorNoError, boolError: false, textError : ''};
    }
}

function check_personName(personName){
    personName=personName.replace(regular_personName_ru, '');
    personName=personName.substr(0, 12);
    if(personName.toString().length<3){
        return {name: personName, color: colorError, boolError: true, textError: 'не корректное имя'};
    }else{
        return {name: personName, color: colorNoError, boolError: false, textError: ''};
    }
}

function check_typeMessage(typeMessage){
    typeMessage=typeMessage.replace(regular_personNumber,'');
    typeMessage=Number.parseInt(typeMessage.substr(0, 3));
    if(isNaN(typeMessage)){typeMessage=0;}
    if(typeMessage<1){
        return {type: typeMessage, color: colorError, boolError: true, textError: 'не корректный тип сообщения'};
    }else{
        return {type: typeMessage, color: colorNoError, boolError: false, textError: ''};
    }
}

function check_Message(message){
    message=message.replace(regular_personText, '');
    message=message.substr(0, 2000);
    if(message.toString().length<3){
        return {message: message, color: colorError, boolError: true, textError: 'короткое сообщение'};
    }else{
        return {message: message, color: colorNoError, boolError: false, textError: ''};
    }
}

