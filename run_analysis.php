<?php

#prepares the table analysis_jobs
$mytable = "analysis_jobs";
# connect to the mysql database using db_connect
require 'includes/db_connect.php';

#binds the values from real_search in variables
$taxonomy = $_POST['taxonomy'];
$protein = $_POST['protein'];
$max_results = $_POST['max_results'];


//query inserts the information from the custom query into the analysis_jobs database
// this allows for the data stored to be quered by ncbis search and fetch
$sql = "INSERT INTO $mytable (taxon_name, protein_family, max_sequences, status)
VALUES (:taxonomy, :protein, :max_results, :status)";

$stmt = $pdo->prepare($sql);

// bindValue exchanges the placeholder values
// this allows the results to be executed and fetched with the variable.
// table shows the summary data
$stmt->bindValue(':taxonomy', "$taxonomy", PDO::PARAM_STR);
$stmt->bindValue(':protein', "$protein", PDO::PARAM_STR);
$stmt->bindValue(':max_results', "$max_results", PDO::PARAM_STR);
$stmt->bindValue(':status', "pending", PDO::PARAM_STR);
$stmt->execute();



$job_id = $pdo->lastInsertID();

#data is send over to results_real to be analysed
header("Location: results_real.php?job_id=" . $job_id);

exit;

?>
