<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/demo_cavex/'.'routes.php');
// require_once($_SERVER['DOCUMENT_ROOT'].'/demo_cavex/'.'header.php');
// require_once($aRoutes['paths']['config'].'st_functions_generals.php');
// require_once($aRoutes['paths']['config'].'st_model.php');
/* $server = the IP address or network name of the server
 * $userName = the user to log into the database with
 * $password = the database account password
 * $databaseName = the name of the database to pull data from
 * table structure - colum1 is cas: has text/description - column2 is data has the value
 */
// $con = mysql_connect('localhost', 'username', 'password') or die('Error connecting to server');
 
// mysql_select_db('yourdatabasename', $con); 

// // write your SQL query here (you may use parameters from $_GET or $_POST if you need them)
// $query = mysql_query('SELECT * FROM yourtable');

$table = array();
$table['cols'] = array(
	/* define your DataTable columns here
	 * each column gets its own array
	 * syntax of the arrays is:
	 * label => column label
	 * type => data type of column (string, number, date, datetime, boolean)
	 */
	// I assumed your first column is a "string" type
	// and your second column is a "number" type
	// but you can change them if they are not
    array('label' => 'a', 'type' => 'number'),
	array('label' => 'b', 'type' => 'number')
);

$rows = array();
for ($i=0; $i < 2; $i++) { 
	$n1 = $i;
	$n2 = rand(1,3);

	$temp = array();
	$temp[] = array('v' => (int)$n1);
	$temp[] = array('v' => (int)$n2); 
	$rows[] = array('c' => $temp);
}
    
	// each column needs to have data inserted via the $temp array
	// typecast all numbers to the appropriate type (int or float) as needed - otherwise they are input as strings
	
	// insert the temp array into $rows
    

// populate the table with rows of data
$table['rows'] = $rows;

// encode the table as JSON
$jsonTable = json_encode($table);

// set up header; first two prevent IE from caching queries
header('Cache-Control: no-cache, must-revalidate');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('Content-type: application/json');

// return the JSON data
echo $jsonTable;
?>