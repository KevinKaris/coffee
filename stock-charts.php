<?php include('core/dash-header.php');
//<div style="width: 170px; white-space: normal;">
if (isset($_GET['time'])) {
  // code...

    $period = preg_replace('/[^A-Za-z0-9.\-]/', '', ($_GET['time']));
  $status=  preg_replace('/[^A-Za-z0-9.\-]/', '', ($_GET['status']));
  $chart=  preg_replace('/[^A-Za-z0-9.\-]/', '', ($_GET['chart']));


}

 ?>

    <div class="container-fluid py-4">


<style type="text/css">
  .tentra
  {
    display: inline-block;
  }
</style>




      <div class="row">
        <div style="height:550px;"  class="col-lg-12  mb-lg-0 mb-4">
          <div class="card z-index-2 h-100">
            <div class="card-header pb-0 pt-3 bg-transparent">
              <h6 class="text-capitalize">


                  <div class="dropright tentra" >
  <button class="btn bg-gradient-warning btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    Type
  </button>
  <div class="dropdown-menu shadow-sm" aria-labelledby="dropdownMenuButton">
    <a class="dropdown-item" href="?time=<?php echo($period);?>&status=<?php echo($status);?>&chart=pie">Pie Chart</a>
    <a class="dropdown-item" href="?time=<?php echo($period);?>&status=<?php echo($status);?>&chart=doughnut">Dougnought</a>
    <a class="dropdown-item" href="?time=<?php echo($period);?>&status=<?php echo($status);?>&chart=column">Bar Chart</a>
     <a class="dropdown-item" href="?time=<?php echo($period);?>&status=<?php echo($status);?>&chart=funnel">Funnel Chart</a>
      <a class="dropdown-item" href="?time=<?php echo($period);?>&status=<?php echo($status);?>&chart=pyramid">Pyramid</a>
  </div>
</div>

                  <div class="dropright tentra" >
  <button class="btn bg-gradient-info btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    More
  </button>
  <div class="dropdown-menu shadow-sm" aria-labelledby="dropdownMenuButton">
    
    <a class="dropdown-item" href="?time=<?php echo($period);?>&status=available-grades-stocks&chart=<?php echo($chart); ?>">Available Beans Stock By Grade</a>
    <a class="dropdown-item" href="?time=<?php echo($period);?>&status=green-beans-production-vs-usage&chart=<?php echo($chart); ?>">Grean Bean Stock Usage</a>
    <a class="dropdown-item" href="?time=<?php echo($period);?>&status=parchment-purchase-vs-usage&chart=<?php echo($chart); ?>">Parchment Stock Usage</a>
    
    
 
  </div>
</div>

  <div class="dropright tentra" >
  <button class="btn  bg-gradient-secondary text-uppercase btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    Time
  </button>
  <div class="dropdown-menu shadow-sm" aria-labelledby="dropdownMenuButton">
    <a class="dropdown-item" href="?time=10000&status=<?php echo($status);?>&chart=<?php echo($chart); ?>">All Time</a>
    <a class="dropdown-item" href="?time=60&status=<?php echo($status);?>&chart=<?php echo($chart); ?>">Last 5 Years</a>
    <a class="dropdown-item" href="?time=24&status=<?php echo($status);?>&chart=<?php echo($chart); ?>">Last 2 Years</a>
    <a class="dropdown-item" href="?time=12&status=<?php echo($status);?>&chart=<?php echo($chart); ?>">Last 12 Months</a>
    <a class="dropdown-item" href="?time=6&status=<?php echo($status);?>&chart=<?php echo($chart); ?>">Last 6 Months</a>
    <a class="dropdown-item" href="?time=3&status=<?php echo($status);?>&chart=<?php echo($chart); ?>">Last 3 Months</a>
    <a class="dropdown-item" href="?time=1&status=<?php echo($status);?>&chart=<?php echo($chart); ?>">Last 30 Days</a>
  </div>
</div>
                  
                </a>

              </h6>
                <center><small style="color:crimson;">
     <?php echo($status);?> From Last <?php echo($period);?> Months
   </small></center><br>
            </div>
            <div class="card-body p-3">
     <?php
if (($_SESSION['authority']=='superadmin') || (($_SESSION['accessreports']=='1'))) {
  // authorized to operate
  ?>
 


<?php
if (isset($_GET['time'])) {
  // code...


if ($status=='enabled-vs-disabled') {
  // code...

$ct_active = mysqli_query($con,"SELECT * FROM products WHERE status='active' && created_at >= (DATE_SUB(CURDATE(), INTERVAL $period MONTH)) ");
$ct_actve = mysqli_num_rows($ct_active);

$ct_suspended = mysqli_query($con,"SELECT * FROM products WHERE status='suspended' && created_at >= (DATE_SUB(CURDATE(), INTERVAL $period MONTH)) ");
$ct_sus = mysqli_num_rows($ct_suspended);

$total = $ct_actve+$ct_sus;
$active=number_format(($ct_actve/$total)*100,2);

$suspended= number_format(($ct_sus/$total)*100,2);

$dataPoints = array( 
 
  array("label"=>"Active", "symbol" => "Active (".$ct_actve.")","y"=>$active),
  array("label"=>"Suspended", "symbol" => "Suspended (".$ct_sus.")","y"=>$suspended),
 
 
);
 
?>

<!------by Enabled Vs Disabled----->


<script>
window.onload = function() {
 
var chart = new CanvasJS.Chart("chartContainer", {
  theme: "light3",
  animationEnabled: true,

  data: [{
    type: "<?php echo($chart);?>",
    indexLabel: "{symbol} - {y}",
    yValueFormatString: "#,##0.00\"%\"",
    showInLegend: true,
    legendText: "{label} : {y}",
    dataPoints: <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>
  }]
});
chart.render();
 
}
</script>


  <?php
} elseif ($status=='available-grades-stocks') {
  





$request = $domain."core/stock-grades.php?grades-enlist";
 

$ch = curl_init();
curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
curl_setopt($ch,CURLOPT_URL,$request);
$connect = curl_exec($ch);


//echo($connect);
$retro = json_decode($connect);

/*echo "<pre>";
print_r($retro);
echo "</pre>";
*/
$dataPoints=$retro;
?>


<!------by Consumption----->


<script>
window.onload = function() {
 
var chart = new CanvasJS.Chart("chartContainer", {
  theme: "light3",
  animationEnabled: true,

  data: [{
    type: "<?php echo($chart);?>",
    indexLabel: "{symbol} - {y}",
    yValueFormatString: "#,##0\" Kgs\"",
    showInLegend: true,
    legendText: "{label}",
    dataPoints: <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>
  }]
});
chart.render();
 
}
</script>



<?php }

 elseif ($status=='green-beans-production-vs-usage') {
  
$ct_active = mysqli_query($con,"SELECT SUM(quantity) as total_stock FROM green_beans WHERE processing_status='complete' && created_at >= (DATE_SUB(CURDATE(), INTERVAL $period MONTH)) ");
$ct_actve = mysqli_fetch_array($ct_active);

$ct_suspended = mysqli_query($con,"SELECT SUM(remaining) as remaining_stock FROM green_beans WHERE processing_status='complete' && created_at >= (DATE_SUB(CURDATE(), INTERVAL $period MONTH)) ");
$ct_sus = mysqli_fetch_array($ct_suspended);

$avail = number_format(($ct_sus['remaining_stock']),2);
$total_stock = number_format(($ct_actve['total_stock']),2);


$total = $total_stock+$avail;
$active=number_format(($avail/$total)*100,2);

$processed= number_format(($total_stock/$total)*100,2);

$dataPoints = array( 
 
  array("label"=>"Stock processed", "symbol" => "Stock processed (".$total_stock." Kgs)","y"=>$processed),
  array("label"=>"Available Stock", "symbol" => "Available Stock (".$avail." Kgs)","y"=>$active),
 
 
);
?>




<script>
window.onload = function() {
 
var chart = new CanvasJS.Chart("chartContainer", {
  theme: "light3",
  animationEnabled: true,

  data: [{
    type: "<?php echo($chart);?>",
    indexLabel: "{symbol} - {y}",
    yValueFormatString: "#,##0.00\"%\"",
    showInLegend: true,
    legendText: "{label} : {y}",
    dataPoints: <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>
  }]
});
chart.render();
 
}
</script>



<?php }



 elseif ($status=='parchment-purchase-vs-usage') {
  
$ct_active = mysqli_query($con,"SELECT SUM(quantity) as total_stock FROM parchment WHERE purchase_status='active' && created_at >= (DATE_SUB(CURDATE(), INTERVAL $period MONTH)) ");
$ct_actve = mysqli_fetch_array($ct_active);

$ct_suspended = mysqli_query($con,"SELECT SUM(remaining) as remaining_stock FROM parchment WHERE purchase_status='active' && created_at >= (DATE_SUB(CURDATE(), INTERVAL $period MONTH)) ");
$ct_sus = mysqli_fetch_array($ct_suspended);

$avail = number_format(($ct_sus['remaining_stock']),2);
$total_stock = number_format(($ct_actve['total_stock']),2);


$total = $total_stock+$avail;
$active=number_format(($avail/$total)*100,2);

$processed= number_format(($total_stock/$total)*100,2);

$dataPoints = array( 
 
  array("label"=>"Stock Purchased", "symbol" => "Stock Purchased (".$total_stock." Kgs)","y"=>$processed),
  array("label"=>"Available Stock", "symbol" => "Available Stock (".$avail." Kgs)","y"=>$active),
 
 
);
?>




<script>
window.onload = function() {
 
var chart = new CanvasJS.Chart("chartContainer", {
  theme: "light3",
  animationEnabled: true,

  data: [{
    type: "<?php echo($chart);?>",
    indexLabel: "{symbol} - {y}",
    yValueFormatString: "#,##0.00\"%\"",
    showInLegend: true,
    legendText: "{label} : {y}",
    dataPoints: <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>
  }]
});
chart.render();
 
}
</script>

<?php

}


/*
$gt_data = "SELECT * FROM coffee_grades WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH) ORDER BY created_at";

*/
?>

<div id="chartContainer" style="height: 100%; width: 100%;"></div>


<?php } ?>

<?php } else {?>



<center>

<div class="card m-3 border-warning shadow col-md-6" style="height:400px;border:1px solid crimson;cursor: pointer;" onclick="window.open('supplies-report.php?time=6&status=expenditure&chart=doughnut')">
  
<center>
  
  <div style="width:250px; height: 250px; border-radius:100%" class="shadow-sm bg-gradient-danger mt-1">
    <b style="font-size:140px; color: white;">
      !
    </b>
  </div>
  <br>
  <div class="shadow-sm text-white bg-gradient-danger text-capitalize" style="width:60%; border-radius: 12px;">
    <small>Access Denied</small>
  </div>

</center>

</div>
</center>

<?php } ?>

            </div>
          </div>
        </div>
        

      </div>
     

<?php include('core/dash-footer.php'); ?>