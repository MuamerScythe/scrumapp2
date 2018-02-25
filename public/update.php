<?php
require '../includes/config.php';
require_once('../classes/user.php');
require_once('../classes/customers.php');
$user = new User($db);
$customer = new Customers($db);
if(isset($_GET['id'])) {
	$id = intval($_GET['id']);
}
$results = $customer->get_customer($id);

?>

<div class="row">
	<div class="col-md-6 col-lg-5">
		<div class="box box-primary">
				<div class="box-header with-border">
				  <h3 class="box-title">Postavke</h3>
				</div>
			<form id="update-form" action="view.php?edit_c=1" method="POST">
				<div class="box-body">
					<div class="form-group">
						<label for="">Ime</label>
						<input type="hidden" id="cust-id" name="update" value="<?= $id ?>">
						<input type="text" class="form-control" name="ime" id="ime" placeholder="Unesite ime..." value="<?= $results['ime'] ?>">
					</div>
					<div class="form-group">
						<label for="">Prezime</label>
						<input type="text" class="form-control" name="prezime" id="prezime" placeholder="Unesite prezime..." value="<?= $results['prezime'] ?>">
					</div>
					<div class="form-group">
						<label for="">Broj kašnjenja</label>
						<input type="text" class="form-control" name="counter" id="counter" placeholder="Unesite prezime..." value="<?= $results['counter'] ?>">
					</div>
				</div>
				<div class="box-footer">
					<button type="submit" name="update-c" class="btn btn-block btn-success">Izmjeni</button>
				</div>
			</form>
		</div>
	</div>
</div>
