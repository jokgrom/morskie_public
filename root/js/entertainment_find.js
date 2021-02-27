const colorError='#fcc', colorNoError='#fff';
$(function() {
    const formProductTitle = $('#product-title');
    const formFilterCity = $('.filter-city');
    const formFilterSuburb = $('.filter-suburb');
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

    $(".filter-check").on("change",function (){
        var product=check_productEntertainmentPrice($(this).val());
        editUrl('listEntertainment',product.entertainmentPrice);

        searchProduct();
        if($(this).val()!=0){
            var h1_listEntertainment=$(this).parent().text();
            $('.h1_listEntertainment').html('вид отдыха: '+h1_listEntertainment+',');
        }else{
            $('.h1_listEntertainment').html('');
        }
    });


    $(".reset").on( "click", function() {
        $(".filter").prop('selectedIndex', 0);
        $(".filter-check").prop("checked", 0);
        formProductTitle.val('');
        var url= window.location.href;
        var newUrl;
        newUrl=url.replace(/morskie(.*)/g,'morskie-puti.ru/entertainment/');
        history.pushState('', '', newUrl);
        searchProduct();
        $('.h1_city').html('');
        $('.h1_suburb').html('');
        $('.h1_listEntertainment').html('');
    });

    $(".top-bar").on( "click", "#GO", function() {
        searchProduct();
    });
});