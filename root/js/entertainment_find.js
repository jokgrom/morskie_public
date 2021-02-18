const colorError='#fcc', colorNoError='#fff';
$(function() {
    const formProductTitle = $('#product-title');
    const formFilterCity = $('.filter-city');
    const formFilterSuburb = $('.filter-suburb');
    const mapImgSearch = $('.mapImgSearch');

    function searchProduct(){
        var product={
            "title" :formProductTitle.val(),
            "city" : formFilterCity.val(),
            "suburb" : formFilterSuburb.val()
        };
        $('.filter-check').each(function() { // выбираем тип отдыха
            if(this.checked){//если чек выбран
                product['listEntertainment']=$(this).val();
            }
        });

        $.get('app/search.php',  {product: product}, function(data) {
            $('#product-box').html(data);
        });
    }

    formProductTitle.on("change",function(){
        searchProduct();
    });

    formFilterCity.on("change",function(){
        var product=check_productCity(formFilterCity.val());
        $.get('/root/files/update-location.php',  {city: product.city}, function(data) {
            $('.filter-suburb').html(data);
        });

        $.get('app/mapSearch.php',  {city_id: product.city}, function(data) {
            mapImgSearch.html(data);
        });
        setTimeout(function(){ //идёт задержка пересмены пригорода
            searchProduct();
        }, 600);
    });

    formFilterSuburb.on("change",function(){
        var product=check_productSuburb(formFilterSuburb.val());
        var city_id=check_productCity(formFilterCity.val());
        city_id=(product.suburb>0) ? product.suburb : city_id.city;
        $.get('app/mapSearch.php',  {city_id: city_id}, function(data) {
            mapImgSearch.html(data);
        });
        searchProduct();
    });

    $(".filter-check").on("change",function (){
        searchProduct();
    });


    $(".reset").on( "click", function() {
        $(".filter").prop('selectedIndex', 0);
        $(".filter-check").prop("checked", 0);
        formProductTitle.val('');
        searchProduct();
    });

    $(".top-bar").on( "click", "#GO", function() {
        searchProduct();
    });
});