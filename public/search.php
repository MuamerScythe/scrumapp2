<?php
//sleep(5);
require_once('../includes/config.php');
require_once('../classes/user.php');
require_once('../classes/customers.php');
$user = new User($db);
$customer = new Customers($db);
if(!$user->is_logged_in()) {
	header('Location: login.php');
}
else {
	if($_SESSION['role'] == 1 || $_SESSION['role'] == 2)  {
		$del = 1;
	}
	else $del = 0;
}
//----------------------------------------------------------------
if(isset($_REQUEST['members'])) {
	if(isset($_POST['member_search']) && !empty($_POST['member_search'])) {
		$search_string = $_POST['member_search'];
	}
	else {
		die();
	}
	$result = $user->search_members($search_string);
	if(!$result) {
		echo '<tr>
				<td colspan="6" class="text-center">
					<p class="text-red">
						Nema rezultata za ovaj upit!
					</p>
				</td>
			  </tr>';
	}
	else {
		$now = strtotime('-10 minutes');
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
	}
}
else {
	//SET PAGES
	if(isset($_GET['page']) && !empty($_GET['page'])) {
		$page = intval($_GET['page']);
	}
	else {
		$page = 0;
	}
	//--------------------------------------------------------
	if(isset($_REQUEST['client_search']) && !empty($_REQUEST['client_search'])) {
		$search_string = $_REQUEST['client_search'];
	}
	else $search_string = "";
	if(isset($_REQUEST['grad_id']) && !empty($_REQUEST['grad_id'])) {
            $grad = intval($_REQUEST['grad_id']);
        }
    else $grad = 0;
	if(isset($_REQUEST['kampanje']) && !empty($_REQUEST['kampanje'])) {
		$kampanje = $_REQUEST['kampanje'];
		//die(implode(',',$kampanje));
		}
	else {
		$kampanje = 0;
		}
//SORTING-----------------------------------------------------------
	$class=" fa-sort ";
	if(isset($_REQUEST['sort']) && isset($_REQUEST['sort_what'])) {
		$sort = ['sort' => $_REQUEST['sort'], 'sort_what' => $_REQUEST['sort_what']];
		if($_REQUEST['sort'] == "up") {
			$class = " fa-sort-up ";
		}
		else if($_REQUEST['sort'] == "down") {
			$class = " fa-sort-down ";
		}
	}
	else {
		$sort = [];
	}
//------------------------------------------------------------------

	$result = $customer->search_clients($search_string, $page);
	//die(print_r($result));
	//echo $result;die();
	//PRINT RESULTS --------------------------------------------
	if(!$result) {
		echo '<tr>
				<td colspan="6" class="text-center">
					<p class="text-red text-center">
						Nema rezultata za ovaj upit!
					</p>
				</td>
			  </tr>';
	}
	else {
		echo '<table class="table table-hover"><thead>
								<tr class="tbl-head">
								  <th class="hidden-xs">ID</th>
								  <th>Ime <i name="ime" class="fa '.$class.' sort-all" style="margin-left:8px;"></i></th>
								  <th class="hidden-xs">Broj kašnjenja</th>
								  '.(($del == 1) ? '<th></th>' :'' ).'
								</tr>
							</thead>
							<tbody>';
		foreach ( $result as $var ) {
			echo '<tr role="button" name="'.$var['id'].'" class="profile-btn">
			<td class="hidden-xs">'.$var['id'].'</td>
			<td>'.$var['ime'].' '.$var['prezime'].'</td>
			<td class="hidden-xs">'.$var['counter'].'</td>
			'.(($del == 1) ? '<td style="text-align:right"><button class="btn btn-sm btn-danger delete_c" name="'.$var['id'].'"><i class="fa fa-fw fa-trash"></i></button></td>' : '').'
			</tr>';
		}
		echo '</tbody></table>';
	}
}




?>
