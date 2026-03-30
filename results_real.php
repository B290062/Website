<?php
#header is initialised
include("includes/header.php");
# mysql connection is initalised
require 'includes/db_connect.php';

#binds the job id to a variable
$job_id = $_GET["job_id"];

#selects all the query data previously entered by the user
$sql = "SELECT * FROM analysis_jobs WHERE job_id = :job_id";

#binds to pdo query 
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':job_id', $job_id, PDO::PARAM_STR);
$stmt->execute();

#fetchs the all data
$job = $stmt -> fetch(PDO::FETCH_ASSOC);


$protein = $job["protein_family"];
$taxonomy = $job["taxon_name"];
$max_results = $job["max_sequences"];
?>
<!-- displays all of the summary data on screen -->
<h2>Query Summary</h2>
<p><strong>Protein family:</strong> <?php echo ($protein); ?></p>
<p><strong>Taxonomic group:</strong> <?php echo $taxonomy; ?></p>
<p><strong>Max sequences:</strong> <?php echo $max_results; ?></p>
<p><strong>Created at:</strong> <?php echo htmlspecialchars($job['created_at']); ?></p>
<?php

//query phrase, since this command is going to executed in bash it needs to be formatted like this to avoid
//the query formatting influencing the results. Note - Not partial was added from the FAQ section of the course
// this in theory makes alignment easier
$query =  '"' . $protein . '"[Protein Name] AND "' . $taxonomy . '"[Organism] NOT partial[Title]';
$query_safe = escapeshellarg($query);


// adapted from https://www.php.net/manual/en/function.escapeshellarg.php //
// https://www.php.net/manual/en/function.shell-exec.php //

// query command was adapted from the read me file of Entrez® Direct: E-utilities on the Unix Command Line //

$command = "/home/s2089123/edirect/esearch -db protein -query $query_safe"
."| /home/s2089123/edirect/efetch -format uid" 
. "| head -n $max_results | /home/s2089123/edirect/epost -db protein" 
. "| /home/s2089123/edirect/efetch -format fasta";
//retmax wasn't working in esearch on the server so ai was used to adapted the command in order to head the number of results entered by the user in the fetch
// this could be more efficient if the filtering was done initially in the search rather than fetch.
$fasta = shell_exec($command);

# Checks to see if the output of the command is null, if null it prompts the user to try again
if ($fasta == null){
?>
<h4>No results found please enter something different</h4>
<button onclick="history.back()">Go Back</button>

<?php
exit;
}




//this code block takes the raw fasta format and stores it in the database, for use with clustalo
$sql = "UPDATE analysis_jobs SET raw_fasta = :fasta, status = :status WHERE job_id = :job_id";

$stmt = $pdo->prepare($sql);
$stmt->bindValue(':fasta', $fasta, PDO::PARAM_STR);
$stmt->bindValue(':status', "acquired", PDO::PARAM_STR);
$stmt->bindValue(':job_id', $job_id, PDO::PARAM_STR);

$stmt->execute();

// the following is a fasta parser adapted from https://www.biob.in/2017/09/extracting-multiple-fasta-sequences.html
function get_seq($x) { 
    $fl = explode(PHP_EOL, $x);
    $sh = trim(array_shift($fl));
    if($sh == null) {
        $sh = "UNKNOWN SEQUENCE";
    }
    $fl = array_filter($fl);
    $seq = "";
    foreach($fl as $str) {
        $seq .= trim($str);
    }
    $seq = strtoupper($seq);
    $seq = preg_replace("/[^ACDEFGHIKLMNPQRSTVWY]/i", "", $seq);
    if ((count($fl) < 1) || (strlen($seq) == 0)) {
        print "Sequence is Empty!!";
        exit();
    } else {
        return array($sh, $seq);
    }
}

function fas_get($x) { 
    $gtr = substr($x, 1);
    $sqs = explode(">", $gtr);

    $records = array(); // array is made to take more data than the code
    foreach ($sqs as $sq) {
        if (empty(trim($sq))) continue; 

        $spair = get_seq($sq);

        $header = $spair[0]; //this is the data normally taken from the code
        $sequence = $spair[1];
        
        #ai regex code to separate the accession and species from the header.
        preg_match('/^(\\S+)/', $header, $acc);
        preg_match('/\\[(.*?)\\]/', $header, $sp);

        $records[] = array(
            'header' => $header,
            'accession' => $acc[1] ?? 'unknown',
            'species' => $sp[1] ?? 'unknown',
            'sequence' => $sequence,
            'length' => strlen($sequence)
        );
    }

    return $records; 
}

$records = fas_get($fasta);

// AI fix, stops the same input being duplicated each time the page is refreshed. 
// removes the existing records for the current job_id before the insert.
$delete_sql = "DELETE FROM job_sequences WHERE job_id = :job_id";
$delete_stmt = $pdo->prepare($delete_sql);
$delete_stmt->bindValue(':job_id', $job_id, PDO::PARAM_INT);
$delete_stmt->execute();


$sql = "INSERT INTO job_sequences (job_id, accession, protein_name, species_name, taxonomic_group, sequence_length, sequence)
VALUES (:job_id, :accession, :protein_name, :species_name, :taxonomic_group, :sequence_length, :sequence)";
$stmt = $pdo->prepare($sql);

foreach($records as $record) {
$stmt->bindValue(':job_id', $job_id, PDO::PARAM_INT);
    $stmt->bindValue(':accession', $record['accession'], PDO::PARAM_STR);
    $stmt->bindValue(':protein_name', $record['header'], PDO::PARAM_STR);
    $stmt->bindValue(':species_name', $record['species'], PDO::PARAM_STR);
    $stmt->bindValue(':taxonomic_group', $taxonomy, PDO::PARAM_STR);
    $stmt->bindValue(':sequence_length', $record['length'], PDO::PARAM_INT);
    $stmt->bindValue(':sequence', $record['sequence'], PDO::PARAM_STR);

    $stmt->execute();
}



$sql = "SELECT accession, species_name, sequence_length FROM job_sequences where job_id = $job_id";
$stmt = $pdo->prepare($sql);
$stmt->execute();

$results = $stmt -> fetchAll(PDO::FETCH_ASSOC);

# table from playblast.php (week3)
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

