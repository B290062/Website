<head>
<?php include ('includes/header.php'); ?>
<!-- the following code makes a form with taxonomy, protein and max_results which is posted to run_analysis -->
<h4> Query </h4>
</head>
<body>
<form action="run_analysis.php" method="POST">
<div>
<label for = "taxonomy"> Enter a taxonomic group: </label>
<input id = "taxonomy" type = "text" name = 'taxonomy' required/>
</div>
<div>
<label for = "protein"> Enter a protein family: </label>
<input id = "protein" type = "text" name = 'protein' required/>
</div>
<div>
<label for = "max_results">number of results: </label>
<!-- number type results adapted from https://www.w3schools.com/html/html_form_input_types.asp -->
<!-- required function wont allow the query database button to be pressed unless all three fields are entered -->
<input id = "max_results" type = "number" name = 'max_results' min="1" max="30"/ required value = 3>
</div>

<button>Query database</button>

<!-- code adapted from https://www.youtube.com/watch?v=lcA-yVUh-S8 -->


</form>

</body>