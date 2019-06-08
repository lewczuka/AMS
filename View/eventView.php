<p><font size="20">AMS Events Database</p>
<a href="event.php"><font size= "1.5">Click Here to Enable Admin View (ADMINS ONLY!)</a><br/>
<a href="main.php"><font size= "1.5">Back to Main Menu</a>

<form method="POST" action="eventView.php"> 
   <p><input type="submit" value="Initialize" name="reset"></p>
</form>

<form method="POST" action="eventView.php"> 
   <p><input type="text" placeholder="type event name here.." name="eventSearchString" size="18">
   <input type="submit" value="Search for an event by its event name here" name="eventSearch"></p>
</form>

<form method="POST" action="eventView.php"> 
   <p><input type="date" placeholder="type start date here.." name="eventDateBegin" size="18">
   <input type="date" placeholder="type end date here.." name="eventDateEnd" size="18">
   <input type="submit" value="Search for events between your two given dates" name="eventDateSearch"></p>
</form>



<p><font size="3">Insert a new event info into our Event database table below:</p>

<form method="POST" action="eventView.php">
<!-- refreshes page when submitted -->

   <p>
    <input type="text" placeholder="eventName" name="insEventName" size="18">
    <input type="date" placeholder="eventDate" name="insEventDate" size="18">
    <input type="text" placeholder="Description" name="insDescription" size="18">
    <input type="text" placeholder="Tickets"name="insTickets" size="18">
    <input type="text" placeholder="LocationID" name="insLocationID" size="18">
    <input type="text" placeholder="AMS ExecutiveID" name="insExecutiveID" size="18">
<!-- Define two variables to pass values. -->    
<input type="submit" value="Insert Event Data" name="insertsubmit"></p>
</form>

<!-- Create a form to pass the values.  
     See below for how to get the values. --> 


<html>
<style>
    table {
        width: 20%;
        border: 1px solid black;
    }

    th {
        font-family: Arial, Helvetica, sans-serif;
        font-size: .7em;
        background: #666;
        color: #FFF;
        padding: 2px 6px;
        border-collapse: separate;
        border: 1px solid #000;
    }

    td {
        font-family: Arial, Helvetica, sans-serif;
        font-size: .7em;
        border: 1px solid #DDD;
        color: black;
    }

    ::placeholder {
    color: gray;
    opacity: 0.65; /* Firefox */
    }

    :-ms-input-placeholder { /* Internet Explorer 10-11 */
    color: red;
    }

    ::-ms-input-placeholder { /* Microsoft Edge */
    color: red;
    }
</style>
</html>



<?php

/* This tells the system that it's no longer just parsing 
   HTML; it's now parsing PHP. */

// keep track of errors so it redirects the page only if
// there are no errors


$localvarrr = 3;
$success = True;
$db_conn = OCILogon("ora_ansel", "a15984164", 
                    "dbhost.students.cs.ubc.ca:1522/stu");

function executePlainSQL($cmdstr) { 
     // Take a plain (no bound variables) SQL command and execute it.
	//echo "<br>running ".$cmdstr."<br>";
	global $db_conn, $success;
	$statement = OCIParse($db_conn, $cmdstr); 
     // There is a set of comments at the end of the file that 
     // describes some of the OCI specific functions and how they work.

	if (!$statement) {
		echo "<br>Cannot parse this command: " . $cmdstr . "<br>";
		$e = OCI_Error($db_conn); 
           // For OCIParse errors, pass the connection handle.
		echo htmlentities($e['message']);
		$success = False;
	}

	$r = OCIExecute($statement, OCI_DEFAULT);
	if (!$r) {
		echo "<br>Cannot execute this command: " . $cmdstr . "<br>";
		$e = oci_error($statement); 
           // For OCIExecute errors, pass the statement handle.
		echo htmlentities($e['message']);
		$success = False;
	} else {

	}
	return $statement;

}

function debug_to_console($data) {
    $output = $data;
    if ( is_array( $output ) )
        $output = implode( ',', $output);

    echo "<script>console.log( 'Debug Objects: " . $output . "' );</script>";
}

function executeBoundSQL($cmdstr, $list) {
	/* Sometimes the same statement will be executed several times.
        Only the value of variables need to be changed.
	   In this case, you don't need to create the statement several
        times.  Using bind variables can make the statement be shared
        and just parsed once.
        This is also very useful in protecting against SQL injection
        attacks.  See the sample code below for how this function is
        used. */

	global $db_conn, $success;
	$statement = OCIParse($db_conn, $cmdstr);

	if (!$statement) {
		echo "<br>Cannot parse this command: " . $cmdstr . "<br>";
		$e = OCI_Error($db_conn);
		echo htmlentities($e['message']);
		$success = False;
	}

	foreach ($list as $tuple) {
		foreach ($tuple as $bind => $val) {
			//echo $val;
			//echo "<br>".$bind."<br>";
			OCIBindByName($statement, $bind, $val);
			unset ($val); // Make sure you do not remove this.
                              // Otherwise, $val will remain in an 
                              // array object wrapper which will not 
                              // be recognized by Oracle as a proper
                              // datatype.
		}
		$r = OCIExecute($statement, OCI_DEFAULT);
		if (!$r) {
			echo "<br>Cannot execute this command: " . $cmdstr . "<br>";
			$e = OCI_Error($statement);
                // For OCIExecute errors pass the statement handle
			echo htmlentities($e['message']);
			echo "<br>";
			$success = False;
		}
	}

}

function printTable($resultFromSQL, $namesOfColumnsArray)
{
        echo "<br>Here is the output, nicely formatted:<br>";
        echo "<table>";
        echo "<tr>";
        // iterate through the array and print the string contents
        foreach ($namesOfColumnsArray as $name) {
            echo "<th>$name</th>";
        }
        echo "</tr>";

        while ($row = OCI_Fetch_Array($resultFromSQL, OCI_BOTH)) {
            echo "<tr>";
            $string = "";

            // iterates through the results returned from SQL query and
            // creates the contents of the table
            for ($i = 0; $i < sizeof($namesOfColumnsArray); $i++) {
                $string .= "<td>" . $row["$i"] . "</td>";
            }
            echo $string;
            echo "</tr>";
        }
        echo "</table>";
}



// Connect Oracle...
if ($db_conn) {
    global $localvarrr;
	if (array_key_exists('reset', $_POST)) {
		// Drop old table...
		echo "<br> dropping table <br>";
		executePlainSQL("Drop table event");

		// Create new table...
		echo "<br> creating new table <br>";
		executePlainSQL("create table event (eventName varchar2(30), eventDate date, description varchar2(30), tickets varchar2(30), locationID varchar(8) NOT NULL, executiveID number NOT NULL, primary key (eventName, eventDate))");
        OCICommit($db_conn);

	} else {
		if (array_key_exists('insertsubmit', $_POST)) {
            $localvarrr = 6;
			// Get values from the user and insert data into 
                // the table.
			$tuple = array (
				":bind1" => $_POST['insEventName'],
                ":bind2" => $_POST['insEventDate'],
                ":bind3" => $_POST['insDescription'],
                ":bind4" => $_POST['insTickets'],
                ":bind5" => $_POST['insLocationID'],
                ":bind6" => $_POST['insExecutiveID']
                
			);
			$alltuples = array (
				$tuple
			);
			executeBoundSQL("insert into event values (:bind1, TO_DATE(:bind2,'yyyy/mm/dd'), :bind3, :bind4, :bind5, :bind6)", $alltuples);
			OCICommit($db_conn);

		} 
    }
    $lol = array_key_exists('eventSearch', $_POST) || array_key_exists('eventDateSearch', $_POST);
    $lol = !$lol;
	if ($_POST && $success && $lol) {
        //POST-REDIRECT-GET -- See http://en.wikipedia.org/wiki/Post/Redirect/Get
        header("location: eventView.php");
	} else {
        // Select data...
        if (array_key_exists('eventSearch', $_POST)) {
            $eventsearched = $_POST['eventSearchString'];
            $result = executePlainSQL("select * from event where eventName like '%" . $eventsearched . "%'");
        } elseif (array_key_exists('eventDateSearch', $_POST)) {
            $eventDateSearchedBegin = $_POST['eventDateBegin'];
            $eventDateSearchedEnd = $_POST['eventDateEnd'];
            $result = executePlainSQL("select * from event where eventDate between TO_DATE('" . $eventDateSearchedBegin . "','yyyy/mm/dd') and TO_DATE('" . $eventDateSearchedEnd . "','yyyy/mm/dd')");
        } else {
            $result = executePlainSQL("select * from event");
        }
        $columnNames = array("Event Name", "Event Date", "Event Description", "Event Tickets", "Event Location ID", "AMS Event Exec ID");
        printTable($result, $columnNames);
	}

	//Commit to save changes...
    OCILogoff($db_conn);


} else {
	echo "cannot connect";
	$e = OCI_Error(); // For OCILogon errors pass no handle
	echo htmlentities($e['message']);
}