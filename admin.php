<?php require('includes/config.php');  
 	$sql2 = "SELECT * FROM timesheet"; 
	$res = $db->query($sql2); 
	$sqlemployees = "SELECT * FROM members";
	$employeeresults = $db->query($sqlemployees);
	function sendemail(){
		while($emailrow = $employeeresults->fetch())
		{
		    $addresses[] = $emailrow['email'];
		}
		$to = implode(", ", $addresses);
		$subject = 'the subject';
		$message = 'hello';
		mail($to, "Your Subject", "A message set by you.", "If header information.");
	}
//if not logged in redirect to login page
if(!$user->is_admin_logged_in()){ header('Location: index.php'); exit(); }

//define page title
$title = 'Supervisor Page';
require('layout/header.php'); 
?>
<body style="background-color: #fff;">
<div class="row">
	<div id="words">
	</div>
    <div class="sidestuff">
		
		<h2>Supervisor Control Panel - Welcome <?php echo htmlspecialchars($_SESSION['username'], ENT_QUOTES); ?></h2>
		<p><a href='logout.php' style="color: #EF1F2F"><span class="glyphicon glyphicon-log-out"></span> Logout</a></p>
		<hr>
		  <ul class="nav nav-tabs" style="padding-top: 20px">
		    <li class="active"><a data-toggle="tab" href="#home"style="color: #EF1F2F"><span class="glyphicon glyphicon-dashboard"></span> Dashboard</a></li>
		    <li><a data-toggle="tab" href="#employees" style="color: #EF1F2F"><span class="glyphicon glyphicon-user"></span> Employee Management</a></li>
		    <li><a data-toggle="tab" href="#tools" style="color: #EF1F2F"><span class="glyphicon glyphicon-wrench"></span> Tools</a></li>
		  </ul>

		  <div class="tab-content">
			    <div id="home" class="tab-pane fade in active">
			      <h3>View Employees Entered Times</h3>
			      <div id="toolbar" style="padding-bottom: 20px;">
					  <div class="form-inline" role="form">
					    <div class="form-group has-feedback">
					    	<i class="glyphicon glyphicon-search form-control-feedback"></i>
					      <input name="search" id="searchinput" class="form-control" onkeyup="searchFunction()" type="text" placeholder="Search By Name or Date">
					    </div>
					    <div class="form-group">
					    	<button class="btn btn-primary" style="height: 40px;" onclick="printData()"><span class="glyphicon glyphicon-save-file"></span> Print/Export to PDF</button>
					    </div>
					    <div class="form-group" style="float: right;">
					    	<button class="btn btn-primary" style="height: 40px;" onclick="sendphpmail()"><span class="glyphicon glyphicon-comment"></span> Alert Employees to Submit Time</button>
					    </div>
					  </div>
					</div>
					<div class="panel panel-primary">
    					<div class="panel-heading">All Employee Timesheets</div>
			            <div class="table-responsive">  
							<table id="editable_table" class="table table-bordered table-striped" data-editable="true" data-buttons-toolbar=".toolbar" data-search="true"data-show-refresh="true" >
								<thead>
									<tr>
										<th>Employee ID</th>
										<th>Username</th>
										<th>Date</th>
										<th>Time In</th>
										<th>Time Out</th>
										<th>Total Hours</th>
										<th>Submit Status</th>
										<th>Approved Status</th>
										<th>Time Request</th>
										<th>Edit</th>
									</tr>
								</thead>
								<tbody>
									<?php
										while($row = $res->fetch())
										{
										    echo '
										    <tr>
										    <td>'.$row["user_id"].'</td>				    
										    <td>'.$row["name"].'</td>
										    <td>'.$row["submit_day"].'</td>
										    <td>'.$row["time_in"].'</td>
										    <td>'.$row["time_out"].'</td>
										    <td>'.$row["total_hours"].'</td>
										    <td>'.$row["submit_status"].'</td>
										    <td style="color: green;">'.$row["approve_status"].'</td>
										    <td>'.$row["change_request"].'</td>
										    <td><center><a id="editbtn" href="action.php?id='.$row["id"].'"><span class="glyphicon glyphicon-edit"></span></a></td>
										    </tr>

										    ';
										 }
									?>
								</tbody>
							</table>
						</div>
					</div>   
			    </div>
			     <div id="employees" class="tab-pane fade">
			      <h3>Employee Management</h3>
			      <button type="button" class="btn btn-primary" style="margin-top: 20px; margin-bottom: 20px;" onclick="window.location.replace('http://www.#.com/employee/registeremployee.php')">Add new employee</button>
			      		<div class="panel panel-primary">
	    					<div class="panel-heading">Current List of Employees</div>
				            <div class="table-responsive">  
								<table id="employeetable" class="table table-bordered table-striped" data-editable="true" data-buttons-toolbar=".toolbar" data-search="true"data-show-refresh="true" >
									<thead>
										<tr>
											<th>Employee ID</th>
											<th>Username</th>
											<th>Email</th>
											<th>Admin</th>
											<th>Remove</th>
										</tr>
									</thead>
									<tbody>
										<?php
											while($row = $employeeresults->fetch())
											{
											    echo '
											    <tr>
											    <td>'.$row["memberID"].'</td>				    
											    <td>'.$row["username"].'</td>
											    <td>'.$row["email"].'</td>
											    <td>'.$row["admin"].'</td>
											    <td><center><a id="deletebtn" onclick="ask('.$row["id"].')"><span class="glyphicon glyphicon-remove"></span></a></td>
											    </tr>
											    ';
											 }
										?>
									</tbody>
								</table>
							</div> 
					</div>
			    </div>
			    <div id="tools" class="tab-pane fade">
			      <h3>Tools</h3> 
			      	<div class="col-xs-1 col-sm-1 col-md-4">
				        <div class="panel panel-primary">
				            <div class="panel-heading">Decimal To Hours and Minutes Calculator</div>
				            <div class="panel-body">
				            	<div class="form-group">
				            		<label style="padding-top: 20px;">Decimal Hours:</label>
					                <input type="text" name="inputDecimalHours" id="inputDecimalHours" class="form-control" required>
				            	</div>
				            	<div class="form-group">
				            		<input type="button" class="btn btn-primary" name="convert" id="convert" value="Convert" style="margin-top: 20px; margin-bottom: 20px;" onclick="calculateHoursMinutes()">
				            	</div>
				            	<div class="row">
									<div class="col-xs-6 col-sm-6 col-md-6">
										<div class="form-group">
											<label style="">Hours:</label>
										    <input type="text" name="inputHours" id="inputHours" class="form-control">
										</div>
									</div>
									<div class="col-xs-6 col-sm-6 col-md-6">
										<div class="form-group">
										 <label style="">Minutes:</label>
										 <input type="text" name="inputMinutes" id="inputMinutes" class="form-control">
										</div>
									</div>
								</div>
				            </div>
				        </div>
				    </div>    
			    </div>
		  </div>
	</div>
</div>
</body>
<script type="text/javascript">
	 function printData()
	{
	   var divToPrint=document.getElementById("editable_table");
	   newWin= window.open("");
	   newWin.document.write(divToPrint.outerHTML);
	   newWin.print();
	   newWin.close();
	}
</script>		
 <script>
	function searchFunction() {
		var filter = event.target.value.toUpperCase();
	    var rows = document.querySelector("#editable_table tbody").rows;
	    
	    for (var i = 0; i < rows.length; i++) {
	        var firstCol = rows[i].cells[2].textContent.toUpperCase();
	        var secondCol = rows[i].cells[3].textContent.toUpperCase();
	        if (firstCol.indexOf(filter) > -1 || secondCol.indexOf(filter) > -1) {
	            rows[i].style.display = "";
	        } else {
	            rows[i].style.display = "none";
	        }      
	    }

		document.querySelector('#searchinput').addEventListener('keyup', false);
	}
</script>
<script>
	function calculateHoursMinutes() {
	    var decimalHours = parseFloat($("#inputDecimalHours").val());
	    if (isNaN(decimalHours)) {
	        $("#inputHours").val('');
	        $("#inputMinutes").val('');
	        return
	    }

	    var hrs = parseInt(Number(decimalHours));
	    var min = (Number(decimalHours) - hrs) * 60;
	    min = Math.round(min);
	    $("#inputHours").val(hrs);
	    $("#inputMinutes").val(min);
    }
</script>
<script>
	function ask(rowindex){
		if(confirm("Are you sure you want to delete this user?"))
	    {
	        //alert(e);
	        window.location.replace("deleteuser.php?id="+ rowindex);
	    }
	    else
	    {
	        //e.preventDefault();
	    }
	}
</script>
<script>
	function sendphpmail() {
		/*<?php sendemail();?>*/
		alert("Currently not available");
	}
</script>