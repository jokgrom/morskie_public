<?php
    $url = $_SERVER['REQUEST_URI'];
    $url = explode('?', $url);
    $url = $url[0];
    $url=preg_replace('/index.php/','', $url);
    $url=preg_replace('/[^\D]/','', $url);

    $pageInfo['description']='Сервис поиска посуточного жилья на побережье Черного моря. Сезон 2021.';
    $pageInfo['keywords']='моркие пути, жилье у моря, отдых всей семьёй, сервис поиска посуточного жилья';
    switch ($url){
        case '/':
            $pageInfo['title']='Сервис подбора жилья у моря без посредников. Снять или сдать жильё у моря может каждый.';
            $pageInfo['description']='Бесплатный сервис поиска жилья на море. Список городов и посёлков для отдыха на море в 2021 году.';
            $pageInfo['keywords']=' моркие пути, жилье у моря, поиск жилья, посуточное жильё, города побережья,
            отдых на кубани, морские пути жильё, морское жильё, жильё от собственника.';
            break;

        case '/contacts/':
            $pageInfo['title']='Контактные данные сервиса по поиску посуточного жилья "Морские пути"';
            $pageInfo['description']='Связь со сервисом "Морские пути". Отзывчивая администрация. Телефонный номер, Whatsapp, instagram.com/morskie_puti, форма отправки сообщений. ';
            $pageInfo['keywords']='контакты, телефонный номер, морские пути';
            break;

        case '/residences/':
            $pageInfo['title']='Поиск посуточного жилья у моря без посредников';
            $pageInfo['description']='Бесплатный сервис поиска жилья на побережье Чёрного моря без посредников. Отдых с детьми для всей семьи. Максимальный комплекс удобств.';
            $pageInfo['keywords']=' моркие пути, жилье у моря,
            жилье на море, жилье посуточно, отдых 2021, отдых на море, отдых для всей семьи, отдых семьей, отдых с ребёнком,
            отдых, на черном море, отдых на кубани, отдых у моря, снять жилье, снять жилье у моря,  morskieputi';
            break;

        case '/residence/':
            //вывод из бд объявления
            include_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/db.php');
            include_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/CleanForm.php');
            include_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/CleanFormProduct.php');

            include_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/Product.php');
            include_once($_SERVER['DOCUMENT_ROOT'].'/root/files/class/Residence.php');


            $Residence=new Residence($db);
            $CleanFormProduct = new CleanFormProduct;

            list  ($productId, $boolError, $textError)=$CleanFormProduct->id($_GET['id']);
            $Residence->getInfo($productId);
            $pageInfo['title']=$Residence->title.' | '.$Residence->cityTitle.', '.$Residence->suburbTitle;

            $description=mb_substr($Residence->description, 0, 250);
            $pageInfo['description']=$description;

            $keywords = explode(" ", $Residence->title);
            if(count($keywords)>1){
                foreach($keywords as $key => $value){
                    if(iconv_strlen($value)<3){
                        unset($keywords[$key]);
                    }
                }
            }
            $pageInfo['keywords'] =implode(", ", $keywords);
            break;

        case '/cabinet/residence/add/step1.php':
            $pageInfo['title']='Размещение бесплатного объявления. Шаг 1/2';
            break;

        case '/cabinet/residence/add/step2.php':
            $pageInfo['title']='Размещение бесплатного объявления. Шаг 2/2';
            break;

        default:
            $pageInfo['title']='Сервис подбора жилья у моря без посредников. Снять или сдать жильё у моря может каждый.';
    }
    $updateUrlHash='?h=161229';
    $BettaV='161.229';
    if($_COOKIE['BettaV']!=$BettaV){
//        header('Cache-Control: private, no-cache="set-cookie"');
//        header('Expires: 0');
//        header('Pragma: no-cache');
//        setcookie('BettaV',$BettaV,time() + (3600*24*365),'/'); //перезапишем версию сайта
    }
?>
<title><?php echo $pageInfo['title']; ?></title>
<meta charset="utf-8" />
<link rel="icon" href="https://morskie-puti.ru/favicon.ico" type="image/x-icon">
<meta content="text/javascript" />
<meta content="text/css" />
<meta name="viewport" content="width=device-width, maximum-scale=1">

<link rel="stylesheet" href="/root/css/main.css<?php echo $updateUrlHash; ?>" type="text/css"/>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<link  href="/root/css/slider_fotorama.css<?php echo $updateUrlHash; ?>" rel="stylesheet">
<script src="/root/js/slider_fotorama.js<?php echo $updateUrlHash; ?>"></script>
<style>
	.fotorama__nav {text-align: left;}
	/*.fotorama__nav__frame  {margin-left:5px;margin-right: 5px;}*/
	.fotorama__thumb {border-radius: 3px;}
</style>
<meta name="description" content="<?php echo $pageInfo['description']; ?>">
<meta name="keywords" content="<?php echo $pageInfo['keywords']; ?>">
<script src="/root/js/functions.js<?php echo $updateUrlHash; ?>"></script>

<!-- Yandex.Metrika counter -->
<script type="text/javascript" >
    (function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
        m[i].l=1*new Date();k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)})
    (window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");

    ym(71666248, "init", {
        clickmap:true,
        trackLinks:true,
        accurateTrackBounce:true,
        webvisor:true
    });
</script>
<noscript><div><img src="https://mc.yandex.ru/watch/71666248" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter -->
