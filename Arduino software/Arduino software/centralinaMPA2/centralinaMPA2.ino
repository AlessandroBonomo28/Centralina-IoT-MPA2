#include<stdlib.h>
#include "DHT.h"
#include <SoftwareSerial.h>

#define SSID "" // nome della rete
#define PASS "" // password della rete
#define HOST "giordaniprogetti.altervista.org" // ip o nome di dominio del web server 
#define DHTPIN 7     // pin a cui è collegato il sensore DHT
const int ADMPIN = A0; // pin a cui è collegato il sensore ADMP401
#define DHTTYPE DHT22   // modello sensore DHT
#define Baud_Rate 115200 // velocità di comunicazione seriale con esp
#define LED_VERDE 3 //LED opzionale per il debugging
#define LED_ROSSO 4 //LED opzionale per il debugging

// pagina obiettivo per il metodo get
String GET = "GET /caricaDati.php";

// Parametri dei sensori associati alla centralina
const int idSensoreADM = 11;
const int idSensoreDHT = 10;

// parametri get:
String sintassiGetTemp = "?temperatura="; // sintassi parametro get temperatura
String sintassiGetUmi = "?umidita="; // sintassi parametro get umidita
String sintassiGetRum = "?rumore="; // sintassi parametro get rumore
// Id dei sensori come parametri get
String parGetIdSensoreDHT ="&idSensore="+String(idSensoreDHT);
String parGetIdSensoreADM ="&idSensore="+String(idSensoreADM);



// Istanze delle classi dei sensori
DHT dht(DHTPIN, DHTTYPE);

// creo seriale software (ss) per la comunicazione seriale con esp8266
SoftwareSerial ss(11,12); // rx tx software serial

void setup()
{
  Serial.begin(Baud_Rate); // seriale pc
  Serial.println("void setup");
  ss.begin(Baud_Rate);
  // connessione seriale con esp
  do
  {
    Serial.println("apro connessione seriale con esp...");
    delay(1000);
    ss.println("AT"); 
    delay(2000);
    /* 
     *  verifico apertura della connessione seriale con esp
     *  se esp risponde 'OK' la connessione seriale è stabilita
    */
    if(!(ss.find("OK")))// se connessione seriale con esp fallisce
    {
      Serial.println("connessione seriale con esp fallita..");
    }
  }
  while(ss.find("OK")); // ripeti finche non stabilisci connessione seriale con esp
  Serial.println("connessione seriale con esp riuscita");
  // connessione alla rete wifi configurata
  connettiWifi();
  //inizializzo il sensore DHT
  dht.begin();
  Serial.println("void loop");
}

void loop()
{
  // leggo temperatura e umidita dal sensore DHT 
  float umiditaDHT = dht.readHumidity();
  float tempDHT = dht.readTemperature();
  // mentre i valori letti dal sensore DHT sono nan (not a value)
  while (isnan(umiditaDHT) || isnan(tempDHT)) 
  {
    // stampa messaggio valori nan , attiva led rosso e leggi nuovi valori dal sensore
    Serial.println("valori nan"); 
    // Attiva solo led rosso
    disattivaLed(LED_VERDE);
    attivaLed(LED_ROSSO); 
    umiditaDHT = dht.readHumidity();
    tempDHT = dht.readTemperature();
  }
  
  // Stampo temperature lette
  Serial.print("Tmp DHT: ");
  Serial.println(tempDHT);
  Serial.print("Umi DHT: ");
  Serial.println(umiditaDHT);
  // Aggiornamento del web server con i nuovi valori
  bool aggiornato; // variabile booleana per indicare se la richiesta get è andata a buon fine
  do // AGGIORNAMENTO TEMPERATURA LETTA DAL SENSORE DHT
  {
    aggiornato = richiestaGET(sintassiGetTemp,String(tempDHT),parGetIdSensoreDHT);
    // aggiornato conterrà l'esito della richiesta get al web server
    //se l'aggiornamento NON è riuscito accendi LED rosso
    if(!aggiornato)
    {
      Serial.println("aggiornamento della temperatura letta dal DHT fallito");
      Serial.println("Provo a riconnettermi alla rete..");
      // Attiva solo led rosso
      disattivaLed(LED_VERDE);
      attivaLed(LED_ROSSO); 
      // riconnetti wifi
      connettiWifi();
    }
  }while(!aggiornato);
  Serial.println("aggiornamento della temperatura letta dal DHT riuscito");
  // Attiva solo led verde
  disattivaLed(LED_ROSSO);
  attivaLed(LED_VERDE); 

  do // AGGIORNAMENTO UMIDITA LETTA DAL SENSORE DHT
  {
    aggiornato = richiestaGET(sintassiGetUmi,String(umiditaDHT),parGetIdSensoreDHT);
    // aggiornato conterrà l'esito della richiesta get al web server
    //se l'aggiornamento NON è riuscito accendi LED rosso
    if(!aggiornato)
    {
      Serial.println("aggiornamento dell'umidita letta dal DHT fallito");
      Serial.println("Provo a riconnettermi alla rete..");
      // Attiva solo led rosso
      disattivaLed(LED_VERDE);
      attivaLed(LED_ROSSO); 
      // riconnetti wifi
      connettiWifi();
    }
  }while(!aggiornato);
  Serial.println("aggiornamento dell'umidita letta dal DHT riuscito");
  // Attiva solo led verde
  disattivaLed(LED_ROSSO);
  attivaLed(LED_VERDE);  
  // Per 12 volte : leggi la rumorosita', caricala sul web server e attendi 1 min.
  // (Al termine del ciclo saranno passati 12 min)
  for(int i=0;i<12;i++)
  {
    // leggo ampiezza peak to peak dal microfono
    int micPTPAmp = ottieniPTPAmp(ADMPIN);
    float decibel = 20.0  * log (micPTPAmp+1.);// converto ampiezza in decibel
    
    do // AGGIORNAMENTO RUMOROSITA' LETTA DAL SENSORE ADM
    {
      aggiornato = richiestaGET(sintassiGetRum,String(decibel),parGetIdSensoreADM);
      // aggiornato conterrà l'esito della richiesta get al web server
      //se l'aggiornamento NON è riuscito accendi LED rosso
      if(!aggiornato)
      {
        Serial.println("aggiornamento dell'umidita letta dal DHT fallito");
        Serial.println("Provo a riconnettermi alla rete..");
        // Attiva solo led rosso
        disattivaLed(LED_VERDE);
        attivaLed(LED_ROSSO); 
        // riconnetti wifi
        connettiWifi();
      }
    }while(!aggiornato);
    Serial.println("aggiornamento dell'umidita letta dal DHT riuscito");
    // Attiva solo led verde
    disattivaLed(LED_ROSSO);
    attivaLed(LED_VERDE);  
    delay(60000); // Attendi 1 min
  }
}

bool richiestaGET(String sintassiParGet , String valorePar, String parGetIdSensore)
{
  //inizializza stringa comando AT
  String cmd = "AT+CIPSTART=\"TCP\",\"";
  //concatena nome HOST e porta
  cmd += HOST;
  cmd += "\",80";
  
  //connetti
  ss.println(cmd);
  delay(2000);
  if(ss.find("Error")) //se connessione fallisce ritorna falso
  {
    return false;
  }
  // costruisco comando get
  cmd = GET; // comando get + pagina obiettivo
  cmd += sintassiParGet; // concatena sintassi parametro
  cmd += valorePar; // concatena valore parametro
  cmd += parGetIdSensore;
  cmd = cmd + " HTTP/1.1\r\nHost: "+ HOST +"\r\n\r\n"; // concatena versione http e nome host
  cmd += "Connection: keep-alive\r\n"; // concatena opzione "mantieni la connessione con l'host aperta"
  // comando at per indicare la lunghezza del prossimo comando
  ss.print("AT+CIPSEND=");
  ss.println(cmd.length());
  if(ss.find(">"))
  {
    // invia comando get
    ss.print(cmd);
  }else
  {
    ss.println("AT+CIPCLOSE");
  }
  
  if(ss.find("OK"))
  {
    //successo! Il valore recente dovrebbe essere online.
    return true;
  }else
  {
    return false;
  }
}

boolean tentaConnessioneWifi()
{
  //imposta ESP8266 in modalità comandi AT
  ss.println("AT+CWMODE=1");
  delay(2000);

  //crea comando di connessione con i valori wifi configurati
  String cmd="AT+CWJAP=\"";
  cmd+=SSID;
  cmd+="\",\"";
  cmd+=PASS;
  cmd+="\"";
  
  //connettiti alla rete wifi e aspetta 5 secondi
  ss.println(cmd);
  delay(2000);
  
  //se connesso ritorna vero, altrimenti falso
  if(ss.find("OK"))
  {
    return true;
  }
  else
  {
    return false;
  }
}

// Tenta finche' non stabilisce una connessione wifi
void connettiWifi()
{
   bool connesso;
    do
    {
      Serial.println("Provo a connettermi a wifi...");
      connesso = tentaConnessioneWifi();
      if(!connesso)Serial.println("tentativo di connessione a wifi fallito");
    }
    while(!connesso);
    Serial.println("connesso a wifi");
}

void attivaLed(int led)
{
  // attiva led
  digitalWrite(led, HIGH);  
}
void disattivaLed(int led)
{
  // disattiva led
  digitalWrite(led, LOW);  
}


// Restituisce l'ampiezza Peak-to-Peak letta dal microfono
int ottieniPTPAmp(int pinMic)
{
// Variabili di tempo per il campionamento
   unsigned long tempoInizio= millis();  // Ottengo l'istante di inizio
   unsigned int PTPAmp = 0; // Inizializzo variabile ampiezza Peek to peek
   int tempoCampione = 50; // tempo di campionamento audio in ms 
// Signal variables to find the peak-to-peak amplitude
   unsigned int maxAmp = 0;
   unsigned int minAmp = 1023;
   int micOut = 0;
  
// Trova il massimo e il minimo dell'output 
// del microfono nella finestra di tempo di 50 ms
   while(millis() - tempoInizio < tempoCampione) 
   {
      micOut = analogRead(pinMic);
      if( micOut < 1023) //previeni letture errate
      {
        if (micOut > maxAmp)
        {
          maxAmp = micOut; //salva solo la lettura massima
        }
        else if (micOut < minAmp)
        {
          minAmp = micOut; //salva solo la lettura minima
        }
      }
   }

  PTPAmp = maxAmp - minAmp; // (max amp) - (min amp) = ampiezza peak-to-peak 
  //double micOut_Volts = (PTPAmp * 3.3) / 1024; // Converti ADC in voltaggio (non usato)
  // Ritorna l'ampiezza PTP 
  return PTPAmp;   
}
