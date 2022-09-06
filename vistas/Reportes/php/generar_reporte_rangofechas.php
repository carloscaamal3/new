<?php
require('../fpdf/fpdf.php');
$mysqli = new mysqli("localhost","root","12345678","tramite"); 
	
if(mysqli_connect_errno()){
	echo 'Conexion Fallida : ', mysqli_connect_error();
	exit();
}
	$fecha1=$_GET["reporte_fecha1"];
	$fecha2=$_GET["reporte_fecha2"];
		

	//$nombre=$_POST["nombre_reporte"];

	$pdf = new FPDF('L','mm','A4');
	$pdf->AliasNbPages();
	$pdf->AddPage();
	$pdf->SetFont('Courier','B',15);
		$pdf->Ln(-5);
		$pdf->Cell(5);
		$pdf->Image('../../descarga.png', 40, 15, 18 );
		//$pdf->Cell( 30 ,15, $pdf->Image('../../../controlador/personal/archivos/Fotos/5b372311d83fc-1042g', $pdf->GetX()+5, $pdf->GetY()+3, 20),1,0,'C', false);
		$pdf->SetFont('Courier','B',15);
		$pdf->Ln(20);
		
		if ($_GET["reporte_fecha1"]=="" && $_GET["reporte_fecha2"]=="") {
		$pdf->Cell(40);
		$pdf->Cell(120,10, 'GANANCIA TOTAL POR IMPOTE RECAUDADO',0,1,'C');
			$query1 = "SELECT documento.doc_asunto,
			documento.doc_fecha_recepcion,
			tipo_documento.tipodo_descripcion,
		    
			area.area_nombre
			FROM documento 
			INNER JOIN area ON documento.area_cod = area.area_cod 
			INNER JOIN detalle_ciudadano ON detalle_ciudadano.documento_cod = documento.documento_cod
			INNER JOIN tipo_documento ON documento.tipoDocumento_cod = tipo_documento.tipodocumento_cod ORDER BY documento.documento_cod ASC";

			$query1= "SELECT ciudadano.ciud_dni, 
			CONCAT_WS(' ',ciudadano.ciud_nombres,ciudadano.ciud_apellidoPate,ciudadano.ciud_apellidoMate) AS persona,
			documento.doc_asunto,
			documento.doc_fecha_recepcion,
			area.area_nombre,
			documento.doc_estado,
			documento.documento_cod
			FROM
			ciudadano
			INNER JOIN detalle_ciudadano ON detalle_ciudadano.ciudadano_cod = ciudadano.ciudadano_cod
			INNER JOIN documento ON detalle_ciudadano.documento_cod = documento.documento_cod
			INNER JOIN area ON documento.area_cod = area.area_cod order by documento_cod";
		}else{
			$query1 = "SELECT documento.doc_asunto,
			documento.doc_fecha_recepcion,
			tipo_documento.tipodo_descripcion, 
		    
			area.area_nombre,
		    documento.documento_cod
			FROM documento 
			INNER JOIN area ON documento.area_cod = area.area_cod 
			INNER JOIN detalle_ciudadano ON detalle_ciudadano.documento_cod = documento.documento_cod
			INNER JOIN  tipo_documento ON documento.tipoDocumento_cod = tipo_documento.tipodocumento_cod 
			WHERE DATE_FORMAT(documento.doc_fecha_recepcion,'%Y-%m-%d') >= '$fecha1' and  DATE_FORMAT(documento.doc_fecha_recepcion,'%Y-%m-%d') <= '$fecha2'  ORDER BY documento.documento_cod ASC";


			$query1= "SELECT ciudadano.ciud_dni, 
			CONCAT_WS(' ',ciudadano.ciud_nombres,ciudadano.ciud_apellidoPate,ciudadano.ciud_apellidoMate) AS persona,
			documento.doc_asunto,
			documento.doc_fecha_recepcion,
			area.area_nombre,
			documento.doc_estado,
			documento.documento_cod
			FROM
			ciudadano
			INNER JOIN detalle_ciudadano ON detalle_ciudadano.ciudadano_cod = ciudadano.ciudadano_cod
			INNER JOIN documento ON detalle_ciudadano.documento_cod = documento.documento_cod
			INNER JOIN area ON documento.area_cod = area.area_cod 
			WHERE DATE_FORMAT(documento.doc_fecha_recepcion,'%Y-%m-%d') >= '$fecha1' 
			and  DATE_FORMAT(documento.doc_fecha_recepcion,'%Y-%m-%d') <= '$fecha2'  
			ORDER BY documento.documento_cod ASC";






			$pdf->Cell(80);
			$pdf->Cell(130,-15,'ENTREGAS REGISTRADAS',0,1,'C');
			$pdf->Ln(20);
			$pdf->SetFont('Courier','B',12);
			$pdf->SetFillColor(255, 195, 0);
			$pdf->Cell(70);
			$pdf->Cell(50,6,'FECHA INICIAL: ',1,0,'c',1);
			$pdf->SetFillColor(253, 254, 254);
			if ($_GET["reporte_fecha1"]=="" || $_GET["reporte_fecha2"]=="") {
				$pdf->Cell(100,6,"No Definido",1,1,'c',1);
			}else {
				$pdf->Cell(100,6,$_GET["reporte_fecha1"],1,1,'C',1);
			}
			$pdf->SetFont('Courier','B',12);
			$pdf->SetFillColor(255, 195, 0);
			$pdf->Cell(70);
			$pdf->Cell(50,6,'FECHA FINAL: ',1,0,'c',1);
			$pdf->SetFillColor(253, 254, 254);
			if ($_GET["reporte_fecha1"]=="" || $_GET["reporte_fecha2"]=="") {
				$pdf->Cell(100,6,"No Definido",1,1,'c',1);
			}else {
				$pdf->Cell(100,6,$_GET["reporte_fecha2"],1,1,'C',1);
			}
		}
		$pdf->Ln(5);
	$pdf->SetFillColor(255, 195, 0);
	$pdf->SetFont('Courier','B',10);
	$pdf->Cell(-5);
	$pdf->Cell(20,6,'ID',1,0,'C',1);
	$pdf->Cell(45,6,utf8_decode('FECHA ENTREGA'),1,0,'C',1);
	$pdf->Cell(60,6,'ASUNTO O CANTIDAD',1,0,'C',1);
	$pdf->Cell(95,6,'CIUDADANO',1,0,'C',1);
	$pdf->Cell(30,6,utf8_decode('ÁREA ASIGNADA'),1,0,'C',1);
	$pdf->Cell(37,6,utf8_decode('NUM ENTREGA'),1,1,'C',1);
	$pdf->SetFont('Courier','B',10);
	$cont=0;
		if ($resultado1 = $mysqli->query($query1)) {
			while($row1 = $resultado1->fetch_assoc())
				{
					$pdf->Cell(-5);
					$pdf->SetFont('Courier','B',10);
					$cont++;
					$pdf->Cell(20,6,$cont,1,0,'C');
					$pdf->Cell(45,6,utf8_decode($row1['doc_fecha_recepcion']),1,0,'C');			
					$pdf->Cell(60,6,utf8_decode($row1['doc_asunto']),1,0,'C');
					$pdf->Cell(95,6,utf8_decode($row1['persona']),1,0,'C');
					$pdf->Cell(30,6,utf8_decode($row1['area_nombre']),1,0,'C');
					$pdf->Cell(37,6,utf8_decode($row1['documento_cod']),1,1,'C');
					$pdf->SetFont('Helvetica','B',10);	

				}
		}
		$pdf->SetFont('Courier','B',10);
		if ($cont==0) {
			$pdf->Cell(-5);
			$pdf->Cell(297,6,"No se encontraron Datos",1,1,'C');
		}
		$pdf->Ln(5);
	$pdf->Output();
?>