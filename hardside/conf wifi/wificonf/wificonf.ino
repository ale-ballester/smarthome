#include <ESP8266WiFi.h>
#include <WiFiClient.h> 
#include <ESP8266WebServer.h>

const char* id = "dev1";
const char* code = "code1";
const char* pass = "pass1";

const char *ssid = "dev1";
const char *password = "intemcode1";

const char* host = "intex01.16mb.com";

char wifiname[50];
char wifipass[50];
boolean ssid_set = false;

boolean reset = true;

int manual = 12;
int output = 5;

char* stringToChar(String command){
    if(command.length()!=0){
        char *p = const_cast<char*>(command.c_str());
        return p;
    }
}

ESP8266WebServer server(80);

void handleRoot() {
  if (server.hasArg("wifiname") && server.hasArg("wifipass")){
    server.arg("wifiname").toCharArray(wifiname, server.arg("wifiname").length() + 1);
    server.arg("wifipass").toCharArray(wifipass, server.arg("wifipass").length() + 1);
    ssid_set = true;
    reset = false;
    Serial.println(ssid_set);
    Serial.println("Here we go...");
  }
  String content = "<html><body><form action='' method='POST'>Ingrese los datos de su red wifi<br>";
  content += "Nombre:<input type='text' name='wifiname' placeholder='Nombre'><br>";
  content += "Contrasena:<input type='password' name='wifipass' placeholder='Contrasena'><br>";
  content += "<input type='submit' name='SUBMIT' value='Submit'></form><br></body></html>";
  server.send(200, "text/html", content);
}

void setup() {
  Serial.begin(115200);
  
  WiFi.softAP(ssid, password);

  IPAddress myIP = WiFi.softAPIP();
  server.on("/", handleRoot);
  server.begin();
}

void loop() {
  while (reset) {
    server.handleClient();
  }
  if (ssid_set) {
    Serial.println("Entering connection phase...");
    WiFi.begin(wifiname, wifipass);
    Serial.println(wifiname);
    Serial.println(wifipass);
    while (WiFi.status() != WL_CONNECTED) {
      delay(500);
      Serial.print(".");
    }
    int value = 0;
    while (WiFi.status() == WL_CONNECTED) {
      Serial.println("Connected");
      delay(5000);
      ++value;
  
      String PostData;
      int state;
   
      // Use WiFiClient class to create TCP connections
      WiFiClient client;
      const int httpPort = 80;
  
      if (!client.connect(host, httpPort)) {
        return;
      }
   
      // We now create a URI for the request
      String url = "/devices/index";
  
      if (digitalRead(output) == HIGH) {
        state = 255;
      } else {
        state = 0;
      }
  
      PostData = String("id=") + id + "&" + "password=" + pass + "&" + "value=" + state;
      
      // This will send the request to the server
      client.print(String("POST ") + url + " HTTP/1.1\r\n" +
      "Host: " + host + "\r\n" +
      "Content-Type: application/x-www-form-urlencoded" + "\r\n" +
      "Content-Length:"  + PostData.length() + "\r\n" +
      "Connection: close\r\n\r\n" +
      PostData
    );
    
      unsigned long timeout = millis();
      
      while (client.available() == 0) {
      if (millis() - timeout > 5000) {
          client.stop();
          return;
      }
      }
   
      // Read all the lines of the reply from server and print them to Serial
      while(client.available()){
      String line = client.readStringUntil('\r');
      Serial.println(line);
      if (String(line.charAt(1)) == String("<")) {
        String result = line.substring(2, line.length() - 1);
        if (result == "255") {
          digitalWrite(output, HIGH);
        } else {
          digitalWrite(output, LOW);
        }
      }
      }
    }  
  }
}
