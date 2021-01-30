$(function() {
    const formPersonName = $('#person-name');
    const formPersonPhone = $('#person-phone');
    const formPersonMail = $('#person-mail');
    const formPersonPassword = $('#person-password');
    const formPersonPassword2 = $('#person-password2');
    var statusError=[];

    function check_profileError(){
        var profile;
        profile=check_personName(formPersonName.val());
        formPersonName.css('backgroundColor',  profile.color);
        if(profile.textError){statusError.push(profile.textError+'<br>');}


        profile=check_personPhone(formPersonPhone.val());
        formPersonPhone.css('backgroundColor',  profile.color);
        if(profile.textError){statusError.push(profile.textError+'<br>');}


        profile=check_personMail(formPersonMail.val());
        formPersonMail.css('backgroundColor',  profile.color);
        if(profile.textError){statusError.push(profile.textError+'<br>');}


        if(statusError.length>0){
            $(".info-text").html(statusError);
            statusError = [];
            return true;
        }else{
            $(".info-text").html('');
            statusError = [];
            return false;
        }
    }

    function check_passwordError(){
        var password, password2;
        password=check_personPassword(formPersonPassword.val());
        formPersonPassword.css('backgroundColor',  password.color);
        if(password.textError){statusError.push(password.textError+'<br>');}

        password2=check_personPassword(formPersonPassword2.val());
        formPersonPassword2.css('backgroundColor',  password2.color);
        if(password2.textError){statusError.push(password2.textError+'<br>');}

        if(password.password!==password2.password){
            statusError.push('пароли не совпадают <br>');
            formPersonPassword.css('backgroundColor',  colorError);
            formPersonPassword2.css('backgroundColor',  colorError);
        }

        if(statusError.length>0){
            $(".info-text").html(statusError);
            statusError = [];
            return true;
        }else{
            $(".info-text").html('');
            statusError = [];
            return false;
        }
    }


    formPersonName.on('keyup',function(){
        var profile=check_personName(formPersonName.val());
        formPersonName.val(profile.name);
        if(profile.boolError===false){formPersonName.css('backgroundColor',  profile.color);}
    });

    formPersonPhone.on('keyup',function(){
        var profile=check_personPhone(formPersonPhone.val());
        formPersonPhone.val(profile.phone);
        if(profile.boolError===false){formPersonPhone.css('backgroundColor',  profile.color);}
    });

    formPersonMail.on('keyup',function(){
        var profile=check_personMail(formPersonMail.val());
        formPersonMail.val(profile.mail);
        if(profile.boolError===false){formPersonMail.css('backgroundColor',  profile.color);}
    });

    formPersonPassword.on("keyup",function(){
        var profile=check_personPassword(formPersonPassword.val());
        formPersonPassword.val(profile.password);
        if(profile.boolError===false){
            formPersonPassword.css('backgroundColor',  profile.color);
            formPersonPassword2.css('backgroundColor',  profile.color);
        }
    });

    formPersonPassword2.on("keyup",function(){
        var profile=check_personPassword(formPersonPassword2.val());
        formPersonPassword2.val(profile.password);
        if(profile.boolError===false){
            formPersonPassword.css('backgroundColor',  profile.color);
            formPersonPassword2.css('backgroundColor',  profile.color);
        }
    });


    $("#profile-edit").on('click',function (){
        if(!check_profileError()){
            var person={
                "name" : formPersonName.val(),
                "phone" : formPersonPhone.val(),
                "mail" : formPersonMail.val()
            };
            $.get('app/profileEdit.php',  {formPerson: person}, function(data) {
                $('#getApp').html(data);
            });
        }
    });

    $("#password-edit").on('click',function (){
        if(!check_passwordError()){
            var password={
                "password" : formPersonPassword.val(),
                "password2" : formPersonPassword2.val()
            };
            $.get('app/passwordEdit.php',  {formPassword: password}, function(data) {
                $('#getApp').html(data);
            });
        }
    });
});