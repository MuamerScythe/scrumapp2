<?php
$path = '../';
include($path.'includes/config.php');
include($path.'classes/user.php');
include($path.'classes/analytics.php');
$user = new User($db);
//$customer = new Customers($db);
//$anal = new Analytics($db);
require($path.'layout/header.php');

//define page title
$title = 'Demo';

//include header template
//require('layout/header.php');
//require('layout/menu.php');
?>
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Dashboard
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Dashboard</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-md-4">
          <div class="comp" style="height:400px;width:330px">
          <div class="comp-5"><div class="chartjs-size-monitor" style="position: absolute; left: 0px; top: 0px; right: 0px; bottom: 0px; overflow: hidden; pointer-events: none; visibility: hidden; z-index: -1;"><div class="chartjs-size-monitor-expand" style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;"><div style="position:absolute;width:1000000px;height:1000000px;left:0;top:0"></div></div><div class="chartjs-size-monitor-shrink" style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;"><div style="position:absolute;width:200%;height:200%;left:0; top:0"></div></div></div>
            <div class="header">
              <div class="title">
                Danas
              </div>
            </div>
            <canvas id="myChart-3" width="350" height="250" style="display: block;" class="chartjs-render-monitor"></canvas>
            <div id="chart-legends"></div>
          </div>
        </div>
      </div>
      <div class="col-md-5">
        <div class="comp">
    <div class="comp-4">
      <div class="header">
        <div class="title">
          Statistika kašnjenja
        </div>
        <div class="navigation">
          <div class="ellips">
            <i class="fa fa-ellipsis-v" aria-hidden="false"></i>
          </div>
        </div>
        <div id="reports">
        </div>
        <div class="menu-drop">
          <div>Menu</div>
          <div>List Item 1</div>
          <div>List Item 2</div>
        </div>
      </div>
      <canvas id="myChart-2" height="170" style="width:calc(100% + 50px);margin-left:-30px;"></canvas>
    </div>
</div>
      </div>
    </div>
    </section>
    <!-- /.content -->
  </div>
  <?php
    $anal = new Analytics($db);
  	$result = $anal->today_activity();
    $week = $anal->week_late();
  	//$result_clients = $anal->client_stats();
  ?>
  <script>
    var data1   = <?php print_r($result); ?>;
    var data2   = <?php print_r($week); ?>;
    var data = data1[0];
    var week = data2;
    //console.log(week);
  </script>
<?php
//include header template
require($path.'layout/footer.php');
?>
<script>
  drawMe(data);
  draw_week(week);
</script>
