<?php require('includes/config.php'); 
$title = 'Login';
if($user->is_logged_in()){ header('Location: timesheet.php'); exit(); }

//include header template
require('layout/header.php'); 
?>
<div class="bg-image"></div>
<div class="content">
	<div class="container">
		<div class="rectangle">
			<div style="flex-grow: 100;">
				<div class="row">
					<div class="col-lg-8 col-md-offset-2">
						<form role="form" method="post" action="" autocomplete="off">
							<div class="row">
								<center><h2>Select Login Type</h2></center>
									<p><a href='http://#.com' style="color: #EF1F2F"><span class="glyphicon glyphicon-menu-left"></span> Back to home page</a></p>
							</div>
							<div class="row">
								<hr>
								<button type="button" class="btn btn-primary" onclick="window.location.href='http://www.#.com/employee/employee.php'">Employee Login</button>
							</div>
							<div class="row">
								<button type="button" class="btn btn-primary" style="margin-top: 20px;" onclick="window.location.href='http://www.#.com/employee/supervisor.php'">Supervisor Login</button>
							</div>
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