$(function(){
    const formPersonMail = $('#person-mail');
    const formPersonDescription = $('#person-description');
    const formTextInfo=$(".info-text");
    const formTextInfo2=$(".info-text2");

    formPersonMail.on("keyup",function(){
        var profile=check_personMail($(this).val());
        $(this).val(profile.mail);
        if(profile.boolError===false){
            formPersonMail.css('backgroundColor',  profile.color);
            formTextInfo.html('');
        }
    });

    $("#password-reset1").on("click",function(){
        var profile=check_personMail(formPersonMail.val());
        formPersonMail.css('backgroundColor',  profile.color);
        if(profile.textError){
            formTextInfo.html(profile.textError);
        }else{
            formTextInfo.html('');
            $.get('app/password-reset1.php',  {personEmail: profile.mail}, function(data) {
                $('#getApp').html(data);
            });
        }
    });

    formPersonDescription.on("keyup",function(){
        var profile=check_personDescription($(this).val());
        $(this).val(profile.description);
        if(profile.boolError===false){
            formPersonDescription.css('backgroundColor',  profile.color);
            formTextInfo2.html('');
        }
    });

    $("#password-reset2").on("click",function(){
        var profile=check_personDescription(formPersonDescription.val());
        formPersonDescription.css('backgroundColor',  profile.color);
        if(profile.textError){
            formTextInfo2.html(profile.textError);
        }else{
            formTextInfo2.html('');
            $.get('app/password-reset2.php',  {description: profile.description}, function(data) {
                $('#getApp').html(data);
            });
        }
    });
});