<?php
    require_once('class/db.php');
    require_once('class/CleanForm.php');
    require_once('class/CleanFormProduct.php');
    require_once('class/TemplateProduct.php');

    $CleanFormProduct= new CleanFormProduct();
    $product["address"]=$_GET['product_address'];
    list  ($product["address"], $boolError, $textError)=$CleanFormProduct->address($product["address"]);

    $address='';
    $coordinates[1]='';
    $coordinates[0]='';
    if(!$boolError){
        $params = array(
            'geocode' => $product["address"],                       // адрес
            'format'  => 'json',                                    // формат ответа
            'results' => 1,                                         // количество выводимых результатов
            'apikey'     => 'fa7ee138-6420-4aff-b098-2057cf6dcca3', //api key
        );
        $response = json_decode(file_get_contents('http://geocode-maps.yandex.ru/1.x/?' . http_build_query($params, '', '&')));
        if ($response->response->GeoObjectCollection->metaDataProperty->GeocoderResponseMetaData->found > 0){
            $coordinates= $response->response->GeoObjectCollection->featureMember[0]->GeoObject->Point->pos;
            $address= $response->response->GeoObjectCollection->featureMember[0]->GeoObject->metaDataProperty->GeocoderMetaData->text;

            $coordinates = explode(" ", $coordinates);
        }
    }
    $TemplateProduct=new TemplateProduct($db);
    echo $TemplateProduct->map($address, $coordinates[1],$coordinates[0]);
?>

<script type="text/javascript">
    $('#product-address').attr('addressLatitude', '<?php echo $coordinates[1]; ?>');
    $('#product-address').attr('addressLongitude', '<?php echo $coordinates[0]; ?>');
    $('#product-address').attr('address', '<?php echo $address; ?>');
</script>