<?php

	// Define function to handle basic user input
	function parse_input($data) 
	{
		$data = trim($data);
		$data = stripslashes($data);
		$data = htmlspecialchars($data);
		return $data;
	}

 
	// PHP script used to connect to backend Azure SQL database
	require 'ConnectToDatabase.php';

	// Start session for this particular PHP script execution.
	session_start();

	// Define ariables and set to empty values
	$Make = $Model = $StartDate = $EndDate = $EmployeeName = $errorMessage = NULL;

	// Get input variables
	$Make=  parse_input($_POST['vehMake']);
	$Model=  parse_input($_POST['vehModel']);
	$StartDate= (int) parse_input($_POST['startDate']);
	$EndDate= (int) parse_input($_POST['endDate']);
	$EmployeeName= parse_input($_POST['employeeName']);
	

	// // Get the authentication claims stored in the Token Store after user logins using Azure Active Directory
	// $claims= json_decode($_SERVER['MS_CLIENT_PRINCIPAL'])->claims;
	// foreach($claims as $claim)
	// {		
	// 	if ( $claim->typ == "http://schemas.xmlsoap.org/ws/2005/05/identity/claims/emailaddress" )
	// 	{
	// 		$userEmail= $claim->val;
	// 		break;
	// 	}
	// }

	///////////////////////////////////////////////////////
	//////////////////// INPUT VALIDATION /////////////////
	///////////////////////////////////////////////////////

	//Initialize variable to keep track of any errors
	$anyErrors= FALSE;

	// // Check category validity
	// if ($expenseCategory == '-1') {$errorMessage= "Error: Invalid Category Selected"; $anyErrors= TRUE;}
	
	// // Check date validity
	// $isValidDate= checkdate($Model, $Make, $StartDate);
	// if (!$isValidDate) {$errorMessage= "Error: Invalid Date"; $anyErrors= TRUE;}

	// // Check that the expense amount input has maximum of 2 decimal places (check against string input, not the float parsed input)
	// $isValidEndDate= validateTwoDecimals(parse_input($_POST['expense_amount']));
	// if (!$isValidEndDate) {$errorMessage= "Error: Invalid Expense Amount"; $anyErrors= TRUE;}


	///////////////////////////////////////////////////////
	////////// INPUT PARSING AND WRITE TO SQL DB //////////
	///////////////////////////////////////////////////////

	// Only input information into database if there are no errors
	if ( !$anyErrors ) 
	{
		// // Create a DateTime object based on inputted data
		// $dateObj= DateTime::createFromFormat('Y-m-d', $StartDate . "-" . $Model . "-" . $Make);

		// // Get the name of the month (e.g. January) of this expense
		// $ModelName= $dateObj->format('F');

		// // Get the day of the week (e.g. Tuesday) of this expense
		// $MakeOfWeekNum= $dateObj->format('w');
		// $days = array('Sunday', 'Monday', 'Tuesday', 'Wednesday','Thursday','Friday', 'Saturday');
		// $MakeOfWeek = $days[$MakeOfWeekNum];

		// Connect to Azure SQL Database
		$conn = ConnectToDabase();

		// Build SQL query to insert new expense data into SQL database
		$tsql=
		"INSERT INTO parking2 (	
				vehMake,
				vehModel,
				startDate,
				endDate,
				employeeName)
		VALUES ('" . $Make . "',, 
				'" . $Model . "',
				'" . $StartDate . "', 
				'" . $EndDate . "', 
				'" . $EmployeeName . "')";

		echo $tsql;

		// Run query
		$sqlQueryStatus= sqlsrv_query($conn, $tsql);

		// Close SQL database connection
		sqlsrv_close ($conn);
	}

	// Initialize an array of previously-posted info
	$prevSelections = array();

	// Populate array with key-value pairs
	$prevSelections['errorMessage']= $errorMessage;
	$prevSelections['prevMake']= $Make;
	$prevSelections['prevModel']= $Model;
	$prevSelections['prevStartDate']= $StartDate;
	$prevSelections['prevEndDate']= $EndDate;
	$prevSelections['prevEmployeeName']= $EmployeeName;

	// Store previously-selected data as part of info to carry over after URL redirection
	$_SESSION['prevSelections'] = $prevSelections;

	/* Redirect browser to home page */
	header("Location: /"); 
?>