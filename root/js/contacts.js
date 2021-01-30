$(function() {
    const formTypeMessage=$('#person-typeMessage');
    const formMessage=$('#person-message');
    var statusError=[];

    function check_MessageError(){
        var message;
        message=check_typeMessage(formTypeMessage.val());
        formTypeMessage.css('backgroundColor',  message.color);
        if(message.textError){statusError.push(message.textError+'<br>');}
        console.log(message);

        message=check_Message(formMessage.val());
        formMessage.css('backgroundColor',  message.color);
        if(message.textError){statusError.push(message.textError+'<br>');}

        console.log(message);
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

    formTypeMessage.on("change",function(){
        var message=check_typeMessage(formTypeMessage.val());
        if(message.boolError===false){formTypeMessage.css('backgroundColor',  message.color);}
    });

    formMessage.on("keyup",function(){
        var message=check_Message(formMessage.val());
        formMessage.val(message.message);
        if(message.boolError===false){formMessage.css('backgroundColor',  message.color);}
    });

    $("#GO").on("click",function(){
        if(!check_MessageError()){
            var message={
                "typeMessage" : formTypeMessage.val(),
                "message" : formMessage.val()
            };

            $.get('app/message.php',  {message}, function(data) {
                $('#getApp').html(data);
            });
        }
    });
});