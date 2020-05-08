<?php
// fuente http://www.fpdf.org/es/tutorial/index.php
include_once("fpdf/fpdf.php");

//$pdf = new FPDF('P','mm','A4');
$pdf = new FPDF('P','mm',array(80,80));  //ancho y alto
$pdf->SetMargins(3,3,3); //LEFT, TOP RIGHT
$pdf->AddPage();

$pdf->setFont("Courier","B",11);   //se establece el tipo de letra

$pdf->Cell(0,5,"Reporte de cierre de caja","LBR",1,"C"); //imprime una celda de borse rectangular

$pdf->setFont("Courier","B",9);

$pdf->Cell(30,5,$_GET["date"],0,1);

$pdf->Cell(55,3,"Apertura de caja",0,0);
$pdf->Cell(0,3,": ".$_GET["apertura_caja"],0,1);

$pdf->Cell(55,3,"Cobros en efectivo",0,0);
$pdf->Cell(0,3,": ".$_GET["cobros_efectivo"],0,1);

$pdf->Cell(55,3,"Cobros con tarjeta",0,0);
$pdf->Cell(0,3,": ".$_GET["cobros_tarjeta"],0,1);

$pdf->Cell(55,3,"Total de ventas",0,0);
$pdf->Cell(0,3,": ".$_GET["total_ventas"],0,1);

$pdf->Cell(55,3,"Autoconsumo",0,0);
$pdf->Cell(0,3,": ".$_GET["autoconsumo"],0,1);

$pdf->Cell(55,3,"Ingreso de efectivo en caja",0,0);
$pdf->Cell(0,3,": ".$_GET["ingreso_ef_caja"],0,1);

$pdf->Cell(55,3,"Retiro de efectivo en caja",0,0);
$pdf->Cell(0,3,": ".$_GET["retiro_ef_caja"],0,1);

$pdf->Cell(55,3,"Dinero a entregar",0,0);
$pdf->Cell(0,3,": ".$_GET["dinero_entregar"],0,1);

$pdf->Cell(55,3,"Dinero entregado",0,0);
$pdf->Cell(0,3,": ".$_GET["dinero_entregado"],0,1);

$pdf->Cell(55,3,"Saldo",0,0);
$pdf->Cell(0,3,": ".$_GET["dinero_sobra"],0,1);

$pdf->Output("Reportes_PDF/CierreCaja_".$_GET["user"].$_GET["date1"].".pdf","F");
$pdf->Output();


?>