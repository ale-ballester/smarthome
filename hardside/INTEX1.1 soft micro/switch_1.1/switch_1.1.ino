/*
 *  This sketch sends data via HTTP GET requests to data.sparkfun.com service.
 *
 *  You need to get streamId and privateKey at data.sparkfun.com and paste them
 *  below. Or just customize this script to talk to other HTTP servers.
 *
 */

#include <string>
#include <ESP8266WiFi.h>
#include <WiFiClient.h>
#include <ESP8266WebServer.h>

char* stringToChar(String command){
    if(command.length()!=0){
        char *p = const_cast<char*>(command.c_str());
        return p;
    }
}

// GPI05 output
// White dot is programming mode

ESP8266WebServer server(80);

const char *id = "dev1";
const char *code = "code1";
const char *pass = "pass1";

char *ssid;
char *password;

const char* host = "smarthome-ale-ballester.c9users.io";

int manual = 12;
int output = 4;

int ssid_set = 0;

void handleForm(){
  String msg;
  if (server.hasHeader("Cookie")){   
    Serial.print("Found cookie: ");
    String cookie = server.header("Cookie");
    Serial.println(cookie);
  }
  if (server.hasArg("DISCONNECT")){
    Serial.println("Disconnection");
    String header = "HTTP/1.1 301 OK\r\nSet-Cookie: ESPSESSIONID=0\r\nLocation: /login\r\nCache-Control: no-cache\r\n\r\n";
    server.sendContent(header);
    return;
  }
  if (server.hasArg("ssid") && server.hasArg("password")){
    ssid = stringToChar(server.arg("ssid"));
    password = stringToChar(server.arg("password"));
    ssid_set = 1;
  }
  String content = "<html><body><form action='/login' method='POST'>To log in, please use : admin/admin<br>";
  content += "SSID:<input type='text' name='ssid' placeholder='ssid'><br>";
  content += "Password:<input type='password' name='password' placeholder='password'><br>";
  content += "<input type='submit' name='SUBMIT' value='Submit'></form>" + msg + "<br>";
  server.send(200, "text/html", content);
}

void setup() {
  Serial.begin(115200);
  delay(10);

  WiFi.softAP(id, code);
  IPAddress myIP = WiFi.softAPIP();

  server.on("/", handleForm);

  pinMode(output, OUTPUT);
  digitalWrite(output, HIGH);
  delay(1000);
  digitalWrite(output,LOW);
  delay(1000);

  
}

int value = 0;

void loop() {
      
    server.handleClient();
    if (ssid_set) {
      if (WiFi.status() != WL_CONNECTED) {
        Serial.println();
        Serial.println();
        Serial.print("Connecting to ");
        Serial.println(ssid);
        WiFi.begin(ssid, password);
      }
  
      while (WiFi.status() != WL_CONNECTED) {
        delay(500);
        Serial.print(".");
      }
    }
    if (WiFi.status() == WL_CONNECTED) {  
      delay(5000);
      ++value;
  
      String PostData;
      int state;
  
      Serial.print("connecting to ");
      Serial.println(host);
   
      // Use WiFiClient class to create TCP connections
      WiFiClient client;
      const int httpPort = 80;
  
      if (!client.connect(host, httpPort)) {
      Serial.println("connection failed");
      return;
      }
   
      // We now create a URI for the request
      String url = "/devices/index";
     
      Serial.print("Requesting URL: ");
      Serial.println(url);
  
      if (digitalRead(output) == HIGH) {
        state = 255;
      } else {
        state = 0;
      }
  
      PostData = String("id=") + id + "&" + "password=" + pass + "&" + "value=" + state;
  
      Serial.println(PostData);
    
      // This will send the request to the server
      client.print(String("POST ") + url + " HTTP/1.1\r\n" +
      "Host: " + host + "\r\n" +
      "Content-Type: application/x-www-form-urlencoded" + "\r\n" +
      "Content-Length:"  + PostData.length() + "\r\n" +
      "Connection: close\r\n\r\n" +
      PostData
    );
  
    Serial.println(String("POST ") + url + " HTTP/1.1\r\n" +
      "Host: " + host + "\r\n" +
      "Content-Type: application/x-www-form-urlencoded" + "\r\n" +
      "Content-Length:"  + PostData.length() + "\r\n" +
      "Connection: close\r\n\r\n" +
      PostData
    );
      unsigned long timeout = millis();
      
      while (client.available() == 0) {
      if (millis() - timeout > 5000) {
          Serial.println(">>> Client Timeout !");
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
        Serial.println("-----");
        Serial.println(result);
        if (result == "255") {
          digitalWrite(output, HIGH);
        } else {
          digitalWrite(output, LOW);
        }
      }
      }
   
      Serial.println();
      Serial.println("closing connection");
  }
}

