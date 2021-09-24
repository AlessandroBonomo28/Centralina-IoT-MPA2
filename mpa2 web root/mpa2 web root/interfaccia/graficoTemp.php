<html>
<head>
	<title>Grafico di temperatura</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link rel="icon" href="img/Smart_Home_icon.png" />
    <link rel="stylesheet" href="../bootStrap/materialize.min.css">
    <link href="../bootStrap/icon.css" rel="stylesheet">
    <link href="../bootStrap/style.css" rel="stylesheet" type="text/css">
	<link rel="stylesheet" href="../bootStrap/bootstrap.min.css">
</head>
<body>
<ul class="sidenav sidenav-fixed">
    <li class="sidenav-select"><a href="situazione.php"><i class="material-icons icons-white">home</i>Situazione</a></li>
    <li class="sidenav-select active"><a href=""><i class="material-icons icons-white">ac_unit</i>Temperatura</a></li>
    <li class="sidenav-select"><a href="graficoUmi.php"><i class="material-icons icons-white">invert_colors</i>Umidità</a></li>
    <li class="sidenav-select"><a href="graficoRum.php"><i class="material-icons icons-white">volume_up</i>Rumore</a></li> 
</ul>
<nav class="navbar-wrapper admin-root blue lighten-2">
    <a href="" class="brand-logo center">MPA 2</a>
</nav>

<div class="container">
<form id="form1" name="form1" method="post">
<h1><a>Grafico di temperatura</a></h1>
<br>
<h3><a>Selezionare la centralina e il sensore</a></h3>
<div class="row">
    <div class="col-sm">
      <select id="SelCentralina" name="SelCentralina" 
			onchange="azioneSelCentralina()" class="form-control form-control-lg">
		 <option value="" selected disabled>Seleziona centralina</option>
		 <?php
			include "../connessione.php";
			$risultato = $mysqli->query("SELECT * FROM Centraline");
			if(mysqli_num_rows($risultato) > 0)
			{
				while($riga = mysqli_fetch_assoc($risultato))
				{
					echo "<option value='".$riga['id']."'>".$riga['nome']."</option>";
				}
			}
			$mysqli->close();
		 ?>
	  </select>
    </div>
    <div class="col-sm">
      <select id="SelSensore" name="SelSensore" 
		onchange="azioneSelSensore()" class="form-control form-control-lg">
		 <option value="" selected disabled>Seleziona sensore di temperatura</option>
	  </select>
    </div>
</div>
<h3><a>Selezionare la data</a></h3>
<div class="row">
    <div class="col-sm">
      <select id="SelAnno" name="SelAnno" 
		onchange="azioneSelAnno()" class="form-control form-control-lg">
		 <option value="" selected disabled>Seleziona anno</option>
	  </select>
    </div>
    <div class="col-sm">
      <select id="SelMese" name="SelMese" 
		onchange="azioneSelMese()" class="form-control form-control-lg">
		 <option value="" selected disabled>Seleziona mese</option>
	  </select>
    </div>
    <div class="col-sm">
      <select id="SelGiorno" name="SelGiorno" 
		onchange="azioneSelGiorno()" class="form-control form-control-lg">
		 <option value="" selected disabled>Seleziona giorno</option>
	  </select>
    </div>
</div>
</form>

<canvas id="cnvsGrafico"></canvas>
</div>
<script src="../bootStrap/jquery-3.3.1.min.js"></script>
<script src="../bootStrap/materialize.min.js"></script>
<script src="../bootStrap/Chart.bundle.js"></script>
<script  type="text/javascript">
// Array associativo per i nomi dei mesi
const nomiMesi = {1:"Gennaio", 2:"Febbraio", 3:"Marzo", 4:"Aprile", 5:"Maggio", 6:"Giugno",
				  7:"Luglio", 8:"Agosto", 9:"Settembre", 10:"Ottobre", 11:"Novembre", 12:"Dicembre"};

$(document).ready(function(){ // Inizializzazione pagina
	//alert("hello world");
	var SelCentralina = document.getElementById("SelCentralina");
	var SelSensore = document.getElementById("SelSensore");
	
	var SelAnno = document.getElementById("SelAnno");
	var SelMese = document.getElementById("SelMese");
	var SelGiorno = document.getElementById("SelGiorno");
	
	// Abilito solo la select centralina
	SelCentralina.disabled = false;
	SelSensore.disabled = true;
	SelAnno.disabled = true;
	SelMese.disabled = true;
	SelGiorno.disabled = true;
});
function azioneSelCentralina()
{
	var SelSensore = document.getElementById("SelSensore");
	var SelAnno = document.getElementById("SelAnno");
	var SelMese = document.getElementById("SelMese");
	var SelGiorno = document.getElementById("SelGiorno");
	// Disabilito le altre select
	SelAnno.disabled = true;
	SelMese.disabled = true;
	SelGiorno.disabled = true;
	// Pulisco le select (Lasciando solo il valore di default)
	SelSensore.length=1;
	SelSensore.options[0].selected='selected';
	SelAnno.length=1;
	SelAnno.options[0].selected='selected'; // Seleziona opzione
	SelMese.length=1;
	SelMese.options[0].selected='selected'; 
	SelGiorno.length=1;
	SelGiorno.options[0].selected='selected';
	var data = $("#form1").serialize();
	$.ajax({
		 data: data,
		 type: "post",
		 url: "queryAjax/queryGraficoTemp/sensoriTempDiUnaCentralina.php",
		 success: function(data){
			  //alert("Esito: " + data);
			  var sensori = JSON.parse(data);
			  // Sintassi : Option (nome ,valore , default selected , selected)
			  // Carico la select con i sensori della lista sensori
			  for(var i = 0; i < sensori.length; i++)
					SelSensore.add(new Option(sensori[i]["nome"],sensori[i]["id"],false,false));
			  // Abilito select sensori
			  SelSensore.disabled = false;
			  
		 },
		 error: function (jqXHR, exception) {
			  alert("C'e' stato un errore");
			  return;
		 }
	});
}
function azioneSelSensore()
{
	var SelAnno = document.getElementById("SelAnno");
	var SelMese = document.getElementById("SelMese");
	var SelGiorno = document.getElementById("SelGiorno");
	// Disabilito le altre select
	SelMese.disabled = true;
	SelGiorno.disabled = true;
	// Pulisco le select (Lasciando solo il valore di default)
	SelAnno.length=1;
	SelAnno.options[0].selected='selected'; // Seleziona opzione
	SelMese.length=1;
	SelMese.options[0].selected='selected'; 
	SelGiorno.length=1;
	SelGiorno.options[0].selected='selected';
	var data = $("#form1").serialize();
	$.ajax({
		 data: data,
		 type: "post",
		 url: "queryAjax/queryGraficoTemp/anniLettureSensoriTemp.php",
		 success: function(data){
			  //alert("Esito: " + data);
			  var anni = JSON.parse(data);
			  // Sintassi : Option (nome ,valore , default selected , selected)
			  // Carico la select con gli anni della lista anni
			  for(var i = 0; i < anni.length; i++)
					SelAnno.add(new Option(anni[i],anni[i],false,false));
			  // Abilito select anni
			  SelAnno.disabled = false;
		 },
		 error: function (jqXHR, exception) {
			  alert("C'e' stato un errore");
			  return;
		 }
	});
}
function azioneSelAnno()
{
	var SelMese = document.getElementById("SelMese");
	var SelGiorno = document.getElementById("SelGiorno");
	// Disabilito le altre select
	SelGiorno.disabled = true;
	// Pulisco le select (Lasciando solo il valore di default)
	SelMese.length=1;
	SelMese.options[0].selected='selected'; // Seleziona opzione
	SelGiorno.length=1;
	SelGiorno.options[0].selected='selected';
	var data = $("#form1").serialize();
	$.ajax({
		 data: data,
		 type: "post",
		 url: "queryAjax/queryGraficoTemp/mesiLettureSensoriTemp.php",
		 success: function(data){
			  //alert("Esito: " + data);
			  var mesi = JSON.parse(data);
			  // Sintassi : Option (nome ,valore , default selected , selected)
			  // Carico la select con i mesi della lista mesi
			  for(var i = 0; i < mesi.length; i++)
					SelMese.add(new Option(nomiMesi[mesi[i]],mesi[i],false,false));
			  // Abilito select mesi
			  SelMese.disabled = false;
		 },
		 error: function (jqXHR, exception) {
			  alert("C'e' stato un errore");
			  return;
		 }
	});
}
function azioneSelMese()
{
	var SelGiorno = document.getElementById("SelGiorno");	
	var data = $("#form1").serialize();
	// Pulisco le select (Lasciando solo il valore di default)
	SelGiorno.length=1;
	SelGiorno.options[0].selected='selected'; // Seleziona opzione
	$.ajax({
		 data: data,
		 type: "post",
		 url: "queryAjax/queryGraficoTemp/giorniLettureSensoriTemp.php",
		 success: function(data){
			  //alert("Esito: " + data);
			  var giorni = JSON.parse(data);
			  // Sintassi : Option (nome ,valore , default selected , selected)
			  // Carico la select con i giorni della lista giorni
			  for(var i = 0; i < giorni.length; i++)
					SelGiorno.add(new Option(giorni[i],giorni[i],false,false));
			  // Abilito select mesi
			  SelGiorno.disabled = false;
		 },
		 error: function (jqXHR, exception) {
			  alert("C'e' stato un errore");
			  return;
		 }
	});
}
// Variabile globale contenente le istanze dei grafici
var grafici = []; 
function azioneSelGiorno()
{
	var data = $("#form1").serialize();
	$.ajax({
		 data: data,
		 type: "post",
		 url: "queryAjax/queryGraficoTemp/graficoGiornalieroTemp.php",
		 success: function(data){
			  //alert("Esito: " + data);
			  var datiGrafico = JSON.parse(data);
			  var ore = [];
			  for(var i=0;i<datiGrafico.length;i++)ore[i] = datiGrafico[i]["ora"]+":00";
			  var temperature = [];
			  for(var i=0;i<datiGrafico.length;i++)temperature[i] = datiGrafico[i]["temperatura"];
			  var legenda = "Temperatura in Celsius";
			  var bgRgba = "rgba(255, 0, 0, 0.6)";
			  var borderRgba= "rgba(255, 0, 0, 0.8)";
			  var tipoGrafico = "line";
			  var utlimoGrafico = grafici.pop(); // Ottengo ultimo grafico disegnato
			  if(utlimoGrafico != null)utlimoGrafico.destroy(); // Distruggo l'ultimo grafico disegnato
			  var istanzaGrafico = creaGrafico("cnvsGrafico",temperature,ore,tipoGrafico,legenda,bgRgba,borderRgba);
			  grafici.push(istanzaGrafico);
		 },
		 error: function (jqXHR, exception) {
			  alert("C'e' stato un errore");
			  return;
		 }
	});
	
}
// Crea un grafico in un canvas e ne ritorna l'istanza
function creaGrafico(idCanvas,datiGrafico,labels,tipo,legenda,bgRgba,borderRgba)
{
	var ctx = document.getElementById(idCanvas).getContext('2d');
	var grafico = new Chart(ctx, {
				  type: tipo,
				  data: {
						labels: labels,
						datasets: [{
							label: legenda,
							data: datiGrafico,
							backgroundColor: [
							  bgRgba,
							],
							borderColor: [
							  borderRgba,
							],
							borderWidth: 2
						  }]
					   },
				  options: {
					responsive: true
				  }
				});
	return grafico;
}
</script>
</body>
</html>