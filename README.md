# andreani-php-sdk
Andreani php sdk es una libreria simple para usar la api de andreani v1 y v2 ([documentacion](https://developers.andreani.com/documentacion))




Ejemplo para cotizar un paquete:
```
require("andreani/andreani.php");

$andreaniWs = new Andreani($USUARIO, $CLAVE, TRUE); //true > sandbox / false > produccion

$bultos = array();
				
$bultos[0]["largoCm"] = 25;
$bultos[0]["anchoCm"] = 25;
$bultos[0]["altoCm"] = 30;

$bultos[0]["valorDeclarado"] = 1200;
$bultos[0]["kilos"] = 2;
$bultos[0]["pesoAforado"] = 2;
$bultos[0]["volumen"] = 18750;


$result = $andreaniWs->cotizarEnvio('7000', '400015426', 'CL0003750', 'TND', $bultos);

$tarifa_andreani = json_decode($result[0], true);
$tarifa_andreani = $tarifa_andreani['tarifaConIva']['total'];

echo $tarifa_andreani;
```

Contratos disponibles:
```
300006611 - Sandbox
400015426 - OTELLO S.A.-ENTWS (Entrega estándar en domicilio)
400015428 - OTELLO S.A.-SUCWS (Entrega en sucursal Andreani)
400015429 - OTELLO S.A.-CAMWS (Cambio en domicilio)
400015430 - OTELLO S.A.-RETWS (Retiro en domicilio)
400015431 - OTELLO S.A.-CAMSUCWS (Cambio en sucursal Andreani)
400015432 - OTELLO S.A.-RETSUCWS (Retiro en sucursal Andreani)
```
