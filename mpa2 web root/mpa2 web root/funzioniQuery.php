<!-- Alessandro Bonomo 5D informatica a.s. 2018-2019 -->
<?php
include "funzioni.php";
function queryCaricaTemperatura($idSensore,$temperatura)
{
	$idSensore = pulisciStringa($idSensore);
	$temperatura = pulisciStringa($temperatura);
	// Apertura connessione con db
	include "connessione.php";
	// Query inserimento temperatura 
	$sql="INSERT INTO Letture(temperatura,idSensore) values(".$temperatura.",".$idSensore.");";
	$result = $mysqli->query($sql);
	if (!$result) 
	{
		// La query di inserimento ha fallito
		echo "Questo sito sta avendo problemi.";
		echo "Ecco il perche' la query ha fallito: \n";
		echo "Query: " . $sql . "\n";
		echo "Errno: " . $mysqli->errno . "\n";
		echo "Error: " . $mysqli->error . "\n";
		return false;
	}
	// Chiusura connessione con db
	$mysqli->close();
	return true;
}
function queryCaricaUmidita($idSensore,$umidita)
{
	$idSensore = pulisciStringa($idSensore);
	$umidita = pulisciStringa($umidita);
	// Apertura connessione con db
	include "connessione.php";
	// Query inserimento umidita relativa 
	$sql="INSERT INTO Letture(umidita,idSensore) values(".$umidita.",".$idSensore.");";
	$result = $mysqli->query($sql);
	if (!$result) 
	{
		// La query di inserimento ha fallito
		echo "Questo sito sta avendo problemi.";
		echo "Ecco il perche' la query ha fallito: \n";
		echo "Query: " . $sql . "\n";
		echo "Errno: " . $mysqli->errno . "\n";
		echo "Error: " . $mysqli->error . "\n";
		return false;
	}
	// Chiusura connessione con db
	$mysqli->close();
	return true;
}
function queryCaricaRumore($idSensore,$rumore)
{
	$idSensore = pulisciStringa($idSensore);
	$rumore = pulisciStringa($rumore);
	// Apertura connessione con db
	include "connessione.php";
	// Query inserimento rumore	
	$sql="INSERT INTO Letture(rumore,idSensore) values(".$rumore.",".$idSensore.");";
	$result = $mysqli->query($sql);
	if (!$result) 
	{
		// La query di inserimento ha fallito
		echo "Questo sito sta avendo problemi.";
		echo "Ecco il perche' la query ha fallito: \n";
		echo "Query: " . $sql . "\n";
		echo "Errno: " . $mysqli->errno . "\n";
		echo "Error: " . $mysqli->error . "\n";
		return false;
	}
	// Chiusura connessione con db
	$mysqli->close();
	return true;
}
function queryCaricaTemperaturaUmidita($idSensore,$temperatura,$umidita)
{
	$idSensore = pulisciStringa($idSensore);
	$umidita = pulisciStringa($umidita);
	$temperatura = pulisciStringa($temperatura);
	// Apertura connessione con db
	include "connessione.php";
	// Query inserimento temperatura e umidita
	$sql="INSERT INTO Letture(temperatura,umidita,idSensore)
		  values(".$temperatura.",".$umidita.",".$idSensore.");";
	$result = $mysqli->query($sql);
	if (!$result) 
	{
		// La query di inserimento ha fallito
		echo "Questo sito sta avendo problemi.";
		echo "Ecco il perche' la query ha fallito: \n";
		echo "Query: " . $sql . "\n";
		echo "Errno: " . $mysqli->errno . "\n";
		echo "Error: " . $mysqli->error . "\n";
		return false;
	}
	// Chiusura connessione con db
	$mysqli->close();
	return true;
}
// Se la query fallisce ritorna falso altrimenti ritorna un array associativo
// con chiavi : anno, mese, giorno e ora.
function queryDataUltimaLettura()
{
	// Apertura connessione con db
	include "connessione.php";
	// Query anno mese giorno e ora in cui e' avvenuta l'ultima lettura
	$sql="select year(dataOra) as anno, month(dataOra) as mese,
		  day(dataOra) as giorno, hour(dataOra) as ora from
		  Letture order by id desc limit 1;";
	$result = $mysqli->query($sql);
	// Chiusura connessione con db
	$mysqli->close();
	if(!$result)return false;
	else return mysqli_fetch_assoc($result);
}
// Se la query fallisce ritorna falso altrimenti ritorna il risultato
// della query. 
function medieLettureUltimaOra()
{
	// Leggo la data dell'ultima lettura effettuata
	$dataUltimaLettura = queryDataUltimaLettura(); 
	// Apertura connessione con db
	include "connessione.php";
	// Query media delle letture avvenute nell'ultima ora 
	// in cui sono state effettuate letture, raggruppate per idSensore
	$sql="
			select avg(rumore) as mediaRum,avg(temperatura) as mediaTmp,
			avg(umidita) as mediaUmi,idSensore from Letture 
			where year(dataOra) like ".$dataUltimaLettura['anno']." and
			month(dataOra) like ".$dataUltimaLettura['mese']." and
			day(dataOra) like ".$dataUltimaLettura['giorno']." and
			hour(dataOra) like ".$dataUltimaLettura['ora']."
			group by idSensore;
		";
	$result = $mysqli->query($sql);
	// Chiusura connessione con db
	$mysqli->close();
	if(!$result)return false;
	else return $result;
}
// Se la query fallisce ritorna falso altrimenti ritorna vero.
function cancellaLettureUltimaOra()
{
	// Leggo la data dell'ultima lettura effettuata
	$dataUltimaLettura = queryDataUltimaLettura();
	// Apertura connessione con db
	include "connessione.php";
	// Query di cancellazione delle letture avvenute nell'ultima ora 
	// in cui sono state effettuate letture
	$sql="
			delete from Letture
			where year(dataOra) like ".$dataUltimaLettura['anno']." and
			month(dataOra) like ".$dataUltimaLettura['mese']." and
			day(dataOra) like ".$dataUltimaLettura['giorno']." and
			hour(dataOra) like ".$dataUltimaLettura['ora'].";
		";
	$result = $mysqli->query($sql);
	// Chiusura connessione con db
	$mysqli->close();
	if(!$result)return false;
	else return true;
}
function sostituisciLettureUltimaOraConMedia()
{
	// Leggo la data dell'ultima lettura effettuata
	$dataUltimaLettura = queryDataUltimaLettura(); 
	// Apertura connessione con db
	include "connessione.php";
	$mysqli->query("START TRANSACTION;"); // Inizio sezione critica
	// Query media delle letture avvenute nell'ultima ora 
	// in cui sono state effettuate letture, raggruppate per idSensore
	$sql="
			select avg(rumore) as mediaRum,avg(temperatura) as mediaTmp,
			avg(umidita) as mediaUmi,idSensore from Letture 
			where year(dataOra) like ".$dataUltimaLettura['anno']." and
			month(dataOra) like ".$dataUltimaLettura['mese']." and
			day(dataOra) like ".$dataUltimaLettura['giorno']." and
			hour(dataOra) like ".$dataUltimaLettura['ora']."
			group by idSensore;
		";
	// Salva le medie delle letture (e l'id del sensore che ha effettuato la lettura)
	$medieLettureSensori = $mysqli->query($sql);
	if(mysqli_num_rows($medieLettureSensori) > 0)
	{
		// Query di cancellazione delle letture avvenute nell'ultima ora 
		// in cui sono state effettuate letture
		$sql="
				delete from Letture
				where year(dataOra) like ".$dataUltimaLettura['anno']." and
				month(dataOra) like ".$dataUltimaLettura['mese']." and
				day(dataOra) like ".$dataUltimaLettura['giorno']." and
				hour(dataOra) like ".$dataUltimaLettura['ora'].";
			";
		// Cancella tutte le letture effettuate nell'ultima ora
		$mysqli->query($sql);
		// Crea data anno-mese-giorno (ora dell'ultima lettura):00:00
		$nuovaDataOra = 	$dataUltimaLettura['anno']
						."-".$dataUltimaLettura['mese']
						."-".$dataUltimaLettura['giorno']
						." ".$dataUltimaLettura['ora'].":00:00";
		$nuovaDataOra  = date("Y-m-d H:i:s", strtotime($nuovaDataOra));
		// Scrivo nella tabella "Lettura" le medie delle letture con la nuova 
		// dataOra e l'id del sensore che ha effettuato la lettura	
		while($riga = mysqli_fetch_assoc($medieLettureSensori))
		{
			if($riga['mediaTmp'] == "")$riga['mediaTmp']="null";
			if($riga['mediaUmi'] == "")$riga['mediaUmi']="null";
			if($riga['mediaRum'] == "")$riga['mediaRum']="null";
			$mysqli->query( "INSERT INTO Letture(dataOra,rumore,temperatura,umidita,idSensore)
							 values('".$nuovaDataOra."',".$riga['mediaRum'].",".$riga['mediaTmp'].",
							 ".$riga['mediaUmi'].",".$riga['idSensore'].");"); 
		}
	}
	$mysqli->query("COMMIT;"); // Fine sezione critica
	$mysqli->close();
}
?>