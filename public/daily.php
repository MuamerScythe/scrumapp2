<?php
require_once('../includes/config.php');
require_once('../classes/user.php');
require_once('../classes/customers.php');
$user = new User($db);
$customer = new Customers($db);
require_once('../layout/header.php');

if(isset($_REQUEST['del'])) {
	$del_id = intval($_REQUEST['del']);
	$user->del_agent($del_id);
}
?>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Daily Scrum
      </h1>
      <ol class="breadcrumb">
        <li><a href="../public/index.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Dail Scrum</li>
      </ol>
    </section>
    <!-- Main content -->
    <section class="content">
		<div class="row">
		<div class="col-xs-12">
          <div class="box">
            <div class="box-header">
							<div class="row">
								<div class="col-md-6 col-xs-12">
								  <div class="box-tools" style="">
									<form action="" method="post" id="search_members">
									<div class="input-group input-group-sm" style="">
									  <input type="text" name="member_search" class="form-control pull-right" placeholder="Search">
									  <div class="input-group-btn">
										<button type="submit" class="btn btn-default" style="background: #7c288a;color:white;"><i class="fa fa-search"></i></button>
									  </div>
									</div>
									</form>
								  </div>
								</div>
							</div>
            </div>
            <!-- /.box-header -->
            <div class="box-body table-responsive no-padding">
              <table class="table table-hover tbl-member">
			  				<thead>
	                <tr>
	                  <th class="hidden-xs">ID</th>
	                  <th>Ime</th>
	                  <th>Vrijeme dolaska</th>
										<th class="text-center">Na vrijeme</th>
	                </tr>
								</thead>
								<tbody id="members_table">
				          <?php
									if(isset($_REQUEST['page'])) {
										$page=intval($_REQUEST['page']);
									}
									else $page = 0;

									$result = $user->get_scrum($page);
									foreach ( $result as $var ) {
										$format_time = "";
										$on_time = "";
										if($var['vrijeme']) {
											$time = $var['vrijeme'];
											$format_time = date('H:i:s', strtotime($var['vrijeme']));
											if(strtotime(MEETING_TIME) > strtotime($time)) {
												$on_time = "<span class='status-tbl bg-yes'>DA</span>";
											}
											else {
												$on_time = "<span class='status-tbl bg-no'>NE</span>";
											}
										}
										echo '<tr class="view-agent" id="'.$var['id'].'">
										<td class="hidden-xs">'.$var['id'].'</td>
										<td>'.$var['ime'].' '.$var['prezime'].'</td>
										<td>'.($var['vrijeme'] ? $format_time : "<button class='set_arrival btn-ui ui-gradient' id=".$var['id'].">Prijavi</button>").'</td>
										<td class="text-center status-td">'.$on_time.'</td>
										</tr>';
									}
								?>
              </tbody></table>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
		</div>
    </section>
    <!-- /.content -->
  </div>

	<div class="modal fade" id="setModal">
				<div class="modal-dialog" style="margin-top:10%;">
					<div class="modal-content">
				<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span></button>
				<h4 class="modal-title">Prijavi dolazak radnika</h4>
				</div>
				<div class="modal-body">
					<div class="box-body">
						<div class="col-md-6 col-xs-12">
							<input id="timepicker1" type="text" class="form-control input-small">
						</div>
					</div>
			<!-- /.box-body -->
				</div>
				<div class="modal-footer">
				<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Zatvori</button>
				<button type="button" data-dismiss="modal" id="save" class="btn btn-primary">Spremi</button>
				</div>
					</div>
					<!-- /.modal-content -->
				</div>
				<!-- /.modal-dialog -->
			</div>



<?php
	require('../layout/footer.php');
?>
