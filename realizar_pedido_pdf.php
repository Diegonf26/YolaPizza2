<?php
// fuente http://www.fpdf.org/es/tutorial/index.php
include_once("fpdf/fpdf.php");
//require('fpdf/fpdf.php');

// Better table
function ImprovedTable1($pdf,$header,$data)
{
    $pdf->setFont("Courier","B",9);
    // Column widths
    $q=4;
    $w = array(20, 48);
    // Header
    $pdf->setFont("Courier","BI",9);
    for($i=0;$i<count($header);$i++)
        $pdf->Cell($w[$i],4,utf8_decode($header[$i]),0,0,"L");
    $pdf->Ln();
    $pdf->setFont("Courier","B",9);

    // Data
    for ($x = 0; $x < (sizeof($data)/4); $x++) {
        $pdf->Cell($w[0],4,utf8_decode($data[$x*$q]),0,0,"C");
        $pdf->Cell($w[1],4,utf8_decode($data[$x*$q+1]),0,0,"L");
        $pdf->Ln();
    }

    // Closing line
    //footer
    $pdf->Ln();
    $pdf->setFont("Courier","B",10);
    if($_GET["servir"]==0) $pdf->Cell(40,4,"Para llevar",0,1,'C');
    else $pdf->Cell(40,4,"Para servir",0,1,'C');
   // $pdf->Ln();


    
}


//$pdf = new FPDF('P','mm','A4');
$values = explode(",", $_GET["orden"]);
$filas=80+4*((sizeof($values)/4)-11);
if($filas<80) $filas=80;
$pdf = new FPDF('P','mm',array(80,$filas));  //ancho y alto ALTO SIEMPRE MAYO AL ANCHO
//$pdf->SetMargins(3,3,3,3); //LEFT, TOP RIGHT
$pdf->AddPage();
$pdf->SetMargins(3, 3, 3); 
$pdf->SetAutoPageBreak(false, 3);

$pdf->setFont("Courier","B",9);
$pdf->Cell(60,4,"Fecha: ".$_GET["date"],0,1);

$pdf->setFont("Courier","B",12);   //se establece el tipo de letra
$pdf->Cell(0,5,"Orden # xxxx",0,1,"C"); //imprime una celda de borse rectangular


$header = array('Cantidad', 'Descripción');

ImprovedTable1($pdf,$header,$values);

//$pdf->Output("Reportes_PDF/notaVenta_".$_GET["user"].$_GET["date1"].".pdf","F");
$pdf->Output();


?>