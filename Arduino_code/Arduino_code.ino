// LIBRARIES FOR MFRC522(RFID) AND NODEMCU ESP8266 V3
#include <ESP8266WiFi.h>
#include <ESP8266HTTPClient.h>
#include <SPI.h>
#include <MFRC522.h>

// DEFINE THE WEBSITE TO BE CONNECTED
#define HOST "itprofel.000webhostapp.com"  // Enter HOST URL without "http:// "  and "/" at the end of URL

// DEFINE THE WIFI TO BE CONNECTED BY ESP8266
#define WIFI_SSID "Twitter"                 // WIFI SSID here
#define WIFI_PASSWORD "kimetsunoyaiba5301"  // WIFI password here

// CONSTANT EXPRESSION FOR ESP8266 PINS
constexpr uint8_t RST_PIN = D3;  // Configurable, see typical pin layout above
constexpr uint8_t SS_PIN = D4;   // Configurable, see typical pin layout above

// DEFINE PINS FOR LED AND BUZZER
#define buzzer D0
#define green_led D2
#define red_led D1

MFRC522 rfid(SS_PIN, RST_PIN);  // Instance of the class
MFRC522::MIFARE_Key key;

String swiped_id;
String db_id;
String if_loggedin;  // could be YES or NO
String postData;
String postData1;

void setup() {
  Serial.begin(9600);  // BAUD RATE FOR SERIAL MONITOR
  Serial.println("Communication Started \n\n");
  delay(1000);

  SPI.begin();      // Init SPI bus
  rfid.PCD_Init();  // Init MFRC522

  pinMode(buzzer, OUTPUT);
  pinMode(green_led, OUTPUT);
  pinMode(red_led, OUTPUT);

  // CODE TO CONNECT TO WIFI
  WiFi.mode(WIFI_STA);
  WiFi.begin(WIFI_SSID, WIFI_PASSWORD);  //try to connect with wifi
  Serial.print("Connecting to ");
  Serial.print(WIFI_SSID);
  while (WiFi.status() != WL_CONNECTED) {
    Serial.print(".");
    delay(500);
  }
  Serial.println();
  Serial.print("Connected to ");
  Serial.println(WIFI_SSID);
  Serial.print("IP Address is : ");
  Serial.println(WiFi.localIP());  //print local IP address
  delay(30);
}


// ***********************************************************************

// ***********************************************************************


void loop() {

  if (!rfid.PICC_IsNewCardPresent())  // IF THERE IS NO CARD SWIPED, RETURN AND CONTINUE TO FIND CARD
    return;

  if (rfid.PICC_ReadCardSerial()) {  // IF CARD FOUND, CONTINUE THIS BLOCK
    for (byte i = 0; i < 4; i++) {
      swiped_id += rfid.uid.uidByte[i];
    }
    Serial.println("This is the swiped ID: " + swiped_id);

    // GET FUNCTION SHOULD BE HERE

    // CODE FROM ESP8266 LIBRARY
    HTTPClient http;     // http object of class HTTPClient
    WiFiClient wclient;  // wclient object of class WiFiClient

    http.begin(wclient, "http://itprofel.000webhostapp.com/dbwrite.php");  // CHANGE DBWRITE.PHP IF FILE NAME IS CHANGED OR MOVED
    int httpCode = http.GET();
   

    if (httpCode > 0 ) {
      if (httpCode == HTTP_CODE_OK) {

        String response = http.getString();
        db_id = response;  // Assign the entire response to db_id
        Serial.println("Received CID from database: " + db_id);

      } else {
        Serial.printf("[HTTP] GET... failed, error: %s\n", http.errorToString(httpCode).c_str());
        errorget();
      }
    } else {
      Serial.printf("[HTTP] Unable to connect\n");
      errorget();
    }

    // GET FUNCTION SHOULD BE HERE

    if (swiped_id == db_id) {
      if (digitalRead(green_led) == HIGH) {
        // LOG-OUT
        Serial.println("LOGGED OUT!");
        logoutSound();
        digitalWrite(green_led, LOW);
        noTone(buzzer);

        //  POST REQUEST FUNCTION CODE FOR UNLOCK.PHP

        HTTPClient http_post;     // http object of class HTTPClient

        postData = "swiped_id=" + swiped_id;  // "if_loggedin=" IS THE NAME, + if_loggedin is the value

        http_post.begin(wclient, "http://itprofel.000webhostapp.com/unlock.php");  // Connect to host where MySQL databse is hosted
        http_post.addHeader("Content-Type", "application/x-www-form-urlencoded");       //Specify content-type header

        int httpCode = http_post.POST(postData);  // Send POST request to php file and store server response code in variable named httpCode
        Serial.println("Value of swiped_id = " + swiped_id);

        if (httpCode == 200) {
          Serial.println("Values uploaded successfully.\n");
          Serial.println(httpCode);
        } else {  // if failed to connect then return and restart
          Serial.println(httpCode);
          Serial.println("Failed to upload values. \n");
          http_post.end();
          http.end();
          swiped_id = "";  // Empty the swiped_id variable for the next swipe
          return;
        }


        //
      } else {
        // AUTHORIZED
        digitalWrite(green_led, HIGH);  // Turn on green LED
        Serial.println("Authorized!");
        successSound();
        digitalWrite(red_led, LOW);  // Turn on the green LED

        if_loggedin = "YES";
        // LOGGED IN CODE TO POST

      // POST REQUEST FUNCTION CODE FOR LOCK.PHP

        HTTPClient http_post1;

        postData1 = "swiped_id=" + swiped_id; 

        // Update Host URL here:-

        http_post1.begin(wclient, "http://itprofel.000webhostapp.com/lock.php");  // Connect to host where MySQL databse is hosted
        http_post1.addHeader("Content-Type", "application/x-www-form-urlencoded");       //Specify content-type header

        int httpCode1 = http_post1.POST(postData1);  // Send POST request to php file and store server response code in variable named httpCode
        Serial.println("Value of swiped_id = " + swiped_id);

        // if connection eatablished then do this
        if (httpCode1 == 200) {
          Serial.println("Values uploaded successfully.\n");
          Serial.println(httpCode1);
        } else {  // if failed to connect then return and restart
          Serial.println(httpCode1);
          Serial.println("Failed to upload values. \n");
          http_post1.end();
          http.end();
          swiped_id = "";  // Empty the swiped_id variable for the next swipe
          return;
        }
        // POST REQUEST FUNCTION CODE
        // LOGGED IN CODE TO POST

      }
    } else if (digitalRead(green_led) == LOW) {  // Other cards only if green LED is off
      // UNAUTHORIZED
      Serial.println("Unauthorized!");
      digitalWrite(green_led, LOW);
      errorSound();
    }

    swiped_id = "";  // Empty the swiped_id variable for the next swipe
    rfid.PICC_HaltA();
    rfid.PCD_StopCrypto1();
  }
}


// ***********************************************************************
//              !!!  FUNCTIONS  !!!
// ***********************************************************************


void successSound() {
  tone(buzzer, 1000);  // High-pitched tone for success
  delay(500);
  noTone(buzzer);
}

void errorSound() {
  for (int i = 0; i < 3; i++) {  // Repeat 3 times
    tone(buzzer, 400);           // Lower-pitched tone for error
    digitalWrite(red_led, HIGH);
    delay(250);
    digitalWrite(red_led, LOW);
    noTone(buzzer);
    delay(250);
  }
}

void logoutSound() {
  for (int i = 0; i < 2; i++) {  // Repeat 2 times
    noTone(buzzer);
    digitalWrite(green_led, LOW);
    delay(100);
    tone(buzzer, 750);
    digitalWrite(green_led, HIGH);
    delay(100);
  }
}

void errorget(){
        digitalWrite(red_led, HIGH);  // Turn on the green LED
        tone(buzzer, 1250);  
        delay(50);
        noTone(buzzer);
        digitalWrite(red_led, LOW);
        digitalWrite(green_led, HIGH);  // Turn on green LED
        tone(buzzer, 400); 
        delay(50); 
        noTone(buzzer);
        digitalWrite(green_led, HIGH);
}