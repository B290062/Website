<?php

session_start(); 
#initalises header that contains css formatting
include('includes/header.php'); 
#table variable is created
$mytable = "example_sequences";
#make sure connection to mysql is set up
require 'includes/db_connect.php';
echo "Example database table:";


#selects all the data that was manually assigned to 
$sql = "SELECT * FROM $mytable";

$stmt->execute($sql);

$results = $stmt -> fetchAll(PDO::FETCH_ASSOC);


#table from playblast.php (week3)
echo "<table border='1'>";
echo "<tr>";
foreach (array_keys($results[0]) as $columnName) {
echo "<th>" . htmlspecialchars($columnName) . "</th>";
}
echo "</tr>";

foreach ($results as $row) {
echo "<tr>";
foreach ($row as $value) {
echo "<td>" . htmlspecialchars($value) . "</td>";
}
echo "</tr>";
}
echo "</table>";

?>