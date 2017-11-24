<?php
require_once('../Connections/sac2.php'); 
header( 'Content-type: text/html; charset=iso-8859-1' );

//print_r($_POST);
date_default_timezone_set('America/Mexico_City');

if (!isset($_SESSION)) {
  session_start();
}
$login_ok = $_SESSION['login_ok'];
//RESTRINGIMOS EL ACCESO A USUARIOS NO IDENTIFICADOS
if ($login_ok == "identificado"){
 
}else{
echo "No Identificado";	 
	//session_unset();  // remove all session variables
	session_destroy();  // destroy the session 
	header("Location: index.php");
}

//--------------------------------------GUARDAR EMPLEADO-----------------------------------------------------------
if (isset($_REQUEST['agregar_emp'])) {
	$nombre =  $_POST["nombre"]; 
	$noemp = $_POST["noemp"];
	$base = $_POST["base3"];
	
			$sql = "INSERT INTO CatEmpleados VALUES ('','','".$base."','".$noemp."','".$nombre."','','','','','','','','','".$noemp."','".$plaza."')";
  			//echo $sql; 
			$rs = odbc_exec( $conn, $sql );
  			if ( !$rs ) { 
    		exit( "Error en la consulta CatEmpleados" ); 
  		}   
}
//--------------------------------------AUTOCOMPLETAR---------------------------------------------------------
$empleado = array();
$x=0;

$sql = "select * from CatEmpleados";
  //echo $sql;
  $rs = odbc_exec( $conn, $sql );
  if ( !$rs ) { 
    exit( "Error en la consulta SQL" ); 
  }    
  while ( odbc_fetch_row($rs) ) { 
    $empleado[$x] = "\"" .odbc_result($rs, 'Empleado'). "\",";	
	$x++;    
}//While
$empleado[$x-1] = str_replace(",","",$empleado[$x-1]);

$maquinaria = array();
$x=0;

$sql = "select * from CatMaquinaria";
  //echo $sql;
  $rs = odbc_exec( $conn, $sql );
  if ( !$rs ) { 
    exit( "Error en la consulta SQL" ); 
  }    
  while ( odbc_fetch_row($rs) ) { 
    $maquinaria[$x] = "\"" .odbc_result($rs, 'NoEco'). "\",";	
	$x++;    
}//While
$maquinaria[$x-1] = str_replace(",","",$maquinaria[$x-1]);

//------------------------------------------------ELIMINAR EMPLEADO----------------------------------------------------------
if (isset($_REQUEST['eliminar'])) { 
 $emp = $_POST["emp"];
 
	 $sql = "DELETE FROM CatEmpleados WHERE Empleado = '".$emp."'";
    	//echo $sql;
    	$rs = odbc_exec( $conn, $sql );
   		if ( !$rs ) {
	     exit( "Error en la consulta CatEmpleados" );            
   		}
}
//------------------------------------------------ACTUALIZAR EMPLEADO----------------------------------------------------------
if (isset($_REQUEST['modificar_emp'])) { 
 $emp = $_POST["noemp1"];
 $plaza = $_POST["plaza1"];
 $base = $_POST["base1"];
 
	 $sql = "UPDATE CatEmpleados SET Plaza = '".$plaza."', CvBase = '".$base."' WHERE NoEmp = '".$emp."'";
    	//echo $sql;
    	$rs = odbc_exec( $conn, $sql );
   		if ( !$rs ) {
	     exit( "Error en la consulta CatEmpleados" );            
   		}
}
//------------------------------------------------ACTUALIZAR USUARIO----------------------------------------------------------
if (isset($_REQUEST['modificar_us'])) { 
 $nomb = $_POST["nombre2"];
 $privilegios = $_POST["privilegios"];
 $pass = base64_encode($_POST["password"]);
 
	 $sql = "UPDATE Usuarios SET PRIVILEGIOS = '".$privilegios."' WHERE NOMBRE = '".$nomb."'";
    	//echo $sql;
    	$rs = odbc_exec( $conn, $sql );
   		if ( !$rs ) {
	     exit( "Error en la consulta Usuarios" );            
   		}
	
	if ($pass != ""){
		$sql = "UPDATE Usuarios SET PASSWORD = '".$pass."' WHERE NOMBRE = '".$nomb."'";
    	//echo $sql;
    	$rs = odbc_exec( $conn, $sql );
   		if ( !$rs ) {
	     exit( "Error en la consulta Usuarios" );            
   		}
	}
}
//--------------------------------------GUARDAR MAQUINARIA-----------------------------------------------------------
if (isset($_REQUEST['agregar_maq'])) {
	$base =  $_POST["base"]; 
	$noeco = $_POST["noeco"];
	$desc = $_POST["desc"];
	$tipo = $_POST["tipo"];

	
			$sql = "INSERT INTO CatMaquinaria VALUES ('','".$base."','".$noeco."','".$desc."','".$tipo."')";
  			//echo $sql; 
			$rs = odbc_exec( $conn, $sql );
  			if ( !$rs ) { 
    		exit( "Error en la consulta CatMaquinaria" ); 
  		}   
}
//------------------------------------------------ELIMINAR MAQUINARIA----------------------------------------------------------
if (isset($_REQUEST['eliminar_maq'])) { 
 $num = $_POST["num"];
 
	 $sql = "DELETE FROM CatMaquinaria WHERE NoEco = '".$num."'";
    	//echo $sql;
    	$rs = odbc_exec( $conn, $sql );
   		if ( !$rs ) {
	     exit( "Error en la consulta CatMaquinaria" );            
   		}
}
/**********************************************GRID CATEMPLEADOS***************************************************/
$x=0;
$filas = array();
$sql = "SELECT * FROM CatEmpleados ORDER BY CvBase";
//echo $sql1;
  $rs = odbc_exec( $conn, $sql );
  if ( !$rs ) { 
    exit( "Error en la consulta SQL" ); 
  }   //OBTENEMOS TODA LA FILA 
 while ($row = odbc_fetch_array($rs)) {    
  $filas[$x] = array_map('utf8_encode',$row);    
   $x++;    
 }//While
$datos =  $filas; 
//echo json_encode($datos); 
/**********************************************GRID CATMAQUINARIA***************************************************/
$x=0;
$filas = array();
$sql2 = "SELECT * FROM CatMaquinaria";
//echo $sql;
  $rs = odbc_exec( $conn, $sql2);
  if ( !$rs ) { 
    exit( "Error en la consulta SQL" ); 
  }   //OBTENEMOS TODA LA FILA 
 while ($row = odbc_fetch_array($rs)) {    
  $filas[$x] = array_map('utf8_encode',$row);    
   $x++;    
 }//While
$datos2 =  $filas; 
//echo json_encode($datos2); 
/**********************************************GRID USUARIOS***************************************************/
$x=0;
$filas = array();
$sql = "SELECT * FROM Usuarios where USUARIO NOT IN ('karen','admin','miguel') ORDER BY PRIVILEGIOS";
//echo $sql1;
  $rs = odbc_exec( $conn, $sql );
  if ( !$rs ) { 
    exit( "Error en la consulta SQL" ); 
  }   //OBTENEMOS TODA LA FILA 
 while ($row = odbc_fetch_array($rs)) {    
  $filas[$x] = array_map('utf8_encode',$row);    
   $x++;    
 }//While
$datos3 =  $filas; 
//echo json_encode($datos3); 
/**********************************************GRID TRAMOS***************************************************/
$x=0;
$filas = array();
$sql2 = "SELECT * FROM CatTramos ORDER BY BASE";
//echo $sql;
  $rs = odbc_exec( $conn, $sql2);
  if ( !$rs ) { 
    exit( "Error en la consulta SQL" ); 
  }   //OBTENEMOS TODA LA FILA 
 while ($row = odbc_fetch_array($rs)) {    
  $filas[$x] = array_map('utf8_encode',$row);    
   $x++;    
 }//While
$datos4 =  $filas; 
//echo json_encode($datos4); 
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Documento sin título</title>
<link rel="stylesheet" href="../css/menuLateral_color2.css">
<link type="text/css" rel="stylesheet" href="../js/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css" media="screen">
</link>
<link rel='stylesheet' href='http://s.codepen.io/assets/reset/reset.css'>
<link href="../css/bootstrap.min.css" rel="stylesheet" />
<link rel="stylesheet" href="../css/styl_color2.css">
<link rel="stylesheet" href="../css/menu1_color2.css">
<link rel="stylesheet" href="../jqwidgets/styles/jqx.base_color2.css" type="text/css" />
<script type="text/javascript" src="../scripts/jquery-1.11.1.min.js"></script>
<script type="text/javascript" src="../jqwidgets/jqxcore.js"></script>
<script type="text/javascript" src="../jqwidgets/jqxtooltip.js"></script>
<script type="text/javascript" src="../jqwidgets/jqxdata.js"></script>
<script type="text/javascript" src="../jqwidgets/jqxbuttons.js"></script>
<script type="text/javascript" src="../jqwidgets/jqxscrollbar.js"></script>
<script type="text/javascript" src="../jqwidgets/jqxlistbox.js"></script>
<script type="text/javascript" src="../jqwidgets/jqxdropdownlist.js"></script>
<script type="text/javascript" src="../jqwidgets/jqxmenu.js"></script>
<script type="text/javascript" src="../jqwidgets/jqxgrid.js"></script>
<script type="text/javascript" src="../jqwidgets/jqxgrid.sort.js"></script>
<script type="text/javascript" src="../jqwidgets/jqxgrid.pager.js"></script>
<script type="text/javascript" src="../jqwidgets/jqxgrid.columnsresize.js"></script>
<script type="text/javascript" src="../jqwidgets/jqxgrid.selection.js"></script>
<script type="text/javascript" src="../jqwidgets/jqxinput.js"></script>
<script type="text/javascript" src="../jqwidgets/jqxpanel.js"></script>
<script type="text/javascript" src="../jqwidgets/jqxmaskedinput.js"></script>
<script type="text/javascript" src="../jqwidgets/jqxnumberinput.js"></script>
<script type="text/javascript" src="../jqwidgets/jqxdraw.js"></script>
<script type="text/javascript" src="../jqwidgets/jqxchart.core.js"></script>
<script type="text/javascript" src="../scripts/demos.js"></script>
<script type="text/javascript" src="../js/bootstrap.min.js"></script>
<script type="text/javascript" src="../jqwidgets/jqxtabs.js"></script>
<script type="text/javascript" src="../js/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js"></script>
<script type="text/javascript" src="../scripts/menu1.js"></script>
<style>		
        body, html {
            width: 100%;
            height: 100%;			
            overflow: hidden;
        }
		.pastel {
			width: 50%;
			height: 50%;			
		}
		.barras {
			width: 80%;
			height: 50%;			
		}
		#tabla {
    		display: table;
    		border: 0px solid #000;
    		width: 80%;
			height: 30%;
    		text-align: center;
    		margin: 0 auto;
		}
		#fila {
    		display: table-row;
		}
		#chartContainer, #chartContainer2{
    		display: table-cell;
    		border: 0px solid #000;
    		vertical-align: middle;    		
		}
		
</style>
<script type="text/javascript">
        $(document).ready(function () {
			
			$("#menu-toggle").click(function(e) {
        		e.preventDefault();
        		$("#wrapper").toggleClass("active");
			});			
			
			//AUTOCOMPLETAR
			var empleado = new Array(<?php  
	        foreach ($empleado as &$valor) {
              echo $valor;
            }		
	        ?>);
			var maquinaria = new Array(<?php  
	        foreach ($maquinaria as &$valor) {
              echo $valor;
            }		
	        ?>);
			
			$("#emp").jqxInput({placeHolder: "Escribe un nombre", minLength: 1, source: empleado});
			$("#num").jqxInput({placeHolder: "Numero Economico", minLength: 1, source: maquinaria});
			
			//CREAMOS EL TAB			
            $('#tabsWidget').jqxTabs({ width: '50%', height: '65%', position: 'top'});
            // Focus jqxTabs.
            $('#tabsWidget').jqxTabs('focus');
			
			//---------------------------------------------EMPLEADOS--------------------------------------------------	
			var data =  <?php echo json_encode($datos); ?>;				    
		    var source =
            {
                datafields: [
                    { name: 'CvBase', type: 'string' },	
					{ name: 'NoEmp', type: 'number' },
					{ name: 'Empleado', type: 'string' },				
					{ name: 'Plaza', type: 'string' }									
                ],
                localdata: data 
            };
            var dataAdapter = new $.jqx.dataAdapter(source);

            $("#cat_empleados").jqxGrid(
            {
                width: '99%',
                height: '90%',
                source: dataAdapter,
                
                keyboardnavigation: false,
                columns: [
				  { text: 'No. Empleado', dataField: 'NoEmp', width: '15%', cellsalign: 'center' },
				  { text: 'Nombre', dataField: 'Empleado', width: '40%' },  
				  { text: 'Base', dataField: 'CvBase', width: '10%', cellsalign: 'center' },           
				  { text: 'Plaza', dataField: 'Plaza', width: '18%' }
                ]
            });	
			
			/*var empleados = <?php echo json_encode($datos); ?>;
		    //// prepare the data
			 var source =
            {
                dataType: "json",
                dataFields: [
                    { name: 'CvBase', type: 'string' },	
					{ name: 'NoEmp', type: 'number' },
					{ name: 'Empleado', type: 'string' },				
					{ name: 'Plaza', type: 'string' }                  
                ],
                hierarchy:
                {
                    keyDataField: { name: 'NoEmp' },
                    parentDataField: { name: 'Empleado' }
                },
                id: 'NoEmp',
                localData: empleados
            };
		  
		    var dataAdapter = new $.jqx.dataAdapter(source);
            // create Tree Grid
            $("#treeGrid").jqxTreeGrid(
            {
                width: '90%',
                height: '75%',
                source: dataAdapter,
                filterable: true,
                filterMode: 'simple',
                ready: function()
                {
                    $("#treeGrid").jqxTreeGrid('expandRow', '5');
                },
                columns: [
                  { text: 'No. Empleado', dataField: 'NoEmp', width: '15%', cellsalign: 'center' },
				  { text: 'Nombre', dataField: 'Empleado', width: '40%' },  
				  { text: 'Base', dataField: 'CvBase', width: '10%', cellsalign: 'center' },           
				  { text: 'Plaza', dataField: 'Plaza', width: '18%' }
                ]
            });*/
			
			$('#cat_empleados').on('rowclick', function (event){ 										       
			    $("#empleado1").modal('show'); 			        			
            });
			
			$("#cat_empleados").on('rowselect', function (event) {	
				var plaza = event.args.row.Plaza;			
				var nombre = event.args.row.Empleado;			
				var noemp = event.args.row.NoEmp;			
				var base = event.args.row.CvBase;		
					
				$("<option value='"+plaza+"' selected >"+plaza+"</option>").appendTo("#plaza1");
				$("<option value='"+base+"' selected >"+base+"</option>").appendTo("#base1");
				$("#nombre1").jqxInput({value: nombre, minLength: 1});
				$("#noemp1").jqxInput({value: noemp, minLength: 1 });
				//$("#base1").jqxInput({value: base, minLength: 1 });	
			});	
			
			$("#plaza1").click(function(){			
				$("#plaza1 option:selected").each(function () {
			     elegido=$(this).val();
			     $.post("consulta.php", { base: elegido }, function(data){
				 	 //alert(data);	   								
					$("#base1").html(data);			    
			   });			
              });				
			});
			
			$("#plaza").click(function(){			
				$("#plaza option:selected").each(function () {
			     elegido=$(this).val();
			     $.post("consulta.php", { base: elegido }, function(data){
				 	 //alert(data);	   								
					$("#base3").html(data);			    
			   });			
              });				
			});
			
			//--------------------------------------------------------MAQUINARIA-------------------------------------------------------------------------------------
					
			var data =  <?php echo json_encode($datos2); ?>;				    
		    var source =
            {
                datafields: [
                    { name: 'CvBase', type: 'string' },	
					{ name: 'NoEco', type: 'string' },
					{ name: 'Descrip', type: 'string' },	
					{ name: 'CvTipMaq', type: 'string' }
															
                ], 
                localdata: data  
            };
            var dataAdapter = new $.jqx.dataAdapter(source);

            $("#cat_maquinaria").jqxGrid(
            {
                width: '99%',
                height: '90%',
                source: dataAdapter,
                
                keyboardnavigation: false,
                columns: [
				  { text: 'No. Economico', dataField: 'NoEco', width: '20%' },
				  { text: 'Descripcion', dataField: 'Descrip', width: '45%' },  
				  { text: 'Base', dataField: 'CvBase', width: '8%', cellsalign: 'center' },             
				  { text: 'Tipo Maquinaria', dataField: 'CvTipMaq', width: '15%', cellsalign: 'center' }
                ]
            });	
			
			//---------------------------------------------------USUARIOS---------------------------------------------------------------
				
			var data =  <?php echo json_encode($datos3); ?>;				    
		    var source =
            {
                datafields: [
                    { name: 'NOMBRE', type: 'string' },	
					{ name: 'USUARIO', type: 'string' },
					{ name: 'PRIVILEGIOS', type: 'string' }				
					//{ name: 'PLAZA', type: 'string' }									
                ],
                localdata: data 
            };
            var dataAdapter = new $.jqx.dataAdapter(source);

            $("#cat_usuarios").jqxGrid(
            {
                width: '99%',
                height: '90%',
                source: dataAdapter,
                
                keyboardnavigation: false,
                columns: [
				  { text: 'Nombre', dataField: 'NOMBRE', width: '40%' },
				  { text: 'Usuario', dataField: 'USUARIO', width: '15%' },  
				  { text: 'Privilegios', dataField: 'PRIVILEGIOS', width: '17%' }           
				  //{ text: 'Plaza', dataField: 'PLAZA', width: '18%' }
                ]
            });	
			
			$('#cat_usuarios').on('rowclick', function (event){ 										       
			    $("#usuario1").modal('show'); 			        			
            });
			
			$("#cat_usuarios").on('rowselect', function (event) {			
				var nombre = event.args.row.NOMBRE;	
				var priv = event.args.row.PRIVILEGIOS;	
				//var plaza = event.args.row.PLAZA;		
					
				$("#nombre2").jqxInput({value: nombre, minLength: 1});
				$("<option value='"+priv+"' selected >"+priv+"</option>").appendTo("#privilegios");
				//$("<option value='"+plaza+"' selected >"+plaza+"</option>").appendTo("<div id="plaza2"></div>");
			});	
			
			/*$("#plaza2").click(function(){			
				$("#plaza2 option:selected").each(function () {
			     elegido=$(this).val();
			     $.post("consulta.php", { base: elegido }, function(data){
				 	 //alert(data);	   								
					$("#base2").html(data);			    
			   });			
              });				
			});*/
			
			//------------------------------------------------TRAMOS------------------------------------------------------------------
				
			var data =  <?php echo json_encode($datos4); ?>;				    
		    var source =
            {
                datafields: [
                    { name: 'TRAMO', type: 'string' },	
					{ name: 'BASE', type: 'string' },
					{ name: 'SUBTRAMO', type: 'string' },
					{ name: 'PLAZA', type: 'string' }									
                ],
                localdata: data 
            };
            var dataAdapter = new $.jqx.dataAdapter(source);

            $("#cat_tramos").jqxGrid(
            {
                width: '99%',
                height: '90%',
                source: dataAdapter,
                
                keyboardnavigation: false,
                columns: [
				  { text: 'Tramo', dataField: 'TRAMO', width: '40%' },
				  { text: 'Plaza', dataField: 'PLAZA', width: '15%' },  
				  { text: 'Base', dataField: 'BASE', width: '10%' }, 
				  { text: 'SubTramo', dataField: 'SUBTRAMO', width: '15%' }
                ]
            });	
			
		
			
	});//$(document).ready	
	
function showContent() {
        element = document.getElementById("psw");
        check = document.getElementById("cambiar_psw");
        if (check.checked) {
            element.style.display='block';
            $("#password").prop('disabled' , false);
        }
        else {
            element.style.display='none';
            $("#password").prop('disabled' , true);
        }
    }
	 
</script>
</head>

<body>
<!--Menu 1-->
<div>
    <ul class="dropdown">
      <li class="drop"><a href="#"> <strong> BIENVENIDO &nbsp;</strong><strong><?php echo $_SESSION['S_Nombre']; ?></strong></a>     
        <ul class="sub_menu">
          <li><a href="#"><img src="../imagenes/edit.png" width="21" height="20"  >  Editar Perfil</a></li>
          <li><a href="#"><img src="../imagenes/cambiar.png" width="18" height="20"  >Cambiar contrase&ntilde;a</a></li>          
          <li><a href="index.php"><img src="../imagenes/sesion.png" width="18" height="20"  >Cerrar Sesi&oacute;n </a></li> 
        </ul>
      </li>  
    </ul>  
</div>  
<img src="../images/Head_SAC.png" width="407" height="92" alt=""/><br>


<!--Menu 2-->                             
<div id='cssmenu'>
  <ul>
  <li class='active has-sub'><a href='inicio.php'><span>Inicio</span></a></li>
   <li><a href='../AvanceDiario1.php' id="avancediario1"><span> <span class="glyphicon glyphicon-saved" aria-hidden="true"></span> &nbsp; Avance Diario</span></a> </li>
   <!-- <li><a href='AvisoDePrivacidad.php' id="aviso"><span> <span class="glyphicon glyphicon-flag" aria-hidden="true"></span> &nbsp; Aviso</span></a>
    <li><a href='TerminosCondiciones.php' id="terminos"><span> <span class="glyphicon glyphicon-book" aria-hidden="true"></span> &nbsp;T&eacute;rminos y condiciones</span></a></li>    
    <li><a href="Inscripcion.php" id="incidencias"><span> <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> &nbsp; Inscripción al padrón</span></a> </li>
    <li><a href='NuevoRegistro.php' id="nuevoregistro"><span> <span class="glyphicon glyphicon-folder-open" aria-hidden="true"></span> &nbsp; Mis trámites</span></a> </li> --> 
  </ul>
</div>

<!--Menu 3 -->
<div id="wrapper" class="active"> 
  <!-- Sidebar -->
  <div id="sidebar-wrapper">
    <ul id="sidebar_menu" class="sidebar-nav">
    <div class="col-md-4">
      <li class="sidebar-brand"><a id="menu-toggle" href=""><h3>Cat&aacute;logos</h3> </div> 
      <div class="col-md-7" align="right"><h3><span id="main_icon" class="glyphicon glyphicon-align-justify"></h3></span></div></a></li>
    </ul>
    <ul class="sidebar-nav" id="sidebar"> 
    <div class="row">
    	<li><a href="#" data-toggle="modal" data-target=".empleado">
        <div class="col-md-8">
        	Agregar Empleado
         </div>
		<div class="col-md-4" align="right">
        	<!--<span class="sub_icon glyphicon glyphicon-file"></span>-->
         <h5>   <span class="glyphicon glyphicon-user"></span></h5>
        </div>
        </a></li>
     </div>
     <div class="row">
    	<li><a href="#" data-toggle="modal" data-target=".eliminar_empleado">
        <div class="col-md-8">
        	Eliminar Empleado
         </div>
		<div class="col-md-4" align="right">
        	<!--<span class="sub_icon glyphicon glyphicon-file"></span>-->
         <h5>   <span class="glyphicon glyphicon-remove-sign"></span></h5>
        </div>
        </a></li>
     </div>
     <div class="row">
        <div class="col-md-12">
        <hr size="3">
        </div>
     </div>
     <div class="row">   
     	<li><a href="#" data-toggle="modal" data-target=".maquinaria"> 
      	<div class="col-md-8">
      		Agregar Maquinaria
      	</div>
      	<div class="col-md-4" align="right">
      		<span class="glyphicon glyphicon-wrench"></span>
      	</div>
      	</a></li>
     </div>
     <div class="row">   
     	<li><a href="#" data-toggle="modal" data-target=".eliminar_maquinaria"> 
      	<div class="col-md-8">
      		Eliminar Maquinaria
      	</div>
      	<div class="col-md-4" align="right">
      		<span class="glyphicon glyphicon-remove-circle"></span>
      	</div>
      	</a></li>
     </div><!--
     <div class="row">   
     	<li><a href="#"> 
      	<div class="col-md-8">
      		 Actualizaci&oacute;n
      	</div>
      	<div class="col-md-4" align="right">
      		<span class="sub_icon glyphicon glyphicon-share"></span>
      	</div>
      	</a></li>
     </div>  
     <div class="row">   
     	<li><a href=""> 
      	<div class="col-md-8">
      		Cambio de Veh&iacute;culo
      	</div>
      	<div class="col-md-4" align="right">
      		<span class="sub_icon glyphicon glyphicon-road"></span>
      	</div>
      	</a></li>
     </div>     
     <div class="row">   
     	<li><a href=""> 
      	<div class="col-md-8">
      		Cambio de domicilio
      	</div>
      	<div class="col-md-4" align="right">
      		<span class="sub_icon glyphicon glyphicon-filter"></span>
      	</div>
      	</a></li>
     </div>     
     <div class="row">   
     	<li><a href=""> 
      	<div class="col-md-8">
      		Cambio de placas
      	</div>
      	<div class="col-md-4" align="right">
      		<span class="sub_icon glyphicon glyphicon-search"></span>
      	</div>
      	</a></li>
     </div>  -- 
     <div class="row">   
     	<li><a href="#"> 
      	<div class="col-md-8">
      		Actualizacion Anual <!--de Tr&aacute;mites--
      	</div>
      	<div class="col-md-4" align="right">
      		<span class="sub_icon glyphicon glyphicon-ok"></span>
      	</div>
      	</a></li>
     </div>     <!--
     <div class="row">   
     	<li><a href=""> 
      	<div class="col-md-8">
      		Salir
      	</div>
      	<div class="col-md-4" align="right">
      		<span class="sub_icon glyphicon glyphicon-user"></span>
      	</div>
      	</a></li>
     </div>   -->
    </ul>
  </div>
<!--AQUI VA EL CUERPO DE LA PAGINA-->
  
  <!-- Page content -->
  <div id="page-content-wrapper"> 
    <!-- Keep all page content within the page-content inset div! -->
    <div class="page-content inset">
      <div class="row">
        <div class="col-md-12"> </div>
      </div>
<div align="center"><br>
<!-- Tabla-->
</div>     
<center>
<div id='jqxWidget'>
              <br>
              <span style="font-family:Cambria, 'Hoefler Text', 'Liberation Serif', Times, 'Times New Roman', serif; font-size:28px">Cat&aacute;logos</span>
              <br>
              <br>
        <div id="tabsWidget">
            <ul style="margin-left: 30px;">
                <li>Empleados</li>
                <li>Maquinaria</li>
                <li>Usuarios</li>
                <li>Tramos</li>                             
            </ul>
            <div>
              <center>
              <br>
              <br>
                    <div id="cat_empleados"></div>  
              </center>               
            </div> 
            <div>
              <center>
              <br>
              <br>
                    <div id="cat_maquinaria"></div>  
              </center>               
            </div> 
            <div>
              <center>
              <br>
              <br>
                    <div id="cat_usuarios"></div>  
              </center>               
            </div> 
            <div>
              <center>
              <br>
              <br>
                    <div id="cat_tramos"></div>  
              </center>               
            </div>                                  
        </div>              
    </div>
</center> 
  </div>
  
 <!-- <div id="treeGrid"></div>
</div>-->


<!-- Modal de AGREGAR EMPLEADOS-->
<form  action="inicio.php" method="post" enctype="multipart/form-data" >
<div class="modal fade empleado" id="empleado" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Registro de Empleado</h4>
      </div>
      <div class="modal-body">
<div class="row">   
  <div class="col-md-3">
      No. Empleado:
  </div>
  <div class="col-md-3" align="right">
      <input type='number' id='noemp' name='noemp' class="form-control"/>      
  </div>
</div> 
<div class="row"> 
<br>
</div> 
<div class="row"> 
  <div class="col-md-3">
      Plaza:
  </div>
  <div class="col-md-4">
      <select id='plaza' name='plaza' class="form-control"/>
      <?php
	  $i=1;
	  $selected="";
	  $plaza1="";
      $sql = "select DISTINCT(PLAZA) from CatTramos";
      echo $sql;
      $rs = odbc_exec( $conn, $sql );
      if ( !$rs ) { 
       exit( "Error en la consulta SQL" ); 
      }      while ( odbc_fetch_row($rs) ) { 
        $plaza1 = odbc_result($rs, 'Plaza');
       echo "<option id='".$i."'".$selected.">".$plaza1."</option>";
	   $i++;
      }//While 	 
	   ?>
       </select>
  </div>
  <div class="col-md-2">
      Base:
  </div>
  <div class="col-md-3">
      <select id='base3' name='base3' class="form-control"/>
       </select>
  </div>
</div>  
<div class="row"> 
<br>
</div> 
<div class="row">   
  <div class="col-md-3">
      Nombre
  </div>
  <div class="col-md-7">
      <input type='text' id='nombre' name='nombre' class="form-control" required/>
  </div>
</div>
        <!--FIN-->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        <button type="submit" class="btn btn-success" name="agregar_emp" id="agregar_emp">Agregar</button>
      </div>
    </div>
  </div>
</div>
</form>
<!--FIN MODAL-->

<!-- Modal de MODIFICAR EMPLEADOS-->
<form  action="inicio.php" method="post" enctype="multipart/form-data" >
<div class="modal fade empleado1" id="empleado1" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Modificar Empleado</h4>
      </div>
      <div class="modal-body">
<div class="row">   
  <div class="col-md-3">
      No. Empleado:
  </div>
  <div class="col-md-2" align="right">
      <input type='text' id='noemp1' name='noemp1' class="form-control" readonly/>      
  </div>
</div> 
<div class="row"> 
<br>
</div>
<div class="row"> 
<div class="col-md-3">
      Nombre:
  </div>
  <div class="col-md-7">
      <input type='text' id='nombre1' name='nombre1' class="form-control" readonly/>      
  </div> 
</div>
<div class="row"> 
<br>
</div>
<div class="row">   
  <div class="col-md-3">
      Plaza:
  </div>
  <div class="col-md-4">
  	  <select id='plaza1' name='plaza1' class="form-control"/>
      <?php
	  $i=1;
	  $selected="";
	  $plaza1="";
      $sql = "select DISTINCT(PLAZA) from CatTramos";
      echo $sql;
      $rs = odbc_exec( $conn, $sql );
      if ( !$rs ) { 
       exit( "Error en la consulta SQL" ); 
      }      while ( odbc_fetch_row($rs) ) { 
        $plaza1 = odbc_result($rs, 'Plaza');
       echo "<option id='".$i."'".$selected.">".$plaza1."</option>";
	   $i++;
      }//While 	 
	   ?>
       </select>	
  </div> 
  <div class="col-md-2">
      Base:
  </div>
  <div class="col-md-3" align="right">
      <select id='base1' name='base1' class="form-control"/> 
      </select>     
  </div>
</div>
        <!--FIN-->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        <button type="submit" class="btn btn-success" name="modificar_emp" id="modificar_emp">Modificar</button>
      </div>
    </div>
  </div>
</div>
</form>
<!--FIN MODAL-->

<!-- Modal de ELIMINAR EMPLEADOS-->
<form  action="inicio.php" method="post" enctype="multipart/form-data" >
<div class="modal fade eliminar_empleado" id="eliminar_empleado" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Eliminar Empleado</h4>
      </div>
      <div class="modal-body">
<div class="row">  
  <div class="col-md-1">
  </div> 
  <div class="col-md-3">
      Nombre
  </div>
  <div class="col-md-6" align="right">
      <input type='text' id='emp' name='emp' class="form-control" required/>      
  </div>
</div> 
        <!--FIN-->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        <button type="submit" class="btn btn-primary" name="eliminar" id="eliminar">Eliminar</button>
      </div>
    </div>
  </div>
</div>
</form>
<!--FIN MODAL-->

<!-- Modal de AGREGAR MAQUINARIA-->
<form  action="inicio.php" method="post" enctype="multipart/form-data" >
<div class="modal fade maquinaria" id="maquinaria" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Agregar Maquinaria</h4>
      </div>
      <div class="modal-body">
<div class="row"> 
 <div class="col-md-3">
      Plaza:
  </div>
  <div class="col-md-4">
      <select name="plaza3" id="plaza3" class="form-control" >
      <?php
	  $i=1;
	  $selected="";
	  $base1="";
      $sql = "SELECT DISTINCT(PLAZA) from CatTramos";
      echo $sql;
      $rs = odbc_exec( $conn, $sql );
      if ( !$rs ) { 
       exit( "Error en la consulta SQL" ); 
      }      while ( odbc_fetch_row($rs) ) { 
        $base1 = odbc_result($rs, 'PLAZA');
       echo "<option id='".$i."'".$selected.">".$base1."</option>";
	   $i++;
      }//While 	 
	   ?>
       </select>
  </div>  
  <div class="col-md-2">
     Base
  </div>
  <div class="col-md-3" align="right">
      <select id='base' name='base' class="form-control"/>
       </select>     
  </div>
</div>  
<div class="row"> 
<br>
</div>
<div class="row"> 
  <div class="col-md-3">
      No. Econ&oacute;mico:
  </div>
  <div class="col-md-4">
      <input type="text" name="noeco" id="noeco" class="form-control" required maxlength="9">
  </div>
  <div class="col-md-3">
      Tipo Maquinaria:
  </div>
  <div class="col-md-3">
      <select id='tipo' name='tipo' class="form-control"/>
      <?php
	  $i=1;
	  $selected="";
	  $tipo1="";
      $sql = "select DISTINCT(CvTipMaq) from CatMaquinaria";
      echo $sql;
      $rs = odbc_exec( $conn, $sql );
      if ( !$rs ) { 
       exit( "Error en la consulta SQL" ); 
      }      while ( odbc_fetch_row($rs) ) { 
        $tipo1 = odbc_result($rs, 'CvTipMaq');
       echo "<option id='".$i."'".$selected.">".$tipo1."</option>";
	   $i++;
      }//While 	 
	   ?>
       </select>
  </div>
</div> 
<div class="row"> 
<br>
</div> 
<div class="row">   
  <div class="col-md-2">
      Descripci&oacute;n:
  </div>
  <div class="col-md-4">
      <input type='text' id='desc' name='desc' class="form-control" placeholder="Ej: BARREDORA 11" required/>
  </div>
</div>
        <!--FIN-->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        <button type="submit" class="btn btn-primary" name="agregar_maq" id="agregar_maq">Agregar</button>
      </div>
    </div>
  </div>
</div>
</form>
<!--FIN MODAL-->
   
<!-- Modal de ELIMINAR MAQUINARIA-->
<form  action="inicio.php" method="post" enctype="multipart/form-data" >
<div class="modal fade eliminar_maquinaria" id="eliminar_maquinaria" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Eliminar Maquinaria</h4>
      </div>
      <div class="modal-body">
<div class="row">  
  <div class="col-md-1">
  </div> 
  <div class="col-md-3">
      No. Econ&oacute;mico:
  </div>
  <div class="col-md-6" align="right">
      <input type='text' id='num' name='num' class="form-control" required/>      
  </div>
</div> 
        <!--FIN-->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        <button type="submit" class="btn btn-primary" name="eliminar_maq" id="eliminar_maq">Eliminar</button>
      </div>
    </div>
  </div>
</div>
</form>
<!--FIN MODAL-->   
   
<!-- Modal de MODIFICAR USUARIOS-->
<form  action="inicio.php" method="post" enctype="multipart/form-data" >
<div class="modal fade usuario1" id="usuario1" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Modificar Usuario</h4>
      </div>
      <div class="modal-body">
<div class="row"> 
<div class="col-md-3">
      Nombre:
  </div>
  <div class="col-md-7">
      <input type='text' id='nombre2' name='nombre2' class="form-control" readonly/>      
  </div> 
</div>
<div class="row"> 
<br>
</div>
<div class="row">   
  <div class="col-md-3">
      Privilegios:
  </div>
  <div class="col-md-4">
  	  <select id='privilegios' name='privilegios' class="form-control"/>
      <?php
	  $i=1;
	  $selected="";
	  $priv1="";
      $sql = "select DISTINCT(PRIVILEGIOS) from Usuarios";
      echo $sql;
      $rs = odbc_exec( $conn, $sql );
      if ( !$rs ) { 
       exit( "Error en la consulta SQL" ); 
      }      while ( odbc_fetch_row($rs) ) { 
        $priv1 = odbc_result($rs, 'PRIVILEGIOS');
       echo "<option id='".$i."'".$selected.">".$priv1."</option>";
	   $i++;
      }//While 	 
	   ?>
       </select>	
  </div> 
  <div class="col-md-5">
     <div class="checkbox">
    <label>
      <input type="checkbox" id="cambiar_psw" name="cambiar_psw"  onchange="javascript:showContent()" > Cambiar Password
    </label>
  </div>
  </div>
 </div> 
<div class="row"> 
<br>
</div>
<div class="row" id="psw" style="display:none">   
  <div class="col-md-3">
      Password:
  </div>
  <div class="col-md-4" align="right">
      <input type="password" id='password' name='password' class="form-control"/>      
  </div> 
</div>
        <!--FIN-->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        <button type="submit" class="btn btn-success" name="modificar_us" id="modificar_us">Modificar</button>
      </div>
    </div>
  </div>
</div>
</form>
<!--FIN MODAL-->
      
</body>
</html>