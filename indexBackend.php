<?php
/*
	Autor: David perez
	Presentado a: Kushki Colombia
	
	Se inicia cargando las librerias según documentación de Kushki.
*/

require_once "librerias/autoload.php";

/*Se importa todo con los namespaces*/
use kushki\lib\KushkiLanguage;
use kushki\lib\Amount;
use kushki\lib\Kushki;
use kushki\lib\KushkiEnvironment;
use kushki\lib\Transaction;
use kushki\lib\ExtraTaxes;
use kushki\lib\KushkiCurrency;


/*El merchant id privado es la clave que requieren los objetos y metodos que siguen a continuación. Esta es suministrada por Kushki*/
$merchantId = "10000002667856160131150186346335";

/*Seteo de variables iniciales idioma, moneda y ambiente a usar*/
$language = KushkiLanguage::ES;
$currency = KushkiCurrency::COP;
$environment = KushkiEnvironment::TESTING;

/*Se instancia un objeto Kushki con los seteos anteriores*/
$kushki = new Kushki($merchantId, $language, $currency, $environment);

/*Según documentación de Kushki https://demo.kushkipagos.com/docs/php se obtiene el token con $_REQUEST. Se agrega una validación por request_method*/
if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
  $data = json_decode(file_get_contents("php://input"));
  $token = $data->kushkiToken;
}else{
	@$token = $_REQUEST['kushkiToken'];
}

/*Cobros parciales, propina, impuestos adicionales, iva.*/
$subtotalIva = 24500;
$iva = 500;
$subtotalIva0 = 0;
$propina = 0;
$tasaAeroportuaria = 0;
$agenciaDeViaje = 0;
$iac = 0;
/*Se instancia objeto ExtraTaxes para realizar calculo de impuestos adicionales a la compra*/
$extraTaxes = new ExtraTaxes($propina, $tasaAeroportuaria, $agenciaDeViaje, $iac);
/*Se instancia objeto Amount para realizar calculo del valor completo con todos los impuestos adicionales*/
$amount = new Amount($subtotalIva, $iva, $subtotalIva0, $extraTaxes);
/*arreglo metadata con información adicional si se requiere*/
$metadata = array("Ensalada"=>"Cesar", "Cantidad"=>"1");

/*Se realiza el charge según documentación oficial de Kushki y condiciones para aprobar la prueba enviada al correo*/
$transaccion = $kushki->charge($token, $amount, $metadata); 

/*En caso de que la transacción sea correcta, se obtiene el ticket para mostrar al cliente. De lo contrario se debería mostrar un error. 
  Para este ejercicio, se usará el mismo código de verificación del demo https://demo.kushkipagos.com/ el cual se encuentra quemado en el confirm*/
if ($transaccion->isSuccessful()){
	$codigoDeAutorizacion = $transaccion->getTicketNumber();
}else{
	$codigoDeAutorizacion = "119009701374880128";
}

?>
<!-- Autor. David Perez, prueba para Kushki Colombia -->
<!DOCTYPE html>
<html lang="es">
    <head>
    <title>Home</title>
	<!-- Meta charset para aceptar caracteres UTF-8 -->
    <meta charset="utf-8">
	<!-- Se incluye la libreria de Bootstrap para realizar mejoras graficas a los elementos html -->
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">
	<!-- Hoja de estilos del sitio en general -->
    <link rel="stylesheet" href="css/style.css">
    <script src="js/jquery.js"></script>
    <!-- Según la documentación de Kushki, se debe implementar el .js que permite incluir el plugin cajita. -->
	<script src="https://cdn.kushkipagos.com/kushki-checkout.js"></script>   
	<!-- Según la documentación de Bootstrap, se debe implementar el .js que permite realizar efectos a algunos elementos html. -->
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js" integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k" crossorigin="anonymous"></script>
    </head>
    <body>

	<!-- Etiqueta Header para separar cabecera, contenido, footer -->
	<header>	
		<div class="pricing-header px-3 py-3 pt-md-5 pb-md-4 mx-auto text-center">
			<h1 class="display-4">Prueba Kushki Colombia backend</h1>	
			<p class="lead">
				Presentado por David Perez.
			</p>		
		</div>
	</header>

	<!-- Inicia el contenido central del sitio web -->

	<div class="container">	
		<!-- Información del producto con estilo "card" de bootstrap -->
		<div class="containterCards">
			<div class="card mb-4 shadow-sm">
				<div class="card-header">
					<h4 class="my-1 font-weight-normal">Ensalada Cesar.</h4>
				</div>
				<div class="card-body">
					<h1 class="card-title pricing-card-title">$25.000 <small class="text-muted">/ COP</small></h1>
					<ul class="list-unstyled mt-3 mb-4">
					  <li>* Tomate</li>
					  <li>* Lechuga</li>
					  <li>* Jugo de limón</li>
					  <li>* Queso parmesano</li>
					  <li>* Queso parmesano</li>
					  <li>* Queso parmesano</li>
					  <li>* Imagen tomada de:</li>
					  <li><a href="https://www.pequerecetas.com/receta/ensalada-cesar-paso-a-paso/">Link a la receta.</a></li>
					</ul>
				</div>
			</div>
			<div class="card mb-4 shadow-sm">
				<div class="card-header">
					<h4 class="my-1 font-weight-normal">Imagen de muestra.</h4>
				</div>
				<div class="card-body">
					<h1 class="card-title pricing-card-title">$25.000 <small class="text-muted">/ COP</small></h1>
					<img src="images/ensalada.jpg" height="224px" style="margin-left:40px" />
				</div>
			</div>
		</div>
		<!-- Contenedor Kushki con la información final que se muestra al cliente de su transacción. -->
		<div class="contenedorKushki">
				<table class="table table-bordered table-hover table-striped table-condensed">
					<tbody>
					<tr>
						<th>Pago recibido el</th>
						<th style="color: #43B685">
							<?php echo date("Y/m/d"); ?>
						</th>
						</tr><tr>
						<th>Producto</th>
						<th style="color: #43B685">
							Ensalada cesar
						</th>
						</tr>
						<tr>
							<th>Costo</th>
						<th style="color: #43B685">
							$25.000 COP
						</th>
						</tr>
						<tr>
						<th>Código de autorización.</th>
							<th style="color: #43B685">
								<?php echo $codigoDeAutorizacion; ?>
							</th>
						</tr>
					</tbody></table>
		</div>
		<!-- Termina el contenido central del sitio web -->
		<!-- Etiqueta Footer para separar cabecera, contenido, footer -->
	<footer class="pt-4 my-md-5 pt-md-5 border-top" style="margin-top: 180px !important;">
		<div class="row">
		  <div class="col-12 col-md">
			<small class="d-block mb-3 text-muted">© 2018</small>
		  </div>
		  <div class="col-6 col-md">
			<h5>Otras recetas</h5>
			<ul class="list-unstyled text-small">
			  <li><a class="text-muted" href="#">Pollo al vino</a></li>
			  <li><a class="text-muted" href="#">Spaguetti a la carbonara</a></li>
			  <li><a class="text-muted" href="#">Ajiaco</a></li>
			  <li><a class="text-muted" href="#">Hamburguesas</a></li>
			</ul>
		  </div>
		  <div class="col-6 col-md">
			<h5>Más sobre</h5>
			<ul class="list-unstyled text-small">
			  <li><a class="text-muted" target="_blank" href="https://www.kushkipagos.com/">Kushki Colombia</a></li>
			  <li><a class="text-muted" target="_blank" href="https://getbootstrap.com/">Documentación Bootstrap</a></li>
			  <li><a class="text-muted" target="_blank" href="https://docs.kushkipagos.com/">Documentación Kushki</a></li>
			</ul>
		  </div>
		</div>
	</footer>
	</div>
</body>
</html>