<div class="row">
<?php
	if(isset($_GET['mod'])) {
?>
		<div class="col-md-6 col-lg-5">
			<div class="box box-primary">
				<div class="box-header with-border">
				  <h3 class="box-title">Dodaj moderatora</h3>
				</div>
				<!-- /.box-header -->
				<!-- form start -->
				<form method="post" id="form_mod" action="agents.php">
				  <div class="box-body">
					<div class="form-group">
					  <label for="">Ime</label><span class="error1" style="color:red;"></span>
					  <input type="text" class="form-control" name="ime" id="ime" placeholder="Unesite ime...">
					</div>
					<div class="form-group">
					  <label for="">Prezime</label><span class="error2" style="color:red;"></span>
					  <input type="text" class="form-control" name="prezime" id="prezime" placeholder="Unesite prezime...">
					</div>
					<div class="form-group">
					  <label for="">Role</label><span class="errorr" style="color:red;"></span>
					  <select class="form-control" name="role" id="role">
						<option value="2">Moderator</option>
						<option value="1">SuperUser</option>
					  </select>
					</div>
					<div class="form-group">
					  <label for="">Username</label><span class="error3" style="color:red;"></span>
					  <input type="text" class="form-control" name="username" id="username" placeholder="Unesite username...">
					</div>
					<div class="form-group">
					  <label for="">Email</label><span class="error4" style="color:red;"></span>
					  <input type="email" class="form-control" name="email" id="email" placeholder="Unesite email...">
					</div>
					<div class="form-group">
					  <label for="">Lozinka</label><span class="error5" style="color:red;"></span>
					  <input type="password" class="form-control" name="password" id="password" placeholder="Unesite lozinku...">
					</div>
					<div class="form-group">
					  <label for="">Potvrdi lozinku</label><span class="error6" style="color:red;"></span>
					  <input type="password" class="form-control" name="password2" id="password2" placeholder="Potvrdite lozinku...">
					</div>
				  </div>
				  <!-- /.box-body -->

				  <div class="box-footer">
					<button type="submit" name="add_m" class="btn btn-block btn-success">Dodaj</button>
				  </div>
				</form>
			</div>
		</div>
<?php
	}
	else {
?>
		<div class="col-md-6 col-lg-5">
			<div class="box box-primary">
				<div class="box-header with-border">
				  <h3 class="box-title">Dodaj radnika</h3>
				</div>
				<!-- /.box-header -->
				<!-- form start -->
				<form method="post" action="view.php?users" id="client-form">
				  <div class="box-body">
					<div class="form-group">
					  <label for="">Ime</label> <span class="error1" style="color:red;"></span>
					  <input type="text" class="form-control" name="ime" id="ime" placeholder="Unesite ime...">
					</div>
					<div class="form-group">
					  <label for="">Prezime</label> <span class="error2" style="color:red;"></span>
					  <input type="text" class="form-control" name="prezime" id="prezime" placeholder="Unesite prezime...">
					</div>
				  </div>
				  <!-- /.box-body -->

				  <div class="box-footer">
					<button type="submit" name="add_c" class="btn btn-block btn-success">Dodaj</button>
				  </div>
				</form>
			</div>
		</div>
	</div>
<?php
	}
?>
<script>
$("#client-form").validate({
	ignore: [],
  rules: {
    // simple rule, converted to {required:true}
    ime: "required",
	prezime: "required",
	telefon: "required",
	kampanje: "required",
	grad: {
	    required: true
	},
	grad_id: "required",
    // compound rule
    email: {
      email: true
    }
  },
    messages: {
		email: {
		required: "Email je obavezan",
		email: "Email nije validan",
		remote: "Ovaj email već postoji!"
		},
		ime: {
		required: "Ime je obavezno"
		},
		prezime: {
		required: "Prezime je obavezno"
		},
		telefon: {
		required: "Telefon je obavezan"
		},
		kampanje: {
		required: "Odaberite kampanju sa liste!"
		},
		grad: {
			required: ""
		},
		grad_id: {
			required: "Odaberite grad sa liste!"
		}
	}
});

$("#form_mod").validate({
  rules: {
    // simple rule, converted to {required:true}
    ime: "required",
	prezime: "required",
	username: {
      required: true,
	  remote: {
        url: "check.php",
        type: "post"
	  }
    },
    // compound rule
    email: {
      required: true,
      email: true,
	  remote: {
        url: "check.php",
        type: "post"
		//complete: function(data){
                //if( data.responseText != "success" ) {
                   //alert(data.responseText);
                   //handle failed validation
                //}
             //}
	  }
    },
	role: "required",
	password: "required",
    password2: {
      equalTo: "#password"
    }
  },
  messages: {
		email: {
		required: "Email je obavezan",
		email: "Email nije validan",
		remote: "Ovaj email već postoji!"
		},
		username: {
		required: "Username je obavezan",
		remote: "Username već postoji!"
		}
	}
});
</script>
