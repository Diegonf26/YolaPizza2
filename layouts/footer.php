      </div>
     </div>
    </div>
    
    <!--script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.min.js"></script>
    <script type="text/javascript" src="libs/js/functions.js"></script-->

    <div class="scroll-to-top">
        <i class="icon-arrow-up"></i>
    </div>
    <!--footer-->
    <!--div style="background-color:#a34b24;" class="page-footer">
      <div style="text-align:center">
        <div class="page-footer-inner"> <?php echo  $hoy2 = date("Y"); ?> &copy; <?php echo $nameadmin ?>
          <a  title="Contacta con +58 0426-8734726" target="_blank"><?php echo $nameadmin ?>. Desarrollado por xxxxx</a>
        </div>
      </div>     

    </div-->

  <script src="assets/adminLTE/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.js"></script>
  <script type="text/javascript">
      $('.datepicker').datepicker({
        autoclose: true
      })
  </script> 
    <!-- BEGIN CORE PLUGINS -->
    
    <!--  <script src="assets/global/plugins/jquery.min.js" type="text/javascript"></script> -->
    
    <script src="assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
    <script src="assets/global/plugins/js.cookie.min.js" type="text/javascript"></script>
    <script src="assets/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js" type="text/javascript"></script>
    <script src="assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
    <script src="assets/global/plugins/jquery.blockui.min.js" type="text/javascript"></script>
    
  
    <!-- END CORE PLUGINS -->
    <!-- BEGIN PAGE LEVEL PLUGINS -->
    <script src="assets/global/plugins/moment.min.js" type="text/javascript"></script>
    <script src="assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.js" type="text/javascript"></script>
    <script src="assets/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
    <script src="assets/global/plugins/morris/morris.min.js" type="text/javascript"></script>
    <script src="assets/global/plugins/morris/raphael-min.js" type="text/javascript"></script>
    <script src="assets/global/plugins/counterup/jquery.waypoints.min.js" type="text/javascript"></script>
  

    <!-- END PAGE LEVEL PLUGINS -->
    <!-- BEGIN THEME GLOBAL SCRIPTS -->
    <script src="assets/global/scripts/app.min.js" type="text/javascript"></script>
    <!-- END THEME GLOBAL SCRIPTS -->
    <!-- BEGIN PAGE LEVEL SCRIPTS -->
    <script src="assets/pages/scripts/dashboard.min.js" type="text/javascript"></script>
    <!-- END PAGE LEVEL SCRIPTS -->
    <!-- BEGIN THEME LAYOUT SCRIPTS -->
    <script src="assets/layouts/layout/scripts/layout.min.js" type="text/javascript"></script>
    

    <!-- END PAGE LEVEL PLUGINS -->
    <!-- BEGIN THEME GLOBAL SCRIPTS -->
    <script src="assets/global/scripts/datatable.js" type="text/javascript"></script>
    <script src="assets/global/plugins/datatables/datatables.js" type="text/javascript"></script>
    <script src="assets/table-datatables-responsive.js" type="text/javascript"></script>
    <script src="assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js" type="text/javascript"></script>
    
    <!-- END PAGE LEVEL PLUGINS -->
    <!-- BEGIN THEME GLOBAL SCRIPTS -->


  </body>
</html>

<?php if(isset($db)) { $db->db_disconnect(); } ?>
