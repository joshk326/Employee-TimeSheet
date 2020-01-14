<?php require('includes/config.php'); 
if(!$user->is_logged_in()){ header('Location: index.php'); exit(); }

    $username = $_SESSION['username'];
    $sql = "SELECT * FROM timesheet WHERE name = '$username'"; 
    $res = $db->query($sql); 

///////////////////////////////////////////////////////////////////////

    $timein = htmlspecialchars_decode($_POST['time_in'], ENT_QUOTES);
    $timeout = htmlspecialchars_decode($_POST['time_out'], ENT_QUOTES);
    $subarray = array(htmlspecialchars_decode("Submitted", ENT_QUOTES), htmlspecialchars_decode("on", ENT_QUOTES), date("M,d,Y"));
    $submitstatus = implode(" ", $subarray);
    $userid = $_SESSION['memberID'];
    $subday = htmlspecialchars($_POST['date_in'], ENT_QUOTES);
    //var_dump(round(1.95583, 2));
    $totalhours = round((strtotime($timeout)-strtotime($timein))/3600, 2);
    $timesubmit = "INSERT INTO timesheet (submit_day, user_id, name, time_in, time_out, submit_status, total_hours) VALUES ('$subday', '$userid', '$username', '$timein', '$timeout', '$submitstatus', '$totalhours')";


    $change_day = htmlspecialchars($_POST['change_date_in'], ENT_QUOTES);
    $change_time_in = htmlspecialchars($_POST['change_time_in'], ENT_QUOTES);
    $change_time_out = htmlspecialchars($_POST['change_time_out'], ENT_QUOTES);
    $requestvalue = htmlspecialchars_decode("Requested", ENT_QUOTES);
    $totalchangedhours = round((strtotime($change_time_out)-strtotime($change_time_in))/3600, 2);
    $changearray = array(htmlspecialchars_decode("Change to:", ENT_QUOTES), htmlspecialchars_decode("Time in:", ENT_QUOTES), $change_time_in, htmlspecialchars_decode("Time out:", ENT_QUOTES), $change_time_out, htmlspecialchars_decode("Total Hours:", ENT_QUOTES), $totalchangedhours);
    $requestcomments = implode(" ", $changearray);
    $requestsubmit = "UPDATE timesheet SET change_request='$requestvalue',  request_comments='$requestcomments' WHERE user_id = '$userid' AND submit_day = '$change_day'";
     $stmt = "SELECT * FROM timesheet WHERE user_id = '$userid' AND submit_day = '$subday'";
     $dateres = $db->query($stmt);
     $daterow = $dateres->fetch();
     $datecheck = $daterow['submit_day'];

///////////////////////////////////////////////////////////////////////
if(isset($_POST['submit']))
{
    if (!isset($_POST['date_in'])) $error[] = "Please fill out all fields";
    if (!isset($_POST['time_in'])) $error[] = "Please fill out all fields";
    if (!isset($_POST['time_out'])) $error[] = "Please fill out all fields";
    if($subday == $datecheck) $error[] = "You have already submitted a time for this day.";

    if(!isset($error))
    {
        try{
            $db->query($timesubmit);
            echo "<meta http-equiv='refresh' content='0'>";
        }
        catch(PDOException $e){
            $error[] = "Unexpected error, contact site administrator";
        }
    }
}

if(isset($_POST['submitrequest']))
{
    if (!isset($_POST['change_date_in'])) $error[] = "Please fill out all fields";
    if (!isset($_POST['change_time_in'])) $error[] = "Please fill out all fields";
    if (!isset($_POST['change_time_out'])) $error[] = "Please fill out all fields";
    if(!isset($error))
    {
        try{
            $db->query($requestsubmit);
            echo "<meta http-equiv='refresh' content='0'>";
        }
        catch(PDOException $e){
            $error[] = $e->getMessage();//"Unexpected error, contact site administrator";
        }
    }
}

//define page title
$title = 'Logged in as: ' . $_SESSION['username'];

//include header template
require('layout/header.php'); 
?>
<body style="background-color: #fff;">
<div class="row">
    <div class="col-xs-12 col-sm-8 col-md-8 col-sm-offset-2 col-md-offset-2">
	<h2>Timesheet Management System, Welcome <?php echo htmlspecialchars($_SESSION['username'], ENT_QUOTES); ?></h2>
	<p><a href='logout.php' style="color: #EF1F2F"><span class="glyphicon glyphicon-log-out"></span> Logout</a></p>
	<hr>
	<div class="panel panel-primary">
        <div class="panel-heading">Timesheet</div>
        <div class="table-responsive">  
                                <table id="editable_table" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Employee ID</th>
                                            <th>Date</th>
                                            <th>Time In</th>
                                            <th>Time Out</th>
                                            <th>Total Hours</th>
                                            <th>Submit Status</th>
                                            <th>Approved Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            while($row = $res->fetch())
                                            {
                                                echo '
                                                <tr>
                                                <td>'.$row["user_id"].'</td>                    
                                                <td>'.$row["submit_day"].'</td>
                                                <td>'.$row["time_in"].'</td>
                                                <td>'.$row["time_out"].'</td>
                                                <td>'.$row["total_hours"].'</td>
                                                <td>'.$row["submit_status"].'</td>
                                                <td style="color: green;">'.$row["approve_status"].'</td>
                                                </tr>
                                                ';
                                             }
                                        ?>
                                    </tbody>
                                </table>
                            </div> 
    </div>
    <div class="panel panel-primary">
        <div class="panel-heading">Enter New Time</div>
        <div class="panel-body">
            <form action="" method="post">
                <label>Date:</label>
                <input type="date" name="date_in" id="date_in" class="form-control" required>
                <label style="padding-top: 20px;">Time In:</label>
                <input type="time" name="time_in" id="time_in" class="form-control" required>
                <label style="padding-top: 20px;">Time Out:</label>
                <input type="time" name="time_out" id="time_out" class="form-control" required>
                <input type="submit" class="btn btn-primary" name="submit" value="Submit" style="margin-top: 20px; margin-bottom: 20px;">
                <?php
                    //check for any errors
                    if(isset($error)){
                        foreach($error as $error){
                            echo '<p class="bg-danger"><span class="glyphicon glyphicon-warning-sign"></span> '.$error.'</p>';
                        }
                    }
                ?>
            </form>
        </div>
    </div>
	</div>


    <div class="col-xs-2 col-sm-2 col-md-4 col-sm-offset-2"><!--col-xs-12 col-sm-8 col-md-8 col-sm-offset-2 col-md-offset-2-->
        <div class="panel panel-primary">
            <div class="panel-heading">Request Time Change</div>
            <div class="panel-body">
                <input type="submit" class="btn btn-primary" name="modalopen" id="modalopen" value="Request Time Change" style="margin-top: 10px; margin-bottom: 10px;">
            </div>
        </div>
        <div id="myModal" class="modal">
          <!-- Modal content -->
          <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Select Date & Time For Change</h2>
            <form action="" method="post">
                <label>Date:</label>
                <input type="date" name="change_date_in" id="change_date_in" class="form-control" required>
                <label style="padding-top: 20px;">Change Time In To:</label>
                <input type="time" name="change_time_in" id="change_time_in" class="form-control" required>
                <label style="padding-top: 20px;">Change Time Out To:</label>
                <input type="time" name="change_time_out" id="change_time_out" class="form-control" required>
                <input type="submit" class="btn btn-primary" name="submitrequest" id="submitrequest" value="Submit Request" style="margin-top: 20px; margin-bottom: 20px;">
            </form>    
          </div>
        </div>
    </div>    
</div>
</body>

<?php 
//include header template
require('layout/footer.php'); 
?>

<script>
var modal = document.getElementById("myModal");
var btn = document.getElementById("modalopen");
var span = document.getElementsByClassName("close")[0];
btn.onclick = function() {
  modal.style.display = "block";
}
span.onclick = function() {
  modal.style.display = "none";
}
window.onclick = function(event) {
  if (event.target == modal) {
    modal.style.display = "none";
  }
}
</script>