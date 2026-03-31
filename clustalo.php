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


$job_dir = "/localdisk/home/s2089123/public_html/website_assignment/data/jobs/job_" . $job_id;
#https://stackoverflow.com/questions/13372179/creating-a-folder-when-i-run-file-put-contents?utm_sour
$inputname = ($job_dir . "/input.fasta");
$outputname = ($job_dir . "/aligned.fasta");

if (!file_exists($job_dir)) {
mkdir($job_dir, 0775, true);
}

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

$plotpath = $job_dir . "/conservation";
//https://emboss.sourceforge.net/apps/cvs/emboss/apps/plotcon.html
$plotcon_command = "plotcon -sformat clustal -sequence " . escapeshellarg($outputname) . " -graph png -goutfile " . escapeshellarg($plotpath) . " -auto ";
$plotcon = shell_exec($plotcon_command);


echo "<img src='data/jobs/job_$job_id/conservation.1.png'>";

$patmatmotifs = $job_dir . "/motifs.txt";
$patmatmotifs_command = "patmatmotifs -full -sequence " . escapeshellarg($inputname) . " -outfile " . escapeshellarg($patmatmotifs) . " -rformat excel ";
$patmat = shell_exec($patmatmotifs_command);


$motifs = file($patmatmotifs);

if (count($motifs) == 1) {
echo "<p>No PROSITE motifs were found in the dataset.</p>";
}else {
# https://stackoverflow.com/questions/28690855/str-getcsv-on-a-tab-separated-file?
# table from playblast.php (week3)
$rows = array_map(function($line) {
return str_getcsv($line, "\t");
}, $motifs);
echo "<table border='1'>";

foreach ($rows as $i => $row) {
echo "<tr>";

foreach ($row as $value) {
echo $i === 0
? "<th>" . htmlspecialchars($value) . "</th>"
: "<td>" . htmlspecialchars($value) . "</td>";
}

echo "</tr>";
}

echo "</table>";
}
#https://www.bioinformatics.nl/cgi-bin/emboss/help/pepstats
$pepstat_path = $job_dir . "/stats.txt";
$pepstat_command = "pepstats -sequence " . escapeshellarg($inputname) . " -outfile " . escapeshellarg($pepstat_path);
$pepstat = shell_exec($pepstat_command);

$pepstat_file = file_get_contents($pepstat_path);
echo "<pre>" . htmlspecialchars($pepstat_file) . "</pre>";
?>
