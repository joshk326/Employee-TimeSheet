<?php require('includes/config.php');
if(!$user->is_admin_logged_in()){ header('Location: index.php'); exit(); }


if (isset($_GET["id"])) {

	$id = $_GET['id'];
    $requestedstatus = "SELECT admin FROM members WHERE id = :id";
    $results = $db->prepare($requestedstatus);
    $results->bindValue(':id', $id);
    $results->execute();
    $requestedresult = $results->fetch(PDO::FETCH_ASSOC);
    foreach ($requestedresult as $thatshitsrequested)
	if($user->userContainsAdmin($thatshitsrequested)){
	  try {
	    $sql = "DELETE FROM members WHERE id = :id";//:id

	   $statement = $db->prepare($sql);
	    $statement->bindValue(':id', $id);
	    $statement->execute(); //bg-success
	    $success = "User successfully deleted";
	    echo '<p class="bg-success" style="font-size:50px; margin-top:50px;"><span class="glyphicon glyphicon-ok"></span>'.$success.'</p>';;
	  } catch(PDOException $error) {
	    echo $sql . "<br>" . $error->getMessage();
	  }
	}
	else
	{
		$error = " Admin accounts cannot be deleted";
		echo'<p class="bg-danger" style="font-size:50px; margin-top:50px;"><span class="glyphicon glyphicon-warning-sign"></span>'.$error.'</p>';
	}
}
require('layout/header.php'); 
?>
<p><a href='admin.php' style="color: #EF1F2F; margin-left: 40%; margin-top: 20px; font-size: 50px;"><span class="glyphicon glyphicon-menu-left"></span> Go Back</a></p>
<?php require('layout/footer.php');?>