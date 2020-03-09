<?php
	require_once "php/header.php";
	require_once "php/footer.php";

	$output=file_get_contents("html/faq.html");
	$output=str_replace("<meta/>", file_get_contents("html/meta.html"), $output);
	$output=str_replace("<div id=\"header\"></div>", Header::build(), $output);
	$output=str_replace("<div id=\"footer\"></div>", Footer::build(), $output);
	$output=str_replace("href=\"faq.php\"", "", $output);

	echo $output;
?>