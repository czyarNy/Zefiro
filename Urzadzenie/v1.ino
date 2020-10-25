#include <WiFi.h>
#include "BluetoothSerial.h"
#include <HTTPClient.h>
#include <FastLED.h>
#include <EEPROM.h>
#include "DHT.h"
#include <SoftwareSerial.h>
#include <Wire.h>
#include <Adafruit_Sensor.h>
#include <Adafruit_BME280.h>

BluetoothSerial SerialBT; 
 
DHT dht(5, DHT11);
HTTPClient http;

Adafruit_BME280 bme; // I2C
CRGBPalette16 currentPalette;
TBlendType    currentBlending;


HTTPClient http1;
HTTPClient http2;



String apiKeyValue = "tPmAT5Ab3j7F9";
String device_id = "ep1234";
String error_id = "0";
int cnt = 0;
String msg;
char* ssidall = new char [50];
char* passall = new char [50];
String BtDeviceName = "Zefiro1234";
int btState = 0;


 struct pms5003data {
  uint16_t framelen;
  uint16_t pm10_standard, pm25_standard, pm100_standard;
  uint16_t pm10_env, pm25_env, pm100_env;
  uint16_t particles_03um, particles_05um, particles_10um, particles_25um, particles_50um, particles_100um;
  uint16_t unused;
  uint16_t checksum;
};
const int NUM_LEDS = 144;
struct pms5003data data;
 CRGB leds[NUM_LEDS];
 CRGB alert[8];
SoftwareSerial pmsSerial(4, 2);
int startbit;


void setup() {
  WiFi.begin("", "");
   startbit = 1;
  dht.begin();
  bool status;
  Serial.begin(115200);  
  http2.begin("http://zefiro.pl/app/post-esp-data.php");
 FastLED.addLeds<WS2813, 19>(leds,144);
 FastLED.addLeds<WS2813, 12>(alert,8);
 
  EEPROM.begin(150);
  status = bme.begin(0x76);  
  pmsSerial.begin(9600);

    

}

//SEKCJA WIFI
//SEKCJA WIFI
//SEKCJA WIFI
//SEKCJA WIFI
String ctts(char* in)
{
  String out = "";
  for (int i = 0; i < sizeof(in) / sizeof(char); i++)
    out = out + in[i];
  return out;
}
void alertled(int in)
{
  if(in == 1)
  fill_solid(alert, 8, CRGB(0,255,0));
  if(in == 0)
  fill_solid(alert, 8, CRGB(0,0,0));
  FastLED.show();
  return;
}

void saveEE(String ssid, String pass)
{
  EEPROM.write(0, (int)byte(ssid.length() + 1));
  EEPROM.commit();
  int i = 0;
  int j = 0;
  for (i = 1; j < ssid.length() + 1; i++, j++)
  {
    EEPROM.write(i, (int)byte((int)(char)ssid[j]));
    EEPROM.commit();
  }

  i++;
  EEPROM.write(i, pass.length() + 1);
  EEPROM.commit();
  j = 0;
  for (i; j < pass.length(); i++, j++)
  {
    Serial.println(i);
    EEPROM.write(i, (int)byte((int)(char)pass[j]));
    EEPROM.commit();
  }
  Serial.println("ASD");
  return;
}

void readEE()
{

  String ssid = "";
  int i;
  for (i = 1; i < 100; i++)
  {
    if (EEPROM.read(i) == NULL)
      break;
    ssid = ssid + (char)EEPROM.read(i);
  }

  String pass = "";
  i += 2;

  i++;
  for (i; i < 100; i++)
  {
    if (EEPROM.read(i - 1) == NULL)
      break;
    pass = pass + (char)EEPROM.read(i - 1);
  }

  char* nazwa = new char [50];
  char* haslo = new char [50];
  ssid.toCharArray(ssidall, 50);
  pass.toCharArray(passall, 50);


}


bool WiFiCheck();


String BtIn()
{

  msg = "";
  while (SerialBT.available()  > 0)
  {

    msg = msg + (char)SerialBT.read();
  }
  //prs(msg);
  return msg;
}

void BtHandler(int onf)
{
  if (onf == 1)
  {
    SerialBT.begin(BtDeviceName);
    btState = 1;
  }
  if (onf == 0)
  {
    delay(2000);
    SerialBT.end();
    btState = 0;
  }

}

bool WiFiCheck()
{
  if (WiFi.status() == WL_CONNECTED)
    return 1;
  else
    return 0;
}

bool WiFiConnect(char* ssid, char* pass)
{

  WiFi.begin(ssid, pass);
  int start = millis() / 1000;
  while (WiFi.status() != WL_CONNECTED) {
    delay(1000);
    Serial.print(".");
    if (millis() / 1000 - start == 60)
      break;
  }

  if (WiFi.status() == WL_CONNECTED)
  {
    saveEE(ssid, pass);
    Serial.println("ASDASD");
    if (btState == 1)
      SerialBT.write(1);
    Serial.println("DSADASA");
    BtHandler(0);
    Serial.println("DSADASA");
    Serial.println("Polaczono!");
  }
  else
  {
    Serial.println("Nie Polaczono!");
    if (btState == 1)
      SerialBT.write(0  );

  }
  return WiFiCheck();

}

void prs(String in)
{
  String ssid = "", pass = "";
  int isAll = 0;
  char aktualny;
  int iterator = 0;
  int poziomD = 0;

  while (isAll == 0)
  {
    aktualny = msg[iterator];
    if (aktualny == '"')
    {
      iterator++;
      aktualny = msg[iterator];
      if (poziomD == 0)
      {

        while (aktualny != '"')
        {

          ssid = ssid + aktualny;
          iterator++;
          aktualny = msg[iterator];
        }
        poziomD++;
      }
      else if (poziomD == 1)
      {
        while (aktualny != '"')
        {


          pass = pass + aktualny;
          iterator++;
          aktualny = msg[iterator];
        }
        poziomD++;
      }
      if (poziomD == 2)
        isAll = 1;

      iterator++;

    }
    else
      iterator++;
  }
  ssid.toCharArray(ssidall, 50);
    pass.toCharArray(passall, 50);
  WiFiConnect(ssidall, passall);
}

void brakWiFi()
{
  alertled(1);
  Serial.println("ASD");
  BtHandler(1);
  while (!SerialBT.available());
  return;
}
//SEKCJA LED
//SEKCJA LED
//SEKCJA LED
void ZPaletyNaLeda( uint8_t colorIndex)
{
  
    
    for( int i = 0; i < NUM_LEDS; i++) {
        leds[i] = ColorFromPalette( currentPalette, colorIndex, 255, currentBlending);
        colorIndex += 3;
    }
    FastLED.show();
}
void paleta(int trybp)
{
  if(trybp == 1)
  {
  currentPalette = RainbowColors_p; 
   currentBlending = LINEARBLEND; 
  }
  if(trybp == 2)
  {
    currentPalette = RainbowStripeColors_p; 
   currentBlending = NOBLEND; 
  }
  if(trybp == 3)
  {
    currentPalette = RainbowStripeColors_p; 
   currentBlending = LINEARBLEND; 
  }
  if(trybp == 4)
  {
    currentBlending = LINEARBLEND; 
  }
  static uint8_t startIndex = 0;
    startIndex = startIndex + 1; 
    
    ZPaletyNaLeda(startIndex);
    
    FastLED.show();
    delay(10);
}
void col(int r, int g, int b)
{
  fill_solid(leds, NUM_LEDS, CRGB(r,g,b));
  FastLED.show();
}

int delwpix = 0;
void fade(int numerfade)
{
  int i = numerfade;
  Serial.println(i);
  if(i<256)
  {
  fill_solid(leds, NUM_LEDS, CRGB(i, 255-i, 0));
FastLED.show();
  }
  if(i>=256 && i<512 )
  {
    i = i - 256;
  fill_solid(leds, NUM_LEDS, CRGB(255-i, 0, i));
FastLED.show();
  }
  if(i>=512 && i<768)
  {
    i = i - 512;
  fill_solid(leds, NUM_LEDS, CRGB(0, i, 255-i));
  FastLED.show();
  }
}
float SaveMillisFadeF=0;


String hex="ffffff", tryb="0",tryb_before="0",hex_before;
void getSettings(String in)
{
  String pom = in.substring(8);
  hex = pom.substring(0,pom.indexOf('"'));
  pom = pom.substring(pom.indexOf('"')+1);
  tryb = pom.substring(pom.indexOf('"')+1,pom.lastIndexOf('"'));
 
  htr("#" + hex);
}
int del = 0;
String getted;
bool bcol,brnb,brnblbf,brnblbn,brb,bffade;


int r,g,b;

void htr(String hexstring)
{
    long number = (int) strtol( &hexstring[1], NULL, 16);
     r = number >> 16;
     g = number >> 8 & 0xFF;
     
     b = number & 0xFF;
}


void offled()
{
 bcol=brnb=brnblbn=brnblbf=brb=bffade=false;
}

void trybyOn()
{
String  Vtryb = tryb;
Serial.println(tryb);
   if(Vtryb == "0")
   {
    offled();
    bcol = true;
   }
   if(Vtryb == "1")
   {
    if(tryb_before != "1")
    {
      offled();
      brnb = true;
    }
   }
   if(Vtryb == "2")
   {
    Serial.println("deeper");
    if(tryb_before != "2" && hex_before != hex)
    {
      Serial.println("deeper");
      offled();
      brnblbn = true;
    }
   }
   if(Vtryb == "3")
   {
    if(tryb_before != "3")
    {
      offled();
      brnblbf = true;
    }
   }
   if(Vtryb == "4")
   {
    if(tryb_before != "4")
    {
      SetupTotallyRandomPalette();  
      SetupTotallyRandomPalette();  
      SetupTotallyRandomPalette(); 
      offled();
      brb = true;
    }
   }
   if(Vtryb == "5")
   {
    if(tryb_before != "5")
    {
      offled();
      bffade = true;
    }
   }
}
int fadecnt = 0;


int millisy = 0;
//////////////////////////////
int del3 = 0;
int del2 = 0;
int one = 1;
float tdelt;
  float hdelt;
  int hPa;
int pm1,pm2,pm10;
void loop() {
  
  
  
  if(one == 1)
  {
    pm1=data.pm10_env;
  pm2=data.pm25_env;
  pm10=data.pm100_env;
  hPa = bme.readPressure() / 100.0F;
  
   hdelt = ((2*dht.readHumidity()) + bme.readHumidity())/3; 
  tdelt = (bme.readTemperature()+dht.readTemperature())/2;
  one=0;
  }
  while (!WiFiCheck())
  {
    readEE();


    if (WiFiConnect(ssidall, passall))
      break;
    else
    {
      cnt++;
      delay(10000);
    }
    if (cnt == 3)
    {
      while (!WiFiCheck())
      {
        cnt = 0;
        brakWiFi();
        prs(BtIn());


      }
    }
  }
  
  if (del + 10 <= millis() / 1000) {
    http1.end();
    http1.begin("http://zefiro.pl/app/post-esp-settings.php");
    del = millis()/1000;
    http1.addHeader("Content-Type", "application/x-www-form-urlencoded");

    String poster = "api_key=" + apiKeyValue + "&id=" + device_id + "";
    int httpResponseCode = http1.POST(poster);
    if (httpResponseCode > 0) {
      
      Serial.print("HTTP Response code: ");
      getted = http1.getString();
    }
    else {
      Serial.print("Error code: ");
      Serial.println(httpResponseCode);

      http1.addHeader("Content-Type", "application/x-www-form-urlencoded");

      httpResponseCode = http1.POST(poster);

      if (httpResponseCode > 0) {
          
        Serial.print("HTTP Response code: ");
         getted = http1.getString();
        Serial.println(httpResponseCode);
      }
      else {
        Serial.print("Error code: ");
        Serial.println(httpResponseCode);

      }
      

    }
   
    
  //String getted = "{kolor=\"ffffff\",tryb=\"2\"}";
  String hold1, hold2;
  hold1 = tryb;
  hold2 = hex;
  getSettings(getted);
  trybyOn();
  hex_before = hold2;
  tryb_before= hold1;
  Serial.print(bcol);
  Serial.print(brnb);
  Serial.print(brnblbf);
  Serial.print(brnblbn);
  Serial.print(brb);
  Serial.print(bffade);
  Serial.println("");
  http1.end();
  }
  
  if(bcol)
  col(r,g,b);
  if(brnb)
  paleta(1);
  if(brnblbf)
  paleta(2);
  if(brnblbn)
  paleta(3);
  if(brb)
  paleta(4);

  if(bffade)
  {
    fade(fadecnt++);
  if(fadecnt >= 768)
  fadecnt = 0;
    
  }
 //
 //
 //
  if (readPMSdata(&pmsSerial) && WiFi.status() == WL_CONNECTED) 
  {
     
   
  pm1= (pm1+data.pm10_env)/2;
  pm2= (pm2+data.pm25_env)/2;
  pm10=(pm10+data.pm100_env)/2;
  float pomiar = dht.readHumidity();
  if(!isnan(pomiar))
   hdelt = ((2*pomiar) + bme.readHumidity())/3; 
   pomiar = dht.readTemperature();
  if(!isnan(pomiar))
  tdelt = (bme.readTemperature()+pomiar)/2;
  


  hPa = (hPa + (bme.readPressure() / 100.0F))/2;
  Serial.println(hPa);
  Serial.println(pm1);
  Serial.println(pm2);
  Serial.println(pm10);
  Serial.println(hdelt);
  Serial.println(tdelt);

}
  if(del3 + 600 <=millis()/1000)
  {
    del3 = millis()/1000;
    http2.addHeader("Content-Type", "application/x-www-form-urlencoded");
  

  
    int httpResponseCode = http2.POST( "api_key=" + apiKeyValue + "&id="+device_id + "&error=" + error_id + "&pm1=" + pm1+ "&pm25=" + pm2+ "&pm10=" + pm10 + "&humidity=" + hdelt + "&temp=" + tdelt + "&pressure=" + hPa + "" );

    if (httpResponseCode>0) {
      Serial.print("HTTP Response code: ");
      
      Serial.println(httpResponseCode);
    }
    else {
      Serial.print("Error code: ");
      Serial.println(httpResponseCode);

       http2.addHeader("Content-Type", "application/x-www-form-urlencoded");

    httpResponseCode = http2.POST( "api_key=" + apiKeyValue + "&id="+device_id + "&error=" + error_id + "&pm1=" + pm1+ "&pm25=" + pm2+ "&pm10=" + pm10 + "&humidity=" + hdelt + "&temp=" + tdelt + "&pressure=" + hPa + "" );

    if (httpResponseCode>0) {
      Serial.print("HTTP Response code: ");
      
      Serial.println(httpResponseCode);
    }
    else {
      Serial.print("Error code: ");
      Serial.println(httpResponseCode);

    }
    }
    }
    FastLED.show();
    alertled(0);
}

boolean readPMSdata(Stream *s)
{
  if (! s->available() || s->available() < 32 || s->peek() != 0x42) 
  {
    if(s->peek() != 0x42)
    s->read();
    return false;
  }
    
  uint8_t buffer[32];    
  uint16_t sum = 0;
  s->readBytes(buffer, 32);

  for (uint8_t i=0; i<30; i++)
    sum += buffer[i];
  
  uint16_t buffer_u16[15];
  for (uint8_t i=0; i<15; i++) 
  {
    buffer_u16[i] = buffer[2 + i*2 + 1];
    buffer_u16[i] += (buffer[2 + i*2] << 8);
  }

  memcpy((void *)&data, (void *)buffer_u16, 30);

  if (sum != data.checksum) 
  {
    Serial.println("Checksum failure");
    return false;
  }
  return true;
}

void SetupTotallyRandomPalette()
{
    for( int i = 0; i < 16; i++) 
        currentPalette[i] = CHSV( random8(), 255, random8());
}
