$(function(){
    const formPersonPhone = $('#person-phone');
    const formPersonPassword = $('#person-password');
    const formPersonPassword2 = $('#person-password2');
    const formRegistration = $('#registration');
    var statusError=[];


    function check_registrationError(){
        var profile;
        profile=check_personPhone(formPersonPhone.val());
        formPersonPhone.css('backgroundColor',  profile.color);
        if(profile.textError){statusError.push(profile.textError+'<br>');}

        profile=check_personPassword(formPersonPassword.val());
        formPersonPassword.css('backgroundColor',  profile.color);
        if(profile.textError){statusError.push(profile.textError+'<br>');}

        profile=check_personPassword2(formPersonPassword.val(), formPersonPassword2.val());
        formPersonPassword2.css('backgroundColor',  profile.color);
        if(profile.textError){statusError.push(profile.textError+'<br>');}

        if(statusError.length>0){
            $(".info-text").html(statusError);
            statusError = [];
            // A.splice(0,A.length)
            return true;
        }else{
            $(".info-text").html('');
            statusError = [];
            return false;
        }
    }

    formPersonPhone.on("keyup",function(){
        var profile=check_personPhone(formPersonPhone.val());
        formPersonPhone.val(profile.phone);
        if(profile.boolError===false){formPersonPhone.css('backgroundColor',  profile.color);}
    });

    formPersonPassword.on("keyup",function(){
        var profile=check_personPassword(formPersonPassword.val());
        formPersonPassword.val(profile.password);
        if(profile.boolError===false){formPersonPassword.css('backgroundColor',  profile.color);}
    });

    formPersonPassword2.on("keyup",function(){
        var profile=check_personPassword2(formPersonPassword2.val() ,$(this).val());
        formPersonPassword2.val(profile.password);
        if(profile.boolError===false){formPersonPassword2.css('backgroundColor',  profile.color);}
    });


    formRegistration.on("click",function(){
        if(!check_registrationError()){
            var person={
                "phone" : formPersonPhone.val(),
                "password" : formPersonPassword.val(),
                "password2" : formPersonPassword2.val()
            };
            $.get('app/registration.php',  {person}, function(data) {
                $('#getApp').html(data);
            });
        }
    });
});