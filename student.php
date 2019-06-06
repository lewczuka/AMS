<p><font size="20">Student Database</p>

<form method="POST" action="student.php"> 
   <p><input type="submit" value="Initialize" name="reset"></p>
</form>

<p><font size="3">Insert a new student info into Student table below:</p>

<form method="POST" action="student.php">
<!-- refreshes page when submitted -->

   <p>
    <input type="text" placeholder="StudentID" name="insStudentID" size="8">
    <input type="text" placeholder="Student Name" name="insStudentName" size="18">
    <input type="text" placeholder="Address"name="insAddress" size="18">
    <input type="text" placeholder="Postal Code" name="insPostalCode" size="18">
    <input type="text" placeholder="City" name="insCity" size="18">
    <input type="text" placeholder="Province" name="insProvince" size="10">
<!-- Define two variables to pass values. -->    
<input type="submit" value="Insert Student Data" name="insertsubmit"></p>
</form>

<!-- Create a form to pass the values.  
     See below for how to get the values. --> 

<p><font size="3"> Update address by inserting your studentID and the desired new address below: </p>
<p><font size="2"> Student ID&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; New Address</font></p>



<form method="POST" action="student.php">
<!-- refreshes page when submitted -->

   <p><input type="text" name="studentID" size="6"><input type="text" name="newAddress" size="18">
<!-- Define two variables to pass values. -->
      
<input type="submit" value="update" name="updatesubmit"></p>
<input type="submit" value="Clear Student Database" name="deleteAll"></p>
</form>

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
	if (array_key_exists('reset', $_POST)) {
		// Drop old table...
		echo "<br> dropping table <br>";
		executePlainSQL("Drop table student");

		// Create new table...
		echo "<br> creating new table <br>";
		executePlainSQL("create table student (studentID number, studentName varchar2(30), address varchar2(30), postalCode varchar(8), city varchar2(30), province varchar2(2), primary key (studentID))");
		OCICommit($db_conn);

	} else {
		if (array_key_exists('insertsubmit', $_POST)) {
			// Get values from the user and insert data into 
                // the table.
			$tuple = array (
				":bind1" => $_POST['insStudentID'],
                ":bind2" => $_POST['insStudentName'],
                ":bind3" => $_POST['insAddress'],
                ":bind4" => $_POST['insPostalCode'],
                ":bind5" => $_POST['insCity'],
                ":bind6" => $_POST['insProvince']

			);
			$alltuples = array (
				$tuple
			);
			executeBoundSQL("insert into student values (:bind1, :bind2, :bind3, :bind4, :bind5, :bind6)", $alltuples);
			OCICommit($db_conn);

		} else {
			if (array_key_exists('updatesubmit', $_POST)) {
				// Update tuple using data from user
				$tuple = array (
					":bind1" => $_POST['studentID'],
                    ":bind2" => $_POST['newAddress']
				);
				$alltuples = array (
					$tuple
				);
                executeBoundSQL("update student set address=:bind2 where studentID=:bind1", $alltuples);
                //executeBoundSQL("update student set nickname=:bind4 where nickname=:bind3", $alltuples);
				OCICommit($db_conn);
                } else {
                    if (array_key_exists('deleteAll', $_POST)) {
                        executePlainSQL("delete from student");
                        OCICommit($db_conn);
                    }
                }
            }
        }
	if ($_POST && $success) {
		//POST-REDIRECT-GET -- See http://en.wikipedia.org/wiki/Post/Redirect/Get
		header("location: student.php");
	} else {
		// Select data...
		$result = executePlainSQL("select * from student");
		/*printResult($result);*/
           /* next two lines from Raghav replace previous line */
           $columnNames = array("StudentID", "Student Name", "Address", "Postal Code", "City", "Province");
           printTable($result, $columnNames);
	}

	//Commit to save changes...
    OCILogoff($db_conn);


} else {
	echo "cannot connect";
	$e = OCI_Error(); // For OCILogon errors pass no handle
	echo htmlentities($e['message']);
}