<?php
include("includes/header.php");
require("includes/db_connect.php");

$job_id = $_GET["job_id"];

$sql = "SELECT raw_fasta, protein_family, taxon_name
FROM analysis_jobs WHERE job_id = :job_id";

$stmt = $pdo->prepare($sql);
$stmt->bindValue(':job_id', $job_id, PDO::PARAM_STR);
$stmt->execute();
$job = $stmt -> fetch(PDO::FETCH_ASSOC);


$fasta = $job['raw_fasta'];

#https://stackoverflow.com/questions/13372179/creating-a-folder-when-i-run-file-put-contents?utm_sour
$inputname = ("/localdisk/home/s2089123/public_html/website_assignment/data/jobs/" . $job_id . ".fasta");
$outputname = ("/localdisk/home/s2089123/public_html/website_assignment/data/jobs/" . $job_id . ".aligned");


// These commands are needed for this step to work
//chmod 755 /localdisk/home/s2089123/public_html/website_assignment/data
//chmod 777 /localdisk/home/s2089123/public_html/website_assignment/data/jobs
// adapted from https://www.php.net/manual/en/function.file-put-contents.php
file_put_contents($inputname, $fasta);

#adapted from https://www.biostars.org/p/9511253/#:~:text=Even%20if%20you%20were%20to,threads%20and%20a%20speedy%20computer.
$command = "clustalo -i " . escapeshellarg($inputname). " -o " . escapeshellarg($outputname) . " --outfmt=clu ";

$fasta_output = shell_exec($command);


$aligned = file_get_contents($outputname);
echo "<pre>" . htmlspecialchars($aligned) . "</pre>";

