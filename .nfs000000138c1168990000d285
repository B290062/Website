<?php

session_start(); 
include ('includes/header.php');
$mytable = "example_sequences";
require 'includes/db_connect.php';



$species = $_POST['species'];
$protein = $_POST['protein'];

# doesn't display the query information if the fields are empty, it just shows the whole example table
if(!empty($species) && !empty($protein)){
echo "<p> Displaying query results for ", $_POST['species'], " and ", $_POST['protein'],"</p>";
}
$sql = "SELECT * FROM $mytable
WHERE species_name LIKE :species
AND protein_name LIKE :protein";

$stmt = $pdo->prepare($sql);

// bindValue exchanges the placeholder values for values similar (%) to the user input variable
// this allows the results to be executed and fetched with the variable.
$stmt->bindValue(':species', "%$species%", PDO::PARAM_STR);
$stmt->bindValue(':protein', "%$protein%", PDO::PARAM_STR);
$stmt->execute();

$results = $stmt -> fetchAll(PDO::FETCH_ASSOC);

// adapted from week three content 
// placeholders where added to the sql 
// query as it didn't initially work with $variable name 
// bindValue was adapted from https://www.php.net/manual/en/pdostatement.bindvalue.php


if (count($results) == 0) {
echo "No results found";
} else {

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
}
//table adapted from playblast.php (week3), added error handling for 0 results.
?>