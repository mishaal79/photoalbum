<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999" xml:lang="en" lang="en">
<head>
    <meta charset ="utf-8"/>
    <meta name="description" content="AWS photo search"/>
    <meta name="author" content="Mishal Ismeth"/>
    <!-- viewport is used to allow the browser to accomadate varying screen sizes-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Photo Album</title>
    <link href="styles/styles.css" rel="stylesheet"/>
  </head>
  <header class="w3-container w3-teal">
    <h1>Get Photos</h1>
  </header> 
    <body>
		<!-- Search Photos using Title,Keyword and Date -->
	<form method = "get" >
	<fieldset><legend>Search Photos</legend>
		<p class="row">	<label for="photoTitle">Title: </label>
			<input type="text" name="photoTitle" id="photoTitle" /></p>
				<p class="row">	<label for="photoKeyword">Keyword: </label>
			<input type="text" name="photoKeyword" id="photoKeyword" placeholder="anime" /></p>
				<p class="row">	<label for="photoDate">StartDate: </label>
			<input type="date" name="photoStartDate" id="photoStartDate" />
			<label for="photoDate">EndDate: </label>
			<input type="date" name="photoEndDate" id="photoEndDate" /></p>
		<p>	<input type="submit" value="Search" /></p>
	</fieldset>
	</form>

	
	<!--php block-->
	<?php

		// function to clean trailing spaces and html elements from data inputs
		function sanitise_input($data) {
			$data = trim($data);
			$data = stripslashes($data);
			$data = htmlspecialchars($data);
			return $data;
		}

		//function to check whether variable is set from GET request
		function checkVarIsSet($string) {

			if (isset($_GET[$string] ) ){
				
				return $_GET[$string];
			}
			else{
				
				return "undefined";
			}
		//	if (isset($_POST["idnumber"]))
		}
	?>

	<?php

	require_once "settings.php";	// Load MySQL log in credentials
	$conn = new mysqli($host,$user,$pwd,$sql_db);	// Log in and use database
	if ($conn->connect_error) // check is database is available for use
	{ 
		echo "<p>Unable to connect to the database.</p>";
		die("Connection failed: ".$conn->connect_error);
	}
//	 else {

		// Declare all local variables

		$table = "photos";
		$photoTitle = sanitise_input(checkVarIsSet("photoTitle"));
		$photoKeyword = sanitise_input(checkVarIsSet("photoKeyword"));
		$photoStartDate = sanitise_input(checkVarIsSet("photoStartDate"));
		$photoEndDate = sanitise_input(checkVarIsSet("photoEndDate"));

	
		/*
		*Check what variables are set
		*Based on the variables set perform the search
		*Three if statements to check variables
		*Three select statements which would return the result into variable query

			function checkSelectStatement($var) {
			if (var =)
		}
		*/
	
		$query = "select * from photos where title = '$photoTitle' OR FIND_IN_SET('$photoKeyword',keywords) OR (date >= '$photoStartDate' AND date <= '$photoEndDate')";
		$result = $conn->query($query); // perform the search by title and return to result

		if ($result->num_rows > 0 )	{
			echo " 
				<table class=\"w3-table w3-blue\">
				<tr>
					<th>Title</th>
					<th>Description</th>
					<th>Date</th>
					<th>Keyword</th>
					<th>Reference</th>
				</tr>"; // create a table
			while($records = $result->fetch_assoc())	{
				$photoUrl = $records["reference"]; //assigns the photo link

				echo "<tr><td>".$records["title"]."</td><td>" // output all the records in table
						.$records["description"]."</td><td>".$records["date"]."</td><td>"
						.$records["keywords"]."</td><td>".
						"<a href='$photoUrl'>  <img src='$photoUrl' style=\"width:150px;height:150px;border:0;\"> Click to view</a>"."</td></tr>"; //output the reference link as an image and link

			}
			echo "</table>";
		} else {
				echo "No results found";
		}
		$conn->close();
		 
	?>

	</body>
</html>
