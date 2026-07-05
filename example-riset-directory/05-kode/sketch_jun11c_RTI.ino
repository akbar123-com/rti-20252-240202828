#include <WiFi.h>
#include <HTTPClient.h>
#include <Wire.h>
#include "MAX30105.h"

MAX30105 particleSensor;

// ================= KONFIGURASI =================
const char* ssid = "V2043";       
const char* password = "44444444";     
// BUG DITEMUKAN & DIPERBAIKI: sebelumnya "http:///RTI/insert.php" (IP kosong,
// tiga garis miring) sehingga ESP32 tidak akan pernah berhasil mengirim data.
// GANTI 192.168.1.100 dengan IP IPv4 laptop kamu (cek lewat: ipconfig di CMD,
// cari baris "IPv4 Address" pada adapter Wi-Fi yang aktif). IP ini WAJIB
// diisi sebelum upload sketch ke ESP32, dan tetap sama baik saat menguji
// MySQL maupun PostgreSQL (yang berbeda hanya $db_engine di koneksi.php).
const char* serverName = "http://192.168.1.100/RTI/insert.php";

#define LED_PIN 2
#define TOMBOL_BOOT 0 // Pin bawaan untuk tombol BOOT di ESP32

const int maxData = 1000;
long dataDetak[maxData];
int dataCount = 0;

bool isRecorded = false; 
bool isShooting = false; 
// ===============================================

void setup() {
  Wire.begin(21, 22);
  
  pinMode(LED_PIN, OUTPUT);
  pinMode(TOMBOL_BOOT, INPUT_PULLUP); 
  digitalWrite(LED_PIN, LOW);

  if (!particleSensor.begin(Wire, I2C_SPEED_FAST)) {
    while (1) {
      digitalWrite(LED_PIN, HIGH); delay(100);
      digitalWrite(LED_PIN, LOW); delay(100);
    }
  }
  particleSensor.setup(); 
  particleSensor.setPulseAmplitudeRed(0x0A);
  particleSensor.setPulseAmplitudeIR(0x0A);

  WiFi.begin(ssid, password);
  while(WiFi.status() != WL_CONNECTED) {
    delay(500);
  }
  
  // Kedipan LED 3 kali lambat tanda Wi-Fi sukses & SIAP REKAM JARI
  for(int i=0; i<3; i++){
    digitalWrite(LED_PIN, HIGH); delay(300);
    digitalWrite(LED_PIN, LOW); delay(300);
  }
}

void loop() {
  // FASE 1: OTOMATIS MEREKAM SAAT JARI DITEMPEL (Hanya 1x)
  if (!isRecorded) {
    long irValue = particleSensor.getIR();

    if (irValue > 10000) { 
      digitalWrite(LED_PIN, HIGH); 
      dataDetak[dataCount] = irValue;
      dataCount++;
      
      if (dataCount >= maxData) {
        isRecorded = true; 
        digitalWrite(LED_PIN, LOW); 
      }
    } else {
      digitalWrite(LED_PIN, LOW);
    }
    delay(10); 
  } 
  
  // FASE 2: MENGUNGGU TOMBOL BOOT DITEKAN UNTUK MENEMBAK
  else {
    if (digitalRead(TOMBOL_BOOT) == LOW && !isShooting) {
      delay(50); // Anti-bouncing
      
      if (digitalRead(TOMBOL_BOOT) == LOW) {
        isShooting = true; // KUNCI RAPAT agar tidak bisa tertekan dua kali
        
        kirimData(); 
        
        // Tahan kode di sini sampai jari benar-benar lepas dari tombol
        while(digitalRead(TOMBOL_BOOT) == LOW) { delay(10); } 
        delay(1000); // Ekstra jeda pengaman 1 detik (anti double-klik)
        isShooting = false; // Buka kunci kembali
      }
    }
  }
}

void kirimData() {
  if(WiFi.status() == WL_CONNECTED) {
    // LED Nyala mantap tanda mulai mengirim data
    digitalWrite(LED_PIN, HIGH);
    
    for (int i = 0; i < maxData; i++) {
      HTTPClient http; // Dideklarasikan di dalam loop agar SELALU membuat koneksi fresh
      
      http.begin(serverName);
      http.addHeader("Content-Type", "application/x-www-form-urlencoded");

      String httpRequestData = "nilai_sensor=" + String(dataDetak[i]);
      http.POST(httpRequestData);
      
      http.end(); // Langsung tutup koneksi secara paksa, hindari auto-retry
      
      yield();  
      delay(20); // Nafas untuk XAMPP agar tidak hang/loading terus
    }
    
    // LED Mati tanda selesai 100% terkirim. Siap pindah database!
    digitalWrite(LED_PIN, LOW);
  }
}