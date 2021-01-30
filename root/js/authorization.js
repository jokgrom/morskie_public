$(function(){
    const formPersonPhone = $('#person-phone');
    const formPersonPassword = $('#person-password');
    var statusError=[];

    function check_authorizationError(){
        var profile;
        profile=check_personPhone(formPersonPhone.val());
        formPersonPhone.css('backgroundColor',  profile.color);
        if(profile.textError){statusError.push(profile.textError+'<br>');}

        profile=check_personPassword(formPersonPassword.val());
        formPersonPassword.css('backgroundColor',  profile.color);
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

    $("#authorization").on("click",function(){
        if(!check_authorizationError()){
            var person={
                "phone" : formPersonPhone.val(),
                "password" : formPersonPassword.val()
            };
            $.get('app/authorization.php',  {person}, function(data) {
                $('#getApp').html(data);
            });
        }
    });
});