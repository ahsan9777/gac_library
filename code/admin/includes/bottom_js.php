<!-- JAVASCRIPTS -->
<!-- Placed at the end of the document so the pages load faster -->
<!-- JQUERY -->
<script src="js/jquery/jquery-2.0.3.min.js"></script>
<!-- JQUERY UI-->
<script src="js/jquery-ui-1.10.3.custom/js/jquery-ui-1.10.3.custom.min.js"></script>
<!-- BOOTSTRAP -->
<script src="bootstrap-dist/js/bootstrap.min.js"></script>

	
<!-- DATE RANGE PICKER -->
<script src="js/bootstrap-daterangepicker/moment.min.js"></script>

<script src="js/bootstrap-daterangepicker/daterangepicker.min.js"></script>
<!-- SLIMSCROLL -->
<script type="text/javascript" src="js/jQuery-slimScroll-1.3.0/jquery.slimscroll.min.js"></script>
<!-- SLIMSCROLL -->
<script type="text/javascript" src="js/jQuery-slimScroll-1.3.0/jquery.slimscroll.min.js"></script><script type="text/javascript" src="js/jQuery-slimScroll-1.3.0/slimScrollHorizontal.min.js"></script>
<!-- BLOCK UI -->
<script type="text/javascript" src="js/jQuery-BlockUI/jquery.blockUI.min.js"></script>
<!-- BOOTBOX -->
	<script type="text/javascript" src="js/bootbox/bootbox.min.js"></script>
<!-- ISOTOPE -->
	<script type="text/javascript" src="js/isotope/jquery.isotope.min.js"></script>
	<script type="text/javascript" src="js/isotope/imagesloaded.pkgd.min.min.js"></script>
        <script type="text/javascript" src="js/colorbox/jquery.colorbox.min.js"></script>
<!-- SPARKLINES -->
<script type="text/javascript" src="js/sparklines/jquery.sparkline.min.js"></script>
<!-- EASY PIE CHART -->
<script src="js/jquery-easing/jquery.easing.min.js"></script>
<script type="text/javascript" src="js/easypiechart/jquery.easypiechart.min.js"></script>
<!-- FLOT CHARTS -->
<script src="js/flot/jquery.flot.min.js"></script>
<script src="js/flot/jquery.flot.time.min.js"></script>
<script src="js/flot/jquery.flot.selection.min.js"></script>
<script src="js/flot/jquery.flot.resize.min.js"></script>
<script src="js/flot/jquery.flot.pie.min.js"></script>
<script src="js/flot/jquery.flot.stack.min.js"></script>
<script src="js/flot/jquery.flot.crosshair.min.js"></script>
<!-- TODO -->
<script type="text/javascript" src="js/jquery-todo/js/paddystodolist.js"></script>
<!-- TIMEAGO -->
<script type="text/javascript" src="js/timeago/jquery.timeago.min.js"></script>
<!-- DATE PICKER -->
<script type="text/javascript" src="js/datepicker/picker.js"></script>
<script type="text/javascript" src="js/datepicker/picker.date.js"></script>
<script type="text/javascript" src="js/datepicker/picker.time.js"></script>


<script>
  $(function() {
      $( ".datepicker" ).datepicker({
                changeMonth: true,
		changeYear: true,
                dateFormat: 'yy/mm/dd',
		yearRange: "1947:c"
      
    });
     $( ".datepickerfrm" ).datepicker({
                changeMonth: true,
		changeYear: true,
                dateFormat: 'yy/mm/dd',
		
      
    });
      
      $( "#frm1" ).datepicker({
      changeMonth: true,
      dateFormat: 'yy/mm/dd',
      onSelect: function(selected) {
          $(".to1").datepicker("option","minDate", selected)
        }
    });
    $( "#to" ).datepicker({
      changeMonth: true,
      dateFormat: 'yy/mm/dd',
      onSelect: function(selected) {
          $("#frm1").datepicker("option","maxDate", selected)
        }
    });
    
       
    $( ".from" ).datepicker({
      defaultDate: "-1m",
      changeMonth: true,
      numberOfMonths: 1,
      
      onClose: function( selectedDate ) {
        $( ".to" ).datepicker( "option", "minDate", selectedDate );
      }
    });
    
        $( ".from" ).datepicker( "setDate", "-1m" );
        $(".from").datepicker("option",{  
            dateFormat: 'yy/mm/dd',  
             
          // whatever option Or event you want   
        });  
    $( ".to" ).datepicker({
      //defaultDate: "+1w",
      changeMonth: true,
      numberOfMonths: 1,
      onClose: function( selectedDate ) {
        $( ".from" ).datepicker( "option", "maxDate", selectedDate );
      }
    });
       var currentDate = new Date();  
        $(".to").datepicker("setDate",currentDate);  
          
        $(".to").datepicker("option",{  
            dateFormat: 'yy/mm/dd',  
           
          // whatever option Or event you want   
        });  
        
  });
  </script>
<!-- FULL CALENDAR -->
<script type="text/javascript" src="js/fullcalendar/fullcalendar.min.js"></script>
<!-- COOKIE -->
<script type="text/javascript" src="js/jQuery-Cookie/jquery.cookie.min.js"></script>
<!-- GRITTER -->
<!--<script type="text/javascript" src="js/gritter/js/jquery.gritter.min.js"></script>-->
<script type="text/javascript" src="js/ckeditor/ckeditor.js"></script>
<!-- COOKIE -->
<script type="text/javascript" src="js/jQuery-Cookie/jquery.cookie.min.js"></script>

<script type="text/javascript" src="js/bootstrap-switch/bootstrap-switch.min.js"></script>
<!---validaion-->
<script src="js/jquery-validate/jquery.validate.min.js"></script>
<!--<script type="text/javascript" src="js/jquery.validate.password.js"></script>-->
        <script id="demo" language="javascript">
$(document).ready(function(){
	$("#frm").validate();
      
});
</script>
	
	
<!-- CUSTOM SCRIPT -->
<script src="js/script.js"></script>
<script>
	jQuery(document).ready(function() {		
		App.setPage("index");  //Set current page
		App.init(); //Initialise plugins and elements
	});
</script>
<!-- /JAVASCRIPTS -->