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


echo "<div class='table-wrap'>";
echo "<table class ='left-table'>";
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
echo "</table>";
echo "</div>";
?>
<!-- AI adapted -->
<div class="tabs">
<button onclick="showSection('alignment')">Alignment</button>
<button onclick="showSection('plot')">Conservation Plot</button>
<button onclick="showSection('motifs')">Motifs</button>
<button onclick="showSection('pepstats')">PEPSTATS</button>
</div>
<div id="alignment" class="section" style ="display:none;">
<h3>Alignment</h3>
<!-- info on Clustalo https://www.labxchange.org/library/items/lb:LabXchange:5b84cc84:html:1 -->
<p> ClustalO alignment was used to align the protein sequences.</p>
<p><b>*</b> represents fully conserved, identical sequences</p>
<p><b>-</b> represents a gap in the sequence</p>
<p><b>:</b> represents a consevative mutation</p>
<p><b>()</b> represents a non conservative mutation</p>
<div class="scroll-box">
<pre><?php echo htmlspecialchars(file_get_contents("data/jobs/job_267/aligned.fasta")); ?></pre>
</div>
</div>
<div id="plot" class="section" style="display:none;">
<h3>Conservation Plot</h3>
<!-- info from https://www.bioinformatics.nl/cgi-bin/emboss/help/plotcon -->
<p> EMBOSS plotcon reads the alignment from ClustalO and draws a plot of sequence conservation</p>
<p> it does this by making windows of a specified length across the alignment and compares all possible bases combinations to determine the pair wise subsitution scores</p>
<img src="data/jobs/job_267/conservation.1.png">
</div>
<div id="motifs" class="section" style="display:none;">
<!-- info from https://emboss.bioinformatics.nl/cgi-bin/emboss/help/patmatmotifs -->
<h3>Motifs</h3>
<p> scanning the motifs using EMBOSS patmatmotifs. this compares the seqeunces with the PROSITE database of motifs to see if the functions of proteins can be identified.</p>
<pre><?php echo "Motifs would be displayed here but in this example there was none"  ?></pre>
</div>
<div id="pepstats" class="section" style="display:none;">
<!-- info from https://emboss.sourceforge.net/apps/cvs/emboss/apps/pepstats.html -->
<h3>PEPSTATS</h3>
<p> calculates statistics of protein properties such as number of residues and molecular weight for each sequence</p>
<div class="scroll-box">
<pre><?php echo htmlspecialchars(file_get_contents("data/jobs/job_267/stats.txt")); ?></pre>
</div>
</div>
<!-- ai adapted -->
<script>
function showSection(id) {
document.querySelectorAll('.section').forEach(function(s) {
s.style.display = 'none';
});
document.getElementById(id).style.display = 'block';
}
</script>
