const colorError='#fcc', colorNoError='#fff';
$(function() {
    const formProductTitle = $('#product-title');
    const formFilterCity = $('.filter-city');
    const formFilterSuburb = $('.filter-suburb');
    const formFilterGuest = $('.filter-guest');
    const formFilterTypeHousing = $('.filter-typeHousing');
    const formFilterDistance = $('.filter-distance');
    const formPriceFrom = $('.price-from');
    const formPriceTo = $('.price-to');
    const formPriceMonth = $('.filter-price');
    const formFilterAdOwner = $('.filter-adOwner');
    const formFilterSort = $('.filter-sort');
    const mapImgSearch = $('.mapImgSearch');


    function editUrlPage(){
        var key='page';
        var key1='\\?'+key;
        var key2='&'+key;
        var regular1=new RegExp(key1+'=[\\d]*', "g");
        var regular2=new RegExp(key2+'=[\\d]*', "g");
        var url=window.location.href;
        var newUrl='';
        if(url.includes(key)){
            if(url.includes('&')){
                if(url.includes('?'+key)){
                    newUrl=url.replace(regular1,'');
                    if(key==='city'){
                        newUrl=newUrl.replace(/&suburb=[\d]*/g,'');
                    }
                    newUrl=newUrl.replace(/&/,'?');
                    history.pushState('', '', newUrl);
                }else{
                    newUrl=url.replace(regular2,'');
                    if(key==='city'){
                        if(url.includes('?suburb')){
                            newUrl=newUrl.replace(/\?suburb=[\d]*/g,'');
                            newUrl=newUrl.replace(/&/,'?');
                        }else{
                            newUrl=newUrl.replace(/&suburb=[\d]*/g,'');
                        }
                    }
                    history.pushState('', '', newUrl);
                }
            }else{
                newUrl=url.replace(regular1,'');
                history.pushState('', '', newUrl);
            }
        }
    }

    function editUrl(key='',value=''){
        editUrlPage();

        value=String(value);
        var key1='\\?'+key;
        var key2='&'+key;
        var regular=new RegExp(key+'=[\\d]*', "g");
        var regular1=new RegExp(key1+'=[\\d]*', "g");
        var regular2=new RegExp(key2+'=[\\d]*', "g");
        var url=window.location.href;
        var newUrl='';
        if(value!=='0'){
            if(url.includes(key)){
                if(key==='city'){
                    newUrl=url.replace(/&suburb=[\d]*/g,'');
                    newUrl=newUrl.replace(regular,key+'='+value);
                    history.pushState('', '', newUrl);
                }else{
                    newUrl=url.replace(regular,key+'='+value);
                    history.pushState('', '', newUrl);
                }
            }else{
                if(url.includes('?')){
                    history.pushState('', '', url+'&'+key+'='+value);
                }else{
                    history.pushState('', '', url+'?'+key+'='+value);
                }
            }
        }else{
            if(url.includes(key)){
                if(url.includes('&')){
                    if(url.includes('?'+key)){
                        newUrl=url.replace(regular1,'');
                        if(key==='city'){
                            newUrl=newUrl.replace(/&suburb=[\d]*/g,'');
                        }
                        newUrl=newUrl.replace(/&/,'?');
                        history.pushState('', '', newUrl);
                    }else{
                        newUrl=url.replace(regular2,'');
                        if(key==='city'){
                            if(url.includes('?suburb')){
                                newUrl=newUrl.replace(/\?suburb=[\d]*/g,'');
                                newUrl=newUrl.replace(/&/,'?');
                            }else{
                                newUrl=newUrl.replace(/&suburb=[\d]*/g,'');
                            }
                        }
                        history.pushState('', '', newUrl);
                    }
                }else{
                    newUrl=url.replace(regular1,'');
                    history.pushState('', '', newUrl);
                }
            }
        }
    }

    function editUrlConveniences(key='',value=[]){
        editUrlPage();

        var url=window.location.href;
        var newUrl='';
        var convenience_url, conveniences;
        var index, length_conveniences;
        if(url.includes(key)){
            convenience_url=url.match(/conveniences=[\d_]*/);
            conveniences=convenience_url[0].replace(/[^\d_]*/, '');
            conveniences = conveniences.split('_');
            index=conveniences.indexOf(value);
            if(index===-1){//добавим в массив новое значение
                newUrl=url.replace(/conveniences=[\d_]*/, convenience_url[0]+value+'_');
            }else{//удалим имеющееся значение
                conveniences.splice(index, 1);
                length_conveniences=conveniences.length;
                conveniences=conveniences.join('_');
                var convenience_url2=convenience_url[0].replace(/[\d_]+/, conveniences);
                newUrl=url.replace(/conveniences=[\d_]*/, convenience_url2);

                if(length_conveniences===1){//если больше нет значений
                    if(newUrl.includes('&')){
                        if(newUrl.includes('?'+key)){
                            newUrl=newUrl.replace(/\?conveniences=[\d_]*/,'');
                            newUrl=newUrl.replace(/&/,'?');
                        }else{
                            newUrl=newUrl.replace(/&conveniences=[\d_]*/,'');
                        }
                    }else{
                        newUrl=newUrl.replace(/\?conveniences=[\d_]*/,'');
                    }
                }
            }
            history.pushState('', '', newUrl);
        }else{
            if(url.includes('?')){
                history.pushState('', '', url+'&'+key+'='+value+'_');
            }else{
                history.pushState('', '', url+'?'+key+'='+value+'_');
            }
        }
    }

    function searchProduct(){
        var product_conveniences=[];
        $('.filter-check').each(function() { // собираем данные по удобствам
            if(this.checked){//если чек выбран
                if(!product_conveniences.includes(this.value)){//если его ещё нету в массиве
                    product_conveniences.push(this.value);
                }
            }else{
                if(product_conveniences.includes(this.value)){
                    product_conveniences.splice(product_conveniences.indexOf(this.value), 1);
                }
            }
        });

        var product={
            "title" :formProductTitle.val(),
            "city" : formFilterCity.val(),
            "suburb" : formFilterSuburb.val(),
            "guest" : formFilterGuest.val(),
            "typeHousing" : formFilterTypeHousing.val(),
            "distance" : formFilterDistance.val(),
            "adOwner" : formFilterAdOwner.val(),
            "conveniences" : product_conveniences,
            "priceFrom" : formPriceFrom.val(),
            "priceTo" : formPriceTo.val(),
            "priceMonth" : formPriceMonth.val(),
            "sort" : formFilterSort.val(),
            "page" : 1
        };

        $.get('app/search.php',  {product: product}, function(data) {
            $('#product-box').html(data);
        });
    }

    formProductTitle.on("change",function(){
        searchProduct();
    });

    formFilterCity.on("change",function(){
        var product=check_productCity(formFilterCity.val());
        editUrl('city',product.city);
        $.get('/root/files/update-location.php',  {city: product.city}, function(data) {
            $('.filter-suburb').html(data);
        });

        $.get('app/mapSearch.php',  {city_id: product.city}, function(data) {
            mapImgSearch.html(data);
        });
        setTimeout(function(){ //идёт задержка пересмены пригорода
            searchProduct();
        }, 600);

        if($(this).val()!=0){
            var h1_city=$(this).find('option:selected').text();
            $('.h1_city').html('г. '+h1_city+',');
        }else{
            $('.h1_city').html('');
        }
        $('.h1_suburb').html('');
    });

    formFilterSuburb.on("change",function(){
        var product=check_productSuburb(formFilterSuburb.val());
        var city_id=check_productCity(formFilterCity.val());
        editUrl('suburb',product.suburb);
        city_id=(product.suburb>0) ? product.suburb : city_id.city;
        $.get('app/mapSearch.php',  {city_id: city_id}, function(data) {
            mapImgSearch.html(data);
        });

        searchProduct();
        if($(this).val()!=0){
            var h1_suburb=$(this).find('option:selected').text();
            $('.h1_suburb').html(h1_suburb+',');
        }else{
            $('.h1_suburb').html('');
        }
    });

    formFilterGuest.on("change",function(){
        editUrl('guest',formFilterGuest.val());
        searchProduct();
        console.log($(this).val());
        if($(this).val()!=0){
            var h1_guest=$(this).find('option:selected').text();
            $('.h1_guest').html('вместительность номера: '+h1_guest+',');
        }else{
            $('.h1_guest').html('');
        }
    });

    formFilterTypeHousing.on("change",function(){
        editUrl('typeHousing',formFilterTypeHousing.val());
        searchProduct();
        if($(this).val()!=0){
            var h1_typeHousing=$(this).find('option:selected').text();
            $('.h1_typeHousing').html('тип жилья: '+h1_typeHousing+',');
        }else{
            $('.h1_typeHousing').html('');
        }
    });

    formFilterDistance.on("change",function(){
        editUrl('distance',formFilterDistance.val());
        searchProduct();
        if($(this).val()!=0){
            var h1_distance=$(this).find('option:selected').text();
            $('.h1_distance').html('до моря: '+h1_distance+',');
        }else{
            $('.h1_distance').html('');
        }
    });

    formFilterAdOwner.on("change",function(){
        editUrl('adOwner',formFilterAdOwner.val());
        searchProduct();
        if($(this).val()!=0){
            var h1_adOwner=$(this).find('option:selected').text();
            $('.h1_adOwner').html('владелец объявления: '+h1_adOwner+',');
        }else{
            $('.h1_adOwner').html('');
        }
    });

    formPriceFrom.on("keyup",function(){
        var product=check_productPrice($(this).val());
        $(this).val(product.price);
        editUrl('priceFrom',product.price);

        var priceFrom=Number.parseInt(formPriceFrom.val());
        var priceTo=Number.parseInt(formPriceTo.val());
        if(priceFrom>=priceTo && priceTo!==0){
            formPriceFrom.css('backgroundColor',  colorError);
            formPriceTo.css('backgroundColor',  colorError);
        }else{
            formPriceFrom.css('backgroundColor',  colorNoError);
            formPriceTo.css('backgroundColor',  colorNoError);
        }
    });

    formPriceTo.on("keyup",function(){
        var product=check_productPrice($(this).val());
        $(this).val(product.price);
        editUrl('priceTo',product.price);

        var priceFrom=Number.parseInt(formPriceFrom.val());
        var priceTo=Number.parseInt(formPriceTo.val());
        if(priceFrom>=priceTo && priceTo!==0){
            formPriceFrom.css('backgroundColor',  colorError);
            formPriceTo.css('backgroundColor',  colorError);
        }else{
            formPriceFrom.css('backgroundColor',  colorNoError);
            formPriceTo.css('backgroundColor',  colorNoError);
        }
    });

    formPriceFrom.on("change",function(){
        searchProduct();
        if($(this).val()!=0){
            var h1_priceFrom=$(this).val();
            $('.h1_priceFrom').html('цена от: '+h1_priceFrom+',');
        }else{
            $('.h1_priceFrom').html('');
        }
    });
    formPriceTo.on("change",function(){
        searchProduct();
        if($(this).val()!=0){
            var h1_priceTo=$(this).val();
            $('.h1_priceTo').html('цена до: '+h1_priceTo+',');
        }else{
            $('.h1_priceTo').html('');
        }
    });

    formPriceMonth.on("change",function(){
        editUrl('priceMonth',formPriceMonth.val());
        searchProduct();
        if($(this).val()!=0){
            var h1_priceMonth=$(this).find('option:selected').text();
            $('.h1_priceMonth').html('цена: '+h1_priceMonth+',');
        }else{
            $('.h1_priceMonth').html('');
        }
    });

    formFilterSort.on("change",function(){
        editUrl('sort',formFilterSort.val());
        searchProduct();
        if($(this).val()!=0){
            var h1_sort=$(this).find('option:selected').text();
            $('.h1_sort').html('сортировать: '+h1_sort+',');
        }else{
            $('.h1_sort').html('');
        }
    });

    $(".filter-check").on("change",function (){
        editUrlConveniences('conveniences',this.value);
        searchProduct();
    });


    $(".reset").on( "click", function() {
        $(".filter").prop('selectedIndex', 0);
        $(".filter-check").prop("checked", 0);
        formPriceFrom.val(0);
        formPriceTo.val(0);
        formProductTitle.val('');

        var url= window.location.href;
        var newUrl;
        newUrl=url.replace(/morskie(.*)/g,'morskie-puti.ru/residences/');
        history.pushState('', '', newUrl);
        searchProduct();
        $('.h1_city').html('');
        $('.h1_suburb').html('');
        $('.h1_guest').html('');
        $('.h1_typeHousing').html('');
        $('.h1_distance').html('');
        $('.h1_priceFrom').html('');
        $('.h1_priceTo').html('');
        $('.h1_priceMonth').html('');
        $('.h1_adOwner').html('');
        $('.h1_sort').html('');
    });


    $(".top-bar").on( "click", "#GO", function() {
        searchProduct();
    });
});