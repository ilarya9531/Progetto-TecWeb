<?php
	require_once "php/header.php";
	require_once "php/footer.php";
	require_once "php/dbhandler.php";
	if (!isset($_SESSION)) {
		session_start();
	}
	if (!isset($_SESSION["username"])) {
		header("Location: login.php");
		exit();
	}
	if ($_SESSION["type"] == "admin") {
		header("Location: admin.php");
		exit();
	}
	$connessione = connessione();
	$username=$_SESSION["username"];
	$result=$connessione->query("SELECT * FROM utenti WHERE username='$username';");
	$imgUser=$connessione->query("SELECT * FROM foto JOIN utenti ON venditore=username WHERE venditore='$username';");
	if(!$result || !$imgUser || mysqli_num_rows($result)!=1){
		header("Location: 404.php");
		exit();
	}
	$utente=$result->fetch_assoc();
	$nome=$utente["nome"];
	$cognome=$utente["cognome"];
	$email=$utente["email"];
	
	function getImages(){
		global $imgUser;
		$img="<div class=\"foto\"><ul>";
		while($foto=$imgUser->fetch_assoc()){
			$idImg=$foto["id"];
			if(file_exists("upload/".$idImg.'.png')){
				$url="upload/".$idImg.'.png';
			}else if(file_exists("upload/".$idImg.'.jpg')){
					$url="upload/".$idImg.'.jpg';
			}else if(file_exists("upload/".$idImg.'.jpeg')){
					$url="upload/".$idImg.'.jpeg';
			}
			$titoloImg=$foto["titolo"];
			$prezzoImg=$foto["prezzo"];
			$statoImg=$foto["stato"];
			//QUANTITA' VENDUTE
			//MI PIACE RICEVUTI
			//VOLTE IN CUI E' STATA MESSA TRA I PREFERITI
			$img.="<li><img class=\"imgElement\" src=\"".$url."\" alt=\"".$foto["titolo"]."\"/></a><p>
				<strong>Titolo: </strong>".$titoloImg."<br/>
				<strong>Prezzo: </strong>".$prezzoImg." &euro;<br/>
				<strong>Stato: </strong>".$statoImg."<br/>
				</p></li>";
		}
		$img.="</ul></div>";
		return $img;
	}
	
	$output=file_get_contents("html/profile.html");
	$output=str_replace("<div id=\"header\"></div>", Header::build(), $output);
	$output=str_replace("<div id=\"footer\"></div>", Footer::build(), $output);
	$output=str_replace("<div class=\"foto\"/>",getImages(),$output);
	$output=str_replace("<meta/>",file_get_contents("html/meta.html"),$output);
	$output=str_replace("<div class=\"foto\"/>",getImages(),$output);
	$output=str_replace("%username%",$username,$output);
	$output=str_replace("%nome%",$nome,$output);
	$output=str_replace("%cognome%",$cognome,$output);
	$output=str_replace("%email%",$email,$output);
	
	echo $output;