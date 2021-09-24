<!-- Alessandro Bonomo 5D informatica a.s. 2018-2019 -->
<?php
include "funzioniQuery.php";
// Controllo che il sensore sia impostato
if(!isset($_GET["idSensore"]))die("Sensore non impostato");
$idSensore = $_GET["idSensore"];
// Ottengo data attuale
$annoAttuale = date("Y");
$meseAttuale = date("n");
$giornoAttuale = date("j");
$oraAttuale = date("G");
// Ottengo data dell'ultima lettura
$dataUltimaLettura = queryDataUltimaLettura();
// Se la data attuale (anno-mese-giorno ora) supera la data dell'ultima lettura
if(confrontaDate($annoAttuale, $meseAttuale, $giornoAttuale, $oraAttuale,0,0,
   $dataUltimaLettura['anno'],$dataUltimaLettura['mese'],$dataUltimaLettura['giorno'],$dataUltimaLettura['ora'],0,0))
{
		sostituisciLettureUltimaOraConMedia();
}
// Casi parametri get :
if(isset($_GET["temperatura"]) && isset($_GET["umidita"])) // Caso temperatura e umidita' impostati
{
	$temperatura = $_GET["temperatura"];
	$umidita = $_GET["umidita"];
	// Caricamento temperatura e umidita ricevute in get nel db
	$esito = queryCaricaTemperaturaUmidita($idSensore,$temperatura,$umidita);
	if($esito == TRUE)echo "<h1>Caricamento temperatura e umidita riuscito</h1>";
	else echo "<h1>Caricamento temperatura e umidita fallito</h1>";
}
else if(isset($_GET["temperatura"])) // Caso temperatura impostata
{
	$temperatura = $_GET["temperatura"];
	// Caricamento temperatura ricevuta in get nel db
	$esito = queryCaricaTemperatura($idSensore,$temperatura);
	if($esito == TRUE)echo "<h1>Caricamento temperatura riuscito</h1>";
	else echo "<h1>Caricamento temperatura fallito</h1>";
}
else if(isset($_GET["umidita"])) // Caso umidita' impostata
{
	$umidita = $_GET["umidita"];
	// Caricamento umidita ricevuta in get nel db
	$esito = queryCaricaUmidita($idSensore,$umidita);
	if($esito == TRUE)echo "<h1>Caricamento umidita riuscito</h1>";
	else echo "<h1>Caricamento umidita fallito</h1>";
}
else if(isset($_GET["rumore"])) // Caso rumore impostato
{
	$rumore = $_GET["rumore"];
	// Caricamento rumore ricevuto in get nel db
	$esito = queryCaricaRumore($idSensore,$rumore);
	if($esito == TRUE)echo "<h1>Caricamento rumore riuscito</h1>";
	else echo "<h1>Caricamento rumore fallito</h1>";
}
else echo "<h1>I parametri get non sono stati impostati correttamente</h1>";

?>