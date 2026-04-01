<head>
<?php include ('includes/header.php'); ?>

</head>
<div class ="cred-box">
<strong>Header:</strong>
<p>Navbar for the wesbite used on all pages was adapted from https://www.youtube.com/watch?v=f3uCSh6LIY0. 
The inclusion of the header and Db_connect was adapted from https://stackoverflow.com/questions/18712338/make-header-and-footer-files-to-be-included-in-multiple-html-pages</p>
 
<strong> Database connection:</strong>
<p> this was adapted from week 3 mysql class code with AI modification (chatgpt) - local host was changed to 127.0.0.1 as I was facing connection issues.</p>

<strong>File structure:</strong>
<p>Ai was used to suggest the file stucture for the website files on the server aswell as directed learning 2a. This file stucture  was not stuck to very closely as the project developed.
Ai was also used to identify and produce the sql database tables, which again only provided a loose structure.</p>

<strong>real_search.php: (Form to be sent to run_analysis.php)</strong>
<p>Number type results for retrieving the max_results was adapted from https://www.w3schools.com/html/html_form_input_types.asp as well as the required function.
Additonally, filling in the query box with the example inputs was adapted from https://www.w3schools.com/js/tryit.asp?filename=tryjs_form_radio</p>

<strong>run_analysis: (inserts query into a table and sends values to results_real) </strong>
<p>pdo query was adapted from https://www.w3schools.com/php/php_mysql_prepared_statements.asp</p>

<strong>results_real: </strong>
<p>sanity check for query data was produced by AI which trims and lowers the query names to help with retrival. escapeshellarg was used to run shell commands in php adapted from https://www.php.net/manual/en/function.escapeshellarg.php
shell exec was also used from the same website. The query command was adapted from the read.me file of of Entrez Direct: E-utilities on the Unix Command Line. 
Fasta parser was used from https://www.biob.in/2017/09/extracting-multiple-fasta-sequences.html This originally was purposed to output a header and a sequence but AI was used to create an array and regex
 to separate the data for storage. AI was used for array slice to bebuild a clean fasta. 
 Which wouldn't have been necessary if Retmax worked. The final use of AI in this section to  write code to stop multiple instances of the same query being stored. Table was displayed using foreach function from 
 https://www.php.net/manual/en/control-structures.foreach.php</p>

<strong>Example:</strong>
<p>https://www.php.net/manual/en/control-structures.foreach.php was adapted again to loop through results in php. Info on Clustalo https://www.labxchange.org/library/items/lb:LabXchange:5b84cc84:html:1.
Info on plotcon info from https://www.bioinformatics.nl/cgi-bin/emboss/help/plotcon. Patmatmotifs info from https://emboss.bioinformatics.nl/cgi-bin/emboss/help/patmatmotifs. 
 info on pepstats from https://emboss.sourceforge.net/apps/cvs/emboss/apps/pepstats.html. The sections function to display the results was adapted using AI (function showsection)</p>

<strong>Previous analysis:</strong>
<p>for each loop to produce html/php table was adapted from https://www.php.net/manual/en/control-structures.foreach.php</p>
 
<strong>CSS stylesheet:</strong>
<p> https://htmlcheatsheet.com/css/ was used to produce the code for the title. The remaining code was adapted from https://www.youtube.com/watch?v=f3uCSh6LIY0 and https://www.w3schools.com/css/css_table_size.asp 
 </div>