<?php
require_once('../includes/config.php');
require_once('../classes/user.php');
require_once('../classes/customers.php');
$user = new User($db);
$customer = new Customers($db);
require_once('../layout/header.php');

//require('layout/menu.php');
if($_SESSION['role'] == 1 || $_SESSION['role'] == 2)  {
		$del = 1;
	}
else $del = 0;

if(isset($_REQUEST['del'])) {
	$del_id = intval($_REQUEST['del']);
	$customer->del_customer($del_id);
}

if(isset($_GET['users'])) {
	$heading = "Radnici";
}
if(isset($_POST['add_c'])) {
	$customer->add_customer($_POST);
}
if(isset($_GET['edit_c'])) {
	$customer->update($_POST);
}
?>
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Radnici
      </h1>
      <ol class="breadcrumb">
        <li><a href="../public/index.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Radnici</li>
      </ol>
    </section>
    <!-- Main content -->
    <section class="content">
		<div class="row">
			<div class="col-md-12" id="profile-ajax"></div>
		</div>
		<div class="row">
		<div class="col-xs-12" id="cst-view">
          <div class="box">
            <div class="box-header">
				<div class="row">
					  <div class="col-md-2 col-xs-12" style="margin-bottom:10px;">
					  <button class="btn btn-ui ui-gradient" id="add_c"><i class="fa fa-fw fa-user-plus"></i> Dodaj radnika</button>
					  </div>
					  <form action="" method="post" id="search_clients">
						  <div class="col-md-4 col-xs-12 box-tools" style="">
							<div class="input-group input-group-sm" style="">
							  <input type="text" id="client_search" name="client_search" class="form-control pull-right" placeholder="Pretraga...">
							  <div class="input-group-btn">
								<button type="submit" class="btn btn-default" style="border-color: #6d2279;background: #7c288a;color:white;"><i class="fa fa-search"></i></button>
							  </div>
							</div>
						  </div>
					  </form>
				</div>
            </div>
            <!-- /.box-header -->
            <div class="box-body table-responsive no-padding" id="clients_table">
                <?php
				if(isset($_GET['my_clients'])) {
					if(isset($_GET['page']) && !empty($_GET['page'])) {
						$page = intval($_GET['page']);
						if($_SESSION['role'] == 1 || $_SESSION['role'] == 2) {
								$pass_id = intval($_GET['id']);
						}
						else	$pass_id = $_SESSION['memberID'];
						$result = $user->get_my_clients($pass_id,$page);
					}
					else {
						if($_SESSION['role'] == 1 || $_SESSION['role'] == 2) {
								$pass_id = intval($_GET['id']);
						}
						else	$pass_id = $_SESSION['memberID'];
						$result = $user->get_my_clients($pass_id);
					}
				}
				else {
					if(isset($_GET['page']) && !empty($_GET['page'])) {
						$page = intval($_GET['page']);
						$result = $customer->get_customers($page);
					}
					else {
						$result = $customer->get_customers();
					}
				}
					echo '<table class="table table-hover"><thead>
								<tr class="tbl-head">
								  <th class="hidden-xs">ID</th>
								  <th class="click-sort">Ime i prezime <i name="ime" class="fa fa-sort sort-all"></i></th>
								  <th class="hidden-xs">Broj kašnjenja</th>
									<th></th>
								  '.(($del == 1) ? '<th></th>' :'' ).'
								</tr>
							</thead>
							<tbody>';
					foreach ( $result as $var ) {
						echo '<tr name="'.$var['id'].'">
						<td class="hidden-xs">'.$var['id'].'</td>
						<td>'.$var['ime'].' '.$var['prezime'].'</td>
						<td class="hidden-xs">'.$var['counter'].'</td>
						'.(($del == 1) ? '
						<td class="bts-tbl" style="text-align:right">
						<button class="btn btn-sm btn-danger edit_c" style="padding:2px" name="'.$var['id'].'"><i class="fa fa-fw fa-edit"></i></button>
						</td>
						<td class="bts-tbl" style="text-align:right">
						<button class="btn btn-sm btn-danger delete_c" style="padding:2px" name="'.$var['id'].'"><i class="fa fa-fw fa-trash"></i></button>
						</td>' : '').'
						</tr>';
					}
				echo '</tbody></table>';
				?>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
		</div>
    </section>
    <!-- /.content -->
  </div>
<?php
	require('../layout/footer.php');
?>
