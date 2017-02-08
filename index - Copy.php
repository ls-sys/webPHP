<?php
function lectura($result,$campo,$tag){ 
	if(strlen(oci_result($result, $campo))== 0){return chr(13)."<$tag></$tag>"; } else
		{return chr(13)."<$tag>".oci_result($result, $campo)."</$tag>"; }
		}
function lectura_preguntas() {
		$link =  oci_pconnect("SQLADMIN","SQLADMIN","ZEUSMDH") or die(oci_error());
		$sql="select * from q_pregunta where estado = 1 order by pregunta, orden ";		
		$result = oci_parse($link,$sql);
		oci_execute($result);
		$datosxlm = "<preguntas>";
		while (oci_fetch($result)) 
           { 	
				$datosxlm .= chr(10)."<registro>"
				.lectura($result,'GRUPO','GRUPO')
				.lectura($result,'PREGUNTA','PREGUNTA')
				.lectura($result,'NOMBRE','NOMBRE')
				.lectura($result,'TIPO_RESPUESTA','TIPO_RESPUESTA')
				.chr(10)."</registro>";
		}  
	$datosxlm .= chr(10)."</preguntas>".chr(10);	
 return $datosxlm;
}

function lectura_formularios() {
		$link =  oci_pconnect("SQLADMIN","SQLADMIN","ZEUSMDH") or die(oci_error());
		$sql="select * from q_formulario where estado = 1 order by formulario ";		
				
		$result = oci_parse($link,$sql);
		oci_execute($result);
		$datosxlm = "<preguntas>";
		while (oci_fetch($result)) 
           { 	
				$datosxlm .= chr(10)."<registro>"
				.lectura($result,'EMPRESA','EMPRESA')
				.lectura($result,'NOMBRE','NOMBRE')
				.lectura($result,'FECHA','FECHA')
				.lectura($result,'DESCRIPCION','DESCRIPCION')
				.chr(10)."</registro>";
		}  
	$datosxlm .= chr(10)."</preguntas>".chr(10);	
 return $datosxlm;
}

function lectura_encuestas() {
		$link =  oci_pconnect("SQLADMIN","SQLADMIN","ZEUSMDH") or die(oci_error());
		$sql="select * from q_encuesta where estado = 1 order by empresa,formulario, encuesta ";		
				
		$result = oci_parse($link,$sql);
		oci_execute($result);
		$datosxlm = "<preguntas>";
		while (oci_fetch($result)) 
           { 	
				$datosxlm .= chr(10)."<registro>"
				.lectura($result,'EMPRESA','EMPRESA')
				.lectura($result,'FORMULARIO','FORMULARIO')
				.lectura($result,'ENCUESTA','ENCUESTA')
				.lectura($result,'FECHA','FECHA')
				.lectura($result,'ENCUESTADOR','ENCUESTADOR')
				.lectura($result,'ENCUESTADO','ENCUESTADO')
				.lectura($result,'FECHA','FECHA')
				.lectura($result,'NOTA','NOTA')
				.lectura($result,'NUM_ENCUESTA','NUM_ENCUESTA')
				.chr(10)."</registro>";
		}  
	$datosxlm .= chr(10)."</preguntas>".chr(10);	
 return $datosxlm;
}

function lectura_encuestado() {
		$link =  oci_pconnect("SQLADMIN","SQLADMIN","ZEUSMDH") or die(oci_error());
		$sql="select * from q_encuestado order by encuestado ";		
				
		$result = oci_parse($link,$sql);
		oci_execute($result);
		$datosxlm = "<preguntas>";
		while (oci_fetch($result)) 
           { 	
				$datosxlm .= chr(10)."<registro>"
				.lectura($result,'ENCUESTADO','ENCUESTADO')
				.lectura($result,'NOMBRE','NOMBRE')
				.chr(10)."</registro>";
		}  
	$datosxlm .= chr(10)."</preguntas>".chr(10);	
 return $datosxlm;
}

function lectura_grupos() {
		$link =  oci_pconnect("SQLADMIN","SQLADMIN","ZEUSMDH") or die(oci_error());
		$sql="select * from q_grupo where estado = 1 order by empresa,formulario, grupo, orden ";		
				
		$result = oci_parse($link,$sql);
		oci_execute($result);
		$datosxlm = "<preguntas>";
		while (oci_fetch($result)) 
           { 	
				$datosxlm .= chr(10)."<registro>"
				.lectura($result,'EMPRESA','EMPRESA')
				.lectura($result,'FORMULARIO','FORMULARIO')
				.lectura($result,'GRUPO','GRUPO')
				.lectura($result,'NOMBRE','NOMBRE')
				.chr(10)."</registro>";
		}  
	$datosxlm .= chr(10)."</preguntas>".chr(10);	
 return $datosxlm;
}

function lectura_opciones() {
		$link =  oci_pconnect("SQLADMIN","SQLADMIN","ZEUSMDH") or die(oci_error());
		$sql="select * from q_opcion where estado = 1 order by empresa,formulario, grupo, pregunta, opcion";		
				
		$result = oci_parse($link,$sql);
		oci_execute($result);
		$datosxlm = "<preguntas>";
		while (oci_fetch($result)) 
           { 	
				$datosxlm .= chr(10)."<registro>"
				.lectura($result,'EMPRESA','EMPRESA')
				.lectura($result,'FORMULARIO','FORMULARIO')
				.lectura($result,'GRUPO','GRUPO')
				.lectura($result,'PREGUNTA','PREGUNTA')
				.lectura($result,'OPCION','OPCION')
				.lectura($result,'NOMBRE','NOMBRE')
				.chr(10)."</registro>";
		}  
	$datosxlm .= chr(10)."</preguntas>".chr(10);	
 return $datosxlm;
}


function lectura_respuestas() {
		$link =  oci_pconnect("SQLADMIN","SQLADMIN","ZEUSMDH") or die(oci_error());
		$sql="select * from q_respuesta where estado = 1 order by empresa,formulario, encuesta, pregunta, opcion";		
				
		$result = oci_parse($link,$sql);
		oci_execute($result);
		$datosxlm = "<preguntas>";
		while (oci_fetch($result)) 
           { 	
				$datosxlm .= chr(10)."<registro>"
				.lectura($result,'EMPRESA','EMPRESA')
				.lectura($result,'FORMULARIO','FORMULARIO')
				.lectura($result,'ENCUESTA','ENCUESTA')
				.lectura($result,'PREGUNTA','PREGUNTA')
				.lectura($result,'OPCION','OPCION')
				.lectura($result,'VALOR_TXT','VALOR_TXT')
				.lectura($result,'VALOR_NUM','VALOR_NUM')
				.lectura($result,'VALOR_FECHA','VALOR_FECHA')
				.lectura($result,'ACCION_CORRECTIVA','ACCION_CORRECTIVA')
				.lectura($result,'ACCION_RESPONSABLE','ACCION_RESPONSABLE')
				.lectura($result,'ACCION_FECHA','ACCION_FECHA')
				.lectura($result,'ACCION_INDICADOR','ACCION_INDICADOR')
				.lectura($result,'FECHA','FECHA')
				.lectura($result,'FECHA_PLAN','FECHA_PLAN')
				.lectura($result,'CORRELATIVO','CORRELATIVO')
				.lectura($result,'PLAN_ACCION','PLAN_ACCION')
				.lectura($result,'LISTA_EDITABLE','LISTA_EDITABLE')
				.chr(10)."</registro>";
		}  
	$datosxlm .= chr(10)."</preguntas>".chr(10);	
 return $datosxlm;
}

function lectura_fincas() {
		$link =  oci_pconnect("SQLAD
		MIN","SQLADMIN","ZEUSMDH") or die(oci_error());
		$sql="select * from vc_finca where estado = 1 AND EMPRESA=21 order by empresa, productor, finca";		
				
		$result = oci_parse($link,$sql);
		oci_execute($result);
		$datosxlm = "<preguntas>";
		while (oci_fetch($result)) 
           { 	
				$datosxlm .= chr(10)."<registro>"
				.lectura($result,'EMPRESA','EMPRESA')
				.lectura($result,'PRODUCTOR','PRODUCTOR')
				.lectura($result,'FINCA','FINCA')
				.lectura($result,'PRODUCTOR_INTERNO','PRODUCTOR_INTERNO')
				.lectura($result,'NOMBRE','NOMBRE')
				.lectura($result,'DIRECCION','DIRECCION')
				.lectura($result,'PAIS','PAIS')
				.lectura($result,'DEPTO','DEPTO')
				.lectura($result,'CIUDAD','CIUDAD')
				.lectura($result,'LATITUD','LATITUD')
				.lectura($result,'LONGITUD','LONGITUD')
				.lectura($result,'AREA_TOTAL','AREA_TOTAL')
				.lectura($result,'AREA_CULTIVADA','AREA_CULTIVADA')
				.lectura($result,'LOTES','LOTES')
				//.lectura($result,'AREAS_OTROS_CULTIVOS').lectura($result,'AREAS_CONSERVACION').lectura($result,'DENSIAD_SIEMBRA')
				.lectura($result,'TRAB_PERMANENTES','TRAB_PERMANENTES')
				.lectura($result,'TRAB_TEMPORALES','TRAB_TEMPORALES')
				.lectura($result,'TRAB_COSECHA','TRAB_COSECHA')
				.lectura($result,'COSECHA_PROMEDIO','COSECHA_PROMEDIO')
				.lectura($result,'TRAB_EN_FINCA','TRAB_EN_FINCA')
				.lectura($result,'ALTITUD','ALTITUD')
				.lectura($result,'CUERPOS_AGUA','CUERPOS_AGUA')
				.lectura($result,'AGUA_POTABLE','AGUA_POTABLE')
				//.lectura($result,'TRAB_FAMILIARIES')
				.lectura($result,'TIENE_BODEGA','TIENE_BODEGA')
				.lectura($result,'TIENE_OFICINA','TIENE_OFICINA')
				.lectura($result,'TIENE_BENEFICIO','TIENE_BENEFICIO')
				.lectura($result,'TIENE_VIVIENDAS','TIENE_VIVIENDAS')
				.lectura($result,'TIPO_CUERPO_AGUA','TIPO_CUERPO_AGUA')
				.lectura($result,'AREA_TOTAL_NUM','AREA_TOTAL_NUM')
				.lectura($result,'QQ_PROMEDIO','QQ_PROMEDIO')
				.chr(10)."</registro>";
		}  
	$datosxlm .= chr(10)."</preguntas>".chr(10);	
 return $datosxlm;
}

function lectura_productores() {
		$link =  oci_pconnect("SQLADMIN","SQLADMIN","ZEUSMDH") or die(oci_error());
		$sql="select * from vc_productor where estado = 1 order by empresa, productor";
		
		$result = oci_parse($link,$sql);
		oci_execute($result);
		$datosxlm = "<preguntas>";
		while (oci_fetch($result)) 
           { 	
				$datosxlm .= chr(10)."<registro>"
				.lectura($result,'EMPRESA','EMPRESA')
				.lectura($result,'PRODUCTOR','PRODUCTOR')
				.lectura($result,'NOMBRE','NOMBRE')
				.lectura($result,'NIT','NIT')
				.lectura($result,'NUM_IDENTIFICACION','NUM_IDENTIFICACION')
				.lectura($result,'TIPO_IDENTIFICACION','TIPO_IDENTIFICACION')
				.lectura($result,'FECHA_NACIMIENTO','FECHA_NACIMIENTO')
				.lectura($result,'DIRECCION','DIRECCION')
				.lectura($result,'TELFONOS','TELFONOS')
				.lectura($result,'EMAIL','EMAIL')
				.lectura($result,'PRODUCTOR_INTERNO','PRODUCTOR_INTERNO')
				.chr(10)."</registro>";
		}  
	$datosxlm .= chr(10)."</preguntas>".chr(10);
 return $datosxlm;
}

function lectura_productores_fincas() {
		$link =  oci_pconnect("SQLADMIN","SQLADMIN","ZEUSMDH") or die(oci_error());
/*		$sql="*/
        $sql = "select vf.empresa EMPRESA, (vf.productor*100000 + vf.finca) PRODUCTOR, vf.nombre NOMBRES, '' APELLIDOS,";
        $sql .= "(vf.productor*1000) SUPERVISOR, '' CELULAR, '' TELEFONO, '' EMAIL, vf.pais PAIS, vf.depto DEPTO,";
        $sql .= "vf.ciudad CIUDAD, vf.latitud GPS_LATITUD, vf.longitud GPS_LONGITUD, '' FOTOGRAFIA,";
        $sql .= "'0' ACTIVIDAD_REALIZADA, '0' ACTIVIDAD_PENDIENTE, '0' ACTIVIDAD_ANULADA,"; 
		$sql .= "'0' METRICA_REALIZADA, '0' METRICA_PENDIENTE, '0' METRICA_ANULADA,";
		$sql .= "vf.direccion TITULO, '' FORMULARIO, '' FORMULARIO2,";
		$sql .= "5 NIVEL, vf.direccion DIRECCION ";
        $sql .= "from vc_finca vf ";
        //$sql .= "where vf.estado=1 "; 		
        $sql .= "union ";
        $sql .= "select vf.empresa EMPRESA, vf.promotor PRODUCTOR, vf.nombre NOMBRES, vf.apellidos APELLIDOS,";
        $sql .= "vf.supervisor SUPERVISOR, vf.celular CELULAR, vf.telefono TELEFONO, vf.email EMAIL,"; 
        $sql .= "vf.pais PAIS, vf.depto DEPTO,";
        $sql .= "vf.ciudad CIUDAD, vf.gps_latitud GPS_LATITUD, vf.gps_longitud GPS_LONGITUD, vf.fotografia FOTOGRAFIA,";
        $sql .= "vf.actividad_realizada ACTIVIDAD_REALIZADA, vf.actividad_pendiente ACTIVIDAD_PENDIENTE, vf.actividad_anulada ACTIVIDAD_ANULADA,"; 
		$sql .= "vf.metrica_realizada METRICA_REALIZADA, vf.metrica_pendiente METRICA_PENDIENTE, vf.metrica_anulada METRICA_ANULADA,";
		$sql .= "vf.titulo TITULO,vf.formulario FORMULARIO, vf.formulario2 FORMULARIO2,";
		$sql .= "vf.nivel NIVEL, vf.direccion DIRECCION "; 
        $sql .= "from promotor vf ";
		$sql .= "union "; 
  
  
        $sql .= " select vf.empresa EMPRESA, (vf.productor*1000) PRODUCTOR, vf.nombre NOMBRES, vf.nit APELLIDOS, ";
        $sql .= "1 SUPERVISOR, '' CELULAR, vf.telfonos TELEFONO, vf.email EMAIL, "; 
        $sql .= "0 PAIS, 0 DEPTO, ";
        $sql .= "0 CIUDAD, '0' GPS_LATITUD, '0' GPS_LONGITUD, '' FOTOGRAFIA, ";
        $sql .= "'0' ACTIVIDAD_REALIZADA, '0' ACTIVIDAD_PENDIENTE, '0' ACTIVIDAD_ANULADA, "; 
		$sql .= "'0' METRICA_REALIZADA, '0' METRICA_PENDIENTE, '0' METRICA_ANULADA, ";
		$sql .= "'' TITULO,'' FORMULARIO, '' FORMULARIO2, ";
		$sql .= "4 NIVEL, vf.direccion DIRECCION "; 
        $sql .= "from vc_productor vf "; 

		
		$result = oci_parse($link,$sql);
		oci_execute($result);
		$datosxlm = "<productores_fincas>";
		while (oci_fetch($result)) 
           { 
				$datosxlm .= chr(13)."<registro>"
				.lectura($result,'EMPRESA','empresa')
				.lectura($result,'PRODUCTOR','productor')
				.lectura($result,'NOMBRES','nombres')
				.lectura($result,'APELLIDOS','apellidos')
				.lectura($result,'SUPERVISOR','supervisor')
				.lectura($result,'CELULAR','celular')
				.lectura($result,'TELEFONO','telefono')
				.lectura($result,'EMAIL','email')
				.lectura($result,'PAIS','pais')
				.lectura($result,'DEPTO','depto')
				.lectura($result,'CIUDAD','ciudad')
				.lectura($result,'GPS_LATITUD','latitud')
				.lectura($result,'GPS_LONGITUD','longitud')
				.lectura($result,'FOTOGRAFIA','fotografia')
				.lectura($result,'ACTIVIDAD_REALIZADA','a_realizada')
				.lectura($result,'ACTIVIDAD_PENDIENTE','a_pendiente')
				.lectura($result,'ACTIVIDAD_ANULADA','a_anulada')
				.lectura($result,'METRICA_REALIZADA','m_realizada')
				.lectura($result,'METRICA_PENDIENTE','m_pendiente')
				.lectura($result,'METRICA_ANULADA','m_anulada')
				.lectura($result,'TITULO','titulo')
				.lectura($result,'FORMULARIO','formulario')
				.lectura($result,'FORMULARIO2','formulario2')
				.lectura($result,'NIVEL','nivel')  
				.lectura($result,'DIRECCION','direccion')
				.chr(13)."</registro>";
		}  
	$datosxlm .= chr(13)."</productores_fincas>".chr(13);	
	
 return $datosxlm;
}



if(isset($_REQUEST["leer"])){$item = $_REQUEST["leer"];

switch ($item){
case 1: echo lectura_preguntas(); break;
case 2: echo lectura_formularios(); break;
case 3: echo lectura_encuestas(); break;
case 4: echo lectura_encuestado(); break;
case 5: echo lectura_grupos(); break;
case 6: echo lectura_opciones(); break;
case 7: echo lectura_respuestas(); break;
case 8: echo lectura_fincas(); break;
case 9: echo lectura_productores(); break;
case 10: echo lectura_productores_fincas(); break;
default:

} 
} else { $item = 0;

echo "<h1>Sincronización de Tablas</h1>";
echo "<ul> ";
echo "<li><a onclick='lectura(1);'>Preguntas</a></li>";
echo "<li><a onclick='lectura(2);'>Formularios</a></li>";
echo "<li><a onclick='lectura(3);'>Encuestas</a></li>";
echo "<li><a onclick='lectura(4);'>Encuestado</a></li>";
echo "<li><a onclick='lectura(5);'>Grupo</a></li>";
echo "<li><a onclick='lectura(6);'>Opciones</a></li>";
echo "<li><a onclick='lectura(7);'>Respuestas</a></li>";
echo "<li><a onclick='lectura(8);'>Fincas</a></li>";
echo "<li><a onclick='lectura(9);'>Productores</a></li>";
echo "<li><a onclick='lectura(10);'>Productores con Fincas</a></li>";
echo "</ul>";

echo "<script>   function lectura(leer){window.location = 'index.php?leer='+leer;}</script>";
}
?>