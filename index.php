<!DOCTYPE html>
<html>
	<head>
		<link rel="stylesheet" type="text/css" href="css/style.css">
		<link rel="stylesheet" type="text/css" href="font-awesome-4.7.0/css/font-awesome.min.css">
	</head>
	<body >
		
	<?php

	// muning para maka connect si php sa database
	// then macreate ang database, then ang table

		$dbhost = 'localhost';
		$dbuser = 'root';
		$dbpass = '';
		$dbname = 'events_shed_db';
		$conn = mysqli_connect($dbhost, $dbuser, $dbpass);

		$query = "CREATE DATABASE IF NOT EXISTS $dbname";

		if(mysqli_query($conn, $query)){
			$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

			// trial query lang ni, bisan unsa pa imong iselect dira na column
			// basta from "tbl_events_sched" na table jud
			$query = "SELECT ID FROM tbl_events_sched"; 
			// try ug check if ga exist ba ang table
			$result = mysqli_query($conn, $query);

			// if ang result is empty, meaning wala pay table na nacreate, so icreate siya
			if(empty($result)) {
				$query = "CREATE TABLE tbl_events_sched (
						id INT(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
						event_name VARCHAR(50) NOT NULL,
						event_date VARCHAR(20) NOT NULL,
						event_time VARCHAR(20) NOT NULL,
						location VARCHAR(50),
						notes VARCHAR(100)
						)"; 
				// kani siya ang maexecute mysqli_query($conn, $query) which creates the table						
				if (mysqli_query($conn, $query)) {

				} else {
					echo "Error creating table: " . mysqli_error($conn);
				}
			}

	    } // end sa if query to check if nahimo ba ang create database
	    else {
			echo("<script>alert('Oops Error Not Connected!')</script>");
			die('Cannot connect to database. Check user and password' . mysqli_error());
	     }

	     // ***************************************
	     // part ning isset mag add nag event sa database
	     // ***************************************
		if ( isset($_POST['add-event']) ) {
			$event_name = $_POST["event_name"]; // kaning sulod sa [bracket], mao nang name="" sa input field
			$event_name = mysql_real_escape_string($event_name);

			$month = $_POST["month"];
			$month = mysql_real_escape_string($month);

			$date = $_POST["date"];
			$date = mysql_real_escape_string($date);

			$year = $_POST["year"];
			$year = mysql_real_escape_string($year);

			$event_date = $month . ' ' . $date . ', '. $year;


			$hour = $_POST["hour"];
			$hour = mysql_real_escape_string($hour);

			$minute = $_POST["minute"];
			$minute = mysql_real_escape_string($minute);

			$time = $_POST["time"];
			$time = mysql_real_escape_string($time);

			$event_time = $hour . ':' . $minute . ' '. $time;



			$location = $_POST["location"];	
			$location = mysql_real_escape_string($location);

			$notes =  $_POST["notes"]; 
			$notes = mysql_real_escape_string($notes);


			$the_query = "INSERT INTO tbl_events_sched (event_name, location, event_date, event_time, notes) VALUES(
							'".$event_name."', 
							'".$location."',
							'".$event_date."', 
							'".$event_time."',  
							'".$notes."') ";		// maond daghan char2x dot, single double quote kai para sure 
													// na kung mag insert ug special characs, dili magerror
			mysqli_query( $conn, $the_query); 	

			if(mysqli_affected_rows($conn) > 0 ) {
				echo("<script>alert('SUCCESSFULLY ADDED!')</script>");
			} 
			else {
				echo("<script>alert('Ooops Error Detected. Not added')</script>");
				echo mysqli_error($conn);
			}		

		} // end sa isset

	     // ***************************************
	     // DELETE EVENT FROM DATABASE
	     // ***************************************
		if ( isset($_POST['delete-event']) ) {
				$the_id = $_POST["event_id"];
				$the_id = mysql_real_escape_string($the_id);

				$event_name = $_POST["event_name"]; 
				$event_name = mysql_real_escape_string($event_name);


				if( empty($the_id) ) {
					echo "<script>alert('Nothing to Delete. No Event Selected');</script>";
				}
				else {
					$query = "DELETE FROM tbl_events_sched WHERE id=$the_id";
					if (mysqli_query($conn, $query)) {
					     echo "<script>alert('$event_name was SUCCESSFULLY DELETED');</script>";
					} else {
					    echo "Error deleting record: " . mysqli_error($conn);
					}
				}
			} // end sa DELETE


	     // ***************************************
	     // UPDATE EVENT FROM DATABASE
	     // ***************************************
		if ( isset($_POST['update-event']) ) {
				$the_id = $_POST["event_id"];
				$the_id = mysql_real_escape_string($the_id);

				$event_name = $_POST["event_name"]; // kaning sulod sa [bracket], mao nang name="" sa input field
				$event_name = mysql_real_escape_string($event_name);



				$month = $_POST["month"];
				$month = mysql_real_escape_string($month);

				$date = $_POST["date"];
				$date = mysql_real_escape_string($date);

				$year = $_POST["year"];
				$year = mysql_real_escape_string($year);

				$event_date = $month . ', ' . $date . ' '. $year;


				$hour = $_POST["hour"];
				$hour = mysql_real_escape_string($hour);

				$minute = $_POST["minute"];
				$minute = mysql_real_escape_string($minute);

				$time = $_POST["time"];
				$time = mysql_real_escape_string($time);

				$event_time = $hour . ':' . $minute . ' '. $time;


				$location = $_POST["location"];	
				$location = mysql_real_escape_string($location);

				$notes =  $_POST["notes"]; 
				$notes = mysql_real_escape_string($notes);
				$check = True;

				if( empty($the_id) ) {
					$check = False;
					echo "<script>alert('Nothing was UPDATED. You did not select an event to edit.');</script>";
				} else {
					$query = "UPDATE tbl_events_sched 
							SET event_name = '$event_name',
								event_date = '$event_date',
								event_time = '$event_time',
								location = '$location',
								notes = '$notes '
							WHERE id=$the_id";

					if (mysqli_query($conn, $query)) {
					     echo "<script>alert('The event $event_name was SUCCESSFULLY UPDATED');</script>";
					} else {
					 echo "Error Updating record: " . mysqli_error($conn);
					}
				}
		} // end of update

	?>
		<!-- KANI MUGAWAS ANG HTML JUD NA DISPLAY -->

		<header class="">
			<section class="logo"><img src="images/jaj.png" id="logo"> </section>
		</header>
		<main class="wrapper">			
			<div class="form-container margin-auto col-4">
				<form class="add-event col-12  margin-auto " method="post" action="">
					<div class="inputs margin-auto col-11 ">
						<input type="hidden" name="event_id" id="event_id" placeholder="Event ID - Auto Generated" readonly>
						<label>Event:</label><br>
						<input class="" type="text" name="event_name"  id="event_name" placeholder="Event Name" required>
						
						<label>Date: </label><br>
						<select name="month" id="input-month">
							<option value="">Month</option>
							<option value="January">January</option>
							<option value="Febuary">Febuary</option>
							<option value="March">March</option>
							<option value="April">April</option>
							<option value="May">May</option>
							<option value="June">June</option>
							<option value="July">July</option>
							<option value="August">August</option>
							<option value="September">September</option>
							<option value="October">October</option>
							<option value="November">November</option>
							<option value="December">December</option>
						</select>

						<input type="text" name="date" placeholder="Date" id="input-date">
						<input type="text" name="year" placeholder="Year" id="input-year"><br>
						

						<label>Time: </label><br>

						<select name="hour" id="input-hour" >
							<option value="">Hour</option>
							<option value="1">1</option>
							<option value="2">2</option>
							<option value="3">3</option>
							<option value="4">4</option>
							<option value="5">5</option>
							<option value="6">6</option>
							<option value="7">7</option>
							<option value="8">8</option>
							<option value="9">9</option>
							<option value="10">10</option>
							<option value="11">11</option>
							<option value="12">12</option>
						</select>

						<input type="text" name="minute" id="input-minute" placeholder="minute" >
						<select name="time" id="input-time">
							<option value="">Time</option>
							<option value="AM">AM</option>
							<option value="PM">PM</option>
						</select>
						<br>

						<label>Location: </label>
						<input class="" type="text" name="location"  id="location" placeholder="Location" ><br>
						<label>Notes: </label>
						<textarea type="text" name="notes" id="notes" placeholder="Additional info of the event"></textarea>
					</div>
					<div class="actions margin-auto col-12">
						<input type="submit" name="add-event" value="Add">
						 <input type="submit" name="delete-event" value="Delete">
						 <input type="button" name="clear-all" value="Clear All" onclick="sched.clearAll()">
						 <input type="submit" name="update-event" value="Update">
					</div>
				</form>					
			</div> <!-- end sa form container -->
			<div class="display-container col-8  margin-auto">
				<table id="display-event" class="events-disp col-11 margin-auto">
				    <thead>
				        <tr>
				            <th><span class="fa fa-newspaper-o"></span> Event</th>
				            <th><span class="fa fa-calendar"></span> Date</th>
				            <th><span class="fa fa-clock-o"></span> Time</th>
				            <th><span class="fa fa-location-arrow"></span> Location</th>
				            <th><span class="fa fa-comments"></span> Notes</th>
				        </tr>
				    </thead>
				    <tbody>

				<?php 
					$query = "SELECT id, event_name, event_date, event_time, location, notes FROM tbl_events_sched";
					$result = mysqli_query($conn, $query);

					if (mysqli_num_rows($result) > 0) {
					    // output data of each row
					    while($row = mysqli_fetch_assoc($result)) {
					    	echo "<tr id=" ."'event".$row["id"]."' onclick=sched.displayEvent(this)>";
					        echo "<td>" . $row["event_name"] . "</td>";
					        echo "<td>" . $row["event_date"] . "</td>";
					        echo "<td>" . $row["event_time"] . "</td>";
					        echo "<td>" . $row["location"] . "</td>";
					        echo "<td>" . $row["notes"] . "</td>";	
					    	echo "</tr>";				  
					    }
					} else {
					    echo "<div class='empty'>You don't have events yet. Schedule Now!</div>";
					}

				?>
					</tbody>
				</table>
			</div>
		</main>

		<script type="text/javascript" src="js/scheduler.js"></script>	
		<!-- <script type="text/javascript" src="js/bg.js"></script>	 -->
	</body>

	<?php mysqli_close($conn); ?>
</html>
