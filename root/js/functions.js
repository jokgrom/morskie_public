
function get_cookie (cookie_name){
	var results_cookie = document.cookie.match ( '(^|;) ?' + cookie_name + '=([^;]*)(;|$)' );
  	if (results_cookie) return (unescape(results_cookie[2]));
}

function delete_cookie (cookie_name){
  var cookie_date = new Date ( );  // Текущая дата и время
  cookie_date.setTime(1000*3600*24*365);
  document.cookie = cookie_name += "=; expires=" + cookie_date.toGMTString();
}

function person_exit(){

	var cookie_date = new Date(); // Берём текущую дату
	cookie_date.setTime(1000*3600*24*365); // Возвращаемся в "прошлое"
	var cookies = document.cookie.split(";");
	for (var i = 0; i < cookies.length; i++) {
		var cookie = cookies[i];
		var eqPos = cookie.indexOf("=");
		var name = eqPos > -1 ? cookie.substr(0, eqPos) : cookie;
		document.cookie = name + '=; path=/; expires=' + cookie_date.toGMTString();
	}

	setTimeout(function(){
        window.location.href = "/cabinet/authorization.php";
    }, 300);
}

$(function(){
	const leftBar=$('.left-bar');
	$('.footer-menu-block').on("click",function(){
		if(leftBar.css( "display")=='none'){
			leftBar.css( "display", "flex");
		}else{
			leftBar.css( "display", "none");
		}
    });

    $(".footer").on("click", ".modal", function (){
    	$(".modal").css("display", "none");
    });

	$(document).ajaxStart(function() {
		$(".loading").css("display", "block");
	});
	$(document).ajaxComplete(function() {
		$(".loading").css("display", "none");
    	setTimeout(function(){
            $(".modal").css("display", "none");
        }, 2000);
	});

});


