<p><font size="20">Student Database</p>

<form method="POST" action="student.php"> 
   <p><input type="submit" value="Reset" name="reset"></p>
</form>

<p>Insert a new student info into Student table below:</p>
<p><font size="2"> Number&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
Name&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Nickname</font></p>
<form method="POST" action="student.php">
<!-- refreshes page when submitted -->

   <p><input type="text" name="insNo" size="6"><input type="text" name="insName" size="18"><input type="text" name="insNickname" size="18">
<!-- Define two variables to pass values. -->    
<input type="submit" value="insert" name="insertsubmit"></p>
</form>

<!-- Create a form to pass the values.  
     See below for how to get the values. --> 

<p> Update the name by inserting the old and new values below: </p>
<p><font size="2"> Old Name&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; New Name</font></p>
<form method="POST" action="student.php">
<!-- refreshes page when submitted -->

   <p><input type="text" name="oldName" size="6"><input type="text" name="newName" size="18">
<!-- Define two variables to pass values. -->
      
<input type="submit" value="update" name="updatesubmit"></p>
<input type="submit" value="run hardcoded queries" name="dostuff"></p>
</form>

<p><font size="2"> Old Nickname&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; New Nickname</font></p>
<form method="POST" action="student.php">
<!-- refreshes page when submitted -->

   <p><input type="text" name="oldNickname" size="6"><input type="text" name="newNickname" size="18">
<!-- Define two variables to pass values. -->
      
<input type="submit" value="updateNickname!" name="updatesubmit"></p>
<input type="submit" value="DeleteAllNicknameContainingJungyeon" name="deletealljungyeon"></p>
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

/*
Function printTable created by Raghav Thakur on 2018-11-15.

Input:  takes in a result returned from your SQL query and an array of
        strings of the column names
Output: prints an HTML table of the results returned from your SQL query.

printTable is an easy way to iteratively print the columns of a table, 
instead of having to manually print out each column which can be
cumbersome and lead to duplicate code all over the place.

If you will be making calls to printTable multiple times and intend to
use it for multiple php files, please do the following:

Step 1) Create a new php file and copy the printTable function and the
        associated HTML styling code into the file you created, give
        this file a meaningful name such as 'print-table.php'.
        (Search for "style" above.)

Step 2) In whichever file you want to use the printTable function,
        assuming this file also contains the server code to communicate
        with the database:  Type in "include 'print-table.php'" without
        double quotes.  If the file in which you want to use printTable
        is not in the root directory, you'll need to specify the path of 
        root directory where 'print-table.php' is.  As an example:
        "include '../print-table.php'" without double quotes.

Step 3) You can now make calls to the printTable function without 
        needing to redeclare it in your current file.

Note:  You can move all the server code into a separate file called 
       'server.php' in a similar way, except whichever file needs to
       use the server code needs to have "require 'server.php'" without
       double quotes.  So, you might have something like what's shown
       below in each file:

require 'server.php';
require 'print-table.php'

Using printTable as an example:

Note: PHP uses '$' to declare variables

$result = executePlainSQL("SELECT CUST_ID, NAME, PHONE_NUM FROM CUSTOMERS");

$columnNames = array("Customer ID", "Name", "Phone Number");
printTable($result, $columnNames); // this will print the table
                                   // in the current webpage

*/

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

	} else
		if (array_key_exists('insertsubmit', $_POST)) {
			// Get values from the user and insert data into 
                // the table.
			$tuple = array (
				":bind1" => $_POST['insNo'],
                ":bind2" => $_POST['insName'],
                ":bind3" => $_POST['insNickname']
			);
			$alltuples = array (
				$tuple
			);
			executeBoundSQL("insert into student values (:bind1, :bind2, :bind3)", $alltuples);
			OCICommit($db_conn);

		} else
			if (array_key_exists('updatesubmit', $_POST)) {
				// Update tuple using data from user
				$tuple = array (
					":bind1" => $_POST['oldName'],
                    ":bind2" => $_POST['newName'],
                    ":bind3" => $_POST['oldNickname'],
					":bind4" => $_POST['newNickname']
				);
				$alltuples = array (
					$tuple
				);
                executeBoundSQL("update student set name=:bind2 where name=:bind1", $alltuples);
                executeBoundSQL("update student set nickname=:bind4 where nickname=:bind3", $alltuples);
				OCICommit($db_conn);
            } else
                if (array_key_exists('deletealljungyeon', $_POST)) {
                    // Delete tuple in the 
                    executePlainSQL("delete from student where nickname like '%jungyeon%'");
                    OCICommit($db_conn);
                } else
                    if (array_key_exists('dostuff', $_POST)) {
                        // Insert data into table...
                        executePlainSQL("insert into student values (10, 'Frank', 'papaFranku')");
                        // Insert data into table using bound variables
                        $list1 = array (
                            ":bind1" => 6,
                            ":bind2" => "All",
                            ":bind3" => "All"
                        );
                        $list2 = array (
                            ":bind1" => 7,
                            ":bind2" => "John",
                            ":bind3" => "JohnnieBoy"
                        );
                        $allrows = array (
                            $list1,
                            $list2
                        );
                        executeBoundSQL("insert into student values (:bind1, :bind2, :bind3)", $allrows); //the function takes a list of lists
            // Update data...
            //executePlainSQL("update student set nid=10 where nid=2");
            // Delete data...
            //executePlainSQL("delete from student where nid=1");
            OCICommit($db_conn);
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