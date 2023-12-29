
 // LIBRARIES FOR MFRC522(RFID) AND NODEMCU ESP8266 V3
#include <ESP8266WiFi.h>
#include <ESP8266HTTPClient.h>
#include <SPI.h> 
#include <MFRC522.h> 

// DEFINE THE WEBSITE TO BE CONNECTED
#define HOST "itprofel.000webhostapp.com"          // Enter HOST URL without "http:// "  and "/" at the end of URL

// DEFINE THE WIFI TO BE CONNECTED BY ESP8266
#define WIFI_SSID "Twitter"                       // WIFI SSID here                                   
#define WIFI_PASSWORD "kimetsunoyaiba5301"        // WIFI password here

// CONSTANT EXPRESSION FOR ESP8266 PINS
constexpr uint8_t RST_PIN = D3;     // Configurable, see typical pin layout above
constexpr uint8_t SS_PIN = D4;     // Configurable, see typical pin layout above

MFRC522 rfid(SS_PIN, RST_PIN); // Instance of the class
MFRC522::MIFARE_Key key;


// DECLARE GLOBAL VARIABLES
String tag;     //variable to store tag id
String postData;  // variable to store the uid variable that also stars the tag variable to post to php script


void setup() {
  Serial.begin(9600); // BAUD RATE FOR SERIAL MONITOR
  Serial.println("Communication Started \n\n");  
  delay(1000);

  SPI.begin(); // Init SPI bus
  rfid.PCD_Init(); // Init MFRC522

  pinMode(LED_BUILTIN, OUTPUT);     // initialize built in led on the board
 

// CODE TO CONNECT TO WIFI
WiFi.mode(WIFI_STA);           
WiFi.begin(WIFI_SSID, WIFI_PASSWORD);          //try to connect with wifi
Serial.print("Connecting to ");
Serial.print(WIFI_SSID);
while (WiFi.status() != WL_CONNECTED) 
{ Serial.print(".");
    delay(500); }

Serial.println();
Serial.print("Connected to ");
Serial.println(WIFI_SSID);
Serial.print("IP Address is : ");
Serial.println(WiFi.localIP());    //print local IP address

delay(30);
}



void loop() {

  // 
  if ( ! rfid.PICC_IsNewCardPresent())    // IF THERE IS NO CARD SWIPED, RETURN AND CONTINUE TO FIND CARD
    return;                               
  if (rfid.PICC_ReadCardSerial()) {       // IF CARD IS SWIPED, LOOP THROUGH UID OF CARD AND APPEND TO TAG VARIABLE
    for (byte i = 0; i < 4; i++) {
      tag += rfid.uid.uidByte[i];
    }
   
 Serial.println(tag);

// CODE FROM ESP8266 LIBRARY
HTTPClient http;    // http object of class HTTPClient
WiFiClient wclient; // wclient object of class WiFiClient   

String uid = tag;  
 
postData = "uid=" + uid; // with apostrophe should be the column name from the table which is 'uid'

// Update Host URL here:-  
http.begin(wclient, "http://itprofel.000webhostapp.com/dbwrite.php");              // Connect to host where MySQL databse is hosted
http.addHeader("Content-Type", "application/x-www-form-urlencoded");            //Specify content-type header

  
int httpCode = http.POST(postData);   // Send POST request to php file and store server response code in variable named httpCode
Serial.println("Values are, uid = " + uid);


// if connection eatablished then do this
if (httpCode == 200) { 
Serial.println("Values uploaded successfully."); 
Serial.println(httpCode); 
String webpage = http.getString();    // Get html webpage output and store it in a string
Serial.println(webpage + "\n"); 
}

// if failed to connect then return and restart

else { 
  Serial.println(httpCode); 
    uid = "";         // uid variable emptied for the next card to be swiped
    tag = "";         // tag variable emptied for the next card to be swiped
  Serial.println("Failed to upload values. \n"); 
  http.end(); 
  return; }


delay(3000); 
digitalWrite(LED_BUILTIN, LOW);
delay(3000);
digitalWrite(LED_BUILTIN, HIGH);


    uid = "";         // uid variable emptied for the next card to be swiped
    tag = "";         // tag variable emptied for the next card to be swiped
    rfid.PICC_HaltA();
    rfid.PCD_StopCrypto1();
  }
}