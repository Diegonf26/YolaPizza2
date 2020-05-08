// Progrma de venta de productos 2
//src="libs/js/realizar_venta.js"
var categ, p_tama, p_tipo, p_extras, p_forma, pizza_vent='0';
var aux_fila_elim = 0;
  
function selec_categ(nombre_cat) {
  if(nombre_cat=="Pizzas"){
    var g = document.getElementById("cont_categ");
    var e = document.getElementById("selc_pizzas_nor_esp");
    var f = document.getElementById("selc_pizzas_tam");
    var r=document.getElementById("funcion_regresar");
    centrar(f);
    centrar(r);
    r.style.justifyContent= 'left';
    r.style.paddingLeft= '3%';
    //quitar pantalla anterior
    e.style.display = 'none';
  }
  categ=nombre_cat;
  pizza_vent=0;   //Ventana de tamano
  g.style.pointerEvents="none";
}

function tam_pizzas(tama){
  if(tama!="Porcion"){
    var e = document.getElementById("selc_pizzas_nor_esp");
    var f = document.getElementById("selc_pizzas_tam");
    var g = document.getElementById("selc_pizzas_tipo");
    centrar(e);
    f.style.display = 'none';
    g.style.display = 'none';
  }
  p_tama=tama;
  pizza_vent=1;   //Ventana de especial o normal
}

function pizzas_normal(){
  var e = document.getElementById("selc_pizzas_tipo");
  var f = document.getElementById("selc_pizzas_nor_esp");
  var g = document.getElementById("selc_extra");
  centrar(e);
  f.style.display = 'none';
  g.style.display = 'none';
  pizza_vent=2;   //Ventana de tipo
}

function tip_pizza(tipo){
  var e = document.getElementById("selc_extra");
  var f = document.getElementById("selc_pizzas_tipo");
  var g = document.getElementById("selc_pizzas_forma");
  centrar(e);
  f.style.display = 'none';
  g.style.display = 'none';
  p_tipo=tipo;
  pizza_vent=3;   //Ventana de extras
}

function ingre_extra(extra){
  var e = document.getElementById("selc_pizzas_forma");
  var f = document.getElementById("selc_extra");
  centrar(e);
  f.style.display = 'none';
  p_extras=extra;
  pizza_vent=4;   //Ventana de servr o llevar
}
 function forma_servir(forma) {
  p_forma=forma;
  //Creacion de nueva fila
  var fila_id=aux_fila_elim++;
  console.log("Cread:"+fila_id);
  var precio="<?php foreach ($g as $ggg){ echo remove_junk($ggg['price']); }?>";

  var newRow = $("<tr id="+fila_id+">");
  var cols = "";
  cols += '<td class="text-center"><input name="cantidad" type="number" value="1" min="1" style="width: 60%;"></td>';
  cols += '<td class="text-justify">'+categ+","+p_tama+","+p_tipo+""+p_forma+'</td>';
  cols += '<td id+class="text-center"><input name="precio" type="text"  style="width: 100%;" disabled value='+precio+'></td>';
  cols += '<td class="text-center"><input name="total" type="text"  style="width: 100%;" disabled></td>';
  cols += '<td class="text-center""> <span id="hola" onclick="eliminar_fila('+fila_id+')"  class="btn btn-xs btn-danger" data-toggle="tooltip" title="Eliminar"><span class="glyphicon glyphicon-trash"></span></span></td>';

  newRow.append(cols);
  $("table.table-striped.table-hover.table-condensed").append(newRow);
  

  //var win = window.open("realizar_venta.php?"+"&"+"pizz_tam="+p_tama+"&"+"pizz_tipo="+p_tipo+"&"+"pizz_extra="+p_extras+"&"+"pizz_forma="+p_forma,"_self");
 }

function centrar(id){
  id.style.display = 'flex';
  id.style.paddingTop='2%';
  id.style.alignItems='center';
  id.style.flexWrap= 'wrap';
  id.style.justifyContent= 'center';
}

function regresar_carac(){
  switch (pizza_vent) {
    case 1:
      selec_categ(categ);
      break;
    case 2:
      tam_pizzas(p_tama)
      break;
    case 3:
      pizzas_normal()
      break;
    case 4:
      tip_pizza(p_tipo);
      break;
    case 0:
      var g = document.getElementById("cont_categ");
      var f = document.getElementById("selc_pizzas_tam");
      var r = document.getElementById("funcion_regresar");
      g.style.pointerEvents="auto"; //Habilitar categorias
      f.style.display = 'none';     //Desaparecer caracteristicas pizzas
      r.style.display = 'none';
      break;
  }
}

function eliminar_fila(tr_id) {
  //Eliminar fila
  $('#tabla_factura tbody tr#'+tr_id).remove();
}
