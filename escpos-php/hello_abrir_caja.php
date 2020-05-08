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


try {

	//$connector = new WindowsPrintConnector("POS-80");
	$connector = new FilePrintConnector("/dev/usb/lp0"); //linux
	$printer = new Printer($connector);
	/* Initialize */
	$printer -> initialize();
	/* Text */
	//$printer -> text("Hello world\n");
	/* Pulse */
	$printer -> pulse();
	/* Always close the printer! On some PrintConnectors, no actual
	 * data is sent until the printer is closed. */
        /* Information for the receipt */
        
    $printer -> close();

    redirect('../caja_ingreso_retiro.php?status=siAbriocaja',false);  //cambiar a donde se quiere que vaya venta
	
	//redirect('../admin.php?status=siImpreso',false);  //cambiar a donde se quiere que vaya venta

}
catch (Exception $e) {
	echo "Couldn't print to this printer: " . $e -> getMessage() . "\n";    
	redirect('../caja_ingreso_retiro.php?status=noAbriocaja',false); 
}



?>
