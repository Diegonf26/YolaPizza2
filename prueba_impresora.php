<?php
  $page_title = 'Lista de ventas';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
   page_require_level(3);
   if(isset($_GET['p_desVenta'])) {
    $venta=$_GET['p_desVenta'];
    $subtotal=$_GET['p_subtotal'];

   }
?>


<!--script>
    var user = "<?php echo $user['username']; ?>";
    var date = "<?php echo make_date(); ?>";
    var d = new Date();
    var date1=d.getFullYear().toString()+"_"+d.getMonth().toString()+"_"+d.getDate().toString()+"_"+d.getHours().toString()+"_"+d.getMinutes().toString();
    
    var ordenString=<?php echo $venta;?>;
    var orden1 = ordenString.split(",");
    var orden=[];
    
    var efectivo=0; //0 con tarjeat, 1 con efectivo
    var servir=1; //0 llevar, 1 servirse

    var subtotal="<?php echo $user['subtotal']; ?>";
    var numorden=25;



    for (i = 0; i < Number(orden1.length)/4; i++) { 
      orden[i]=[Number(orden1[(4*i)]),orden1[(4*i+1)],Number(orden1[(4*i+2)]),Number(orden1[(4*i+3)])];
  }

  var win = window.open("escpos-php/hello.php?"+"servir="+servir+"&"+"numorden="+numorden+"&"+"efectivo="+efectivo+"&"+"user="+user+"&"+"date="+date+"&"+"subtotal="+subtotal+"&"+"orden="+orden+"&"+"date1="+date1,"_SELF"); // will open new tab on document ready
</script-->

<script>
    var user = "<?php echo $user['username']; ?>";
    var date = "<?php echo make_date(); ?>";
    var d = new Date();
    var date1=d.getFullYear().toString()+"_"+d.getMonth().toString()+"_"+d.getDate().toString()+"_"+d.getHours().toString()+"_"+d.getMinutes().toString();
    
    var efectivo=0; //0 con tarjeat, 1 con efectivo
    var servir=1; //0 llevar, 1 servirse

    var subtotal=130;
    var numorden=25;

    var orden = [
      [1,"Pizza porcion",1,1.00],
      [2,"Pizza mediana",15,15.01],
      [3,"Pizza pequenia",1,20],
      [4,"Pizza mediana pina",17,17.78],
      [5,"Pizza familiar pina",17,17],
      [6,"Pizza mangiare",17,17],
      [7,"Pizza mediana pina",17,17]
      ];

  var win = window.open("escpos-php/hello.php?"+"servir="+servir+"&"+"numorden="+numorden+"&"+"efectivo="+efectivo+"&"+"user="+user+"&"+"date="+date+"&"+"subtotal="+subtotal+"&"+"orden="+orden+"&"+"date1="+date1); // will open new tab on document ready

</script>
