<?php
    require_once('includes/load.php');

    $cc = find_conta('contador');
    $contador=$cc[0]['conta'];
    $impresion=false;
?>

<?php
function redirect1($url, $permanent = false)
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
		
		$nameCols = 46;
		$qtyCols = 1;
		$m = 1;
		
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

    //Convertir orden en filas de productos
    $values = explode(",", $_GET["orden"]);
    $items = array();
    $hayalgo_comp=false;
    $k=(sizeof($values)/4);
    for($x = 0; $x < $k; $x++){
        if(stristr($values[$x*4+1],"porcion") == false){
            $items[$x]=new item("".$values[$x*4+1],"".(int)$values[$x*4], "".number_format((float)$values[$x*4+3], 2, '.', ''));
            $hayalgo_comp=true;
        }
        else{
            if($hayalgo_comp==false)
                $items[$x]=new item();
            else
                $items[$x]=new item("".$values[$x*4+1],"".(int)$values[$x*4], "".number_format((float)$values[$x*4+3], 2, '.', ''));
            
        } 
    }

    $hayalgo=false;
    $itemsco = array();
    for($x = 0; $x < $k; $x++){  
        if(substr($values[$x*4+1],0,1)=="C"||substr($values[$x*4+1],0,1)=="I"||stristr($values[$x*4+1],"porcion") == true){
            $itemsco[$x]=new itemcocina();
        }
        else{
            if(substr($values[$x*4+1], -1)=='L') $ast=true;
            else $ast=false;
            $itemsco[$x]=new itemcocina($ast,"".$values[$x*4+1],"".(int)$values[$x*4],true);
            $hayalgo=true;
        }
    }

    if($hayalgo_comp){
        /* Name of shop */
        $printer -> setJustification(Printer::JUSTIFY_CENTER);
        $printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
        $printer -> text("PIZZERIA AMANGIARE.\n");
        $printer -> selectPrintMode();
        /* Title of receipt */
        $printer -> setEmphasis(true);
        $printer -> feed(1);
        $numOrden=$contador;
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
            // $printer -> pulse();
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

        //Variable asegurarse q se imprime para aumentar el contador
        $impresion=true;
    }
    $printer -> pulse();
    
    //PAPEL DE COCINA
    if($hayalgo){
        /* Name of shop */
        $printer -> setJustification(Printer::JUSTIFY_CENTER);
        $printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
        // $printer -> text("PIZZERIA AMANGIARE.\n");
        /* Title of receipt */
        // $printer -> setEmphasis(true);
        // $printer -> feed(1);
        $numOrden=$contador;
        $printer -> text("Orden# $numOrden\n");
        $printer -> selectPrintMode();
        $printer -> setEmphasis(true);
        $printer -> text("Fecha: ");
        $printer -> setEmphasis(false);
        $printer -> text("$date\n");
        $printer -> text("================================================");
        // $printer -> setEmphasis(true);
        // $printer -> text(new itemcocina(false, 'Descrip.', 'Cant.'));
        /* Items */
        $printer -> setJustification(Printer::JUSTIFY_LEFT);
        $printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
        $printer -> setEmphasis(true);
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

    if($impresion=true){
        //AUMENTAR EL CONTADOR DE ORDENES
        $contador++;
        $query = "UPDATE contador SET ";        //Insertar la BD en la memoria de usuario
        $query .=" conta = '{$contador}' WHERE id = 1;";
        if($db->query($query)){}
    }

    $printer -> close();

    $comandos='&servir='.$_GET["servir"].'&orden='.$_GET["orden"].'&date1='.$_GET["date1"];

    redirect1('../final_compra_vuelto.php?status=siImpreso&p_efect='.$_GET["p_efect"].'&p_vuelto='.$_GET["p_vuelto"].'&p_pago='.$_GET["p_pago"].'&numorden='.$_GET["numorden"].'&subtotal='.$_GET["subtotal"].'&date='. $_GET["date"].'&user='. $_GET["user"].$comandos,false);  //cambiar a donde se quiere que vaya venta
	
	//redirect1('../admin.php?status=siImpreso',false);  //cambiar a donde se quiere que vaya venta

}
catch (Exception $e) {
    echo "Couldn't print to this printer: " . $e -> getMessage() . "\n";    
    //redirect1('../realizar_venta.php?status=noImpreso',false);
    $comandos='&servir='.$_GET["servir"].'&orden='.$_GET["orden"].'&date1='.$_GET["date1"];
    redirect1('../final_compra_vuelto.php?status=noImpreso&p_efect='.$_GET["p_efect"].'&p_vuelto='.$_GET["p_vuelto"].'&p_pago='.$_GET["p_pago"].'&numorden='.$_GET["numorden"].'&subtotal='.$_GET["subtotal"].'&date='. $_GET["date"].'&user='. $_GET["user"].$comandos,false);  //cambiar a donde se quiere que vaya venta

}

?>
