<?php

session_start(); 
#initalises header that contains css formatting
include('includes/header.php'); 
#table variable is created
$mytable = "job_sequences";
#make sure connection to mysql is set up
require 'includes/db_connect.php';
echo "Example database table:";


//job 253 was the example query and was run with real analysis and then taken to be used, Intially I manually entered a query however
//this method felt most beneficial as it uses 'real' data from the pipeline.
$sql = "SELECT protein_name, sequence_length, taxonomic_group, sequence FROM $mytable WHERE job_id = 267";
$stmt = $pdo->prepare($sql);
$stmt->execute();

$results = $stmt -> fetchAll(PDO::FETCH_ASSOC);



echo "<table border='1'>";
echo "<tr>";
echo "<th>Header (Accession, protein name & species)</th>";
echo "<th>Sequence length</th>";
echo "<th>Taxonomy</th>";
echo "<th>Sequence preview</th>";
echo "</tr>";

//https://www.php.net/manual/en/control-structures.foreach.php
// this allows for looping through each row in the data
// sequence is set do only display the first 40 characters to make it easier to view
foreach ($results as $row) {
echo "<tr>";
echo "<td>" . htmlspecialchars($row['protein_name']) . "</td>";
echo "<td>" . htmlspecialchars($row['sequence_length']) . "</td>";
echo "<td>" . htmlspecialchars($row['taxonomic_group']) . "</td>";
echo "<td>" . htmlspecialchars(substr($row['sequence'], 0, 40)) . "...</td>";
echo "</tr>";
}


?>

