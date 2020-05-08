<?php
// fuente http://www.fpdf.org/es/tutorial/index.php
include_once("fpdf/fpdf.php");
//require('fpdf/fpdf.php');

// Better table
function ImprovedTable($pdf,$header,$data,$subtotal)
{
    $pdf->setFont("Courier","B",9);
    // Column widths
    $q=4;
    $w = array(4, 80, 13, 13);
    // Header
    for($i=0;$i<count($header);$i++)
        $pdf->Cell($w[$i],4,utf8_decode($header[$i]),0,0,"L");
    $pdf->Ln();
    // Data

    $k=(sizeof($data)/4);
    for ($x = 0; $x < $k; $x++) {
        $pdf->Cell($w[0],4,utf8_decode($data[$x*$q]),0,0,"L");
        $pdf->Cell($w[1],4,utf8_decode($data[$x*$q+1]),0,0,"L");
        $pdf->Cell($w[2],4,number_format((float)$data[$x*$q+2], 2, '.', ''),0,0,"L");
        $pdf->Cell($w[3],4,number_format((float)$data[$x*$q+3], 2, '.', ''),0,0,"L");
        $pdf->Ln();
    }

    global $filas;
    // Closing line
    //footer
    $pdf->Ln();
    $pdf->setFont("Courier","B",11);
    $pdf->Cell(95,4,"Subtotal","T",0,"C");
    $pdf->Cell(11,4,$subtotal,"T",0,"L");
    $pdf->Ln();
    $pdf->Cell(array_sum($w),0,'',0,1);
    $pdf->setFont("Courier","B",9);

    if($_GET["p_pago"]=="efectivo") $pdf->Cell(40,4,"Ef",0);
    else $pdf->Cell(40,4,"Tar",0);
    if($_GET["servir"]==0) $pdf->Cell(10,4,"Para llevar",0,0,'C');
    else $pdf->Cell(10,4,"Para servir",0,1,'C');
   // $pdf->Ln();   
}

// Better table
function ImprovedTable1($pdf,$header,$data)
{
    $pdf->setFont("Courier","B",9);
    // Column widths
    $q=4;
    $w = array(15, 20, 48);
    // Header
    $pdf->setFont("Courier","BI",9);
    for($i=0;$i<count($header);$i++)
        $pdf->Cell($w[$i],4,utf8_decode($header[$i]),0,0,"L");
    $pdf->Ln();
    $pdf->setFont("Courier","B",9);

    // Data
    for ($x = 0; $x < (sizeof($data)/4); $x++) {
        if(substr($data[$x*$q+1],0,1)=="C"||substr($data[$x*$q+1],0,1)=="I"||substr($data[$x*$q+1],0,1)=="B"){
            //$pdf->Cell($w[0],4,"",0,0,"C");
            //$pdf->Cell($w[1],4,"",0,0,"L");
        }
        else{
            if(substr($data[$x*$q+1], -1)=='L'||substr($data[$x*$q+1], -1)=='S'){
                //$pdf->Ln();
                if(substr($data[$x*$q+1], -1)=='L') $pdf->Cell($w[0],4,"*",0,0,"C");
                else $pdf->Cell($w[0],4,"",0,0,"C");
                
                $pdf->Cell($w[0],4,utf8_decode($data[$x*$q]),0,0,"C");
                $pdf->Cell($w[1],4,utf8_decode($data[$x*$q+1]),0,0,"L");
            }  
            else{
                $pdf->Cell($w[0],4,"",0,0,"C");
                $pdf->Cell($w[0],4,utf8_decode($data[$x*$q]),0,0,"C");
                $pdf->Cell($w[1],4,utf8_decode($data[$x*$q+1]),0,0,"L");
            }  
            $pdf->Ln();
        }
        
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
$ancho=120;
$filas=$ancho+4*((sizeof($values)/4)-7);
if($filas<$ancho) $filas=$ancho;
$pdf = new FPDF('P','mm',array($ancho,$filas));  //ancho y alto ALTO SIEMPRE MAYO AL ANCHO
//$pdf->SetMargins(3,3,3,3); //LEFT, TOP RIGHT
$pdf->AddPage();
$pdf->SetMargins(3, 3, 3); 
$pdf->SetAutoPageBreak(false, 3);

$pdf->setFont("Courier","B",11);   //se establece el tipo de letra
$pdf->Cell(0,5,utf8_decode("Pizzería Amangiare"),"LBRT",1,"C"); //imprime una celda de borse rectangular

$pdf->setFont("Courier","B",9);
$pdf->Cell(20,4,utf8_decode("Dirección:"),0);
$pdf->Cell(43,4,"Conocoto, ....",0,1);
$pdf->Cell(20,4,"Fecha    :",0);
$pdf->Cell(43,4,$_GET["date"],0,1);
$pdf->Cell(20,4,"Telf     : ",0);
$pdf->Cell(43,4,"02-3442096",0,1);

$numOrden=$_GET["numorden"];
$pdf->setFont("Courier","B",11);   //se establece el tipo de letra
$pdf->Cell(0,5,"Orden #".$numOrden,0,1,"C"); //imprime una celda de borse rectangular


$header = array('C', 'Descripción', 'Val', 'Tot');

ImprovedTable($pdf,$header,$values,$_GET["subtotal"]);

//$pdf->Output("Reportes_PDF/notaVenta_".$_GET["user"].$_GET["date1"].".pdf","F");

$pdf->AddPage();
$pdf->SetMargins(3, 3, 3); 
$pdf->SetAutoPageBreak(false, 3);

$pdf->setFont("Courier","B",9);
$pdf->Cell(60,4,"Fecha: ".$_GET["date"],0,1);

$pdf->setFont("Courier","B",12);   //se establece el tipo de letra
$pdf->Cell(0,5,"Orden #".$numOrden,0,1,"C"); //imprime una celda de borse rectangular


$header = array('LLevar','Cantidad', 'Descripción');

ImprovedTable1($pdf,$header,$values);

$pdf->Output();


?>