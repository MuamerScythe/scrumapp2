<?php
//include config
require_once('../includes/config.php');
require_once('../classes/user.php');
require_once('../classes/phpmailer/mail.php');
require_once('../classes/customers.php');
$user = new User($db);
$customer = new Customers($db);
//check if already logged in move to home page

if( $user->is_logged_in() ){ header('Location: ../public/index.php'); }

//process login form if submitted
if(isset($_POST['submit'])){

	$username = $_POST['username'];
	$password = $_POST['password'];

	if($user->login($username,$password)){
		$_SESSION['username'] = $username;
		header('Location: ../public/index.php');
		exit;

	} else {
		$error[] = 'Pogrešno korisničko ime ili lozinka!';
	}

}//end if submit

//define page title
$title = 'Login';

//include header template
//require('layout/header.php');
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Call Center | Log in</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="../bower_components/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../bower_components/font-awesome/css/font-awesome.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../dist/css/AdminLTE.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="../plugins/iCheck/polaris/polaris.css">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
  <style>
  .login-page, .register-page {
		background: rgba(28,199,229,1);
	  background: -moz-linear-gradient(left, rgba(28,199,229,1) 0%, rgba(29,233,183,1) 100%);
	  background: -webkit-gradient(left top, right top, color-stop(0%, rgba(28,199,229,1)), color-stop(100%, rgba(29,233,183,1)));
	  background: -webkit-linear-gradient(left, rgba(28,199,229,1) 0%, rgba(29,233,183,1) 100%);
	  background: -o-linear-gradient(left, rgba(28,199,229,1) 0%, rgba(29,233,183,1) 100%);
	  background: -ms-linear-gradient(left, rgba(28,199,229,1) 0%, rgba(29,233,183,1) 100%);
	  background: linear-gradient(to right, rgba(28,199,229,1) 0%, rgba(29,233,183,1) 100%);
	  filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#1cc7e5', endColorstr='#1de9b7', GradientType=1 );
  }
	.login-box {
		background-color: rgba(0, 0, 0, 0.15);
	}
  .login-box-body, .register-box-body {
		background: transparent;
		position: relative;
		z-index: 1000;
  }
  #username, #password {
		background: transparent;
		border:1px solid transparent;
		border-bottom:1px solid white;
  	color: white;
  }
  .login-logo a, .register-logo a {
		color:white;
  }
	.form-group {
		margin-bottom:25px;
	}
  .login-box-body .form-control-feedback, .register-box-body .form-control-feedback {
	  color:white;
  }
  .btn-primary {
		color: white;
		background: rgba(137,158,211,1);
		background: -moz-linear-gradient(left, rgba(137,158,211,1) 0%, rgba(160,136,210,1) 100%);
		background: -webkit-gradient(left top, right top, color-stop(0%, rgba(137,158,211,1)), color-stop(100%, rgba(160,136,210,1)));
		background: -webkit-linear-gradient(left, rgba(137,158,211,1) 0%, rgba(160,136,210,1) 100%);
		background: -o-linear-gradient(left, rgba(137,158,211,1) 0%, rgba(160,136,210,1) 100%);
		background: -ms-linear-gradient(left, rgba(137,158,211,1) 0%, rgba(160,136,210,1) 100%);
		background: linear-gradient(to right, rgba(137,158,211,1) 0%, rgba(160,136,210,1) 100%);
		filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#899ed3', endColorstr='#a088d2', GradientType=1 );
		transition: all 0.2s ease;
		padding:12px 30px;
		border: none;
		border:1px solid white;
		border-radius:0.5em;
		letter-spacing: 1px;
		cursor: pointer;
		font-weight: 600;
		padding:12px;
		box-shadow: 0px 5px 20px -3px #565656;
	}
	.btn-primary:active, .btn-primary:focus {
		background: #813290 !important;
		border-color: black !important;
		outline:none !important;
	}
	.login-box, .register-box {
		border: 3px solid white;
		border-radius: 0.5em;
    width: 400px;
		padding:50px 60px;
    margin: 0 auto;
		position:relative;;
		top:10%;
	}
	.login-logo, .register-logo {
		margin-bottom:10px;
	}
	.login-box-msg {
		padding-bottom:40px;
	}
	@media only screen and (max-width: 500px) {
		.login-page, .register-page {
		background-size: cover;
	}
		.login-box, .register-box {
			border:none;
			top:5%;
		}
		.login-box, .register-box {
		width: 360px;
	}
  </style>
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
    <a href="#"><b>Walter</b> Scrum App</a>
  </div>
  <!-- /.login-logo -->
  <div class="login-box-body" style="">
    <p class="login-box-msg" style="color:white;font-size:18px;">Prijava</p>
	<?php
				//check for any errors
				if(isset($error)){
					foreach($error as $error){
						echo '<p class="bg-danger text-center">'.$error.'</p>';
					}
				}

				if(isset($_GET['action'])){

					//check the action
					switch ($_GET['action']) {
						case 'active':
							echo "<h2 class='bg-success'>Your account is now active you may now log in.</h2>";
							break;
						case 'reset':
							echo "<h2 class='bg-success'>Please check your inbox for a reset link.</h2>";
							break;
						case 'resetAccount':
							echo "<h2 class='bg-success'>Password changed, you may now login.</h2>";
							break;
					}

				}


				?>
    <form role="form" method="post" action="" autocomplete="off">
      <div class="form-group has-feedback">
        <input type="text" name="username" id="username" class="form-control input-lg" placeholder="Korisničko ime..." value="<?php if(isset($error)){ echo $_POST['username']; } else echo 'Muamer'; ?>" tabindex="1">
		<span class="glyphicon glyphicon-user form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
        <input type="password" name="password" id="password" class="form-control input-lg" placeholder="Lozinka..." value="habana" tabindex="3">
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
      </div>
      <div class="row">
        <div class="col-xs-8">
          <div class="checkbox icheck">
            <label>
              <input type="checkbox"> <span style="padding-left:5px;color:white;"> Zapamti me</span>
            </label>
          </div>
        </div>
        <!-- /.col -->
        <div class="col-xs-12">
          <input style="margin-top:5px;" type="submit" name="submit" value="Login" class="btn btn-primary btn-block" tabindex="5">
        </div>
        <!-- /.col -->
      </div>
    </form>

    <!--a href="#">I forgot my password</a><br>
    <a href="register.html" class="text-center">Register a new membership</a>-->

  </div>
  <!-- /.login-box-body -->
</div>
<!-- /.login-box -->

<!-- jQuery 3 -->
<script src="../bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- iCheck -->
<script src="../plugins/iCheck/icheck.min.js"></script>
<script>
  $(function () {
    $('input').iCheck({
      checkboxClass: 'icheckbox_polaris',
      radioClass: 'iradio_polaris',
      increaseArea: '20%' // optional
    });
  });
</script>
</body>
</html>
