<?php
error_reporting(0);
$empresa = '21';
//$pais = '(11,30)';
$pais = '(30)';
function lectura($result,$campo,$tag){ 
	if(strlen(oci_result($result, $campo))== 0){return chr(10)."<$tag></$tag>"; } else
		{return chr(10)."<$tag>".(oci_result($result, $campo))."</$tag>"; }
		//{return chr(10)."<$tag>".strtoupper(oci_result($result, $campo))."</$tag>"; }
		}
function lectura_preguntas_error() {		
		$datosxlm = "<preguntas>"
		            .chr(10)."<registro>"
		            .chr(10)."<IDENCUESTA>555</IDENCUESTA>"
					.chr(10)."<ORDEN_PREGUNTA>1</ORDEN_PREGUNTA>"
					.chr(10)."<PREGUNTA>Nombre de la finca</PREGUNTA>"
                    .chr(10)."<RESPUESTA1>Cumple</RESPUESTA1>"
					.chr(10)."<DIBUJO1>cumple.png</DIBUJO1>"
					.chr(10)."<RESPUESTA2>No Cumple</RESPUESTA2>"
					.chr(10)."<DIBUJO2>nocumple.png</DIBUJO2>"
					.chr(10)."<RESPUESTA3>No Aplica</RESPUESTA3>"
					.chr(10)."<DIBUJO3>noaplica.png</DIBUJO3>"
					.chr(10)."<SQL_TABLA></SQL_TABLA>"
					.chr(10)."<CAMPO></CAMPO>"
					.chr(10)."<TIPO_RESPUESTA>1</TIPO_RESPUESTA>"
					.chr(10)."<AYUDA>Ingrese nombre completo de la finca</AYUDA>"
					.chr(10)."<ID_TRANSACCION></ID_TRANSACCION>"
					.chr(10)."</registro>"
					
		            .chr(10)."<IDENCUESTA>555</IDENCUESTA>"
					.chr(10)."<ORDEN_PREGUNTA>2</ORDEN_PREGUNTA>"
					.chr(10)."<PREGUNTA>Direccion</PREGUNTA>"
                    .chr(10)."<RESPUESTA1>Cumple</RESPUESTA1>"
					.chr(10)."<DIBUJO1>cumple.png</DIBUJO1>"
					.chr(10)."<RESPUESTA2>No Cumple</RESPUESTA2>"
					.chr(10)."<DIBUJO2>nocumple.png</DIBUJO2>"
					.chr(10)."<RESPUESTA3>No Aplica</RESPUESTA3>"
					.chr(10)."<DIBUJO3>noaplica.png</DIBUJO3>"
					.chr(10)."<SQL_TABLA></SQL_TABLA>"
					.chr(10)."<CAMPO></CAMPO>"
					.chr(10)."<TIPO_RESPUESTA>1</TIPO_RESPUESTA>"
					.chr(10)."<AYUDA>Ingrese direccion postal de la finca</AYUDA>"
					.chr(10)."<ID_TRANSACCION></ID_TRANSACCION>"
					.chr(10)."</registro>"

		            .chr(10)."<IDENCUESTA>555</IDENCUESTA>"
					.chr(10)."<ORDEN_PREGUNTA>3</ORDEN_PREGUNTA>"
					.chr(10)."<PREGUNTA>Telefono</PREGUNTA>"
                    .chr(10)."<RESPUESTA1>Cumple</RESPUESTA1>"
					.chr(10)."<DIBUJO1>cumple.png</DIBUJO1>"
					.chr(10)."<RESPUESTA2>No Cumple</RESPUESTA2>"
					.chr(10)."<DIBUJO2>nocumple.png</DIBUJO2>"
					.chr(10)."<RESPUESTA3>No Aplica</RESPUESTA3>"
					.chr(10)."<DIBUJO3>noaplica.png</DIBUJO3>"
					.chr(10)."<SQL_TABLA></SQL_TABLA>"
					.chr(10)."<CAMPO></CAMPO>"
					.chr(10)."<TIPO_RESPUESTA>1</TIPO_RESPUESTA>"
					.chr(10)."<AYUDA>Ingres telefono de la oficina del adminsitrador de la finca</AYUDA>"
					.chr(10)."<ID_TRANSACCION></ID_TRANSACCION>"
					.chr(10)."</registro>"

					.chr(10)."</preguntas>".chr(10);
		echo $datosxlm;
		return $datosxlm;
	}
	
function lectura_preguntas() {
global $empresa;
global $pais;
		$link =  oci_pconnect("SQLADMIN","SQLADMIN","ZEUSMDH") or die(lectura_preguntas_error());
		$sql = "select a.formulario formulario, (a.grupo*100+a.pregunta) idpregunta, ('[' || upper(b.nombre) ||'] '  || a.nombre) nombre,".
    	        "a.tipo_respuesta tipo_respuesta, a.campo campo, a.sql_tabla sql_tabla,a.empresa,".
 				"a.pregunta nopregunta, a.ayuda ayuda, a.codigo_alterno codigo, a.accion_correctiva accion_correctiva from q_pregunta a, q_grupo b " .
				"where b.estado=1  and a.empresa=$empresa and b.empresa=$empresa and a.formulario = b.formulario and a.grupo=b.grupo order by a.formulario, a.grupo, a.pregunta";
		$result = oci_parse($link,$sql);
		oci_execute($result);
		$datosxlm = "<preguntas>";
		while (oci_fetch($result)) 
           { 	
		        $idencuesta = oci_result($result, "FORMULARIO");
				//$idencuesta = 3;
				$idpregunta = oci_result($result, "NOPREGUNTA"); 
				$datosxlm .= chr(10)."<registro>"
				//.chr(10)."<IDENCUESTA>3</IDENCUESTA>"
				.lectura($result,'FORMULARIO','IDENCUESTA')
				//.lectura($result,'GRUPO','IDENCUESTA')
				.lectura($result,'IDPREGUNTA','IDPREGUNTA')
				.lectura($result,'NOPREGUNTA','ORDEN_PREGUNTA')
				.lectura($result,'EMPRESA','EMPRESA')
				.lectura($result,'NOMBRE','PREGUNTA')
				//.lectura($result,'TIPO_RESPUESTA','TIPO_REPUESTA')
				//. chr(10)."<TIPO_RESPUESTA>4</TIPO_RESPUESTA>"
				//.chr(10)."<RESPUESTA1>SI</RESPUESTA1>"
				//.chr(10)."<DIBUJO1>SI.JPG</DIBUJO1>"
				//.chr(10)."<RESPUESTA2>NO</RESPUESTA2>"
				//.chr(10)."<DIBUJO2>NO.JPG</DIBUJO2>"
				//.chr(10)."<RESPUESTA3>NO APLIC</RESPUESTA3>"
				//.chr(10)."<DIBUJO3>NOAPLICA.JPG</DIBUJO3>"
				. lectura_opciones_pregunta($idencuesta, $idpregunta)
				//.lectura($result,'TIPO1','TIPO1')
				//.lectura($result,'RESPUESTA1','RESPUESTA1')
				//.lectura($result,'DIBUJO1','DIBUJO1')
				//.lectura($result,'RESPUESTA2','RESPUESTA2')
				//.lectura($result,'DIBUJO2','DIBUJO2')
				//.lectura($result,'RESPUESTA3','RESPUESTA3')
				//.lectura($result,'DIBUJO3','DIBUJO3')
				.lectura($result,'SQL_TABLA','SQL_TABLA')
				//.lectura($result,'ID_TRANSACCION','ID_TRANSACCION')
				.lectura($result,'CAMPO','CAMPO')
				.lectura($result,'TIPO_RESPUESTA','TIPO_RESPUESTA')
				.lectura($result,'AYUDA','AYUDA')
				.lectura($result,'CODIGO','ID_TRANSACCION')
				.lectura($result,'ACCION_CORRECTIVA','ACCION_CORRECTIVA')
				.chr(10)."</registro>";
		}  
	$datosxlm .= chr(10)."</preguntas>".chr(10);	
 return $datosxlm;
}


function lectura_opciones_pregunta($idencuesta, $orden_pregunta) {
global $empresa;
global $pais;
		$link2 =  oci_pconnect("SQLADMIN","SQLADMIN","ZEUSMDH") or die(oci_error());

		$sql2="select formulario, (grupo*100+pregunta) idpregunta, opcion, nombre, imagen ". 
		      "from q_opcion where estado=1 and formulario = $idencuesta and pregunta=$orden_pregunta and empresa=$empresa order by formulario, grupo, pregunta, opcion";
		$result2 = oci_parse($link2,$sql2);
		oci_execute($result2);
		$datosxlm2 = "";
		$j = 0;
		while (oci_fetch($result2) and $j < 3) 
           { 	$j = $j + 1;
				$datosxlm2 .= ""
				.lectura($result2,'NOMBRE','RESPUESTA' . $j)
				.lectura($result2,'IMAGEN','DIBUJO' . $j)
				."";
		}  
		if ($j==0){return chr(10)."<RESPUESTA1></RESPUESTA1>".chr(10)."<DIBUJO1></DIBUJO1>".chr(10)."<RESPUESTA2></RESPUESTA2>".chr(10)."<DIBUJO2></DIBUJO2>".chr(10)."<RESPUESTA3></RESPUESTA3>".chr(10)."<DIBUJO3></DIBUJO3>";}
 return $datosxlm2;
}

function lectura_formularios_error() {
global $empresa;
global $pais;
		$datosxlm = "<formularios>"
		            .chr(10)."<registro>"
		            .chr(10)."<EMPRESA>1</EMPRESA>"
					.chr(10)."<NOMBRE>Datos de la finca</NOMBRE>"
                    .chr(10)."<FECHA>Cumple</FECHA>"
					.chr(10)."<DESCRIPCION>Registro de datos de la finca</DESCRIPCION>"
					.chr(10)."</registro>"
					.chr(10)."</formularios>".chr(10);
		echo $datosxlm;
		return $datosxlm;
}
function lectura_formularios() {
global $empresa;
global $pais;
		$link =  oci_pconnect("SQLADMIN","SQLADMIN","ZEUSMDH") or die(lectura_formularios_error());
		$sql="select * from q_formulario where estado = 1 and empresa=$empresa order by formulario ";		
				
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

function lectura_encuestas_error() {
global $empresa;
global $pais;
		$datosxlm = "<formularios>"
		            .chr(10)."<registro>"
		            .chr(10)."<EMPRESA>1</EMPRESA>"
					.chr(10)."<FORMULARIO>555</FORMULARIO>"
					.chr(10)."<ENCUESTA>1</ENCUESTA>"
                    .chr(10)."<FECHA></FECHA>"
					.chr(10)."<ENCUESTADOR></ENCUESTADOR>"
					.chr(10)."<ENCUESTADO></ENCUESTADO>"
					.chr(10)."<NOTA></NOTA>"
					.chr(10)."<NUM_ENCUESTA></NUM_ENCUESTA>"
					.chr(10)."</registro>"
					.chr(10)."</formularios>".chr(10);
		echo $datosxlm;
		return $datosxlm;
}
function lectura_encuestas() {
global $empresa;
global $pais;
		$link =  oci_pconnect("SQLADMIN","SQLADMIN","ZEUSMDH") or die(lectura_encuestas_error());
		$sql="select * from q_encuesta where estado = 1 and empresa = $empresa order by formulario, encuesta ";		
				
		$result = oci_parse($link,$sql);
		oci_execute($result);
		$datosxlm = "<preguntas>";
		while (oci_fetch($result)) 
           { 	
				$datosxlm .= chr(10)."<registro>"
				.lectura($result,'EMPRESA','EMPRESA')
				.lectura($result,'FORMULARIO','FORMULARIO')
				.lectura($result,'ENCUESTA','ENCUESTA')
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

function lectura_encuestado_error() {
global $empresa;
global $pais;
		$datosxlm = "<formularios>"
		            .chr(10)."<registro>"
					.chr(10)."<ENCUESTADO>1</ENCUESTADO>"
					.chr(10)."<NOMBRE></NOMBRE>"
					.chr(10)."</registro>"
					.chr(10)."</formularios>".chr(10);
		echo $datosxlm;
		return $datosxlm;
}
function lectura_encuestado() {
global $empresa;
global $pais;
		$link =  oci_pconnect("SQLADMIN","SQLADMIN","ZEUSMDH") or die(lectura_encuestado_error());
		$sql="select * from q_encuestado where empresa=$empresa order by encuestado ";		
				
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
function lectura_grupos_error() {
global $empresa;
global $pais;
		$datosxlm = "<formularios>"
		            .chr(10)."<registro>"
					.chr(10)."<EMPRESA>1</EMPRESA>"
					.chr(10)."<FORMULARIO></FORMULARIO>"
					.chr(10)."<GRUPO></GRUPO>"
					.chr(10)."<NOMBRE></NOMBRE>"
					.chr(10)."</registro>"
					.chr(10)."</formularios>".chr(10);
		echo $datosxlm;
		return $datosxlm;
}
function lectura_grupos() {
global $empresa;
global $pais;
		$link =  oci_pconnect("SQLADMIN","SQLADMIN","ZEUSMDH") or die(lectura_grupos_error());
		$sql="select * from q_grupo where estado = 1 and empresa=$empresa order by formulario, grupo, orden ";		
				
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
function lectura_opciones_error() {
global $empresa;
global $pais;
		$datosxlm = "<formularios>"
		            .chr(10)."<registro>"
					.chr(10)."<EMPRESA>1</EMPRESA>"
					.chr(10)."<FORMULARIO></FORMULARIO>"
					.chr(10)."<GRUPO></GRUPO>"
					.chr(10)."<PREGUNTA></PREGUNTA>"
					.chr(10)."<OPCION></OPCION>"
					.chr(10)."<NOMBRE></NOMBRE>"
					.chr(10)."</registro>"
					.chr(10)."</formularios>".chr(10);
		echo $datosxlm;
		return $datosxlm;
}
function lectura_opciones() {
global $empresa;
global $pais;
		$link =  oci_pconnect("SQLADMIN","SQLADMIN","ZEUSMDH") or die(lectura_opciones_error());
		$sql="select * from q_opcion where empresa=$empresa and estado = 1 order by formulario, grupo, pregunta, opcion";						
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
function lectura_pais() {
global $empresa;
global $pais;
		$link =  oci_pconnect("SQLADMIN","SQLADMIN","ZEUSMDH") or die(lectura_opciones_error());
		$sql="select * from pais where pais in $pais ";	
	
		$result = oci_parse($link,$sql);
		oci_execute($result);
		$datosxlm = "<paises>";
		while (oci_fetch($result)) 
           { 	
				$datosxlm .= chr(10)."<registro>"
				.lectura($result,'PAIS','PAIS')
				.lectura($result,'NOMBRE','NOMBRE')
				.chr(10)."</registro>";
		}  
	$datosxlm .= chr(10)."</paises>".chr(10);	
 return $datosxlm;
}
function lectura_departamento() {
global $empresa;
global $pais;
		$link =  oci_pconnect("SQLADMIN","SQLADMIN","ZEUSMDH") or die(lectura_opciones_error());
		$sql="select * from departamento where pais in $pais ";	
		$result = oci_parse($link,$sql);
		oci_execute($result);
		$datosxlm = "<departamentos>";
		while (oci_fetch($result)) 
           { 	
				$datosxlm .= chr(10)."<registro>"
				.lectura($result,'PAIS','PAIS')
				.lectura($result,'DEPTO','DEPTO')
				.lectura($result,'NOMBRE','NOMBRE')
				.chr(10)."</registro>";
		}  
	$datosxlm .= chr(10)."</departamentos>".chr(10);	
 return $datosxlm;
}
function lectura_ciudad() {
global $empresa;
global $pais;
		$link =  oci_pconnect("SQLADMIN","SQLADMIN","ZEUSMDH") or die(lectura_opciones_error());
		$sql="select * from ciudad where pais in $pais ";	
		$result = oci_parse($link,$sql);
		oci_execute($result);
		$datosxlm = "<ciudad>";
		while (oci_fetch($result)) 
           { 	
				$datosxlm .= chr(10)."<registro>"
				.lectura($result,'PAIS','PAIS')
				.lectura($result,'DEPTO','DEPTO')
				.lectura($result,'CIUDAD','CIUDAD')
				.lectura($result,'NOMBRE','NOMBRE')
				.chr(10)."</registro>";
		}  
	$datosxlm .= chr(10)."</ciudad>".chr(10);	
 return $datosxlm;
}
function lectura_respuestas_error() {
global $empresa;
global $pais;
		$datosxlm = "<formularios>"
		            .chr(10)."<registro>"
					.chr(10)."<EMPRESA>1</EMPRESA>"
					.chr(10)."<FORMULARIO></FORMULARIO>"
					.chr(10)."<ENCUESTA></ENCUESTA>"
					.chr(10)."<PREGUNTA></PREGUNTA>"
					.chr(10)."<OPCION></OPCION>"
					.chr(10)."<VALOR_TXT></VALOR_TXT>"
					.chr(10)."<VALOR_NUM></VALOR_NUM>"
					.chr(10)."<VALOR_FECHA></VALOR_FECHA>"
					.chr(10)."<ACCION_CORRECTIVA></ACCION_CORRECTIVA>"
					.chr(10)."<ACCION_RESPONSABLE></ACCION_RESPONSABLE>"
					.chr(10)."<ACCION_INDICADOR></ACCION_INDICADOR>"
					.chr(10)."<ACCION_FECHA></ACCION_FECHA>"
					.chr(10)."<FECHA></FECHA>"
					.chr(10)."<FECHA_PLAN></FECHA_PLAN>"
					.chr(10)."<CORRELATIVO></CORRELATIVO>"
					.chr(10)."<PLAN_ACCION></PLAN_ACCION>"
					.chr(10)."<LISTA_EDITABLE></LISTA_EDITABLE>"
					.chr(10)."</registro>"
					.chr(10)."</formularios>".chr(10);
		echo $datosxlm;
		return $datosxlm;
}
function lectura_respuestas() {
global $empresa;
global $pais;
		$link =  oci_pconnect("SQLADMIN","SQLADMIN","ZEUSMDH") or die(lectura_respuestas_error());
		$sql="select * from q_respuesta where estado = 1 and empresa=$empresa order by formulario, encuesta, pregunta, opcion";		
				
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


function lectura_fincas_error() {
global $empresa;
global $pais;
		$datosxlm = "<formularios>"
		            .chr(10)."<registro>"
					.chr(10)."<EMPRESA>1</EMPRESA>"
					.chr(10)."<PRODUCTOR>1</PRODUCTOR>"
					.chr(10)."<FINCA>1</FINCA>"
					.chr(10)."<PRODUCTOR_INTERNO>1</PRODUCTOR_INTERNO>"
					.chr(10)."<NOMBRE>1</NOMBRE>"
					.chr(10)."<DIRECCION>1</DIRECCION>"
					.chr(10)."<PAIS>1</PAIS>"
					.chr(10)."<DEPTO>1</DEPTO>"
					.chr(10)."<CIUDAD>1</CIUDAD>"
					.chr(10)."<LATITUD>1</LATITUD>"
					.chr(10)."<LONGITUD>1</LONGITUD>"
					.chr(10)."<AREA_TOTAL>1</AREA_TOTAL>"
					.chr(10)."<AREA_CULTIVADA>1</AREA_CULTIVADA>"
					.chr(10)."<LOTES>1</LOTES>"
					.chr(10)."<TRAB_PERMANENTES>1</TRAB_PERMANENTES>"
					.chr(10)."<TRAB_TEMPORALES>1</TRAB_TEMPORALES>"
					.chr(10)."<TRAB_COSECHA>1</TRAB_COSECHA>"
					.chr(10)."<COSECHA_PROMEDIO>1</COSECHA_PROMEDIO>"
					.chr(10)."<TRAB_EN_FINCA>1</TRAB_EN_FINCA>"
					.chr(10)."<ALTITUD>1</ALTITUD>"
					.chr(10)."<CUERPOS_AGUA></CUERPOS_AGUA>"
					.chr(10)."<AGUA_POTABLE></AGUA_POTABLE>"
					.chr(10)."<TIENE_BODEGA></TIENE_BODEGA>"
					.chr(10)."<TIENE_OFICINA></TIENE_OFICINA>"
					.chr(10)."<TIENE_BENEFICIO></TIENE_BENEFICIO>"
					.chr(10)."<TIENE_VIVIENDAS></TIENE_VIVIENDAS>"
					.chr(10)."<TIPO_CUERPO_AGUA></TIPO_CUERPO_AGUA>"
					.chr(10)."<AREA_TOTAL_NUM></AREA_TOTAL_NUM>"
					.chr(10)."<QQ_PROMEDIO></QQ_PROMEDIO>"
					.chr(10)."<fotografia></fotografia>"
					.chr(10)."<a_realizada></a_realizada>"
					.chr(10)."<a_pendiente></a_pendiente>"
					.chr(10)."<a_anulada></a_anulada>"
					.chr(10)."<m_realizada></m_realizada>"
					.chr(10)."<m_pendiente></m_pendiente>"
					.chr(10)."<m_anulada></m_anulada>"
					.chr(10)."</registro>"
					.chr(10)."</formularios>".chr(10);
		echo $datosxlm;
		return $datosxlm;
}
function lectura_fincas() {
global $empresa;
global $pais;
		$link =  oci_pconnect("SQLADMIN","SQLADMIN","ZEUSMDH") or die(lectura_fincas_error());
		$sql="select EMPRESA, (productor*100000 + finca)  FINCA, (PRODUCTOR * 1000) PRODUCTOR, PRODUCTOR_INTERNO, NOMBRE";
		$sql.=", DIRECCION, PAIS, DEPTO, CIUDAD, LATITUD, LONGITUD, AREA_TOTAL";
		$sql.=", AREA_CULTIVADA, LOTES, TRAB_PERMANENTES, TRAB_TEMPORALES, TRAB_COSECHA";
		$sql.=", COSECHA_PROMEDIO, TRAB_EN_FINCA, ALTITUD, CUERPOS_AGUA, AGUA_POTABLE";
		$sql.=", TIENE_BODEGA, TIENE_OFICINA, TIENE_BENEFICIO, TIENE_VIVIENDAS, TIPO_CUERPO_AGUA"; 
		$sql.=", AREA_TOTAL_NUM, QQ_PROMEDIO, FOTOGRAFIA, ACTIVIDAD_REALIZADA, ACTIVIDAD_PENDIENTE";
		$sql.=", CELULAR, TELEFONO, EMAIL, FECHA_INICIO_COSECHA ";
		$SQL.=", AREA_OTROS_CULTIVOS, AREA_ONSERVACION, DENSIDAD_SIEMBRA ";
		$sql.=", ACTIVIDAD_ANULADA, METRICA_REALIZADA, METRICA_PENDIENTE, METRICA_ANULADA ";
		$sql.=" from vc_finca where estado = 1 AND EMPRESA=$empresa and pais in $pais order by productor, finca";		
	

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
				.lectura($result,'AREA_OTROS_CULTIVOS','AREA_OTROS_CULTIVOS')
				.lectura($result,'AREA_COSERVACION','AREA_CONSERVACION')
				.lectura($result,'DENSIDAD_SIEMBRA','DENSIDAD_SIEMBRA')
				.lectura($result,'CELULAR','CELULAR')
				.lectura($result,'TELEFONO','TELEFONO')
				.lectura($result,'EMAIL','EMAIL')
				.lectura($result,'FECHA_INICIO_COSECHA','FECHA_INICIO_COSECHA')
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
				.lectura($result,'FOTOGRAFIA','FOTOGRAFIA')
				.lectura($result,'ACTIVIDAD_REALIZADA','ACTIVIDAD_REALIZADA')
				.lectura($result,'ACTIVIDAD_PENDIENTE','ACTIVIDAD_PENDIENTE')
				.lectura($result,'ACTIVIDAD_ANULADA','ACTIVIDAD_ANULADA')
				.lectura($result,'METRICA_REALIZADA','METRICA_REALIZADA')
				.lectura($result,'METRICA_PENDIENTE','METRICA_PENDIENTE')
				.lectura($result,'METRICA_ANULADA','METRICA_ANULADA')
				
				.chr(10)."</registro>";
		}  
	$datosxlm .= chr(10)."</preguntas>".chr(10);	
 return $datosxlm;
}
function lectura_productores_error() {
global $empresa;
global $pais;
		$datosxlm = "<formularios>"
		            .chr(10)."<registro>"
					.chr(10)."<EMPRESA>1</EMPRESA>"
					.chr(10)."<PRODUCTOR></PRODUCTOR>"
					.chr(10)."<NOMBRE></NOMBRE>"
					.chr(10)."<NIT></NIT>"
					.chr(10)."<NUM_IDENTIFICACION></NUM_IDENTIFICACION>"
					.chr(10)."<FECHA_NACIMIENTO></FECHA_NACIMIENTO>"
					.chr(10)."<DIRECCION></DIRECCION>"
					.chr(10)."<TELFONOS></TELFONOS>"
					.chr(10)."<EMAIL></EMAIL>"
					//.chr(10)."<PRODUCTOR_INTERNO></PRODUCTOR_INTERNO>"
					.chr(10)."<fotografia></fotografia>"
					.chr(10)."<a_realizada></a_realizada>"
					.chr(10)."<a_pendiente></a_pendiente>"
					.chr(10)."<a_anulada></a_anulada>"
					.chr(10)."<m_realizada></m_realizada>"
					.chr(10)."<m_pendiente></m_pendiente>"
					.chr(10)."<m_anulada></m_anulada>"
					.chr(10)."</registro>"
					.chr(10)."</formularios>".chr(10);
		echo $datosxlm;
		return $datosxlm;
}
function lectura_productores() {
global $empresa;
global $pais;
		$link =  oci_pconnect("SQLADMIN","SQLADMIN","ZEUSMDH") or die(lectura_productores_error());
		
		$sql  =" select empresa,  (productor*100000) as productor, nombre, nit, num_identificacion, tipo_identificacion, ";
		$sql .=" fecha_nacimiento, direccion, telfonos, email, pais, depto, ciudad, fotografia, ";
		$sql .=" actividad_realizada, actividad_pendiente, actividad_anulada, ";
		$sql .=" metrica_realizada, metrica_pendiente, metrica_anulada ";
		$sql .=" from vc_productor where estado = 1 and empresa=$empresa and pais in $pais ";

		//$sql = "select * from vc_productor ";
		$result = oci_parse($link,$sql);
		oci_execute($result);
		$datosxlm = "<productores>";
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
				.lectura($result,'PAIS','PAIS')
				.lectura($result,'DEPTO','DEPTO')
				.lectura($result,'CIUDAD','CIUDAD')
				//.lectura($result,'PRODUCTOR_INTERNO','PRODUCTOR_INTERNO')
				.lectura($result,'FOTOGRAFIA','fotografia')
				.lectura($result,'ACTIVIDAD_REALIZADA','a_realizada')
				.lectura($result,'ACTIVIDAD_PENDIENTE','a_pendiente')
				.lectura($result,'ACTIVIDAD_ANULADA','a_anulada')
				.lectura($result,'METRICA_REALIZADA','m_realizada')
				.lectura($result,'METRICA_PENDIENTE','m_pendiente')
				.lectura($result,'METRICA_ANULADA','m_anulada')
				.chr(10)."</registro>";
		}  
	$datosxlm .= chr(10)."</productores>".chr(10);
 return $datosxlm;
}


function lectura_productores_promotores() {
global $empresa;
global $pais;
		$link =  oci_pconnect("SQLADMIN","SQLADMIN","ZEUSMDH") or die(lectura_productores_error());
		
		$sql .= "select vf.empresa empresa, vf.promotor productor, vf.nombre || ' ' || vf.apellidos nombre, null nit, null num_identificacion, null tipo_identificacion, ";
//        $sql .= "vf.supervisor SUPERVISOR, vf.celular CELULAR,  ";
	    $sql .= " null fecha_nacimiento, vf.direccion direccion, vf.telefono telfonos, vf.email email, vf.pais pais, vf.depto depto, vf.ciudad ciudad, vf.fotografia fotografia, ";	
        //$sql .= " vf.gps_latitud GPS_LATITUD, vf.gps_longitud GPS_LONGITUD, ";
        $sql .= "vf.actividad_realizada ACTIVIDAD_REALIZADA, vf.actividad_pendiente ACTIVIDAD_PENDIENTE, vf.actividad_anulada ACTIVIDAD_ANULADA,"; 
		$sql .= "vf.metrica_realizada METRICA_REALIZADA, vf.metrica_pendiente METRICA_PENDIENTE, vf.metrica_anulada METRICA_ANULADA ";
		//$sql .= "vf.titulo TITULO,vf.formulario FORMULARIO, vf.formulario2 FORMULARIO2,";
		//$sql .= "vf.nivel NIVEL "; 
        $sql .= "from promotor vf where estado = 1 and empresa=$empresa and pais in $pais ";
  	    $sql .= " union ";
		$sql .=" select empresa,  (productor*100000) as productor, nombre, nit, num_identificacion, tipo_identificacion, ";
		$sql .=" fecha_nacimiento, direccion, telfonos, email, pais, depto, ciudad, fotografia, ";
		$sql .=" actividad_realizada, actividad_pendiente, actividad_anulada, ";
		$sql .=" metrica_realizada, metrica_pendiente, metrica_anulada ";
		$sql .=" from vc_productor where estado = 1 and empresa=$empresa and pais in $pais ";

		//$sql = "select * from vc_productor ";
		$result = oci_parse($link,$sql);
		oci_execute($result);
		$datosxlm = "<productores>";
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
				.lectura($result,'PAIS','PAIS')
				.lectura($result,'DEPTO','DEPTO')
				.lectura($result,'CIUDAD','CIUDAD')
				//.lectura($result,'PRODUCTOR_INTERNO','PRODUCTOR_INTERNO')
				.lectura($result,'FOTOGRAFIA','fotografia')
				.lectura($result,'ACTIVIDAD_REALIZADA','a_realizada')
				.lectura($result,'ACTIVIDAD_PENDIENTE','a_pendiente')
				.lectura($result,'ACTIVIDAD_ANULADA','a_anulada')
				.lectura($result,'METRICA_REALIZADA','m_realizada')
				.lectura($result,'METRICA_PENDIENTE','m_pendiente')
				.lectura($result,'METRICA_ANULADA','m_anulada')
				.chr(10)."</registro>";
		}  
	$datosxlm .= chr(10)."</productores>".chr(10);
 return $datosxlm;
}
function lectura_productores_fincas_error() {
global $empresa;
global $pais;
			$datosxlm = "<formularios>"
				.chr(13)."<registro>"
				.chr(10)."<empresa></empresa>"
				.chr(10)."<productor></productor>"
				.chr(10)."<nombres></nombres>"
				.chr(10)."<apellidos></apellidos>"
				.chr(10)."<supervisor></supervisor>"
				.chr(10)."<celular></celular>"
				.chr(10)."<telefono></telefono>"
				.chr(10)."<email></email>"
				.chr(10)."<pais></pais>"
				.chr(10)."<depto></depto>"
				.chr(10)."<ciudad></ciudad>"
				.chr(10)."<latitud></latitud>"
				.chr(10)."<longitud></longitud>"
				.chr(10)."<fotografia></fotografia>"
				.chr(10)."<metrica0101></metrica0101>"
				.chr(10)."<metrica0102></metrica0102>"
				.chr(10)."<metrica0103></metrica0103>"
				.chr(10)."<metrica0201></metrica0201>"
				.chr(10)."<metrica0202></metrica0202>"
				.chr(10)."<metrica0203></metrica0203>"
				.chr(10)."<titulo></titulo>"
				.chr(10)."<formulario></formulario>"
				.chr(10)."<formulario2></formulario2>"
				.chr(10)."<nivel></nivel>"
				.chr(10)."<direccion></direccion>"
				.chr(13)."</registro>"
				.chr(10)."</formularios>".chr(10);
		echo $datosxlm;
		return $datosxlm;
}


function lectura_productores_fincas() {
global $empresa;
global $pais;
		$link =  oci_pconnect("SQLADMIN","SQLADMIN","ZEUSMDH") or die(lectura_productores_fincas_error_error());
    	$sql="";
        $sql = "select vf.empresa EMPRESA, (vf.productor*100000 + vf.finca) PRODUCTOR, vf.nombre NOMBRES, '' APELLIDOS,";
        $sql .= "(vf.productor*100000) SUPERVISOR, vf.celular CELULAR, vf.telefono TELEFONO, vf.email EMAIL, vf.pais PAIS, vf.depto DEPTO,";
        $sql .= "vf.ciudad CIUDAD, vf.latitud GPS_LATITUD, vf.longitud GPS_LONGITUD, vf.fotografia FOTOGRAFIA,";
        $sql .= "vf.actividad_realizada ACTIVIDAD_REALIZADA, vf.actividad_pendiente ACTIVIDAD_PENDIENTE, vf.actividad_anulada ACTIVIDAD_ANULADA,"; 
		$sql .= "vf.metrica_realizada METRICA_REALIZADA, vf.metrica_pendiente METRICA_PENDIENTE, vf.metrica_anulada METRICA_ANULADA,";
		$sql .= "'Finca' TITULO, '1' FORMULARIO, '' FORMULARIO2,";
		$sql .= "5 NIVEL, vf.direccion DIRECCION ";
        $sql .= "from vc_finca vf ";
        $sql .= "where vf.estado=1 and vf.empresa=$empresa "; 		
        $sql .= "union ";
	
        $sql .= "select vf.empresa EMPRESA, vf.promotor PRODUCTOR, vf.nombre NOMBRES, vf.apellidos APELLIDOS,";
        $sql .= "vf.supervisor SUPERVISOR, vf.celular CELULAR, vf.telefono TELEFONO, vf.email EMAIL,"; 
        $sql .= "vf.pais PAIS, vf.depto DEPTO,";
        $sql .= "vf.ciudad CIUDAD, vf.gps_latitud GPS_LATITUD, vf.gps_longitud GPS_LONGITUD, vf.fotografia FOTOGRAFIA,";
        $sql .= "vf.actividad_realizada ACTIVIDAD_REALIZADA, vf.actividad_pendiente ACTIVIDAD_PENDIENTE, vf.actividad_anulada ACTIVIDAD_ANULADA,"; 
		$sql .= "vf.metrica_realizada METRICA_REALIZADA, vf.metrica_pendiente METRICA_PENDIENTE, vf.metrica_anulada METRICA_ANULADA,";
		$sql .= "vf.titulo TITULO,vf.formulario FORMULARIO, vf.formulario2 FORMULARIO2,";
		$sql .= "vf.nivel NIVEL, vf.direccion DIRECCION "; 
        $sql .= "from promotor vf where vf.empresa=$empresa  ";
		$sql .= "union "; 
        $sql .= " select vf.empresa EMPRESA, (vf.productor*100000) PRODUCTOR, vf.nombre NOMBRES, vf.nit APELLIDOS, ";
        $sql .= "vf.supervisor SUPERVISOR, vf.celular CELULAR, vf.telfonos TELEFONO, vf.email EMAIL, "; 
        $sql .= "vf.pais PAIS, vf.depto DEPTO, ";
        $sql .= "vf.ciudad CIUDAD, vf.gps_latitud GPS_LATITUD, vf.gps_longitud GPS_LONGITUD, vf.fotografia FOTOGRAFIA, ";
        $sql .= "vf.actividad_realizada ACTIVIDAD_REALIZADA, vf.actividad_pendiente ACTIVIDAD_PENDIENTE, vf.actividad_anulada ACTIVIDAD_ANULADA,"; 
 		$sql .= "vf.metrica_realizada METRICA_REALIZADA, vf.metrica_pendiente METRICA_PENDIENTE, vf.metrica_anulada METRICA_ANULADA,";
		$sql .= "'Productor' TITULO,'1' FORMULARIO, '2' FORMULARIO2, ";
		$sql .= "4 NIVEL, vf.direccion DIRECCION "; 
        $sql .= "from vc_productor vf where estado = 1 and empresa = $empresa  order by NIVEL, NOMBRES"; 

		
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
				.lectura($result,'ACTIVIDAD_REALIZADA','metrica0101')
				.lectura($result,'ACTIVIDAD_PENDIENTE','metrica0102')
				.lectura($result,'ACTIVIDAD_ANULADA','metrica0103')
				.lectura($result,'METRICA_REALIZADA','metrica0201')
				.lectura($result,'METRICA_PENDIENTE','metrica0202')
				.lectura($result,'METRICA_ANULADA','metrica0203')
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

function escritura_encuesta($empresa2,$formulario,$estado,$productor,$finca,$cosecha) {
global $empresa;
global $pais;
	$link =  oci_pconnect("SQLADMIN","SQLADMIN","ZEUSMDH") or die(oci_error());
	//1. si no existe la encuesta se crea  encuesta y sus respuestas se crean por trigger
	   $sql="insert into q_encuesta (empresa, formulario, estado, encuesta,usuario,productor,finca,cosecha)".
              "	values ($empresa2,$formulario,$estado,(select encuesta from (select encuesta  from q_encuesta order by encuesta DESC )where rownum < 2 )+1,'MOVIL',$productor,$finca,'$cosecha')";	   
//echo $sql;
	   
	   $result = oci_parse($link,$sql); 
	   oci_execute($result);
	//2. localizar la encuesta que se creo, como corre desde el mismo webservice, no hay problema de colision o creaciones simultaneas
	   $sql="select encuesta from (select encuesta  from q_encuesta order by encuesta DESC )where rownum < 2 ";
	   $result = oci_parse($link,$sql); oci_execute($result); oci_fetch($result);
	   $encuesta = oci_result($result, "ENCUESTA");
	//3. se colocan todas las preguntas como que si cumplen
	   $sql = "update q_respuesta set opcion = 3 where empresa=$empresa2 and  formulario=$formulario and encuesta=$encuesta ";
	   
	   $result = oci_parse($link,$sql); 
	   oci_execute($result);
	   
	return $encuesta;
}
function escritura_respuestas() {
global $empresa;
global $pais;
//http://200.30.150.165:8080/webservidor/index.php?leer=27&campos=21,3,1,0,3,2,01-06-15,1,401,1,2014-2015&ac=ejemplo,1.&ar=ejemlo,2.2&ai=ejemplo33
    $CAMPOS_RECIBIDOS = $_REQUEST['campos'];
	$CAMPOS = explode(",",$CAMPOS_RECIBIDOS);
	$empresa2     = $CAMPOS[0]; 
	$formulario  = $CAMPOS[1];
	$grupo       = $CAMPOS[2];
	$pregunta    = $CAMPOS[4];
	$opcion             = $CAMPOS[5];
	$accion_correctiva  = $_REQUEST['ac'];
	$accion_responsable = $_REQUEST['ar'];
	$accion_indicador   = $_REQUEST['ai'];
    $accion_fecha       =  $CAMPOS[6];
	$estado     = $CAMPOS[7];
	$productor  = $CAMPOS[8];
	$finca      = $CAMPOS[9];
	$cosecha    = $CAMPOS[10];
	if($CAMPOS[3]==0) {
	      $encuesta   = escritura_encuesta($empresa2,$formulario,$estado,$productor,$finca,$cosecha);} 
    else
	     {$encuesta   = $CAMPOS[3];}
	$link =  oci_pconnect("SQLADMIN","SQLADMIN","ZEUSMDH") or die(oci_error());
	
	//4. se actualiza respuesta de  pregunta  y su plan de accion
	   $sql="update q_respuesta set opcion=$opcion, accion_correctiva='$accion_correctiva', accion_responsable='$accion_responsable', accion_indicador='$accion_indicador', accion_fecha_texto='$accion_fecha' " .
	        " where empresa=$empresa2 and  formulario=$formulario and encuesta=$encuesta and pregunta=$pregunta and grupo=$grupo";
  	   $result = oci_parse($link,$sql);
	   oci_execute($result);
     return $encuesta;
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
case 11: echo lectura_pais(); break;
case 12: echo lectura_departamento(); break;
case 13: echo lectura_ciudad(); break;
case 14: echo lectura_productores_promotores(); break;

case 21: echo escritura_preguntas(); break;
case 22: echo escritura_formularios(); break;
case 23: echo escritura_encuestas(); break;
case 24: echo escritura_encuestado(); break;
case 25: echo escritura_grupos(); break;
case 26: echo escritura_opciones(); break;
case 27: echo escritura_respuestas(); break;
case 28: echo escritura_fincas(); break;
case 29: echo escritura_productores(); break;
case 30: echo esctitura_promotres(); break;


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
echo "<li><a onclick='lectura(11);'>Paises</a></li>";
echo "<li><a onclick='lectura(12);'>Departamentos</a></li>";
echo "<li><a onclick='lectura(13);'>Ciudades</a></li>";
echo "<li><a onclick='lectura(14);'>Productores-Promotores</a></li>";

echo "</ul>";

echo "<script>   function lectura(leer){window.location = 'index.php?leer='+leer;}</script>";
}
?>