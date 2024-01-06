    </div>
</div>
<!--Start Back To Top Button-->
<a href="javaScript:void();" class="back-to-top"><i class="fa fa-angle-double-up"></i> </a>
    <!--End Back To Top Button-->
	
	<!--Start footer-->
	<footer class="footer" style="position:fixed;">
      <div class="container">
        <div class="text-center">
          Copyright © <?= date('Y'); ?> Neev Soft Technologies
        </div>
      </div>
    </footer>
	<!--End footer-->
   
  </div><!--End wrapper-->


  <!-- Bootstrap core JavaScript-->
  <script src="<?= base_url(); ?>/assets/js/jquery.min.js"></script>
  <script src="<?= base_url();?>assets/js/jquery-ui.min.js"></script>
  <script src="<?= base_url(); ?>/assets/js/popper.min.js"></script>
  <script src="<?= base_url(); ?>/assets/js/bootstrap.min.js"></script>
	
  <!-- simplebar js -->
  <script src="<?= base_url(); ?>/assets/plugins/simplebar/js/simplebar.js"></script>
  <!-- waves effect js -->
  <script src="<?= base_url(); ?>/assets/js/waves.js"></script>
  <!-- sidebar-menu js -->
  <script src="<?= base_url(); ?>/assets/js/sidebar-menu.js"></script>
  <!-- Custom scripts -->
  <script src="<?= base_url(); ?>/assets/js/app-script.js"></script>
  <script src="<?= base_url(); ?>assets/plugins/bootstrap-datatable/js/jquery.dataTables.min.js"></script>
  <script src="<?= base_url(); ?>assets/plugins/bootstrap-datatable/js/dataTables.bootstrap4.min.js"></script>
  <script src="<?= base_url(); ?>assets/plugins/bootstrap-datatable/js/dataTables.buttons.min.js"></script>
  <script src="<?= base_url(); ?>assets/plugins/bootstrap-datatable/js/buttons.bootstrap4.min.js"></script>
  <script src="<?= base_url(); ?>assets/plugins/bootstrap-datatable/js/jszip.min.js"></script>
  <script src="<?= base_url(); ?>assets/plugins/bootstrap-datatable/js/pdfmake.min.js"></script>
  <script src="<?= base_url(); ?>assets/plugins/bootstrap-datatable/js/vfs_fonts.js"></script>
  <script src="<?= base_url(); ?>assets/plugins/bootstrap-datatable/js/buttons.html5.min.js"></script>
  <script src="<?= base_url(); ?>assets/plugins/bootstrap-datatable/js/buttons.print.min.js"></script>
  <script src="<?= base_url(); ?>assets/plugins/bootstrap-datatable/js/buttons.colVis.min.js"></script>
  
  <script src="<?= base_url(); ?>assets/plugins/notifications/js/lobibox.min.js"></script>
  <script src="<?= base_url(); ?>assets/plugins/notifications/js/notifications.min.js"></script>
  <script src="<?= base_url(); ?>assets/plugins/notifications/js/notification-custom-script.js"></script>

  <script>
     $(document).ready(function() {
      //Default data table
       $('.default-datatable').DataTable({
            responsive: true,
            dom: 'Bfrtip',
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ]
       });

     });

    </script>

    <!--Switchery Js-->
    <script src="<?= base_url(); ?>assets/plugins/switchery/js/switchery.min.js"></script>
    <script>
      var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
      $('.js-switch').each(function() {
            new Switchery($(this)[0], $(this).data());
       });
    </script>

    <!--Bootstrap Switch Buttons-->
    <script src="assets/plugins/bootstrap-switch/bootstrap-switch.min.js"></script>
    <script>
    $(".bt-switch input[type='checkbox'], .bt-switch input[type='radio']").bootstrapSwitch();
    var radioswitch = function() {
        var bt = function() {
            $(".radio-switch").on("switch-change", function() {
                $(".radio-switch").bootstrapSwitch("toggleRadioState")
            }), $(".radio-switch").on("switch-change", function() {
                $(".radio-switch").bootstrapSwitch("toggleRadioStateAllowUncheck")
            }), $(".radio-switch").on("switch-change", function() {
                $(".radio-switch").bootstrapSwitch("toggleRadioStateAllowUncheck", !1)
            })
        };
        return {
            init: function() {
                bt()
            }
        }
    }();
    $(document).ready(function() {
        radioswitch.init()
    });
    </script>
	<script src="<?= base_url('assets/js/select2.js')?>"></script>
    <script>
    function MySearch() {
        $("select").select2({
            width:'100%'
        });
    }
        $(function(){
            MySearch();
        });

        setInterval(function(){
            $.ajax({
                type: "POST",
                url: "<?= base_url(); ?>Auth/CheckState",
                data: {state : 1},
                success: function(data){
                    if($.trim(data) === "empty"){
                        window.location.href="<?= base_url(); ?>Auth/LogOut";
                    }
                    else{
                        console.log(data);
                    }
                    
                }
            });
        }, 60000);
        

    </script>
      <script type="text/javascript" src="<?= base_url('assets/js/BrowserPrint-1.0.4.js')?>"></script>
    <script type="text/javascript" src="<?= base_url('assets/js/DevDemo.js')?>"></script>
    <script type="text/javascript">
        var OSName="Unknown OS";
        if (navigator.appVersion.indexOf("Win")!=-1) OSName="Windows";
        //{
        //OSName="Windows";
        //document.write('<a href="ZebraWebPrint.exe" class="navbar-brand" href="#">Download the '+OSName+' App</a>');
        //}
        if (navigator.appVersion.indexOf("Mac")!=-1) OSName="MacOS";
        if (navigator.appVersion.indexOf("X11")!=-1) OSName="UNIX";
        if (navigator.appVersion.indexOf("Linux")!=-1) OSName="Linux";
    </script>
</body>

</html>