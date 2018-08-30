<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en-US"> 

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<title> 
		Las Colinas Visitor Check-in
	</title>


	<style>
		.error {color: #FF0000;}
	</style>

	<!-- Include CSS for different screen sizes -->
	<link rel="stylesheet" type="text/css" href="defaultstyle.css">
</head>

<body>

<?php
	
	require 'connectToDatabase.php';

	// Connect to Azure SQL Database
	$conn = ConnectToDabase();

	// Get data for expense categories
	$tsql="SELECT CATEGORY FROM Expense_Categories ORDER BY CATEGORY ASC";
	$expenseCategories= sqlsrv_query($conn, $tsql);

	// Populate dropdown menu options 
	$options = '';
	while($row = sqlsrv_fetch_array($expenseCategories)) {
		$options .="<option>" . $row['CATEGORY'] . "</option>";
	}

	// Close SQL database connection
	sqlsrv_close ($conn);

	// Get the session data from the previously selected Expense Month, if it exists
	session_start();
	if ( !empty( $_SESSION['prevSelections'] ))
	{ 
		$prevSelections = $_SESSION['prevSelections'];
		unset ( $_SESSION['prevSelections'] );
	}

	// Extract previously-selected Month and Year
	$prevStartDate= $prevSelections['prevStartDate'];
	$prevEndDate= $prevSelections['prevEndDate'];
?>

<div class="intro">

	<h2> Input Expense Form </h2>

	<!-- Display redundant error message on top of webpage if there is an error -->
	<h3> <span class="error"> <?php echo $prevSelections['errorMessage'] ?> </span> </h3>

</div>

<!-- Define web form. 
The array $_POST is populated after the HTTP POST method.
The PHP script insertToDb.php will be executed after the user clicks "Submit"-->
<div class="container">
	<form action="insertToDb.php" method="post">

		<label>Make:</label>
		<input type="text" name="make" required>

		<label>Model:</label>
		<input type="text" name="model" required>

		<label>Start Date:</label>
		<input type="number" name="start_date" required>

		<label>End Date:</label>
		<input type="number" name="end_date" required>

		<label>Employee Name (First Last):</label>
		<input type="text" name="employee_name" required>

		<button type="submit">Submit</button>
	</form>
</div>

<h3> Previous Input (if any) - for verification purposes:</h3>
<p> Previous Make: <?php echo $prevSelections['prevMake'] ?> </p>
<p> Previous Model: <?php echo $prevSelections['prevModel'] ?> </p>
<p> Previous Start Date: <?php echo $prevSelections['prevStartDate'] ?> </p>
<p> Previous End Date: <?php echo $prevSelections['prevEndDate'] ?> </p>
<p> Previous Employee Name: <?php echo $prevSelections['prevEmployeeName'] ?> </p>
<p> <span class="error"> <?php echo $prevSelections['errorMessage'] ?> </span> </p>

</body>
</html>
