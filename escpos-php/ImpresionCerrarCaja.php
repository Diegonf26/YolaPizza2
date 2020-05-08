<?php
    require_once('includes/load.php');
    include 'ChromePhp.php';
    ChromePhp::log('Hello console!');

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

function imprimirReportediario($p_apertura_caja,$p_cobros_efectivo,$p_cobros_tarjeta,$p_total_ventas,$p_autoconsumo,$p_escuelas,$p_ingreso_ef_caja,$p_retiro_ef_caja,$p_dinero_entregar,$p_dinero_entregado,$p_dinero_sobra,$p_caja_manana){
    
    $cc = find_conta('contador'); //Extraer informacion de ultimo cotador para conocernumero de ordenes
    $totalOrdenes=$cc[0]['conta'];
    // Especificaciones de Impresora
    try {
        //PARAMETROS DE INICIALIZACION IMPRESORA
        $connector = new WindowsPrintConnector("POS-80");   //Wiindows
        //$connector = new FilePrintConnector("/dev/usb/lp0"); //linux
        $printer = new Printer($connector);
        $printer -> initialize();
        
        //COMFIGURACION DE ESTILO DE IMPRESION
        // Name of shop
        $printer -> setJustification(Printer::JUSTIFY_CENTER);
        $printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
        $printer -> text("PIZZERIA YOLA.\n");
        $printer -> selectPrintMode();
        // Title of receipt
        $printer -> setEmphasis(true);
        $printer -> feed(1);
        $printer -> text("Reporte Cierre de caja\n");
        $printer -> text(date("F j, Y, g:i a"));
        $printer -> setEmphasis(false);
        //Header 
        $printer -> setJustification(Printer::JUSTIFY_LEFT);
        $printer -> setEmphasis(true);
        $printer -> feed(1);
        $printer -> text("================================================");
        $printer -> setEmphasis(true);
        // Items
        $printer -> setEmphasis(false);
        //$p_apertura_caja,$p_cobros_efectivo,$p_cobros_tarjeta,$p_total_ventas,$p_autoconsumo,$p_escuelas,$p_ingreso_ef_caja,$p_retiro_ef_caja,$p_dinero_entregar,$p_dinero_entregado,$p_dinero_sobra,$p_date,$p_user,$ingresoUnificado,$retiroUnificado
        //Creacion de Filan en tabla
        $printer -> text(new item('Ant Caja =', $p_apertura_caja));  
        $printer -> text(new item('Cobros Efecect =', $p_cobros_efectivo));  
        $printer -> text(new item('Cobros Tarjeta =', $p_cobros_tarjeta));  
        $printer -> text(new item('Cobros Uber =', "Uber")); 
        $printer -> text(new item('Cobros Escuelas =', $p_escuelas));
        //$printer -> feed(1); 
        $printer -> text("-------------------------------------------------");  
        $printer -> text(new item('Total Vendido =', $p_total_ventas));
        $printer -> text(new item('  *    Gastos =', $p_retiro_ef_caja));
        $printer -> text(new item('  *  Ingresos =', $p_ingreso_ef_caja));
        //$printer -> feed(1);
        $printer -> text("-------------------------------------------------");  
        $printer -> text(new item(' TOTAL DINERO =', $p_dinero_entregar));
        $printer -> text(new item(' Dinero entregado =', $p_dinero_entregado));
        $printer -> text(new item(' Diferencia =', $p_dinero_sobra));
        //$printer -> feed(1);
        /* Cut */
        //$printer -> feed(1);
        $printer -> cut();
        $printer -> text("================================================");
        $printer -> text(date("F j, Y, g:i a \n"));
        $printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
        $printer -> text(new item(' CAJA MANANA =', $p_caja_manana));

        $printer -> selectPrintMode();

        // Cut 
        $printer -> feed(1);
        $printer -> cut();
        
        $printer -> pulse();
        
        $printer -> close();        
    }
    catch (Exception $e) {
        echo "No se ha podido imprimir Reporte de cierre: " . "\n";    
       
    }
}

?>
