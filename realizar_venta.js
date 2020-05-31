
  var venta_aux=[];
  var venta_aux_extra=[];
  var item_eliminados=[];
  var categ, p_tama, p_tipo, p_extras, p_forma, p_pago, pizza_vent='0';
  var fila_id = 0;
  var num_extras = 0;
  var str_extra="";
  var pagoTotal=1; 
  var p_id_pizza='';      //Informacion de id de la pizza a la cual corresponden los extras
  var caja_mediana=0;
  var caja_grande=0;
  var DOMAIN = "http://localhost/Pizzeria/";

  var titu_regre=document.getElementById("titulo_regresar"); //Titulo regresar 
  var btn_regre=document.getElementById("btn_regresar");    //Boton regresar
  var btn_cont=document.getElementById("cont_compra");    //Boton continuar con la compra

  //var total_venta=Number(document.getElementById("total_compra").value);    
  //var total_tarjeta=document.getElementById("total_compra").value;
  //var total_domicilio=document.getElementById("total_compra").value;
  //var total_uber=document.getElementById("total_compra").value;

//------------------------------------------------------------------------------------------------
    
  function selec_categ(nombre_cat) {
    var g=document.getElementById("cont_categ"); //Cotenedor catego boqueo selecion
    var regr=document.getElementById("funcion_regresar"); //Funcion regresar
    centrar(regr);
    //Ajustar boton regresar
    regr.style.justifyContent= 'left';
    regr.style.paddingLeft= '3%';

    btn_regre.style.display = 'flex';

    if(nombre_cat=="Pizzas"){
      var e = document.getElementById("selc_pizzas_nor_esp"); //Sig Pizzas tipo
      var f = document.getElementById("selc_pizzas_tam"); //Actual Pizzas Tamano
      //Cetrar layout a visualizar
      centrar(f);
      //Regresar a pantalla anterior
      e.style.display = 'none';
      //titulo de categoria
      titu_regre.innerText = "Seleccione el tamaño de pizza";
      pizza_vent=0;   //Ventana de tamano
    }
    else if (nombre_cat=="Bebidas") {
      var f = document.getElementById("selc_bebidas");   //Actual
      centrar(f);
      //titulo de categoria
      titu_regre.innerText = "Seleccione bebida";
      pizza_vent=5; 
    }
    
    else if (nombre_cat=="Ingredientes") {
      var f = document.getElementById("selc_ingredientes");   //Actual
      centrar(f);
      //titulo de categoria
      titu_regre.innerText = "Seleccione ingrediente";  
      pizza_vent=6;  
    }
    
    else if (nombre_cat=="Cajas Pizza") {
      var f = document.getElementById("selc_cajas");   //Actual
      centrar(f);
      //titulo de categoria
      titu_regre.innerText = "Seleccione Caja Pizza";  
      pizza_vent=7;  
    }
    else if (nombre_cat=="Otros") {
      var f = document.getElementById("selc_otros");   //Actual
      centrar(f);
      //titulo de categoria
      titu_regre.innerText = "Seleccione Producto";
      pizza_vent=8; 
    }
    else if (nombre_cat=="Lasagna") {
      var f = document.getElementById("selc_lasagna");   //Actual
      centrar(f);
      //titulo de categoria
      titu_regre.innerText = "Seleccione Sabor Lasagna";
      pizza_vent=9; 
    }
    else if (nombre_cat=="Pastas") {
      var f = document.getElementById("selc_pastas");   //Actual
      centrar(f);
      //titulo de categoria
      titu_regre.innerText = "Seleccione Pastas";
      pizza_vent=10; 
    }

    else if (nombre_cat=="Ensaladas") {
      var f = document.getElementById("selc_ensaladas");   //Actual
      centrar(f);
      //titulo de categoria
      titu_regre.innerText = "Seleccione Ensaladas";
      pizza_vent=11; 
    }
    
    categ=nombre_cat;
    g.style.pointerEvents="none";   //Bloqueo de categoria
    
    btn_cont.disabled=true;   //Bloqueo boton contiuar compra
  }
  //---------- Categoria PIZZAS --------------
  // 0=> Ventana de tamano
  // 1=> Ventana de tipo
  // 2=> Ventana de sabor
  // 3=> Ventana de extra 

  //-1)---Tamano PIZZA
  function tam_pizzas(tama){
    p_tama=tama;
    var f = document.getElementById("selc_pizzas_tam"); //SE CIERRA:ventana actual

    if(tama!="porcion"){
      var e = document.getElementById("selc_pizzas_nor_esp"); //SE ABRE:ventana se abre
      var g = document.getElementById("selc_pizzas_sabor");  //SE CIERRA:ventana siguiente en regreso
      var g2 = document.getElementById("selc_pizzas_especiales");  //SE CIERRA:ventana siguiente en regreso
      
      centrar(e);
      f.style.display = 'none';
      g.style.display = 'none';
      g2.style.display = 'none';
      // g3.style.display = 'none';
      pizza_vent=1;   //Ventana de especial o normal
    }
    else{
      f.style.display = 'none';
      pizzas_normal(tama);
    }
    //titulo de categoria
    titu_regre.innerText = "Seleccione el tipo de pizza";
    //Inicializacion parametros extra
    num_extras=0;
    str_extra="";
  }

  //-2)---Tipo PIZZA
  function pizzas_normal(tipo){
    var f = document.getElementById("selc_pizzas_nor_esp"); //SE CIERRA:ventana actual
    var g = document.getElementById("selc_extra"); //SE CIERRA:ventana siguiente en regreso
    if (tipo=="porcion") 
      var e = document.getElementById("selc_pizzas_sabor_PORCION");    //SE ABRE:ventana se abre 
    else if (tipo=="normal")
      var e = document.getElementById("selc_pizzas_sabor");    //SE ABRE:ventana se abre
    else
      var e = document.getElementById("selc_pizzas_especiales");    //SE ABRE:ventana se abre
    centrar(e);
    var g3 = document.getElementById("selc_personalizada"); 
    f.style.display = 'none';
    g.style.display = 'none';
    g3.style.display = 'none';
    p_tipo=tipo;
    //titulo de categoria
    titu_regre.innerText = "Seleccione el sabor de pizza";
    pizza_vent=2;   //Ventana de sabor
  }

  //-3)---Sabor PIZZA
  function sabor_pizza(tipo,on_regres,ingre_especial){
    var precio=0;
    if (on_regres==0 && tipo!="laMiaPizza") {
      if(tipo.search("LaMiaPizza")!=(-1)){
        p_sabor="lamiapizza";              //Determinar piza especial sin ingredien
        str_extra+=(ingre_especial.toString())+",";
      }
      else
        p_sabor=tipo;
      //Requrimiento de precio a BD se demora mas que la siguiente linea secuencial
      $.ajax({url: DOMAIN+"buscar_precio.php?p_tama="+p_tama+"&p_tipo="+p_tipo+"&p_sabor="+p_sabor, success: function(result){
        precio=Number(result);
        var descrip= categ+" "+p_tama+" "+p_tipo+" "+tipo;
        agregar_fila(descrip,precio);

        //Guardar venta ---------------------------------------------------------------------------
        var venta_pizza={id:fila_id,categ:categ,canti:1,tama:p_tama,tipo:p_tipo,sabor:p_sabor,extra:str_extra,forma:"123",precioP:precio,fpago:"pago"};
        venta_aux.push(venta_pizza); 
      }}); 
    }
    if(tipo=="laMiaPizza"){
      var e = document.getElementById("selc_personalizada");
      //Titulo de ventana
      titu_regre.innerText = "Seleccione ingredientes:";
    }
    else{
      var e = document.getElementById("selc_extra");
      var selc_personalizada = document.getElementById("selc_personalizada");
      selc_personalizada.style.display = 'none';
      //Titulo de ventana
      titu_regre.innerText = "¿Desea algún ingrediente extra?"; 
      btn_regre.style.display = 'none';     //Desaparecer boton regresar de ingredientes extras 
    }

    var sabor_porcion = document.getElementById("selc_pizzas_sabor_PORCION");
    var extra = document.getElementById("selc_extra2");
    var continuar = document.getElementById("fun_cont_extra");
    
    var f = document.getElementById("selc_pizzas_sabor");
    var f2 = document.getElementById("selc_pizzas_especiales");
    var g = document.getElementById("selc_pizzas_forma"); //Ven sig cierra REGRESAR
    centrar(e);
    centrar(extra);
    centrar(continuar);
    f.style.display = 'none';
    f2.style.display = 'none';
    g.style.display = 'none';
    sabor_porcion.style.display = 'none';
    
    pizza_vent=3;   //Ventana de servir
    p_id_pizza=(fila_id+1);     //Corrspondencia de pizza con extras
    // alert(p_id_pizza);
  }

  function ingre_extra(extra){
    num_extras++;
    //Buscar precios de extras y cracion de fila en nota de venta
    $.ajax({url: DOMAIN+"buscar_precio_extra.php?p_tama="+p_tama+"&p_extra="+extra, success: function(result){
      // alert(result);
      precio=Number(result);
      var descrip= "Extra "+extra+" en pizza "+p_tama;
      agregar_fila(descrip,precio);
      //Objeto para almacenar extras en tabla de comprobante
      var venta_extra={id:fila_id,id_pizza:p_id_pizza,categ:"Extra",canti:1,tama:p_tama,p_extra:extra,precioP:precio};
      venta_aux.push(venta_extra);
      //Array paracargar a BD con ingredientes extras repetidos
      var v_extra={id:fila_id,id_pizza:p_id_pizza,p_extra:extra};
      venta_aux_extra.push(v_extra);
      //Bloquear
      document.getElementById("canti_"+fila_id).disabled = true;
    }});
    
  }

  function avanzar_extra() {
    var e = document.getElementById("selc_pizzas_forma");
    var f = document.getElementById("selc_extra");
    centrar(e);
    f.style.display = 'none';
    pizza_vent=4;   //Ventana de servir
    titu_regre.innerText ="Forma de Consumo";
  }

  function forma_servir(forma) {
    p_forma=forma; 
    venta_aux.forEach(element => {
      if (element.id==(Number(fila_id-num_extras))) {   //Es necesario contar el numero de xtras porq tambien generan filas
        element.forma=p_forma;
      }
    });

    if (p_forma=="llevar"  && !(p_tama=="porcion")) {
      var descrip="Caja Pizza "+p_tama;
      if(p_tama=="mediana")
        var precio_caja=0;      //Determinar precio de caja mediana
      else 
        var precio_caja=0;      //Determincar precio de caja grande
      
      agregar_fila(descrip, precio_caja);
      var venta_forma={id:fila_id,id_pizza:p_id_pizza,categ:"Caja_pizza",canti:1,tama:p_tama,precioP:precio_caja};
      venta_aux.push(venta_forma);
      document.getElementById("canti_"+fila_id).disabled = true;
    }
    //Quitar  el contenedor al finalizar
    var e = document.getElementById("selc_pizzas_forma");
    var regr=document.getElementById("funcion_regresar"); 
    var g = document.getElementById("cont_categ");
    e.style.display = 'none';
    regr.style.display = 'none';
    g.style.pointerEvents="auto"; //Habilitar pulsacion
    centrar(btn_cont);
    btn_cont.disabled=false;   //Desbloqueo boton contiuar compra
    //Titulo de ventana
    titu_regre.innerText = "La pizza es para:";
    //Deshabilitar el contador de caja para actulizacion automatica
  }


  function centrar(id){
    id.style.display = 'flex';
    id.style.paddingTop='2%';
    id.style.alignItems='center';
    id.style.flexWrap= 'wrap';
    id.style.justifyContent= 'center';
  }

  function regresar_carac(){
    var r = document.getElementById("funcion_regresar");
    var z=document.getElementById("cont_categ");
    switch (pizza_vent) {
      case 1:     //Ventana de tipo
        selec_categ(categ);   
        break;
      case 2:     //Ventana de sabor
        if(p_tama=="porcion"){
          var g = document.getElementById("selc_pizzas_sabor_PORCION");
          selec_categ(categ);
          g.style.display = 'none';
        } 
        else
          tam_pizzas(p_tama);
        break;
      case 3:     //Ventana de extra
        pizzas_normal(p_tipo);
        break;
      case 4:     //Ventana de servirse
        sabor_pizza(p_sabor,1,0);   //1 determina que esta regresando a la venta anterior
        break;
      case 0:
        var g = document.getElementById("cont_categ");
        var f = document.getElementById("selc_pizzas_tam");
        
        g.style.pointerEvents="auto"; //Habilitar categorias
        btn_cont.disabled=false;      //Habilitar continuar compra
        f.style.display = 'none';     //Desaparecer caracteristicas pizzas
        r.style.display = 'none';
        break;
      case 5:       //Regresar bebidas
        var g = document.getElementById("selc_bebidas");
        r.style.display = 'none';
        g.style.display="none";
        z.style.pointerEvents="auto"; //Habilitar pulsacion
        btn_cont.disabled=false;
        break;

      case 6:       //Regresar ingredientes
        var f = document.getElementById("selc_ingredientes");
        r.style.display = 'none';
        f.style.display="none";
        z.style.pointerEvents="auto"; //Habilitar pulsacion
        btn_cont.disabled=false;
        break;

      case 7:       //Regresar Caja Pizza
        var f = document.getElementById("selc_cajas");
        r.style.display = 'none';
        f.style.display="none";
        z.style.pointerEvents="auto"; //Habilitar pulsacion
        btn_cont.disabled=false;
        break;

      case 8:       //Regresar Otros
        var f = document.getElementById("selc_otros");
        r.style.display = 'none';
        f.style.display="none";
        z.style.pointerEvents="auto"; //Habilitar pulsacion
        btn_cont.disabled=false;
        break;

      case 9:       //Regresar Lasagna
        var f = document.getElementById("selc_lasagna");
        r.style.display = 'none';
        f.style.display="none";
        z.style.pointerEvents="auto"; //Habilitar pulsacion
        btn_cont.disabled=false;
        break;
      
      case 10:       //Regresar Pastas
        var f = document.getElementById("selc_pastas");
        r.style.display = 'none';
        f.style.display="none";
        z.style.pointerEvents="auto"; //Habilitar pulsacion
        btn_cont.disabled=false;
        break;
      
      case 11:       //Regresar Pastas
        var f = document.getElementById("selc_ensaladas");
        r.style.display = 'none';
        f.style.display="none"; 
        z.style.pointerEvents="auto"; //Habilitar pulsacion
        btn_cont.disabled=false;
        break;

      case 12:       //Regresar Pastas
        var f = document.getElementById("selc_ensaladas");
        r.style.display = 'none';
        f.style.display="none";
        z.style.pointerEvents="auto"; //Habilitar pulsacion
        btn_cont.disabled=false;
        break;
    }
    document.getElementById("formulario-perso").reset();
  }

  function eliminar_fila(tr_id) {
    //Eliminar fila de comprobante de venta
    $('#tabla_factura tbody tr#'+tr_id).remove();     //Eliminar fila  de tabla
    item_eliminados.push(tr_id);
    //Eliminar adicionales de pizza eliminadasfor
    venta_aux.forEach(element => {
      if (element.id==tr_id) {
        if(element.categ=="Pizzas"){
          venta_aux.forEach(elem_relac => {
            if(elem_relac.id_pizza==tr_id){
              $('#tabla_factura tbody tr#'+elem_relac.id).remove();
              item_eliminados.push(elem_relac.id);
            }
          });
        }
        else if(element.categ=="Caja_pizza"){
          venta_aux.forEach(elem_relac2 => {
            //Eliminar pizzas vinculadas con caja
            if(elem_relac2.categ=="Pizzas"){
              if(elem_relac2.id==element.id_pizza){
                $('#tabla_factura tbody tr#'+elem_relac2.id).remove();
                item_eliminados.push(elem_relac2.id);
              }
            }
            //Eliminar adicionales de pizzas vinculadas con caja
            else if(elem_relac2.id_pizza==element.id_pizza){
              $('#tabla_factura tbody tr#'+elem_relac2.id).remove();
              item_eliminados.push(elem_relac2.id);
            }
          });
          element.id_pizza=0;
        }
      }
    });
    //alert(item_eliminados);
    sum_productos();
  }

  function actu_precio(id){
    var cantidad=document.getElementById('canti_'+id).value;
    var precio=document.getElementById('precio_'+id).value;
    var total=(cantidad*precio).toFixed(2);
    document.getElementById('total_'+id).value=total;

    //Busqueda en array de productos
    venta_aux.forEach(element => {
      if (element.id==(Number(id))) {
        element.canti=cantidad;
        element.precioP=total;
        // alert(total);

        //Cambio automatico # de caja para llevar
        if(element.categ=="Pizzas"){
          venta_aux.forEach(caja => {
            if(caja.id_pizza==(Number(id))){
              //alert(caja.id_pizza);
              caja.canti=cantidad;
              precio=document.getElementById('precio_'+caja.id).value;
              caja.precioP=(cantidad*precio).toFixed(2);
              //ACtualizacion en nota de venta
              document.getElementById('total_'+caja.id).value=caja.precioP;
              document.getElementById('canti_'+caja.id).value=cantidad;
            }
          });
        }
      }
    });

    //Busqueda en Extras para repetirlo
    var aux_string="";
    venta_aux_extra.forEach(elementEx => {
      if(elementEx.id==(Number(id))){
        var ingre= Array.from(new Set(elementEx.p_extra.split(','))).toString();    //eliminar elementos repetidos de string
        aux_string=ingre;
        for(var j=1;j<cantidad;j++){      //cargar el numero de veces que el extra a sido colocado
          aux_string=aux_string+","+ingre;
        }
        elementEx.p_extra=aux_string;
      }
    });
    sum_productos();
  }

  function sum_productos() {
    var porc_iva=Number(document.getElementById('valor_iva').value)/100;
    var sum=0;
    for (i=1; i<=fila_id; i++) {
      if (document.getElementById('total_'+i)!=null) {
        var total=document.getElementById('total_'+i).value;
        sum+=Number(total);
      }
    }
    document.getElementById('sub_producto').value=sum.toFixed(2); 
    document.getElementById('iva').value=(porc_iva*sum).toFixed(2);
    var valor_iva=Number(document.getElementById('iva').value);
    document.getElementById('total_compra').value=(valor_iva+sum).toFixed(2);
  }

  function agregar_fila(descrip, prec) {
    fila_id++;
    var newRow = $("<tr id="+fila_id+">");
    var cols = "";
    cols += '<td class="text-center" style=width: 100%;"><input id="canti_'+fila_id+'" name="cantidad" type="number" value="1" min="1" style="width: 60%;" onchange="actu_precio('+fila_id+')"></td>';
    cols += '<td class="text-justify" style=width: 100%;">'+descrip+'</td>';
    cols += '<td class="text-center" style=width: 100%;">$ <input class="text-center" id="precio_'+fila_id+'" name="precio" type="text" style="width: 70%;" disabled value='+prec.toFixed(2)+'></td>';
    cols += '<td class="text-center" style=width: 100%;">$ <input class="text-center" id="total_'+fila_id+'" name="total" type="text"  style="width: 70%;" disabled value='+prec.toFixed(2)+'></td>';
    cols += '<td class="text-center" style=width: 100%;"> <span onclick="eliminar_fila('+fila_id+')"  class="btn btn-xs btn-danger" data-toggle="tooltip" title="Eliminar"><span class="glyphicon glyphicon-trash"></span></span></td>';

    newRow.append(cols);
    $("#tabla_factura").append(newRow);
    
    sum_productos();
    
  }

  function f_final_compra(){
    var nFilas = $("#tabla_factura tbody tr").length;
    if (nFilas==0) {
      alert("No existen productos en la factura");
      location.reload();
    }
    else{
      var total = document.getElementById('total_compra').value;
      document.getElementById('total_efectivo').value=parseFloat(total).toFixed(2);

      document.getElementById('cont_vuelto').style.display='block';
    }
  }

  function ingre_especial1(){
    var ingre=0;
    var ingre_esp=[];
    for(k=1;k<=4;k++){
      var e = document.getElementById("ingred_"+k);
      var strUser = e.options[e.selectedIndex].text;
      if(strUser!='Seleccione el sabor del ingrediente'){
        ingre++;
        ingre_esp.push(strUser);        //Ingresar ingredientes de personalizada en array ingre_esp
      }
    }
    if(ingre>=2){
      var str_esp= 'LaMiaPizza:';
      for(l=0;l<ingre_esp.length;l++)
        str_esp+=("1/"+ingre+" "+ingre_esp[l]+",");
      sabor_pizza(str_esp,0,ingre_esp);
    }
    else{
      ingre_esp=[];
      alert("Numero de ingredientes insuficientes");
    }
    document.getElementById("formulario-perso").reset();
    event.preventDefault();     //Evitar refresh de pagina tras submit
  }

  /////////////////////////////-------------------------FORMA DE PAGO-----------------------------------------------------///////////////////
  function forma_pago(forma){
    var total = document.getElementById('total_compra').value;
    //Tablas
    tabla_vuelto=document.getElementById('tabla_vuelto');
    tabla_uber=document.getElementById('tabla_uber');
    tabla_tarjeta=document.getElementById('tabla_tarjeta');
    //Imagenes
    im_tarjeta=document.getElementById('im_tarjeta');
    im_uber=document.getElementById('im_uber');
    //Tabla domicilio
    panel_domi=document.getElementById('panel_domi');

    if (forma=="efectivo") {
      tabla_vuelto.style.display='block';
      tabla_uber.style.display='none';
      tabla_tarjeta.style.display='none';
      //-----------------------------
      im_tarjeta.style.display='none';
      im_uber.style.display='none';
      //---------------------------------
      panel_domi.style.display='block';

      pagoTotal=1;    //Bandera de pago en efectivo
      document.getElementById('total_efectivo').value=parseFloat(total).toFixed(2);
    }
    else if (forma=="tarjeta"){
      tabla_vuelto.style.display='none';
      tabla_uber.style.display='none';
      tabla_tarjeta.style.display='block';
      //-----------------------------
      centrar(im_tarjeta);
      im_uber.style.display='none';
      //---------------------------------
      panel_domi.style.display='block';

      pagoTotal=0;    //Bandera de pago en tarjeta
      document.getElementById('sub_tarjeta').value=parseFloat(total).toFixed(2);
    }
    else if(forma=="uber") {
      tabla_vuelto.style.display='none';
      tabla_uber.style.display='block';
      tabla_tarjeta.style.display='none';
      //-----------------------------
      im_tarjeta.style.display='none';
      centrar(im_uber);
      //---------------------------------
      panel_domi.style.display='none';
      document.getElementById('chk_domicilio').checked=false;
      resetear_domi();

      pagoTotal=4;    //Bandera de pago Uber
      //Colocar total de venta
      document.getElementById('sub_uber').value=parseFloat(total).toFixed(2);
    }
    actu_domicilio();
  }

  //---------------------------------------------------------------------------

  function f_bebidas(size, flavor){
    var f = document.getElementById("selc_bebidas");   //Actual
    var r = document.getElementById("funcion_regresar");
    var g = document.getElementById("cont_categ");
    g.style.pointerEvents="auto"; //Habilitar pulsacion
    f.style.display="none";
    r.style.display = 'none';
    
    // Buscar precios de extras y cracion de fila en nota de venta

    $.ajax({url: DOMAIN+"buscar_precio_bebidas.php?p_size="+size+"&p_flavor="+flavor, success: function(result){
      precio=Number(result);
      var descrip= size+" de "+flavor;
      agregar_fila(descrip,precio);
      var venta_bebida={id:fila_id,categ:"Bebida",canti:1,tama:size,sabor:flavor,precioP:precio};
      venta_aux.push(venta_bebida);
      centrar(btn_cont);
      btn_cont.disabled=false;  //Desbloqueo boton contiuar compra
    }});
  }

  function f_ingred(nombre){
    var f = document.getElementById("selc_ingredientes");   //Actual
    var r = document.getElementById("funcion_regresar");
    var g = document.getElementById("cont_categ");
    g.style.pointerEvents="auto"; //Habilitar pulsacion
    f.style.display="none";
    r.style.display = 'none';

    // alert(nombre);
    $.ajax({url: DOMAIN+"buscar_precio_ingredi.php?p_nombre="+nombre, success: function(result){
      // alert(result);
      precio=Number(result);
      var descrip= nombre.replace(/(^|\s)\S/g, l => l.toUpperCase());         //Poner en mayuscula primera letra
      agregar_fila(descrip,precio);
      var venta_ingre={id:fila_id,categ:"Ingredientes",canti:1,v_nombre:nombre,precioP:precio};
      venta_aux.push(venta_ingre);
      centrar(btn_cont);
      btn_cont.disabled=false;   //Desbloqueo boton contiuar compra
    }});
  }

  //---------------------------CATEGORIAS PASTAS
  function f_pastas(nombre){
    var f = document.getElementById("selc_pastas");   //Actual
    var r = document.getElementById("funcion_regresar");
    var g = document.getElementById("cont_categ");
    g.style.pointerEvents="auto"; //Habilitar pulsacion
    f.style.display="none";
    r.style.display = 'none';

    $.ajax({url: DOMAIN+"buscar_precio_pasta.php?p_nombre="+nombre, success: function(result){
      // alert(result);
      precio=Number(result);
      var descrip= nombre.replace(/(^|\s)\S/g, l => l.toUpperCase());         //Poner en mayuscula primera letra
      agregar_fila(descrip,precio);
      var venta_ingre={id:fila_id,categ:"Otros",canti:1,v_nombre:nombre,precioP:precio};
      venta_aux.push(venta_ingre);
      centrar(btn_cont);
      btn_cont.disabled=false;   //Desbloqueo boton contiuar compra
    }});
  }

  //---------------------------CATEGORIAS ENSALADAS
  function f_ensaladas(nombre){
    var f = document.getElementById("selc_ensaladas");   //Actual
    var r = document.getElementById("funcion_regresar");
    var g = document.getElementById("cont_categ");
    g.style.pointerEvents="auto"; //Habilitar pulsacion
    f.style.display="none";
    r.style.display = 'none';

    $.ajax({url: DOMAIN+"buscar_precio_ensaladas.php?p_nombre="+nombre, success: function(result){
      // alert(result);
      precio=Number(result);
      var descrip= nombre.replace(/(^|\s)\S/g, l => l.toUpperCase());         //Poner en mayuscula primera letra
      agregar_fila(descrip,precio);
      var venta_ingre={id:fila_id,categ:"Otros",canti:1,v_nombre:nombre,precioP:precio};
      venta_aux.push(venta_ingre);
      centrar(btn_cont);
      btn_cont.disabled=false;   //Desbloqueo boton contiuar compra
    }});
  }

  //---------------------------CATEGORIAS OTROS
  function f_otros(nombre){
    var f = document.getElementById("selc_otros");   //Actual
    var r = document.getElementById("funcion_regresar");
    var g = document.getElementById("cont_categ");
    g.style.pointerEvents="auto"; //Habilitar pulsacion
    f.style.display="none";
    r.style.display = 'none';

    $.ajax({url: DOMAIN+"buscar_precio_otros.php?p_nombre="+nombre, success: function(result){
      // alert(result);
      precio=Number(result);
      var descrip= nombre.replace(/(^|\s)\S/g, l => l.toUpperCase());         //Poner en mayuscula primera letra
      agregar_fila(descrip,precio);
      var venta_ingre={id:fila_id,categ:"Otros",canti:1,v_nombre:nombre,precioP:precio};
      venta_aux.push(venta_ingre);
      centrar(btn_cont);
      btn_cont.disabled=false;   //Desbloqueo boton contiuar compra
    }});
  }

   //---------------------------CATEGORIAS LASAGNA
   function f_lasagna(nombre){
    var n_lasagna="Lasagna "+nombre;
    var f = document.getElementById("selc_lasagna");   //Actual
    var r = document.getElementById("funcion_regresar");
    var g = document.getElementById("cont_categ");
    g.style.pointerEvents="auto"; //Habilitar pulsacion
    f.style.display="none";
    r.style.display = 'none';

    $.ajax({url: DOMAIN+"buscar_precio_lasagna.php?p_nombre="+nombre, success: function(result){
      // alert(result);
      precio=Number(result);
      var descrip= n_lasagna;
      agregar_fila(descrip,precio);
      var venta_ingre={id:fila_id,categ:"Lasagna",canti:1,v_nombre:n_lasagna,precioP:precio};
      venta_aux.push(venta_ingre);
      centrar(btn_cont);
      btn_cont.disabled=false;   //Desbloqueo boton contiuar compra
    }});
  }


  function f_caja(tam){
    var descrip="Caja Pizza "+tam;
    if(tam!='familiarEspecial')
      var precio_caja=1;
    else {
      var precio_caja=1.5;
      tam='familiar';
    }
    
    agregar_fila(descrip, precio_caja);
    var venta_forma={id:fila_id,categ:"Caja_pizza",canti:1,tama:tam,precioP:precio_caja};
    venta_aux.push(venta_forma);

    //Quitar  el contenedor al finalizar
    var e = document.getElementById("selc_cajas");
    var regr=document.getElementById("funcion_regresar"); 
    var g = document.getElementById("cont_categ");
    e.style.display = 'none';
    regr.style.display = 'none';
    g.style.pointerEvents="auto"; //Habilitar pulsacion
    centrar(btn_cont);
    btn_cont.disabled=false;   //Desbloqueo boton contiuar compra
    //Titulo de ventana
    titu_regre.innerText = "Escoja la caja a vender:";
  }

  function f_continuar(conti){
    var aux=0;            //Auxiliar q permite determinar si se debe cargar los datos a la  BD o no
    var efect=document.getElementById('in_efectivo').value;
    var total=document.getElementById('total_compra').value;
    var totalRecibo;
    
    if(conti==1){
      if(document.getElementById('optionsRadios1').checked){   //Pago en efectivo
        totalRecibo=document.getElementById('total_compra').value;
        p_pago="efectivo";
        if(Number(efect)>=Number(total)){
          p_pago="efectivo";
        }
        else{
          alert("Valor de efectivo incorrecto");    //El ingreso de dinero es insuficiente
          aux=1;
        }
      }
      else if(document.getElementId('optionsRadios2').checked){   //Pago en tarjeta
        totalRecibo=document.getElementById('total_tarjeta').value;
        p_pago="tarjeta";

      }
      else if(document.getElementId('optionsRadios3').checked){   //Pago en domicilio
        totalRecibo=document.getElementById('total_domicilio').value;
        p_pago="domicilio";
      }
      else if(document.getElementId('optionsRadios4').checked){   //Pago en uber
        totalRecibo=document.getElementById('total_uber').value;
        p_pago="uber";
      }
    

      var user = "<?php echo $user['username']; ?>";        //Determinar el usuario que ejecuta la venta

      //CARGAR A BD DE VENTA PIZZAS
      var aux_extra=0;    //Aux que cuenta el numero de extras
      str_extra="";
      if(aux==0){
        venta_aux.forEach(element => {
          var aux_eli=item_eliminados.indexOf(element.id);
          if(aux_eli<0){
            //alert(element.id);
            if(element.categ=="Pizzas"){
              //Agregar los extras a la pizza actualemente seleccionada
              venta_aux_extra.forEach(ele_extra => {
                if(ele_extra.id_pizza==element.id){     //Verificar si pertenece a la pizza
                  var aux_eli_extra=item_eliminados.indexOf(ele_extra.id);    //Verificar si no ha sido eliminada
                  if(aux_eli_extra<0){                                        //Verificar si no ha sido eliminada
                    str_extra+=(ele_extra.p_extra+",");
                    //alert(str_extra);
                    aux_extra++;
                  }
                }
              });

              var str_e=""
              if(aux_extra==0){
                if(element.extra!="")
                  str_e=element.extra.slice(0, -1);     //Elimina la coma de sabores de personalozada
              }       
              else
                var str_e=element.extra+str_extra.slice(0, -1);         //Elimina la coma

              $.ajax({url: DOMAIN+"guardar_ventas.php?p_canti="+element.canti+"&p_tama="+element.tama+"&p_tipo="+element.tipo+"&p_sabor="+element.sabor+"&p_extras="+str_e+"&p_forma="+element.forma+"&p_precio="+element.precioP+"&p_pago="+p_pago+"&p_usuario="+user
              });
              str_extra="";
            }
            else if(element.categ=="Bebida"){
              $.ajax({url: DOMAIN+"guardar_ventas_bebida.php?p_canti="+element.canti+"&p_tama="+element.tama+"&p_sabor="+element.sabor+"&p_precio="+element.precioP+"&p_usuario="+user+"&p_forma="+p_pago
              });
            }
            else if(element.categ=="Ingredientes"){
              $.ajax({url: DOMAIN+"guardar_ventas_ingredientes.php?p_canti="+element.canti+"&p_nombre="+element.v_nombre+"&p_precio="+element.precioP+"&p_usuario="+user+"&p_forma="+p_pago
              });
            }
            else if(element.categ=="Caja_pizza"){
              $.ajax({url: DOMAIN+"guardar_ventas_ingredientes.php?p_canti="+element.canti+"&p_nombre="+element.tama+"&p_precio="+element.precioP+"&p_usuario="+user+"&p_forma="+p_pago
              });
              
              $.ajax({url: DOMAIN+"guardar_ventas_cajas.php?p_canti="+element.canti+"&p_tama="+element.tama+"&p_precio="+element.precioP+"&p_usuario="+user
              });
            }
            else if(element.categ=="Otros"){
              $.ajax({url: DOMAIN+"guardar_ventas_ingredientes.php?p_canti="+element.canti+"&p_nombre="+element.v_nombre+"&p_precio="+element.precioP+"&p_usuario="+user+"&p_forma="+p_pago
              });
            }

            else if(element.categ=="Lasagna"){
              $.ajax({url: DOMAIN+"guardar_ventas_ingredientes.php?p_canti="+element.canti+"&p_nombre="+element.v_nombre+"&p_precio="+element.precioP+"&p_usuario="+user+"&p_forma="+p_pago
              });
            }
            
          }
        });
      
        //ENVIO PARA IMPRESION DE COMPRABANTE DE PAGO
        var srt_get="";
        venta_aux.forEach(element => {
          var aux_eli=item_eliminados.indexOf(element.id);    //Verificar si no ha sido eliminada
          if(aux_eli<0){                                        //Verificar si no ha sido eliminada
            srt_get+=(element.canti+","+element.categ+" ");

            if (element.categ=="Pizzas") {      //Determinar que tipo de categoria es
              srt_get+=(element.tama+" ");//+","+element.extra+","+element.forma+","+element.precioP+","+element.fpago);
              if(element.sabor=="lamiapizza"){
                srt_get+="P: ";
                var srt_get2='';
                if(venta_aux_extra.length>0){     //Verificar si hay extras
                  var arr_extras=element.extra.split(",");    //Convertir str_extras en array
                  arr_extras.forEach(extra => {               //Evaluar cada extra de la pizza
                    venta_aux_extra.forEach(ele_extra => {    //Buscar en el array venta aux extra
                      if(ele_extra.id_pizza==element.id){     //Verificar si pertenece a la pizza
                        var aux_eli_extra=item_eliminados.indexOf(ele_extra.id);    //Verificar si no ha sido eliminada
                        if(aux_eli_extra<0){ 
                          if(extra!=ele_extra.p_extra){
                            srt_get2+=extra+",";
                          }
                        }
                      }
                    });
                  });
                }
                else{
                  var srt_get2=(element.extra.slice(0, -1));      //Elimina ultima como y transforma como en espacio
                }
                var str_per=Array.from(new Set(srt_get2.split(','))).toString();    //Quitar duplicados
                var str_per2=str_per.split(","); 
                var str_per3='';
                str_per2.forEach(element => {
                  str_per3+=element.slice(0,3)+"-";
                });
                var str_per4=str_per3.slice(0, -1);            //cambiar , por  - y elimiar el ultimo -

                srt_get+=str_per4;
              }
              else{
                srt_get+=element.sabor;
              }

              if(element.forma=="llevar")
                srt_get+=" L";
              else
                srt_get+=" S";
            }
            else if(element.categ=="Extra"){
              srt_get+=(element.tama+" "+element.p_extra);
            }
            else if (element.categ=="Caja_pizza") {
              srt_get+=(element.tama);
              if(element.tama=="mediana")
                caja_mediana++;
              else 
               caja_grande++;
            }
            else if (element.categ=="Bebida") {
              srt_get+=(element.tama+" "+element.sabor);
            }
            else if (element.categ=="Ingredientes") {
              srt_get+=(element.v_nombre);         //Poner en mayuscula primera letra);
            }
            srt_get+=(","+(element.precioP/element.canti)+","+element.precioP+",");
          } 
        });

        //GUARDAR CONSUMO APROXIMADOS DE INGREDIENTES
        var totalCompra=document.getElementById('total_compra').value;
        var efectivo=document.getElementById('in_efectivo').value;
        var vuelto=document.getElementById('in_vuelto').value;
        
        var str_get2=srt_get.slice(0, -1);

        var date = "<?php echo make_date(); ?>";
        var d = new Date();
        var date1=d.getFullYear().toString()+"_"+d.getMonth().toString()+"_"+d.getDate().toString()+"_"+d.getHours().toString()+"_"+d.getMinutes().toString();
        
        var servir=1; //0 llevar, 1 servirse
        //var servir = [0,1,1,1,1];
        var numorden='<?php echo $contador;?>';

        //guarda venta general y el contador
        $.ajax({url: DOMAIN+"realizar_z.php?"+"servir="+servir+"&"+"numorden="+numorden+"&"+"user="+user+"&"+"date="+date+"&"+"subtotal="+totalCompra+"&"+"orden="+str_get2+"&"+"date1="+date1+"&p_efect="+efectivo+"&p_vuelto="+vuelto+"&p_pago="+p_pago});
        

        //manda a imprimir
        var win = window.open("escpos-php/hello.php?"+"servir="+servir+"&"+"numorden="+numorden+"&"+"user="+user+"&"+"date="+date+"&"+"subtotal="+totalCompra+"&"+"orden="+str_get2+"&"+"date1="+date1+"&p_efect="+efectivo+"&p_vuelto="+vuelto+"&p_pago="+p_pago,"_SELF"); // will open new tab on document ready
      }
    }
    else{
      window.open(DOMAIN+"realizar_venta.php","_self");
    }
    
  }

  //---------------------------------------------------------------------------------------------------------------------
  
  function actu_vuelto(){ //Calculo Efectivo
    var total = document.getElementById('total_compra').value;
    var efectivo = document.getElementById('in_efectivo').value;

    document.getElementById('in_vuelto').value=(efectivo-total).toFixed(2);
    //Mantener 2 decimales
    efectivo = parseFloat(efectivo).toFixed(2);
  }

  function actu_tarjeta(){ //Calculo Tarjeta
    var sub_tar = document.getElementById('sub_tarjeta').value;
    var cargoAdic = document.getElementById('cargo_tarjeta').value;

    document.getElementById('total_tarjeta').value=(parseFloat(sub_tar)+parseFloat(cargoAdic)).toFixed(2);
    document.getElementById('cargo_tarjeta').value=parseFloat(cargoAdic).toFixed(2);
  }

  function actu_domicilio(){ //Calculo Domicilio
    var domicilio = document.getElementById('in_domicilio').value;
    var total_compra = document.getElementById('total_compra').value;

    if(document.getElementById('optionsRadios1').checked){   //Pago en efectivo
      document.getElementById('total_efectivo').value=(parseFloat(total_compra)+parseFloat(domicilio)).toFixed(2);
    }
    else if(document.getElementById('optionsRadios2').checked){//Tarjeta
      document.getElementById('sub_tarjeta').value=(parseFloat(total_compra)+parseFloat(domicilio)).toFixed(2);
    }
    else if(document.getElementById('optionsRadios4').checked){ //Uber
      document.getElementById('in_domicilio').value=(0).toFixed(2);   //Resetear el valor de domicilio
    }

    //Mantener 2 decimales
    document.getElementById('in_domicilio').value=parseFloat(domicilio).toFixed(2);
  }

  function actu_uber(){ //Calculo Uber
    var sub_uber = document.getElementById('sub_uber').value;
    var total_uber = document.getElementById('total_uber').value;

    //document.getElementById('in_subtotal').value=total;
    //document.getElementById('total_domicilio').value=(parseFloat(totalDom)+parseFloat(domicilio)).toFixed(2);
    //Mantener 2 decimales
    document.getElementById('total_uber').value=parseFloat(total_uber).toFixed(2);
  }
  
  function func_domicilio(){

    if (document.getElementById('chk_domicilio').checked) {
      document.getElementById('panel_body_domi').style.display='block';
    }
    else {
      resetear_domi();
      actu_domicilio();
    }
  }

  function resetear_domi(){
    document.getElementById('panel_body_domi').style.display='none';
    document.getElementById('in_domicilio').value=(0).toFixed(2);
  }

