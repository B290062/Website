<?php
include('includes/header.php');
require("includes/db_connect.php");

$sql = "SELECT job_id, protein_family, taxon_name, status, max_sequences, created_at FROM analysis_jobs WHERE status = 'Complete' ORDER BY created_at DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute();

$results = $stmt -> fetchAll(PDO::FETCH_ASSOC);
?>
<table border = "1">
<tr>
<th> Job ID</th>
<th> Protein family</th>
<th> Taxon name </th>
<th> Max sequences</th>
<th> Created at</th>
<th> Status </th>
<th> View </th>
</tr>
<?php
foreach ($results as $job) {
?>
<tr>
    <td><?php echo htmlspecialchars($job['job_id']); ?></td>
    <td><?php echo htmlspecialchars($job['protein_family']); ?></td>
    <td><?php echo htmlspecialchars($job['taxon_name']); ?></td>
    <td><?php echo htmlspecialchars($job['max_sequences']); ?></td>
    <td><?php echo htmlspecialchars($job['created_at']); ?></td>
    <td><?php echo htmlspecialchars($job['status']); ?></td>
    <td>
        <a href="clustalo.php?job_id=<?php echo $job['job_id']; ?>">View</a>
    </td>
</tr>
<?php
}
?>

</table>