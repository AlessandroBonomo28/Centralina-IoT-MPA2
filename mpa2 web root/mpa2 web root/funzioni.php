<!-- Alessandro Bonomo 5D informatica a.s. 2018-2019 -->
<?php
function pulisciStringa($string)
{
  $string = str_replace("'", "", $string); //apici singoli
  $string = str_replace('"', "", $string);  //apici doppi
  //$string = str_replace(array("\n", "\t", "\r"), '', $string); //rimuovi tabs versione 2 
  //$string = preg_replace('/\s+/S', "", $string); //tabs
  //$string = preg_replace('/\t+/', '', $string); //tabs
  $string = preg_replace("/<!--.*?-->/", "", $string); //sequenze di escape e commenti html
  $string = preg_replace("/\/*.*?\*\//", "", $string); //comenti
  $string = strip_tags($string);  
  return ($string);
}

//Ritorna true se la prima data e' maggiore strettamente della seconda
function confrontaDate($annoD1,$meseD1,$giornoD1,$oraD1,$minutoD1,$secondoD1,
					   $annoD2,$meseD2,$giornoD2,$oraD2,$minutoD2,$secondoD2)
{
	// Assemblo data 1 per il confronto
	$data = array($annoD1,$meseD1,$giornoD1);
	$ora = array($oraD1,$minutoD1,$secondoD1);
	$d1 = date('Y/m/d H:i:s', strtotime(implode($data,"/")." ".implode($ora,":"))); 
	// Assemblo data 2 per il confronto
	$data = array($annoD2,$meseD2,$giornoD2);
	$ora = array($oraD2,$minutoD2,$secondoD2);
	$d2 = date('Y/m/d H:i:s', strtotime(implode($data,"/")." ".implode($ora,":"))); 
	// Confronto data 1 con data 2
	if($d1 > $d2)return true;
	else return false;
}
?>