<?php
/*--------------------------------------------------------------*/
/* Function for redirect
/*--------------------------------------------------------------*/
function redirect($url, $permanent = false)
{
    if (headers_sent() === false)
    {
      header('Location: ' . $url, true, ($permanent === true) ? 301 : 302);
    }

    exit();
}

require __DIR__ . '/autoload.php';
use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\CapabilityProfiles\DefaultCapabilityProfile;
use Mike42\Escpos\CapabilityProfiles\SimpleCapabilityProfile;
use Mike42\Escpos\CapabilityProfile;

class item
{
	private $name;
	private $price;
	private $dollarSign;
	private $qty;

	public function __construct($name = '',  $qty = '', $price = '', $dollarSign = false)
	{
		$this -> name = $name;
		$this -> qty = $qty;
		$this -> price = $price;
		$this -> dollarSign = $dollarSign;
	}
	
	public function __toString()
	{
		
		$nameCols = 36;
		$qtyCols = 5;
		$priceCols = 7;

		if ($this -> dollarSign) {
			$nameCols = $nameCols / 2 - $priceCols / 2;
		}
		$left = str_pad($this -> name, $nameCols) ;
		$middle = str_pad($this -> qty, $qtyCols, ' ', STR_PAD_LEFT) ;
		
		$sign = ($this -> dollarSign ? '$ ' : '');
		$right = str_pad($sign . $this -> price, $priceCols, ' ', STR_PAD_LEFT);
		return "$left$middle$right\n";
	}
}

class itemcocina
{
	private $name;
	private $price;
    private $llevar;
    private $imprimir;
	public function __construct($llevar=false,$name = '',  $qty = '', $imprimir=false)
	{
		$this -> name = $name;
		$this -> qty = $qty;
        $this -> llevar = $llevar;
        $this -> imprimir= $imprimir;
    }
    
    public function GetImprimir()
	{
        return $this -> imprimir;
	}
	
	public function __toString()
	{
		
		$nameCols = 39;
		$qtyCols = 6;
		$m = 3;
		
		if($this -> llevar) $left = str_pad('* '.$this -> qty, $qtyCols, ' ', STR_PAD_LEFT) ;
		else $left = str_pad(''.$this -> qty, $qtyCols, ' ', STR_PAD_LEFT) ;
		$middle = str_pad(' ', $m, ' ', STR_PAD_LEFT) ;
		$right = str_pad($this -> name, $nameCols);
		return "$left$middle$right\n";
	}
}
try {

	$connector = new WindowsPrintConnector("POS-80");
	//$connector = new FilePrintConnector("/dev/usb/lp0"); //linux
	$printer = new Printer($connector);
	/* Initialize */
	$printer -> initialize();
	/* Always close the printer! On some PrintConnectors, no actual
	 * data is sent until the printer is closed. */
	    /* Information for the receipt */
    $values = explode(",", $_GET["orden"]);
    $items = array();
    $k=(sizeof($values)/4);
    for($x = 0; $x < $k; $x++){
        $items[$x]=new item("".$values[$x*4+1],"".(int)$values[$x*4], "".number_format((float)$values[$x*4+3], 2, '.', ''));
    }

    $hayalgo=false;
    $itemsco = array();
    for($x = 0; $x < $k; $x++){
        if(substr($values[$x*4+1],0,1)=="C"||substr($values[$x*4+1],0,1)=="I"){
            //||substr($values[$x*4+1],0,1)=="B"
            $itemsco[$x]=new itemcocina();
        }
        else{
            if(substr($values[$x*4+1], -1)=='L') $ast=true;
            else $ast=false;
            $itemsco[$x]=new itemcocina($ast,"".$values[$x*4+1],"".(int)$values[$x*4],true);
            $hayalgo=true;
        }
    }

    /* Name of shop */
    $printer -> setJustification(Printer::JUSTIFY_CENTER);
    $printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
    $printer -> text("PIZZERIA AMANGIARE.\n");
    $printer -> selectPrintMode();
    /* Title of receipt */
    $printer -> setEmphasis(true);
    $printer -> feed(1);
    $numOrden=$_GET["numorden"];
    $printer -> text("Orden# $numOrden\n");
    $printer -> setEmphasis(false);
    /* Header */
    $printer -> setJustification(Printer::JUSTIFY_LEFT);
    $printer -> setEmphasis(true);
    $printer -> feed(1);
    $printer -> text("Dir: ");
    $printer -> setEmphasis(false);
    $printer -> text("Conocoto, Montufar 889 y Garcia Moreno \n");
    $printer -> setEmphasis(true);
    $printer -> text("Telf: ");
    $printer -> setEmphasis(false);
    $printer -> text("02-2073707\n");
    $printer -> setEmphasis(true);
    $printer -> text("Fecha: ");
    $printer -> setEmphasis(false);
    $date=$_GET["date"];
    $printer -> text("$date\n");
    $printer -> text("================================================");
    $printer -> setEmphasis(true);
    $printer -> text(new item('Descrip.', 'Cant.', 'Val.'));
    /* Items */
    $printer -> setEmphasis(false);
    foreach ($items as $item) {
        $printer -> text($item);
    }

    $printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
    $left = str_pad('Total', 14) ;
    $right = str_pad('$ '.$_GET["subtotal"], 10, ' ', STR_PAD_LEFT);
    $printer -> text("$left$right\n");
    $printer -> selectPrintMode();

    //efectivo o tarjeta
    if($_GET['p_pago']=="efectivo"){
        /* Pulse solo con pagos en efectivo*/    
        $printer -> pulse();
        $left = str_pad('Efectivo', 38) ;      
        $right = str_pad('$ '.number_format((float)$_GET["p_efect"], 2, '.', ''), 10, ' ', STR_PAD_LEFT);
        $printer -> text("$left$right\n");

        $left = str_pad('Cambio', 38) ;
        $right = str_pad('$ '.number_format((float)$_GET["p_vuelto"], 2, '.', ''), 10, ' ', STR_PAD_LEFT);
        $printer -> text("$left$right\n");
         //$printer -> text("Ef\n");
    } 
    else $printer -> text("Tar\n");

    /* Footer */
    $printer -> feed(1);
    $printer -> setJustification(Printer::JUSTIFY_CENTER);
    $printer -> text("------Muchas gracias por preferirnos------\n");
    $printer -> setJustification();
 
    /* Cut */
    $printer -> feed(1);
    $printer -> cut();   

    if($hayalgo){
        /* Name of shop */
        $printer -> setJustification(Printer::JUSTIFY_CENTER);
        $printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
        $printer -> text("PIZZERIA AMANGIARE.\n");
        /* Title of receipt */
        $printer -> setEmphasis(true);
        $printer -> feed(1);
        $numOrden=$_GET["numorden"];
        $printer -> text("Orden# $numOrden\n");
        $printer -> feed(1);
        $printer -> selectPrintMode();
        $printer -> setEmphasis(true);
        $printer -> text("Fecha: ");
        $printer -> setEmphasis(false);
        $printer -> text("$date\n");
        $printer -> text("================================================");
        $printer -> setEmphasis(true);
        $printer -> text(new itemcocina(false, 'Descrip.', 'Cant.'));
        /* Items */
        $printer -> setJustification(Printer::JUSTIFY_LEFT);
        $printer -> setEmphasis(false);
        foreach ($itemsco as $item) {
            if($item->GetImprimir()) $printer -> text($item);
        }
        //efectivo o tarjeta
        //if($_GET["efectivo"]==0) $printer -> text("Tar\n");
        //else $printer -> text("Ef\n");
        /* Cut */
        $printer -> feed(1);
        $printer -> cut();   
    }


    $printer -> close();


}
catch (Exception $e) {
    echo "Couldn't print to this printer: " . $e -> getMessage() . "\n";    

}

 

?>
