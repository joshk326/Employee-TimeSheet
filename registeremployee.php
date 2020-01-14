<?php require('includes/config.php');

//if logged in redirect to members page
if(!$user->is_admin_logged_in() ){ header('Location: index.php'); exit(); }

//if form has been submitted process it
if(isset($_POST['submit'])){

    if (!isset($_POST['username'])) $error[] = "Please fill out all fields";
    if (!isset($_POST['email'])) $error[] = "Please fill out all fields";
    if (!isset($_POST['password'])) $error[] = "Please fill out all fields";
    if (!isset($_POST['memberID'])) $error[] = "Please fill out all fields";

	$username = $_POST['username'];
	$memberID = $_POST['memberID'];
	$pass = $_POST['password'];

	//very basic validation
	if(!$user->isValidUsername($username)){
		$error[] = 'Usernames must be at least 3 Alphanumeric characters';
	} else {
		$stmt = $db->prepare('SELECT username FROM members WHERE username = :username');
		$stmt->execute(array(':username' => $username));
		$row = $stmt->fetch(PDO::FETCH_ASSOC);

		if(!empty($row['username'])){
			$error[] = 'Username provided is already in use.';
		}

	}

	if(strlen($pass) < 3){
		$error[] = 'Password is too short.';
	}

	if(strlen($_POST['passwordConfirm']) < 3){
		$error[] = 'Confirm password is too short.';
	}

	if($pass!= $_POST['passwordConfirm']){
		$error[] = 'Passwords do not match.';
	}

	//email validation
	$email = htmlspecialchars_decode($_POST['email'], ENT_QUOTES);
	if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
	    $error[] = 'Please enter a valid email address';
	} else {
		$stmt = $db->prepare('SELECT email FROM members WHERE email = :email');
		$stmt->execute(array(':email' => $email));
		$row = $stmt->fetch(PDO::FETCH_ASSOC);

		if(!empty($row['email'])){
			$error[] = 'Email provided is already in use.';
		}

	}

	if(!$user->isValidMemberID($memberID)){
		$error[] = 'Employee ID must be at least 3 Alphanumeric characters';
	} else {
		$stmt = $db->prepare('SELECT memberID FROM members WHERE memberID = :memberID');
		$stmt->execute(array(':memberID' => $memberID));
		$row = $stmt->fetch(PDO::FETCH_ASSOC);

		if(!empty($row['memberID'])){
			$error[] = 'Employee ID provided is already in use.';
		}

	}


	//if no errors have been created carry on
	if(!isset($error)){

		//hash the password
		$hashedpassword = $user->password_hash($pass, PASSWORD_BCRYPT);

		//create the activasion code
		$activasion = "Yes";

		try {

			//insert into database with a prepared statement
			$stmt = $db->prepare('INSERT INTO members (memberID,username,password,email,active) VALUES (:memberID, :username, :password, :email, :active)');
			$stmt->execute(array(
				':memberID' => $memberID,
				':username' => $username,
				':password' => $hashedpassword,
				':email' => $email,
				':active' => $activasion
			));
			$id = $db->lastInsertId('memberID');

			//send email
			$to = $_POST['email'];
			$subject = "Registration Confirmation";
			$body = "<p>Thank you for registering an employee acccount.</p>
			<p><b>Account Username:</b> $username</p>
			<p><b>Account Passowrd:</b> $pass</p>
			<p>You may now begin using our time managemnet system at http://#.com/employee/employee.php/</p>
			<p>Regards Site Admin</p>";


			$mail = new Mail();
			$mail->setFrom(SITEEMAIL);
			$mail->addAddress($to);
			$mail->subject($subject);
			$mail->body($body);
			$mail->send();

			//redirect to index page
			header('Location: registeremployee.php?action=joined');
			exit;

		//else catch the exception and show the error.
		} catch(PDOException $e) {
		    $error[] = $e->getMessage();
		}

	}

}

//define page title
$title = 'Register New Employee';

//include header template
require('layout/header.php');
?>
<body style="background-color: #fff;">
<div class="row">

    <div class="col-xs-12 col-sm-8 col-md-6 col-sm-offset-2 col-md-offset-3">
		<form role="form" method="post" action="" autocomplete="off">
			<h2>Register New Employee</h2>
			<p><a href='admin.php'><span class="glyphicon glyphicon-menu-left"></span> Go Back to Supervisor Panel</a></p>
			<hr>

			<?php
			//check for any errors
			if(isset($error)){
				foreach($error as $error){
					echo '<p class="bg-danger">'.$error.'</p>';
				}
			}

			//if action is joined show sucess
			if(isset($_GET['action']) && $_GET['action'] == 'joined'){
				echo "<h2 class='bg-success'>Registration successful, employee may now login into their account</h2>";
			}
			?>

			<div class="form-group">
				<input type="text" name="username" id="username" class="form-control input-lg" placeholder="User Name" value="<?php if(isset($error)){ echo htmlspecialchars($_POST['username'], ENT_QUOTES); } ?>" tabindex="1">
			</div>
			<div class="form-group">
				<input type="email" name="email" id="email" class="form-control input-lg" placeholder="Email Address" value="<?php if(isset($error)){ echo htmlspecialchars($_POST['email'], ENT_QUOTES); } ?>" tabindex="2">
			</div>
			<div class="row">
				<div class="col-xs-6 col-sm-6 col-md-6">
					<div class="form-group">
						<input type="password" name="password" id="password" class="form-control input-lg" placeholder="Password" tabindex="3">
					</div>
				</div>
				<div class="col-xs-6 col-sm-6 col-md-6">
					<div class="form-group">
						<input type="password" name="passwordConfirm" id="passwordConfirm" class="form-control input-lg" placeholder="Confirm Password" tabindex="4">
					</div>
				</div>
			</div>
			<div class="form-group">
				<input type="text" name="memberID" id="memberID" class="form-control input-lg" placeholder="Employee ID (For Superviosr Only)" value="<?php if(isset($error)){ echo htmlspecialchars($_POST['memberID'], ENT_QUOTES); } ?>" tabindex="5">
			</div>
			<div class="row">
				<div class="col-xs-6 col-md-6"><input type="submit" name="submit" value="Register" class="btn btn-primary btn-block btn-lg"></div>
			</div>
		</form>
	</div>
</div>
</body>

<?php
//include header template
require('layout/footer.php');
?>
