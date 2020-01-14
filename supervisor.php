<?php
//include config
require_once('includes/config.php');

//check if already logged in move to home page
if( $user->is_logged_in() ){ header('Location: index.php'); exit(); }

//process login form if submitted
if(isset($_POST['submit'])){

	if (!isset($_POST['username'])) $error[] = "Please fill out all fields";
	if (!isset($_POST['password'])) $error[] = "Please fill out all fields";

	$username = $_POST['username'];
	if ( $user->isValidUsername($username))
	{
		if (!isset($_POST['password']))
		{
			$error[] = 'A password must be entered';
		}
		$password = $_POST['password'];
		if($user->loginSupervisor($username,$password))
		{		$ses = session_id();
				$_SESSION['username'] = $username;
				header('Location: admin.php?id='.$ses);
				exit;
		} 
		else 
		{
			$error[] = 'Account does not have supervisor privileges.';
		}
	}
	else
	{
		$error[] = 'Usernames are required to be Alphanumeric, and between 3-16 characters long';
	}



}//end if submit

//define page title
$title = 'Supervisor Login';

//include header template
require('layout/header.php'); 
?>

<div class="bg-image"></div>
<div class="content">
	<div class="container" style="">
		<div class="rectangle">
			<div style="flex-grow: 100;">
				<div class="row">

					<div class="col-lg-8 col-md-offset-2">
						<form role="form" method="post" action="" autocomplete="off">
							<div class="row">
								<center><h2>Supervisor Login</h2></center>
									<p><a href='http://#.com/employee/index.php' style="color: #EF1F2F"><span class="glyphicon glyphicon-menu-left"></span> Back to login selector</a></p>
							</div>
							<div class="row">
								<hr>
								<div class="input-group">
									<span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
									<input type="text" name="username" id="username" class="form-control input-lg" placeholder="Username" value="<?php if(isset($error)){ echo htmlspecialchars($_POST['username'], ENT_QUOTES); } ?>" tabindex="1">
								</div>

								<div class="input-group"style="padding-top: 20px; padding-bottom: 20px;">
									<span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
									<input type="password" name="password" id="password" class="form-control input-lg" placeholder="Password" tabindex="3">
								</div>
							</div>
				
							<div class="row">
								<hr>
								<div class=""><input type="submit" name="submit" value="Login" class="btn btn-primary" tabindex="5"></div>
							</div>
							<?php
							//check for any errors
							if(isset($error)){
								foreach($error as $error){
									echo '<p class="bg-danger">'.$error.'</p>';
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
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<?php 
//include header template
require('layout/footer.php'); 
?>
