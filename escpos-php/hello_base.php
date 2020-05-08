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


/* Change to the correct path if you copy this example! */
require __DIR__ . '/autoload.php';
use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\CapabilityProfiles\DefaultCapabilityProfile;
use Mike42\Escpos\CapabilityProfiles\SimpleCapabilityProfile;
use Mike42\Escpos\CapabilityProfile;

try {
    // Enter the share name for your USB printer here
    $connector = new WindowsPrintConnector("pos-80");
    //$connector = new FilePrintConnector("/dev/usb/lp0"); raspberry
    //$profile = CapabilityProfile::load("default"); // Works for Epson printers
    $profile = CapabilityProfile::load("simple");
    /* Print a "Hello world" receipt" */
    $printer = new Printer($connector,$profile);
    /* Initialize */
    //$printer -> initialize();

    //inicia la factura
    /* A wrapper to do organise item names & prices into columns */
    class item
    {
        private $name;
        private $price;
        private $dollarSign;
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
    /* Information for the receipt */
    $items = array(
        new item("Example item #1", "2", "4.00"),
        new item("Another thing", "5", "3.50"),
        new item("Something else", "120", "1.00"),
        new item("A final item", "23", "4.45"),
    );


    /* Date is kept the same for testing */
    // $date = date('l jS \of F Y h:i:s A');
    $date = "Monday 6th of April 2015 02:56:25 PM";

    /* Name of shop */
    $printer -> setJustification(Printer::JUSTIFY_CENTER);
    $printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
    $printer -> text("PIZZERIA AMANGIARE.\n");
    $printer -> selectPrintMode();
    //$printer -> feed();
    /* Title of receipt */
    $printer -> setEmphasis(true);
    $printer -> feed(1);
    $printer -> text("Orden #xxxx\n");
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
    $printer -> text("12/08/2018\n");
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
    $right = str_pad('$  14.25', 10, ' ', STR_PAD_LEFT);
    $printer -> text("$left$right\n");
    $printer -> selectPrintMode();

    //efectivo o tarjeta
    $printer -> text("Efectivo");

    /* Footer */
    $printer -> feed(1);
    $printer -> setJustification(Printer::JUSTIFY_CENTER);
    $printer -> text("Muchas gracias por preferirnos\n");
 


    /* Emphasis */
/*for ($i = 0; $i < 2; $i++) {
    $printer -> setEmphasis($i == 1);
    $printer -> text("The quick brown fox jumps over the lazy dog\n");
}
$printer -> setEmphasis(false);*/ // Reset

    /* Fonts (many printers do not have a 'Font C') */
/*$fonts = array(
    Printer::FONT_A,
    Printer::FONT_B,
    Printer::FONT_C);
for ($i = 0; $i < count($fonts); $i++) {
    $printer -> setFont($fonts[$i]);
    $printer -> text("The quick brown fox jumps over the lazy dog\n");
}
$printer -> setFont();*/ // Reset

/* Justification */
/*$justification = array(
    Printer::JUSTIFY_LEFT,
    Printer::JUSTIFY_CENTER,
    Printer::JUSTIFY_RIGHT);
for ($i = 0; $i < count($justification); $i++) {
    $printer -> setJustification($justification[$i]);
    $printer -> text("A man a plan a canal panama\n");
}
$printer -> setJustification();*/ // Reset

    /* Cut */
    //$printer -> cut();   
    /* Pulse */
    //$printer -> pulse();
    /* Close printer */
    $printer -> close();

    redirect('../admin.php',false);  //cambiar a donde se quiere que vaya venta
} 
catch (Exception $e) {
    echo "Couldn't print to this printer: " . $e -> getMessage() . "\n";
}
