<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/imatic_elSalvador/'.'routes.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/imatic_elSalvador/'.'header.php');
require_once($aRoutes['paths']['config'].'bs_model.php');
require_once('inc/PHPExcel.php');
$oLogin = new BSLogin();
$oLogin->IsLogged("admin");

$form = $_POST;
if(!empty($form['generate_report'])){
    if(!empty($form['rms_from_datetime']) && !empty($form['rms_to_datetime'])){
        $to_datetime = $form['rms_from_datetime'];
        $from_datetime = $form['rms_to_datetime'];
        header("Location: generate_rms_report.php?from_datetime=$from_datetime&to_datetime=$to_datetime");
    }           
}


?>


<link rel="stylesheet" href="<? echo $aRoutes['paths']['css']?>jquery-ui-1.10.3.custom.css">

<div class="container container-body">
    <h2>Generate Rms report</h2>
    <div class="row">
        <div class="checkbox-color box-report">
            <form class="form-inline" name="rms_report_form" action="rms_report.php" id="rms-report-form" method="post" enctype="multipart/form-data">
            	<div class="single-checkbox" id="from_date_div">
            	    <input type="text" class="datetime_input required" id="rms_from_datetime" name="rms_from_datetime" placeholder="From date" value="<?=$filter_form['from_date']?>"/>
            	</div>
            	<div class="single-checkbox" id="to_date_div">
            	    <input type="text" class="datetime_input required" id="rms_to_datetime" name="rms_to_datetime" placeholder="To date" value="<?=$filter_form['to_date']?>"/>
            	</div>
            	<input class="btn btn-primary" type="submit" name="generate_report" id="generate-rms-report" value="Export"> 
            </form>
        </div>
    </div>
</div>

<script type="text/javascript">
    $('#rms_from_datetime').datetimepicker({
    	dateFormat: "yy-mm-dd",
    	timeFormat: 'HH:mm'
    });
    $('#rms_to_datetime').datetimepicker({
    	dateFormat: "yy-mm-dd",
    	timeFormat: 'HH:mm'
    });

    $("#rms_from_datetime, #rms_to_datetime").blur(function(){
        $(this).valid();
    });

  $('#rms-report-form').validate({
        invalidHandler: function(form){
                alert('Red inputs are empty'); // for demo
                return false; // for demo
            },
            highlight: function(element, errorClass, validClass) {
                $(element).addClass(errorClass).removeClass(validClass);
              },
             unhighlight: function(element, errorClass, validClass) {
                $(element).removeClass(errorClass).addClass(validClass);
              },
              errorPlacement: function(error, element) {      
            }
  });
</script>