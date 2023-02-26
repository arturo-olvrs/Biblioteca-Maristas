#include <SPI.h>              // El shield Etherent usa SPI
#include <Ethernet.h>
#include <MFRC522.h>

//Necesarios para Ethernet Shield
byte mac[] = {   0xDE, 0xAD, 0xBE, 0xEF, 0xFE, 0xED };
IPAddress ip(192,168,1,177); // IP del Arduino
EthernetClient client;


//Necesario para RFID
//RST           D6
//SDA(SS)       D7
//MOSI         D11
//MISO         D12
//SCK          D13
const int RST_PIN = 6;            // Pin 6 para el reset del RC522
const int SS_PIN = 7;            // Pin 7 para el SS (SDA) del RC522
MFRC522 mfrc522(SS_PIN, RST_PIN);   // Crear instancia del MFRC522


//Dirección IP del servidor con la página PHP
char server[]="danipartal.net";

void setup()
{
   Serial.begin(9600);
   while (!Serial) {
    ; // wait for serial port to connect. Needed for native USB port only
   }
   
   Ethernet.begin(mac, ip);
   SPI.begin();         //Función que inicializa SPI
   mfrc522.PCD_Init();     //Función  que inicializa RFID
   Serial.print("IP: ");
   Serial.println(Ethernet.localIP());
}

String codigo;        //Aquí se almacena la respuesta del servidor
String resultado;        //Aquí se almacena el nombre que envía el código PHP
boolean fin = false;

int httpRequest(String);
String ObtenerCodigo(byte, byte);


String CodLlave="";
void loop()
{  
  // Detectar tarjeta
   if (mfrc522.PICC_IsNewCardPresent())
   {
      if (mfrc522.PICC_ReadCardSerial())
      {  
         CodLlave = ObtenerCodigo(mfrc522.uid.uidByte, mfrc522.uid.size);
         // Finalizar lectura actual
         mfrc522.PICC_HaltA();

         httpRequest(CodLlave);
      }
   }
   delay(250);
}



// Con esta función hacemos la conecion con el servidor
int httpRequest(String identificador) {
  // Comprobar si hay conexión
  if (client.connect(server, 8000)) { ///////////////////////////////////Comprobar puerto
    String peticion=""; //Variable para petición HTTP
    Serial.println("nConectado");
    // Enviar la petición HTTP
    //Dirección del archivo php dentro del servidor
    peticion += "GET ";
    peticion+="/biblioteca/hora_entrada.php?CodLlave=";

    //Mandamos la variable junto a la línea de GET
    peticion+=String(identificador);
    peticion += " HTTP/1.1\n";
    //IP del servidor
    peticion+="Host: ";
    peticion+=String(server);
    peticion+="\nUser-Agent: arduino-ethernet\nConnection: close\n\n";
    client.println(peticion);
    Serial.println(peticion);
    peticion="";
  }
  else {
    // Si no conseguimos conectarnos
    Serial.println("Conexión fallida");
    Serial.println("Desconectando");
    client.stop();
    }
  delay(500);
  //Comprobamos si tenemos respuesta del servidor y la
  //almacenamos en el string – --> codigo.
  
  while (client.available()) {
    char c = client.read();
    codigo += c;
    //Habilitamos la comprobación del código recibido
    fin = true;
  }
  
  //Si está habilitada la comprobación del código entramos en el IF
  if (fin)  {
    Serial.println(codigo);
    //Analizamos la longitud del código recibido
    int longitud = codigo.length();
    //Buscamos en que posición del string se encuentra nuestra variable
    int posicion = codigo.indexOf("valor=");
    //Borramos lo que haya almacenado en el string resultado
    resultado = "";
    //Analizamos el código obtenido y almacenamos el nombre en el string nombre
    for (int i = posicion + 6; i < longitud; i ++){
      if (codigo[i] == ';') i = longitud;
      else resultado += codigo[i];
    }
    //Deshabilitamos el análisis del código
    fin = false;
    //Imprimir el nombre obtenido
    Serial.println("Resultado: " + resultado);
    //Cerrar conexión
    Serial.println("Desconectarn");
    client.stop();
  }
  
  //Borrar código y salir de la función//Dirección IP del servidor
  codigo="";
  return 1;
}





String ObtenerCodigo(byte *buffer, byte bufferSize) {
   String CodLlave="";
   for (byte i = 0; i < bufferSize; i++) {
      CodLlave += (buffer[i] < 0x10 ? "-0" : "-");
      CodLlave += String(buffer[i], HEX);
   }
   CodLlave.remove(0,1); //Elimina el guion inicial
   CodLlave.toUpperCase();
   Serial.print ("Card UID: ");
   Serial.print(CodLlave);
   Serial.println();
   return CodLlave;
}
