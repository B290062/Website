
<?php include ('includes/header.php'); ?>
<!-- the following code makes a form with taxonomy, protein and max_results which is posted to run_analysis -->
<body>
<div class ="query-container">
<h4> Custom Query </h4>
<form action="run_analysis.php" method="POST">
<div>
<label for = "taxonomy"> Enter a taxonomic group: </label>
<input id = "taxonomy" type = "text" name = 'taxonomy' required>
</div>
<div>
<label for = "protein"> Enter a protein family: </label>
<input id = "protein" type = "text" name = 'protein' required>
</div>
<div>
<label for = "max_results">Number of results: </label>
<!-- number type results adapted from https://www.w3schools.com/html/html_form_input_types.asp -->
<!-- required function wont allow the query database button to be pressed unless all three fields are entered -->
<input id = "max_results" type = "number" name = 'max_results' min="1" max="30" required>
</div>
<div>
<button>Query database</button>
<button onclick="examplevalues()">Use Example</button>
</div>
<p> <b>NOTE-</b> There's a small chance that the example query will not return any results. if this happens please just refresh the page and try again. Thanks!
<!-- code adapted from https://www.youtube.com/watch?v=lcA-yVUh-S8 -->


</form>
<!-- adapted from https://www.w3schools.com/js/tryit.asp?filename=tryjs_form_radio -->
<script>
function examplevalues(){
document.getElementById("taxonomy").value= "Aves"
document.getElementById("protein").value= "glucose-6-phosphatase"
document.getElementById("max_results").value= "3"
}
</script>
</body>