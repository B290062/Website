<?php
include("includes/header.php");
require("includes/db_connect.php");
?>
<div class ="tabs">
<button onclick="showSection('alignment')">Alignment</button>
<button onclick="showSection('plot')">Conservation Plot</button>
<button onclick="showSection('motifs')">Motifs</button>
<button onclick="showSection('pepstats')">PEPSTATS</button>
</div>
<?php
$job_id = $_GET["job_id"];


$sql = "SELECT raw_fasta, protein_family, taxon_name, max_sequences, created_at
FROM analysis_jobs WHERE job_id = :job_id";

$stmt = $pdo->prepare($sql);
$stmt->bindValue(':job_id', $job_id, PDO::PARAM_STR);
$stmt->execute();
$job = $stmt -> fetch(PDO::FETCH_ASSOC);

$protein = $job['protein_family'];
$taxonomy = $job['taxon_name'];
$max_results = $job['max_sequences'];
$fasta = $job['raw_fasta'];

?>
<!-- displays all of the summary data on screen -->
<h2>Query Summary</h2>
<p><strong>Protein family:</strong> <?php echo ($protein); ?></p>
<p><strong>Taxonomic group:</strong> <?php echo $taxonomy; ?></p>
<p><strong>Max sequences:</strong> <?php echo $max_results; ?></p>
<p><strong>Created at:</strong> <?php echo htmlspecialchars($job['created_at']); ?></p>
<?php


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
?>
<div id ="alignment" class ="section" style = "display:none;">
<h3>Alignment</h3>
<!-- info on Clustalo https://www.labxchange.org/library/items/lb:LabXchange:5b84cc84:html:1 -->
<p> ClustalO alignment was used to align the protein sequences.</p>
<p><b>*</b> represents fully conserved, identical sequences</p>
<p><b>-</b> represents a gap in the sequence</p>
<p><b>:</b> represents a consevative mutation</p>
<p><b>()</b> represents a non conservative mutation</p>
<a href="data/jobs/job_<?= $job_id ?>/aligned.fasta" download>
  Download File
</a>
<div class="scroll-box">
<pre><?php echo htmlspecialchars($aligned); ?></pre>
</div>
</div>
<?php

$plotpath = $job_dir . "/conservation";
//https://emboss.sourceforge.net/apps/cvs/emboss/apps/plotcon.html
$plotcon_command = "plotcon -sformat clustal -sequence " . escapeshellarg($outputname) . " -graph png -goutfile " . escapeshellarg($plotpath) . " -auto ";
$plotcon = shell_exec($plotcon_command);
?>
<div id="plot" class="section" style="display:none;">
    <h3>Conservation Plot</h3>
    <!-- info from https://www.bioinformatics.nl/cgi-bin/emboss/help/plotcon -->
<p> EMBOSS plotcon reads the alignment from ClustalO and draws a plot of sequence conservation</p>
<p> it does this by making windows of a specified length across the alignment and compares all possible bases combinations to determine the pair wise subsitution scores</p>
<a href="data/jobs/job_<?= $job_id ?>/conservation.1.png" download>
  Download Conservation Plot
</a>
<br>
    <img src="data/jobs/job_<?php echo $job_id; ?>/conservation.1.png">
</div>
<?php

$patmatmotifs = $job_dir . "/motifs.txt";
$patmatmotifs_command = "patmatmotifs -full -sequence " . escapeshellarg($inputname) . " -outfile " . escapeshellarg($patmatmotifs) . " -rformat excel ";
$patmat = shell_exec($patmatmotifs_command);


$motifs = file($patmatmotifs);
?>
<div id="motifs" class="section" style="display:none;">
<h3>Motifs</h3>
<!-- info from https://emboss.bioinformatics.nl/cgi-bin/emboss/help/patmatmotifs -->
<p> scanning the motifs using EMBOSS patmatmotifs. this compares the seqeunces with the PROSITE database of motifs to see if the functions of proteins can be identified.</p>
<a href="data/jobs/job_<?= $job_id ?>/motifs.txt" download>
  Download Motifs
</a>
<?php

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
?> 
</div>
<?php
#https://www.bioinformatics.nl/cgi-bin/emboss/help/pepstats
$pepstat_path = $job_dir . "/stats.txt";
$pepstat_command = "pepstats -sequence " . escapeshellarg($inputname) . " -outfile " . escapeshellarg($pepstat_path);
$pepstat = shell_exec($pepstat_command);

$pepstat_file = file_get_contents($pepstat_path);
?>
<div id="pepstats" class="section" style="display:none;">
<!-- info from https://emboss.sourceforge.net/apps/cvs/emboss/apps/pepstats.html -->
<h3>PEPSTATS</h3>
<p> calculates statistics of protein properties such as number of residues and molecular weight for each sequence</p>
<a href="data/jobs/job_<?= $job_id ?>/stats.txt" download>
  Download Pepstats
</a>
<div class="scroll-box">
<pre><?php echo htmlspecialchars($pepstat_file); ?></pre>
</div>
</div>
<script>
function showSection(id) {
    document.querySelectorAll('.section').forEach(s => s.style.display = 'none');
    document.getElementById(id).style.display = 'block';
}
</script>