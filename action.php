<?php require('includes/config.php'); 

if(!$user->is_admin_logged_in()){ header('Location: index.php'); exit(); }

$checkvar;
if (isset($_GET['id'])) {
  

  try {
    $id = $_GET['id'];
    $requestedstatus = "SELECT change_request FROM timesheet WHERE id = :id";
    $results = $db->prepare($requestedstatus);
    $results->bindValue(':id', $id);
    $results->execute();
    $requestedresult = $results->fetch(PDO::FETCH_ASSOC);
    foreach ($requestedresult as $thatshitsrequested)
    if(!$user->isChangeRequested($thatshitsrequested)){
      $isrequested = "SELECT * FROM timesheet WHERE id = :id";
      $statement = $db->prepare($isrequested);
      $statement->bindValue(':id', $id);
      $statement->execute();
      $edited = $statement->fetch(PDO::FETCH_ASSOC);
      $checkvar = true; 
    } 
    else {
      $notrequested = "SELECT approve_status FROM timesheet WHERE id = :id";
      $statement = $db->prepare($notrequested);//submit_day, time_in, time_out, approve_status
      $statement->bindValue(':id', $id);
      $statement->execute();
      $edited = $statement->fetch(PDO::FETCH_ASSOC);
      $checkvar = false;
    }

  } catch(PDOException $error) {
      echo  $error->getMessage();
  }


} 
else {
    echo "Something went wrong!";
    exit;
}

if (isset($_POST['submit'])) {
  try {
    if($checkvar == true){
        $edited =[
        "approve_status" => $_POST['approve_status'],
        "id"        => $_POST['id'],
        "submit_day" => $_POST['submit_day'],
        "time_in"  => $_POST['time_in'],
        "time_out"     => $_POST['time_out'],
        "change_request"  => $_POST['change_request'],
        "request_comments" => $_POST['request_comments'],
        "total_hours" => $_POST['total_hours']
      ];

      $sql = "UPDATE timesheet
              SET approve_status = :approve_status,
                submit_day = :submit_day,
                time_in = :time_in,
                time_out = :time_out,
                change_request = :change_request,
                request_comments = :request_comments,
                total_hours = :total_hours
              WHERE id = :id";
        $statement = $db->prepare($sql);
        $statement->execute($edited);
      }
    else if ($checkvar == false) {
      $id = $_GET['id'];
      $approve_status = ucfirst($_POST['approve_status']);
      $approvesubmit = "UPDATE timesheet SET approve_status='$approve_status' WHERE id = '$id'";
      $db->query($approvesubmit);
    }
  header("Location: http://www.#.com/employee/admin.php");
  } catch(PDOException $error) {
      echo $error->getMessage();
  }
}

?>

<?php require('layout/header.php');  ?>
<body style="background-color: #fff;">
<div class="row">
  <div id="words">
     <br>
  </div>
    <div class="col-xs-12 col-sm-8 col-md-6 col-sm-offset-2 col-md-offset-3">
      <p><a href='admin.php' style="color: #EF1F2F"><span class="glyphicon glyphicon-menu-left"></span> Go Back</a></p>
		<div class="panel panel-primary">
			<div class="panel-heading">Edit Entry</div>
			<div class="panel-body">
        <?php
                  //check for any errors
                  if(isset($error)){
                      foreach($error as $error){
                          echo '<p class="bg-danger"><span class="glyphicon glyphicon-warning-sign"></span> '.$error.'</p>';
                      }
                  }
              ?>
				<form method="post" name="editform">
				    <?php foreach ($edited as $key => $value) : ?>
				      <label style="padding-top: 10px;" for="<?php echo $key; ?>"><?php echo ucwords(str_replace("_"," ", $key)); ?></label>

				      <input style="padding-top: : 10px;"  type="text" class="form-control" name="<?php echo $key; ?>" id="<?php echo $key; ?>" value="<?php echo $value; ?>">
				    <?php endforeach; ?>

				    <input style="margin-top:20px;" class="btn btn-primary" type="submit" name="submit" value="Update Entry">
				</form>
			</div>
		</div>
	</div>
</div>
</body>

<?php require('layout/footer.php');  ?>