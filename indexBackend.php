<?php
require_once "librerias/autoload.php";
use kushki\lib\KushkiLanguage;
use kushki\lib\Amount;
use kushki\lib\Kushki;
use kushki\lib\KushkiEnvironment;
use kushki\lib\Transaction;
use kushki\lib\ExtraTaxes;
use kushki\lib\KushkiCurrency;



$merchantId = "10000002667856160131150186346335";
$language = KushkiLanguage::ES;
$currency = KushkiCurrency::COP; // KushkiCurrency::COP; for Colombia
$environment = KushkiEnvironment::TESTING;

$kushki = new Kushki($merchantId, $language, $currency, $environment);
@$token = $_REQUEST['kushkiToken'];
$subtotalIva = 3200;
$iva = 608;
$subtotalIva0 = 0;
$propina = 10;
$tasaAeroportuaria = 0;
$agenciaDeViaje = 0;
$iac = 0;
$extraTaxes = new ExtraTaxes($propina, $tasaAeroportuaria, $agenciaDeViaje, $iac);
$amount = new Amount($subtotalIva, $iva, $subtotalIva0, $extraTaxes);
$metadata = array("Ensalada"=>"Cesar", "Cantidad"=>"1");

$transaccion = $kushki->charge($token, $amount, $metadata); 

?>

<!DOCTYPE html>
<html lang="en">
     <head>
     <title>Home</title>
     <meta charset="utf-8">
     <link rel="icon" href="images/favicon.ico">
     <link rel="shortcut icon" href="images/favicon.ico" />
     <link rel="stylesheet" href="css/style.css">
     <script src="js/jquery.js"></script>
     <script src="js/jquery-migrate-1.1.1.js"></script>
     <script src="js/jquery.equalheights.js"></script>
     <script src="js/jquery.ui.totop.js"></script>
     <script src="js/jquery.easing.1.3.js"></script>
	 <script src="https://cdn.kushkipagos.com/kushki.min.js"></script>
     <!--[if lt IE 8]>
       <div style=' clear: both; text-align:center; position: relative;'>
         <a href="http://windows.microsoft.com/en-US/internet-explorer/products/ie/home?ocid=ie6_countdown_bannercode">
           <img src="http://storage.ie6countdown.com/assets/100/images/banners/warning_bar_0000_us.jpg" border="0" height="42" width="820" alt="You are using an outdated browser. For a faster, safer browsing experience, upgrade for free today." />
         </a>
    <![endif]-->
    <!--[if lt IE 9]>
    
      <script src="js/html5shiv.js"></script>
      <link rel="stylesheet" media="screen" href="css/ie.css">
    <![endif]-->
    <!--[if lt IE 10]>
      <link rel="stylesheet" media="screen" href="css/ie1.css">
    <![endif]-->
    
     </head>
     <body class="page1">

<!--==============================header=================================-->
 <header> 
  <div class="container_12">
   <div class="grid_12"> 
    <div class="socials">
      <a href="#"></a>
      <a href="#"></a>
      <a href="#"></a>
      <a href="#" class="last"></a>
    </div>
    <h1><a href="index.html"><img src="images/logo.png" alt="Boo House"></a> </h1>
    
<div class="clear"></div>
          </div>
      </div>
</header>

<!--==============================Content=================================-->

<div class="content"><div class="ic">More Website Templates @ TemplateMonster.com - December 02, 2013!</div>
<a href="index-2.html" class="block1">
  <img src="images/blur_img1.jpg" alt="">
  <span class="price"><span>Ensalada Cesar</span><span><small>$</small> 29.35</span><strong></strong></span>
</a>


<div class="contenedorKushki">
	Se ha comprado con exito una ensalada cesar. El código de su compra es: 90a9f2d93ba508c38971890454897fd4
</div>

 
</div>

<!--==============================footer=================================-->

<footer>    
  <div class="container_12">
    <div class="grid_6 prefix_3">
      <div class="copy">
      &copy; 2013 | <a href="#">Privacy Policy</a> <br> Website   designed by <a href="http://www.templatemonster.com/" rel="nofollow">TemplateMonster.com</a>
      </div>
    </div>
  </div>
</footer>
</body>
</html>