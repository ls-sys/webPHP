<?php

	function getStatusCodeMessage($status)
	{
		// these could be stored in a .ini file and loaded
		// via parse_ini_file()... however, this will suffice
		// for an example
		$codes = Array(
			100 => 'Continue',
			101 => 'Switching Protocols',
			200 => 'OK',
			201 => 'Created',
			202 => 'Accepted',
			203 => 'Non-Authoritative Information',
			204 => 'No Content',
			205 => 'Reset Content',
			206 => 'Partial Content',
			300 => 'Multiple Choices',
			301 => 'Moved Permanently',
			302 => 'Found',
			303 => 'See Other',
			304 => 'Not Modified',
			305 => 'Use Proxy',
			306 => '(Unused)',
			307 => 'Temporary Redirect',
			400 => 'Bad Request',
			401 => 'Unauthorized',
			402 => 'Payment Required',
			403 => 'Forbidden',
			404 => 'Not Found',
			405 => 'Method Not Allowed',
			406 => 'Not Acceptable',
			407 => 'Proxy Authentication Required',
			408 => 'Request Timeout',
			409 => 'Conflict',
			410 => 'Gone',
			411 => 'Length Required',
			412 => 'Precondition Failed',
			413 => 'Request Entity Too Large',
			414 => 'Request-URI Too Long',
			415 => 'Unsupported Media Type',
			416 => 'Requested Range Not Satisfiable',
			417 => 'Expectation Failed',
			500 => 'Internal Server Error',
			501 => 'Not Implemented',
			502 => 'Bad Gateway',
			503 => 'Service Unavailable',
			504 => 'Gateway Timeout',
			505 => 'HTTP Version Not Supported'
		);
	 
		return (isset($codes[$status])) ? $codes[$status] : '';
	}
	 
	// Helper method to send a HTTP response code/message
	function sendResponse($status = 200, $body = '', $content_type = 'text/html')
	{
		$status_header = 'HTTP/1.1 ' . $status . ' ' . getStatusCodeMessage($status);
		header($status_header);
		header('Access-Control-Allow-Origin: *');
		header('Content-type: ' . $content_type);
		header('Cache-Control: no-cache');
		echo $body;
	}

	$generalog = 0;  // = 1 genera log, != 1 no genera log.
	if ( $generalog == 1 ){
		$log = fopen('log.txt','a');
		fwrite($log,"ingresando a mediador.php".chr(10).chr(13));	
	}
	
	$conn = oci_connect("SQLADMIN","SQLADMIN","ZEUSMDH") or die("Conexion fallida \n". oci_error());
	if ( $generalog == 1 )
		fwrite($log,"conexion establecida...".chr(10).chr(13));
	
	if (isset ($_REQUEST["user"]) && isset ($_REQUEST["pwd"]))
	{
		$UserName = $_REQUEST["user"];
		$UserPasWord = $_REQUEST["pwd"];
		if ( $generalog == 1 ){
			fwrite($log," usr = ".$UserName.chr(10).chr(13));
			fwrite($log," pwd = ".$UserPasWord.chr(10).chr(13));
		}
		$sql = "Select 
					sqladmin.validar_password('$UserName', '$UserPasWord') as Res,
					(
						Select 
							full_name
						from sqladmin.internal_user
						where username = upper('$UserName')
					) as NOMBRE,
					(
						select promotor FROM sqladmin.promotor where usuario_login = upper('$UserName')
					) as PROMOTOR,
					(Select nvl(max(linea),0) + 1 from vc_foto where usuario = upper('$UserName')) as MAX_FOTO
		 		from dual";
		
		$rs = oci_parse($conn, $sql);
		
		oci_execute($rs);
		
		$ok = -1;
		$nombre = "";
		$PromotorCode = "";
		$MaxFoto = 0;		
		if ($row = oci_fetch_array($rs, OCI_BOTH))
		{
			$ok = $row['RES'];
			$nombre = $row[1];
			$PromotorCode = $row[2];
			$MaxFoto = $row['MAX_FOTO'];
		}
		
		$userEmpresa = 0;
		$userPais = 0;
		
		if ($ok == 1)
		{
			$sql = "Select 
						e.pais,
						u.empresa_default
					from 
						sqladmin.empresa e,
						sqladmin.usuario u 
					where e.empresa = u.empresa_default
					and   u.usuario = upper('$UserName')";
			
			$rs = oci_parse($conn, $sql);
			
			oci_execute($rs);
			
			if ($row = oci_fetch_array($rs, OCI_BOTH))
			{
				$userPais = $row[0];
				$userEmpresa = $row[1];
			}
			
			$sql = "insert into sqladmin.wb_dia(usuario,fecha, ipaddr, nota, ingresos, ult_ingreso, tipo, token, resultado, tipo_resultado, session_id  ) 
                     values('$UserName', sysdate, 'movil', 'acceso exitoso', 1, sysdate,  1 , null, 1, 1, null )";

		    $rs = oci_parse($conn, $sql);
			
			oci_execute($rs);
		}
		else
		{
			$sql = "insert into sqladmin.wb_dia(usuario,fecha, ipaddr, nota, ingresos, ult_ingreso, tipo, token, resultado, tipo_resultado, session_id  ) 
                     values('$UserName', sysdate, 'movil', 'acceso denegado', 1, sysdate,  1 , null, 2, 2, null )";

		    $rs = oci_parse($conn, $sql);
			
			oci_execute($rs);		
		}
		
		$resultPkg = array 
		(
			"ingreso" => $ok,
			"nombreUser" => $nombre,
			"usr" => $UserName,
			"usrEmpresa" => $userEmpresa,
			"usrPais" => $userPais,
			"promotor" => $PromotorCode,
			"max_foto" => $MaxFoto
		);
		
		sendResponse(200, json_encode($resultPkg));	
		//echo json_encode($resultPkg);
	}
	
	if (isset($_REQUEST["cmd"]))
	{
		$Command = $_REQUEST["cmd"];
		
		switch($Command)
		{
			case "GetData":
			
				if (isset($_REQUEST["tableName"]) || isset($_REQUEST["usuario"]) || isset($_REQUEST["empresa"]) || isset($_REQUEST["pais"]))
				{
				
					$NombreTabla = $_REQUEST["tableName"];
					$usr = $_REQUEST["usuario"];
					$emp = $_REQUEST["empresa"];
					$pais = $_REQUEST["pais"];
					
					$DatosSalida = array();
					
					echo $NombreTabla;
					foreach($NombreTabla as $Tabla)
					{
						echo $Tabla;
						switch($Tabla)
						{
						
							case "empresa":
								$sql = "Select 
										empresa,
										nombre,
										pais,
										depto,
										ciudad,
										nombre_corto,
										activa
									from empresa
									where empresa = $emp";
								break;
							case "pais": // filtro por pais
							case "departamento":
							case "ciudad":
								$sql = "Select * from sqladmin.$Tabla
										where pais = $pais";
								break;
							case "promotor":  // solo por empresa 
							case "vc_grupo": // solo por empresa 
							case "vc_productor": // promotor y empresa
							case "vc_finca": // promotor y empresa
							case "q_formulario": // empresa
							case "vc_variedad": //  empresa
							case "vc_tipo_suelo": //  empresa
							case "q_encuesta": // promotor y empresa
							case "q_grupo": // empresa
								$sql = "Select * from sqladmin.$Tabla
										where empresa = $emp
										and  ";
								break;
							default: // sin filtro
								$sql = "Select * from sqladmin.$Tabla";
								break;
						}
						
						echo $sql;
						$rs = oci_parse($conn, $sql);
			
						oci_execute($rs);
						
						$salida = "";
						$typos = "";
						
						while ($row = oci_fetch_array($rs, OCI_ASSOC))
						{
							$InsetSQl = "Insert into m_$Tabla (";
							$ValuesSQl = "Values (";
							
							
							foreach ($row as $key => $value)
							{
								$InsetSQl .= $key . ", ";
								switch (oci_field_type($rs, $key))
								{
									case "NUMBER":
										$ValuesSQl .= $value . ", ";
										break;	
									case "VARCHAR2":
										$ValuesSQl .= "'" . $value . "', ";
										break;
									default:
										$ValuesSQl .= "'" . $value . "', ";
										break;
								}
								
							}
							
							$InsetSQl .= ")";
							$ValuesSQl .= ")";
							
							$InsetSQl = str_replace (", )", ")", $InsetSQl);
							$ValuesSQl = str_replace(", )", ")", $ValuesSQl); 
							
							$salida .= $InsetSQl . $ValuesSQl . ";";
						}
						
						$DatosSalida [] = utf8_encode($salida);
					}
					
					$resultPkg = array 
					(
						"SQls" => $DatosSalida
					);
					
					sendResponse(200, json_encode($resultPkg));	
				}
				else
				{
					sendResponse(400, "faltan Parametros");	
				}
				break;
			
			case "CreateTables":
				if (isset($_REQUEST["ListaTablas"]))
				{
					$lisa = $_REQUEST["ListaTablas"];
					
					
					$salida = array("CREATE TABLE IF NOT EXISTS m_p_usuario ( usr TEXT NOT NULL, empresa NUMERIC NOT NULL, pais NUMERIC NOT NULL);", 
									"CREATE TABLE IF NOT EXISTS m_empresa ( empresa NUMERIC NOT NULL,
																			nombre TEXT,
																			pais NUMERIC,
																			depto NUMERIC,
																			ciudad NUMERIC,
																			nombre_corto NUMERIC ,
																			activa NUMERIC );");
					
					foreach($lisa as $value)
					{
						$sql = "Select Column_name, decode (data_type,
																'NUMBER', 'NUMERIC',
																'VARCHAR2', 'NUMERIC',
																'TEXT') as data_type, 
									DECODE (nullable, 'N', 'NOT NULL',
													  'Y', ' ') AS notnull
								from all_tab_columns
								where owner = upper('sqladmin')
								and   table_name = upper('$value')";
						
					
						$rs = oci_parse($conn, $sql);
			
						oci_execute($rs);
						
						$createSQL = "CREATE TABLE IF NOT EXISTS m_$value (";
						
						while ($row = oci_fetch_array($rs, OCI_NUM))
						{
							$createSQL .= $row[0] . " " . $row[1] . " " . $row[2] .  ", ";
						}
						
						$createSQL .= ")";
						
						$createSQL = str_replace(", )", ")", $createSQL);
						array_push($salida,($createSQL . "; \n"));
					}
					
					$resultPkg = array 
					(
						"SQls" => $salida
					);
					
					sendResponse(200, json_encode($resultPkg));	
				}
				else
				{
					sendResponse(400, "faltan Parametros");	
				}
				break;
			case "SendFoto":
				if (isset($_REQUEST["strFoto"]))
				{
					$Linea = $_REQUEST["linea"];
					$usuario = $_REQUEST["usuario"];
					
					$sql = "Insert into vc_foto_movil 
							( 
								linea, foto_base64, usuario
							)
							values 
							(
								$Linea , :strFoto, '$usuario'
							)";
							
					$stmt = oci_parse($conn, $sql);
					
					$StrFoto = $_REQUEST["strFoto"];
					
					oci_bind_by_name($stmt, ':strFoto', $StrFoto);
					
					if (!oci_execute($stmt))
					{
						$resError = array 
									(
										"errorServer" => "Error .. "
									);
									sendResponse(500, json_encode($resError));
					}					
					$REs = array 
					(
						"OK" => "1"
					);
					sendResponse(200, json_encode($REs));
				}
				break;
			case "SendDataFormMovil":
				if (isset($_REQUEST["Data"]))
				{
					try
					{	
						$Datos = $_REQUEST["Data"];
						$pEmpresa = $_REQUEST["Empresa"];
						$pUsr = $_REQUEST["User"];
						$Log = 0; 
			
						$res2M = array();
						
						$sql = "Select nvl(genlog, 0) as res
								from internal_user
								where username = upper('$pUsr')";
								
						$rs = oci_parse($conn, $sql);
				
						oci_execute($rs);
						
						if ($row = oci_fetch_array($rs, OCI_NUM))
						{
							$Log = $row[0];
						}
						
						oci_free_statement($rs);
						
						foreach($Datos as $subData)
						{
							$Salida = "";
							if (isset($subData["data"]))
							{
								$tableNameMovil = $subData["tablaName"] . "_movil";
								$ListTablas = array 
								(
									"tableName" => $subData["tablaName"],
									"idList" => array ()
								);
								
								foreach($subData["data"] as $val)
								{
									if (isset($val["id"]))
										array_push($ListTablas["idList"], $val["id"]);
									/*else
										if (!isset($val["id"]) && $tableNameMovil != "promotor_gps_movil")
											throw new Exception("No tiene el ID, tabla $tableNameMovil") ;*/
																			
									$NewReg = $val["fuente"] . "";
									
									unset($val["fuente"]);
									
									unset($val["id"]);
									
									if (!isset($val["usuario"]))
										$val["usuario"] = $pUsr;
								
									$InsetSQl = "Insert into $tableNameMovil (";
									$ValuesSQl = "Values (";
									
									//and   table_name = upper('" . $subData["tablaName"] . "')
									$sql = "Select lower(Column_name), data_type
											from all_tab_columns
											where owner = upper('sqladmin')
											and   table_name = upper('$tableNameMovil')";
											
									$rs = oci_parse($conn, $sql);
					
									oci_execute($rs);
								
									while ($row = oci_fetch_array($rs, OCI_NUM))
									{
										$colName = $row[0];
										$dataType = $row[1];
										
										if (isset($val[$colName]))
										{								
											if ($val[$colName] != "undefined" || $val[$colName] != "" || $val[$colName] != null)
											{
												$InsetSQl .= $colName . ", ";
												
												switch($dataType)
												{
													case "NUMBER":
														if ($val[$colName] == "Empty")
															$ValuesSQl .= "null, ";
														else	
															$ValuesSQl .= str_replace(",", ".", $val[$colName]) . ", ";
														break;
													case "VARCHAR2":
														$ValuesSQl .= "'" . $val[$colName] . "', ";
														break;
													case "DATE":
														$temDate = strtotime($val[$colName]);
														$ValuesSQl .= "TO_DATE('" . date("d-m-Y H:i:s", $temDate) . "', 'dd-mm-yyyy HH24:MI:SS'), ";
														break;
													default:
														$ValuesSQl .= "'" . $val[$colName] . "', ";
														break;
												}
											}
										}
									}
								
									oci_free_statement($rs);
									
									$InsetSQl .= ")";
									$ValuesSQl .= ")";
									
									$InsetSQl = str_replace (", )", ", verifica)", $InsetSQl);
									if ($NewReg == "2")
										$ValuesSQl = str_replace(", )", ", 1)", $ValuesSQl); 
									else
										$ValuesSQl = str_replace(", )", ", 2)", $ValuesSQl); 
										
									
									
									$Salida .= $InsetSQl . $ValuesSQl . "; ";
																	
									
									
									if ($Log > 0)
									{
										$Salida .= "\n";
										
									}
								}
								
								
								$Salida = "BEGIN   " . $Salida . "   END; \n";
								
								$Salida = str_replace("'Empty'", "null", $Salida);
								
								if ($Log > 0)
								{
									file_put_contents("logs_usr/$pUsr($Log).txt",$Salida, FILE_APPEND);
								}
								else
								{
									$comp = oci_parse($conn, $Salida);
									
									try
									{
										if (oci_execute($comp))
										{
											array_push($res2M, $ListTablas);
										}
										else
										{
											$err = oci_error($conn);
											$resError = array 
											(
												"errorServer" => "",
												"query" => $Salida
											);
											sendResponse(500, json_encode($resError));
											file_put_contents("logError.txt",json_encode($resError) . "/n", FILE_APPEND);
											exit;
										}
									}
									catch(Exception $e)
									{
										$err = oci_error($conn);
										$resError = array 
											(
												"errorServer" => $err,
												"query" => $Salida
											);
											sendResponse(500, json_encode($resError));
										
										exit;
									}
								}
							}
							
						}
						if ($Log > 0)
						{
							$resError = array 
									(
										"errorServer" => "Internal error, please contact your provider",
										"query" => "Internal error, please contact your provider"
									);
							sendResponse(500, json_encode($resError));
							
							$sql = "update internal_user
									set genlog = genlog + 1
									where username = upper('$pUsr')";
							
							$rs = oci_parse($conn, $sql);
			
							oci_execute($rs);
							exit;
						}
						else
							sendResponse(200, json_encode($res2M));
					}
					catch(Exception $e)
					{
						$resError = array 
									(
										"errorServer" => "Internal error, please contact your provider",
										"query" =>  $e->getMessage()
									);
							sendResponse(500, json_encode($resError));
					}
				}
				else
				{
					sendResponse(400, "faltan Parametros");	
				}
				break;
			case "getListaFotos":
				$pUsr = $_REQUEST["User"];
				
				$resSalida = array();
				
				$sql = "Select distinct linea 
						from vc_foto
						where usuario = '$pUsr'
						order by linea";
						
				$rs = oci_parse($conn, $sql);						
				
				oci_execute($rs);
				
				while($row = oci_fetch_array($rs, OCI_ASSOC))
				{
					array_push($resSalida, $row);
				}
				
				
				sendResponse(200, strtolower(json_encode($resSalida)));
				
				break;
			case "getFotos":
					
					$pUsr = $_REQUEST["User"];
					$pLinea = $_REQUEST["Linea"];
					
					$sql = "Select 
								linea, foto_base64 
							from vc_foto
							where usuario = '$pUsr'
							and   linea = $pLinea
							order by linea";
							
					$rs = oci_parse($conn, $sql);
				
					if (oci_execute($rs))
					{	
						$status_header = 'HTTP/1.1 200 ' . getStatusCodeMessage(200);
						header($status_header);
						header('Access-Control-Allow-Origin: *');
						header('Access-Control-Allow-Headers: *');
						header('Cache-Control: no-cache');
						header('Content-Type: application/json; charset=utf-8');
						
						echo "[";
						while ($row = oci_fetch_array($rs, OCI_NUM))
						{   
							echo "{\"linea\": " . $row[0] . ", \"foto_base64\": \"" . $row[1]->load() . "\", \"largo\":".strlen ($row[1]->load())."}, ";
							//echo $row[1]->load() . "</br>";
						}
						echo "{\"linea\": 0, \"foto_base64\": \"\", \"largo\": 0}]";
						
						//sendResponse(200, json_encode($arrayRes));
					}
					else
					{
						echo "error";
					}
					
				break;
			case "RunMain":
				if (isset($_REQUEST["Empresa"]))
				{
					$pEmpresa = $_REQUEST["Empresa"];
					$pUsr = $_REQUEST["User"];
					
					$spOra = "BEGIN sqladmin.movil_main(:PeCode, :PeText, :pEmpresa, :pUser); END;";
					
					$comp = oci_parse($conn, $spOra);
					
					oci_bind_by_name($comp, ':PeCode', $intError, 2);
					oci_bind_by_name($comp, ':PeText', $txtError, 40);
					oci_bind_by_name($comp, ':pEmpresa', $pEmpresa);
					oci_bind_by_name($comp, ':pUser', $pUsr);
					
					if (oci_execute($comp))
					{
						sendResponse(200, json_encode(array("OK" => 1)));
					}
					else
					{
						$resError = array 
						(
							"errorServer" => $txtError
						);
						sendResponse(500, json_encode($resError));
						
						exit;
					}
				}
				else
				{
					sendResponse(400, "faltan Parametros");	
				}
				
				break;
			case "getTablaDrop":
				if (isset($_REQUEST["ListaTablas"]))
				{
					$lisa = $_REQUEST["ListaTablas"];
					
					$salida = array();
					
					foreach($lisa as $value)
					{					
						$createSQL = "DROP TABLE m_$value";
						
						array_push($salida,($createSQL . "; "));
					}
					
					$resultPkg = array 
					(
						"SQls" => $salida
					);
					
					sendResponse(200, json_encode($resultPkg));	
				}
				else
				{
					sendResponse(400, "faltan Parametros");	
				}
				break;
			case "ListModules":
				if (isset($_REQUEST["Project"]))
				{
					try
					{
						$idP = $_REQUEST["Project"];
						$pUsr = $_REQUEST["User"];
						
						$salida = array
						(
							"project_id" => 0,
							"object_id" => 0,
							"movil_proj" => 0,
							"movil_obj" => 0,
							"tableName" => '',
							"formName" => '',
							"obj_order" => 0,
							"obj_type" => ''
						);
						
						$resultPkg = array 
						(
							"ObjServer" => array(),
							"ObjDetServer" => array()
						);
						
						$sql= "	Select
									bm.project_id,
									bm.object_id,
									bm.proj_id,
									bm.obj_id,
									b.sql_tbname as table_name,
									sqladmin.wb_fdictionary( '$pUsr', b.object_name ) as form_name,
									bm.obj_order,
									bm.obj_type
								from 
									sqladmin.object_movil bm,
									sqladmin.object b
								where bm.status = 1
								and   bm.project_id = $idP
								and   b.project_id = bm.proj_id
								and   b.object_id = bm.obj_id
								order by bm.obj_order ";
								
						$rs = oci_parse($conn, $sql);
				
						oci_execute($rs);
						
						while ($row = oci_fetch_array($rs, OCI_NUM))
						{
							$i = 0;
							$salida["project_id"] = $row[$i++] * 1;
							$salida["object_id"] = $row[$i++] * 1;
							$salida["movil_proj"] = $row[$i++] * 1;
							$salida["movil_obj"] = $row[$i++] * 1;
							$salida["tableName"] = utf8_encode($row[$i++]);
							$salida["formName"] = utf8_encode($row[$i++]);
							$salida["obj_order"] = $row[$i++] * 1;
							$salida["obj_type"] = utf8_encode($row[$i++]);
							
							array_push($resultPkg["ObjServer"], $salida);
						}
						
						oci_free_statement($rs);
						
						$salida2 = array
						(
							"project_id" => 0,
							"object_id" => 0,
							"content_id" => 0,
							"movil_proj" => 0,
							"movil_obj" => 0,
							"movil_name" => "",
							"movil_help" => ""
						);

						$sql= "	Select
								   project_id,
								   object_id,
								   content_id,
								   proj_link,
								   obj_link,
								   sqladmin.wb_fdictionary( '$pUsr', obj_name ) as obj_name,
								   obj_help  
								from sqladmin.object_det_movil
								where status = 1
								and   project_id = $idP";
								
						$rs = oci_parse($conn, $sql);
				
						oci_execute($rs);
						
						while ($row = oci_fetch_array($rs, OCI_NUM))
						{
							$i = 0;
							$salida2["project_id"] = $row[$i++] * 1;
							$salida2["object_id"] = $row[$i++] * 1;
							$salida2["content_id"] = $row[$i++] * 1;
							$salida2["movil_proj"] = $row[$i++] * 1;
							$salida2["movil_obj"] = $row[$i++] * 1;
							$salida2["movil_name"] = utf8_encode($row[$i++]);
							$salida2["movil_help"] = utf8_encode($row[$i++]);
							
							array_push($resultPkg["ObjDetServer"], $salida2);
						}
						if (json_encode($resultPkg))
							sendResponse(200, json_encode($resultPkg, JSON_UNESCAPED_UNICODE), "application/json");					
						else
						{
							switch (json_last_error()) 
							{
								case JSON_ERROR_NONE:
									echo ' - No errors';
								break;
								case JSON_ERROR_DEPTH:
									echo ' - Maximum stack depth exceeded';
								break;
								case JSON_ERROR_STATE_MISMATCH:
									echo ' - Underflow or the modes mismatch';
								break;
								case JSON_ERROR_CTRL_CHAR:
									echo ' - Unexpected control character found';
								break;
								case JSON_ERROR_SYNTAX:
									echo ' - Syntax error, malformed JSON';
								break;
								case JSON_ERROR_UTF8:
									echo ' - Malformed UTF-8 characters, possibly incorrectly encoded';
								break;
								default:
									echo ' - Unknown error';
								break;
							}

							echo PHP_EOL;
						}
					}
					catch(Exception $e)
					{
						sendResponse(500, $e->getMenssage());
					}
				}
				else
					sendResponse(400, "faltan Parametros");
				break;
			case "xmlDef":
				if (isset ($_REQUEST["Project"]) && isset ($_REQUEST["Object"]) && isset($_REQUEST["Where"]))
				{
					$idP = $_REQUEST["Project"];
					$idO = $_REQUEST["Object"];
					$strWhere = $_REQUEST["Where"];
					$strUsr = $_REQUEST["Usr"];
					$sql = "Select 
								XMLType.getClobVal (movil_getdefxmllan($idP, $idO,' $strWhere', '$strUsr')) as res 
							from dual";
					
					$rs = oci_parse($conn, $sql);
			
					oci_execute($rs);
					
					$xml = "";
					
					if($row = oci_fetch_array($rs, OCI_RETURN_LOBS))
					{
						$xml = "<?xml version='1.0' encoding='UTF-8'?>" . str_replace("<-", "</", str_replace("/", "-", utf8_encode($row[0])));
						$xml = str_replace("&apos;", "'", $xml);
						$xml = str_replace("&quot;", "~", $xml);
						sendResponse(200, $xml, "text/xml");
					}
					
					oci_free_statement($rs);
				}
				else
				{
					sendResponse(400, "faltan Parametros");
				}
				break;
			case "xmlData":
				if (isset ($_REQUEST["Project"]) && isset ($_REQUEST["Object"]))
				{
					$idP = $_REQUEST["Project"];
					$idO = $_REQUEST["Object"];
					$strWhere = "";
					$strOrderBy = "";
					
					$empresaLogin = $_REQUEST["empresa"];
					//$usrLogin = $_REQUEST["usr"];
				    $usrCode = $_REQUEST["usrCode"];
					$usrPais = $_REQUEST["usrPais"];
					
					
					$sqlWhere = "Select 
									regexp_replace(nvl(str_where, '1=1'), '[[:space:]]+', chr(32)) as res,
									regexp_replace(nvl(str_order, ' '), '[[:space:]]+', chr(32)) as resOrder
					 			 from movil_def_where
								 where project_id = $idP
								 and   object_id = $idO";
								 
					$rs = oci_parse($conn, $sqlWhere);
					
					$res = oci_execute($rs);
					
					if ($row = oci_fetch_array($rs, OCI_NUM))
					{
					
						eval("\$strWhere = \"" . $row[0] . "\";");
						eval("\$strOrderBy = \"" . $row[1] . "\";");
						
						oci_free_statement($rs);
						
						$sql = "Select 
									regexp_replace(replace (XMLType.getClobVal (sqladmin.movil_getdataxml($idP, $idO,' $strWhere', '$strOrderBy')), '\', ''), '[[:space:]]+', chr(32)) as res 
								from dual";
						
						$rs = oci_parse($conn, $sql) or die (oci_error($conn));
						
						$res = oci_execute($rs);
						
						$xml = "";
						
						if(($row = oci_fetch_array($rs, OCI_RETURN_LOBS)))
						{
							$xml = "<?xml version='1.0' encoding='UTF-8'?>" . utf8_encode($row[0]); //str_replace("<-", "</", str_replace("/", "-", utf8_encode($row[0])));
							
							sendResponse(200, $xml, "text/xml");
						}
						
						
						oci_free_statement($rs);
					}
				}
				else
				{
					sendResponse(400, "faltan Parametros");
				}
				break;
		}
	}
	if ( $generalog == 1 )
		fclose($log);	
	oci_close ($conn);
	
?>