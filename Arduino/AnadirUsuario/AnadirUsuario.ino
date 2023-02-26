#include <SPI.h>              // El shield Etherent usa SPI
#include <Ethernet.h>

#include <EthernetUdp.h>
#include <Time.h>
#include <TimeLib.h>

#include <MFRC522.h>

//Necesarios para Ethernet Shield
byte mac[] = {   0xDE, 0xAD, 0xBE, 0xEF, 0xFE, 0xED };
IPAddress ip(10,11,16,250); // IP local del Arduino. Donde va a estar alojado el programa. ------------------------------------<<<<<<<<<<<<<
EthernetServer server (80);  //Puerto estandard 80

//Necesarios para obtener la hora
unsigned int localPort = 8888;       // local port to listen for UDP packets
const char timeServer[] = "time.nist.gov"; // time.nist.gov NTP server
const int NTP_PACKET_SIZE = 48; // NTP time stamp is in the first 48 bytes of the message
byte packetBuffer[NTP_PACKET_SIZE]; //buffer to hold incoming and outgoing packets
EthernetUDP Udp; // A UDP instance to let us send and receive packets over UDP

//Necesario para RFID
//RST           D6
//SDA(SS)       D7
//MOSI         D11
//MISO         D12
//SCK          D13
const int RST_PIN = 6;            // Pin 6 para el reset del RC522
const int SS_PIN = 7;            // Pin 7 para el SS (SDA) del RC522
MFRC522 mfrc522(SS_PIN, RST_PIN);   // Crear instancia del MFRC522


//Servidor donde se encuentra el formulario para registrar.
String servidor_registrar="www.danipartal.net/biblioteca/add_user.php";

//Necesario para el buzzer
const int buzzer = 5;

bool mostrarHora; //Para Mostrar la Hora o no

void setup()
{
   Serial.begin(9600);
   while (!Serial) {
    ; // wait for serial port to connect. Needed for native USB port only
   }
   Ethernet.begin(mac, ip);
   mostrarHora = false;
   /* HORA ERRÓNEA.
    * 
    * Para mostrar la hora, es necesario este código, pero la IP varía
    * 
     if (Ethernet.begin(mac) == 0) {
      Serial.println("Failed to configure Ethernet using DHCP");
      // Check for Ethernet hardware present
      if (Ethernet.hardwareStatus() == EthernetNoHardware) {
        Serial.println("Ethernet shield was not found.  Sorry, can't run without hardware. :(");
      } else if (Ethernet.linkStatus() == LinkOFF) {
        Serial.println("Ethernet cable is not connected.");
      }
      // no point in carrying on, so do nothing forevermore:
      while (true) {
        delay(1);
      }
    }*/
  
   server.begin();          // Inicia el servidor web
   SPI.begin();         //Función que inicializa SPI
   Udp.begin(localPort);
   mfrc522.PCD_Init();     //Función  que inicializa RFID
   Serial.print("Servidor Web en la direccion: ");
   Serial.println(Ethernet.localIP());
   //Serial.print(":");
   //Serial.println (9090);
   pinMode(buzzer, OUTPUT);
   digitalWrite(buzzer, LOW);
}

//Funciones declaradas abajo
void sendNTPpacket(const char * address);
String ObtenerHora();
String FormatDigits(int);

String ObtenerCodigo(byte, byte);

String CodLlave="---------";
String Date="---------";
void loop()
{  
  // Detectar tarjeta
   if (mfrc522.PICC_IsNewCardPresent())
   {
      if (mfrc522.PICC_ReadCardSerial())
      {  
         CodLlave = ObtenerCodigo(mfrc522.uid.uidByte, mfrc522.uid.size);
         if (mostrarHora){
          Date = ObtenerHora();
         }
         
         //Buzzer
         tone(buzzer, 1600);
         delay(80);
         noTone(buzzer);

         // Finalizar lectura actual
         mfrc522.PICC_HaltA();
      }
   }
   delay(250);

   
   EthernetClient client = server.available();  // Buscamos entrada de clientes
   if (client) 
    { Serial.println("new client");
      boolean currentLineIsBlank = true;  // Las peticiones HTTP finalizan con linea en blanco
      while (client.connected())
        { if (client.available())
             {  char c = client.read();
                Serial.write(c);   // Esto no es necesario, pero copiamos todo a la consola
                // A partir de aquí mandamos nuestra respuesta
               if (c == '\n' && currentLineIsBlank) 
                  {   // Enviar una respuesta tipica
                      client.println("HTTP/1.1 200 OK");             
                      client.println("Content-Type: text/html");
                      client.println("Connection: close");
                      client.println();
                      client.println("<!DOCTYPE HTML>");
                      client.println("<html>");
                      

                      // Desde aqui creamos nuestra pagina con el codigo HTML que pongamos
                      client.print("<head>");
                      //client.print("<meta http-equiv='refresh' content='5'>");
                      client.print("<meta charset='utf-8'><title>Código de Llave</title></head>");
        
                      client.print("<body><h1>Código de llave</h1><p>Código de la ultima llave leída: <strong>");
                      client.print("<a id='CodLlave'>");
                      client.print(CodLlave);
                      client.print("</a></strong>\t");
                      client.print("<button onclick='copyToClipBoard()'><ion-icon name='copy-outline'></ion-icon></button></p>");
                      
                      if (mostrarHora){
                        client.print("<p>Hora de lectura: ");
                        client.print(Date);
                        client.print("</p>");
                      }
                      

                      client.print("<p>Para registrar esta llave, redirígase a <a onclick='copyToClipBoard()' href='http://");
                      client.print(servidor_registrar);
                      client.print("' target='_blank'>http://");
                      client.print(servidor_registrar);
                      client.print("</a></p>");

                      client.print("<p>Para ver el código de la siguiente llave, recarge la página. ");
                      client.print("<button onclick='location.reload()'><ion-icon name='reload-outline'></ion-icon></button></p>");
                      client.print("</p>");

                      
                      client.print("<script>");
                      client.print("function copyToClipBoard() {");
                      client.print("var aux = document.createElement('input');");
                      client.print("aux.setAttribute('value',document.getElementById('CodLlave').innerHTML);");
                      client.print("document.body.appendChild(aux);");
                      client.print("aux.select();");                      
                      client.print("document.execCommand('copy');");
                      client.print("document.body.removeChild(aux);");
                      client.print("document.getElementById('CodLlave').innerHTML='¡Copiado!\t';");
                      client.print("}");
                      client.print("</script>");
                      
                      client.print("<script type='module' src='https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js'></script>");
                      client.print("<script nomodule src='https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js'></script>");
                      client.print("</body></html>");



                      CodLlave="---------";
                      Date="---------";
                      break;
                }
                
            if (c == '\n')
                currentLineIsBlank = true;          // nueva linea
            else if (c != '\r')
                currentLineIsBlank = false;
          }
        }
     
     delay(1);         // Para asegurarnos de que los datos se envia
     client.stop();     // Cerramos la conexion
     Serial.println("client disonnected");
   }
 
}




// send an NTP request to the time server at the given address
void sendNTPpacket(const char * address) {
  // set all bytes in the buffer to 0
  memset(packetBuffer, 0, NTP_PACKET_SIZE);
  // Initialize values needed to form NTP request
  // (see URL above for details on the packets)
  packetBuffer[0] = 0b11100011;   // LI, Version, Mode
  packetBuffer[1] = 0;     // Stratum, or type of clock
  packetBuffer[2] = 6;     // Polling Interval
  packetBuffer[3] = 0xEC;  // Peer Clock Precision
  // 8 bytes of zero for Root Delay & Root Dispersion
  packetBuffer[12]  = 49;
  packetBuffer[13]  = 0x4E;
  packetBuffer[14]  = 49;
  packetBuffer[15]  = 52;

  // all NTP fields have been given values, now
  // you can send a packet requesting a timestamp:
  Udp.beginPacket(address, 123); // NTP requests are to port 123
  Udp.write(packetBuffer, NTP_PACKET_SIZE);
  Udp.endPacket();
}



String ObtenerHora(){
  sendNTPpacket(timeServer); // send an NTP packet to a time server

  // wait to see if a reply is available
  delay(1000);
  int packet_size = Udp.parsePacket();
  if (packet_size) {

    // We've received a packet, read the data from it
    Udp.read(packetBuffer, NTP_PACKET_SIZE); // read the packet into the buffer

    // the timestamp starts at byte 40 of the received packet and is four bytes,
    // or two words, long. First, extract the two words:

    unsigned long highWord = word(packetBuffer[40], packetBuffer[41]);
    unsigned long lowWord = word(packetBuffer[42], packetBuffer[43]);
    // combine the four bytes (two words) into a long integer
    // this is NTP time (seconds since Jan 1 1900):
    unsigned long secsSince1900 = highWord << 16 | lowWord;
    //Serial.print("Seconds since Jan 1 1900 = ");
    //Serial.println(secsSince1900);

    // now convert NTP time into everyday time:
    //Serial.print("Unix time = ");
    // Unix time starts on Jan 1 1970. In seconds, that's 2208988800:
    const unsigned long seventyYears = 2208988800UL;
    // subtract seventy years:
    unsigned long epoch = secsSince1900 - seventyYears;
    // print Unix time:
    //Serial.println(epoch);

    unsigned long secondsInHour = 3600UL;
    epoch += secondsInHour*1; //UTC +1 (horario invierno)
     
    
    setTime (epoch);
  }
  
  


  String Date="<strong>";

  Date += FormatDigits(hour());
  Date += ":";
  Date += FormatDigits(minute());
  Date += ":";
  Date += FormatDigits(second());
  Date += "   ";
  Date += FormatDigits(day());
  Date += "/";
  Date += FormatDigits(month());
  Date += "/";
  Date += FormatDigits(year());
  Date += "</strong><br> Horario Invierno (En verano, +1h)";

  Serial.print ("Hora: ");
  Serial.print(Date);
  Serial.println();
  
  return Date;
}



//Formatea cada dígito para añadir 0
String FormatDigits(int num)
   {     
         String Digit="";
         if(num < 10){
            Digit += "0";
         }
         Digit += num;

         return Digit;
   }



String ObtenerCodigo(byte *buffer, byte bufferSize) {
   String CodLlave="";
   for (byte i = 0; i < bufferSize; i++) {
      CodLlave += (buffer[i] < 0x10 ? "-0" : "-");
      CodLlave += String(buffer[i], HEX);
   }
   CodLlave.remove(0,1); //Elimina el guión inicial
   CodLlave.toUpperCase();
   Serial.print ("Card UID: ");
   Serial.print(CodLlave);
   Serial.println();
   return CodLlave;
}
