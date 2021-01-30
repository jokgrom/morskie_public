const colorError='#fcc', colorNoError='#fff';
$(function() {
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
                newUrl=url.replace(regular,key+'='+value);
                history.pushState('', '', newUrl);
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


    formFilterCity.on("change",function(){
        var product=check_productCity(formFilterCity.val());
        editUrl('city',product.city);
        $.get('/root/files/update-location.php',  {city: product.city}, function(data) {
            $('.filter-suburb').html(data);
        });
        searchProduct();
    });

    formFilterSuburb.on("change",function(){
        editUrl('suburb',formFilterSuburb.val());
        searchProduct();
    });

    formFilterGuest.on("change",function(){
        editUrl('guest',formFilterGuest.val());
        searchProduct();
    });

    formFilterTypeHousing.on("change",function(){
        editUrl('typeHousing',formFilterTypeHousing.val());
        searchProduct();
    });

    formFilterDistance.on("change",function(){
        editUrl('distance',formFilterDistance.val());
        searchProduct();
    });

    formFilterAdOwner.on("change",function(){
        editUrl('adOwner',formFilterAdOwner.val());
        searchProduct();
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

    formPriceFrom.on("change",function(){ searchProduct();});
    formPriceTo.on("change",function(){searchProduct();});

    formPriceMonth.on("change",function(){
        editUrl('priceMonth',formPriceMonth.val());
        searchProduct();
    });

    formFilterSort.on("change",function(){
        editUrl('sort',formFilterSort.val());
        searchProduct();
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

        var url= window.location.href;
        var newUrl;
        newUrl=url.replace(/morskie(.*)/g,'morskie/residences/');
        history.pushState('', '', newUrl);
        searchProduct();
    });


    $(".top-bar").on( "click", "#GO", function() {
        searchProduct();
    });
});