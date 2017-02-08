<?php
include("./config/nusoap.php");
$server = new soap_server();
$server->configureWSDL('Servidor', 'urn:Servidor');


$server->register('WS00',           // method name
    array('deptofiltro' => 'xsd:string'), // input parameters
    array('return' => 'xsd:string'),          // output parameters
    'urn:versionxmlwsdl',             // namespace
    'urn:versionxmlwsdl#versionxml',         // soapaction
    'rpc',                 // style
    'encoded',                // use
    'Retorna el datos'              // documentation
);

$server->register('WS01',           // method name
    array('paisfiltro' => 'xsd:string'), // input parameters
    array('return' => 'xsd:string'),          // output parameters
    'urn:datosxmlwsdl',             // namespace
    'urn:datosxmlwsdl#datosxml',         // soapaction
    'rpc',                 // style
    'encoded',                // use
    'Retorna el datos'              // documentation
);

$server->register('WS02',           // method name
    array('promotor' => 'xsd:string'), // input parameters
    array('return' => 'xsd:string'),          // output parameters
    'urn:ws02wsdl',             // namespace
    'urn:ws02wsdl#ws02',         // soapaction
    'rpc',                 // style
    'encoded',                // use
    'Retorna el datos'              // documentation
);


$server->register('WS03',           // method name
    array('formulario' => 'xsd:string','empresa' => 'xsd:string'), // input parameters
    array('return' => 'xsd:string'),          // output parameters
    'urn:ws03wsdl',             // namespace
    'urn:ws03wsdl#ws03',         // soapaction
    'rpc',                 // style
    'encoded',                // use
    'Retorna el datos'              // documentation
);


$server->register('WS04',           // method name
    array('id_productor' => 'xsd:string','lat_productor' => 'xsd:string','lon_productor' => 'xsd:string'), // input parameters
    array('return' => 'xsd:string'),          // output parameters
    'urn:ws04wsdl',             // namespace
    'urn:ws04wsdl#ws04',         // soapaction
    'rpc',                 // style
    'encoded',                // use
    'Retorna el datos'              // documentation
);

function MetodoPrueba($tcParametroA,$tcParametroB) {
 return "EL SERVIDOR=".$_SERVER['PHP_SELF']."\n"."tcParametroA=".strtoupper($tcParametroA)."\n"."tcParametroB=".strtoupper($tcParametroB);
}


function impuesto($cantidad) {
 return "monto=".$cantidad."\n"."impuesto=".strtoupper($cantidad*0.07);
}

//function WS00($versioncliente) {
function WS00($deptofiltro) {
return "<tabla><registro><nombre>ejemplo</nombre></registro></tabla>";
        $link = odbc_connect ("ZEUSMDH", "sqladmin", "sqladmin") or die ("Could not connect");
		$sql="select * from sqladmin.vc_ciudad order by nombre";
        $result = odbc_exec($link,$sql);
		$datosxlm = "<municipio>";
        while (odbc_fetch_row($result)) 
           { 	$municipio =odbc_result($result, 'id_ciudad'); 
		
				$nombre    =odbc_result($result, 'nombre');  
				$depto     =odbc_result($result, 'id_depto');
				$datosxlm  .= chr(13)."<registro>".chr(13)."<idmunicipio>$municipio</idmunicipio>".chr(13)."<nombre>$nombre</nombre>".chr(13)."<iddepto>$depto</iddepto>".chr(13)."</registro>";
		}

	$datosxlm .= chr(13)."</municipio>".chr(13);
 return $datosxlm;
}

function WS01($paisfiltro) {
return "<tabla><registro><nombre>ejemplo</nombre></registro></tabla>";
        $link = odbc_connect ("ZEUSMDH", "", "") or die ("Could not connect");
		$sql="select * from vc_depto order by nombre desc limit 100";
        $result = odbc_exec($link,$sql);
		$datosxlm = "<depto>";
        while (odbc_fetch_row($result)) 
           { 	$depto =odbc_result($result, 'iddepto'); 
				$nombre =odbc_result($result, 'nombre');  
				$pais =odbc_result($result, 'idpais');
				$datosxlm .= chr(13)."<registro>".chr(13)."<iddepto>$depto</iddepto>".chr(13)."<nombre>$nombre</nombre>".chr(13)."<idpais>$pais</idpais>".chr(13)."</registro>";
		}  
	$datosxlm .= chr(13)."</depto>".chr(13);	
 return $datosxlm;
}

function WS02($promotorfiltro) {
// return chr(13)."<promotor>".chr(13)."<registro>".chr(13)."<nombres>prueba</nombres>".chr(13)."</registro>".chr(13)."</promotor>".chr(13);
        $link = odbc_connect ("ZEUSMDH", "sqladmin", "sqladmin") or die ("Could not connect");
		$sql="select * from volcafeway.vc_productor order by productor desc limit 100";
        $result = odbc_exec($link,$sql);
        $registros = "";
        while (odbc_fetch_row($result)) 
           { 	$empresa 	=chr(13)."<empresa>".odbc_result($result, 'empresa')."</empresa>"; 
				$productor 	=chr(13)."<productor>".odbc_result($result, 'productor')."</productor>";  
				$nombres 	=chr(13)."<nombres>".odbc_result($result, 'nombres')."</nombres>"; 
				$apellidos 	=chr(13)."<apellidos>".odbc_result($result, 'apellidos')."</apellidos>";  
				$supervisor =chr(13)."<supervisor>".odbc_result($result, 'supervisor')."</supervisor>";
				$celular 	=chr(13)."<celular>".odbc_result($result, 'celular')."</celular>"; 
				$telefono  	=chr(13)."<telefono>".odbc_result($result, 'telefono')."</telefono>";   
				$email      =chr(13)."<email>".odbc_result($result, 'email')."</email>";
				$pais    	=chr(13)."<pais>".odbc_result($result, 'pais')."</pais>";    
				$depto     	=chr(13)."<depto>".odbc_result($result, 'depto')."</depto>";      
				$ciudad    	=chr(13)."<ciudad>".odbc_result($result, 'ciudad')."</ciudad>";
				$latitud 	=chr(13)."<latitud>".odbc_result($result, 'gps_latitud')."</latitud>"; 
				$longitud 	=chr(13)."<longitud>".odbc_result($result, 'gps_longitud')."</longitud>";  
				$fotografia 	=chr(13)."<fotografia>".odbc_result($result, 'fotografia')."</fotografia>";
				$a_realizada 	=chr(13)."<a_realizada>".odbc_result($result, 'actividad_realizada')."</a_realizada>"; 
				$a_pendiente 	=chr(13)."<a_pendiente>".odbc_result($result, 'actividad_pendiente')."</a_pendiente>";  
				$a_anulada 	=chr(13)."<a_anulada>".odbc_result($result, 'actividad_anulada')."</a_anulada>";
				$m_realizada 	=chr(13)."<m_realizada>".odbc_result($result, 'metrica_realizada')."</m_realizada>"; 
				$m_pendiente 	=chr(13)."<m_pendiente>".odbc_result($result, 'metrica_pendiente')."</m_pendiente>";  
				$m_anulada 	=chr(13)."<m_anulada>".odbc_result($result, 'metrica_anulada')."</m_anulada>";
				$titulo 	=chr(13)."<titulo>".odbc_result($result, 'titulo')."</titulo>";
				$formulario 	=chr(13)."<formulario>".odbc_result($result, 'formulario')."</formulario>";
				$formulario2 	=chr(13)."<formulario2>".odbc_result($result, 'formulario2')."</formulario2>";
				$nivel   	=chr(13)."<nivel>".odbc_result($result, 'nivel')."</nivel>";
				$direccion 	=chr(13)."<direccion>".odbc_result($result, 'direccion')."</direccion>";

		$registros .= "<registro>".$empresa.$productor.$nombres.$apellidos.$supervisor.$celular.$telefono.$email.$pais.$depto.$ciudad.$latitud.$longitud.$fotografia.$a_realizada.$a_pendiente.$a_anulada.$m_realizada.$m_pendiente.$m_anulada.$titulo.$formulario.$formulario2.$direccion.$nivel.chr(13)."</registro>".chr(13);
		}

 return chr(13)."<promotor>".chr(13).$registros."</promotor>".chr(13);
}



function WS03($formulario,$empresa) {
        $link = odbc_connect ("ZEUSMDH", "sqladmin", "sqladmin") or die ("Could not connect");

   	$sql="select * from volcafeway.encuesta ";
        $result = odbc_exec($link,$sql);


        $registros="";
        while (odbc_fetch_row($result)) 
           { 

		$idencuesta   ="<idencuesta>".odbc_result($result, 'idencuesta')."</idencuesta>"; 
		//$grupo        ="<grupo>"   .odbc_result($result, 'grupo')   ."</grupo>"; 
                $orden_pregunta     ="<orden_pregunta>"   .odbc_result($result, 'orden_pregunta')   ."</orden_pregunta>"   ; 
                $pregunta           ="<pregunta>"   .odbc_result($result, 'pregunta')   ."</pregunta>"   ; 
                $respuesta1         ="<respuesta1>"     .odbc_result($result, 'respuesta1')          ."</respuesta1>"     ; 
                $respuesta2         ="<respuesta2>"     .odbc_result($result, 'respuesta2')          ."</respuesta2>"     ; 
                $respuesta3         ="<respuesta3>"     .odbc_result($result, 'respuesta3')          ."</respuesta3>"     ; 
                $tipo1              ="<tipo1>"          .odbc_result($result, 'tipo1')               ."</tipo1>"          ; 
                $tipo2              ="<tipo2>"          .odbc_result($result, 'tipo2')               ."</tipo2>"          ; 
                $tipo3              ="<tipo3>"          .odbc_result($result, 'tipo3')               ."</tipo3>"          ; 
 
                //$datosxml .= "<registro>".chr(13).$idencuesta.chr(13).$grupo.chr(13).$html.chr(13).$orden_pregunta.chr(13).$pregunta.chr(13).$especialidad.chr(13).$respuesta1.chr(13).$respuesta2.chr(13).$respuesta3.chr(13)."</registro>".chr(13);
		$registros .= "<registro>".chr(13).$idencuesta.chr(13).$orden_pregunta.chr(13).$pregunta.chr(13).$respuesta1.chr(13).$respuesta2.chr(13).$respuesta3.chr(13).$tipo1.chr(13).$tipo2.chr(13).$tipo3.chr(13)."</registro>".chr(13);
           }
        $datosxml ="<tabla>".chr(13).$registros."</tabla>";
//$datosxml = utf8_encode($datosxml);

return  chr(13).$datosxml.chr(13);

// return chr(13).$datosxml.chr(13);

}


function WS04($id_productor,$lat_productor,$lon_productor) {
        $resultado = "0";
        $link = odbc_connect ("ZEUSMDH", "sqladmin", "sqladmin") or die ("Could not connect");
   	$sql="update volcafeway.vc_productor set gps_latitud=$lat_productor, gps_longitud=$lon_productor where productor='$id_productor'";
        $result = odbc_exec($link,$sql);
        $resultado = "1";

 return chr(13)."<resultado><respuesta>".chr(13).$resultado."</respuesta></resultado>".chr(13);
}


$HTTP_RAW_POST_DATA = isset($HTTP_RAW_POST_DATA) ? $HTTP_RAW_POST_DATA : '';
$server->service($HTTP_RAW_POST_DATA);
?>
 