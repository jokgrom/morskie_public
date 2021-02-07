$(function(){
    const formMain=$('.main');

    formMain.on( "click", ".publish", function() {
        var productId= $(this).attr('productId');
        $.get('app/publish.php',  {productId: productId}, function(data) {
            $('#publish'+productId).html(data);
        });
    });

    formMain.on( "click", ".deleteProduct", function() {
        var productId= $(this).attr('productId');
        $.get('app/deleteProduct.php',  {productId: productId}, function(data) {
            $('#getApp').html(data);
        });
        $('#product'+productId).remove();
    });

    formMain.on( "click", ".upPublish", function() {
        var productId= $(this).attr('productId');
        $.get('app/upPublish.php',  {productId: productId}, function(data) {
            $('#upPublish'+productId).html(data);
        });
    });

})