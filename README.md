# 🤖 Laravel AI Chatbot

Aplikasi chatbot AI sederhana yang dibuat menggunakan **Laravel** dan **Groq API**. Aplikasi ini memungkinkan pengguna mengirim pesan dan menerima jawaban dari AI melalui antarmuka web yang sederhana dan responsif.

## ✨ Fitur

- 💬 Mengirim pesan dan menerima respons dari AI.
- ⚡ Menggunakan Groq API untuk memproses percakapan.
- 🎨 Antarmuka chatbot dengan desain modern dan dark mode.
- 🔒 Menggunakan CSRF protection untuk request dari form.
- 🛠️ Penanganan error ketika request API gagal.
- 📱 Tampilan yang nyaman digunakan di berbagai ukuran layar.

## 🧰 Teknologi yang Digunakan

- **PHP** — bahasa pemrograman backend.
- **Laravel** — framework aplikasi web.
- **Groq API** — layanan AI untuk menghasilkan respons chatbot.
- **JavaScript** — menangani interaksi dan pengiriman pesan.
- **Tailwind CSS** — membantu membuat tampilan antarmuka.
- **Composer** — mengelola dependensi PHP.

## 📋 Persyaratan

Pastikan perangkat sudah memiliki:

- PHP sesuai versi yang didukung oleh proyek Laravel.
- Composer.
- Node.js dan npm jika diperlukan oleh aset frontend.
- Groq API key yang valid.

## 🚀 Cara Instalasi

### 1. Clone repository

```bash
git clone https://github.com/hafzhhabdillah/chatbot.git
```

### 2. Masuk ke folder proyek

```bash
cd chatbot
```

### 3. Install dependensi Laravel

```bash
composer install
```

### 4. Buat file environment

Salin file `.env.example` menjadi `.env`.

Windows PowerShell:

```powershell
Copy-Item .env.example .env
```

### 5. Generate application key

```bash
php artisan key:generate
```

### 6. Konfigurasi Groq API

Buka file `.env`, lalu tambahkan atau sesuaikan konfigurasi berikut:

```dotenv
GROQ_API_KEY=masukkan_api_key_groq_kamu
GROQ_MODEL=openai/gpt-oss-20b
GROQ_FALLBACK_MODEL=openai/gpt-oss-120b
```

Ganti `masukkan_api_key_groq_kamu` dengan API key milikmu. Pastikan nama model masih didukung oleh akun dan layanan Groq saat aplikasi dijalankan.

**Penting:** Jangan mengunggah API key ke GitHub atau membagikannya kepada orang lain.

Jika controller membaca konfigurasi melalui `config('services.groq.key')`, pastikan `config/services.php` juga memiliki konfigurasi Groq yang sesuai.

### 7. Bersihkan cache konfigurasi

Jalankan perintah ini di terminal, bukan di dalam file `.env`.

```bash
php artisan config:clear
```

### 8. Jalankan aplikasi

```bash
php artisan serve
```

Buka alamat berikut di browser:

http://127.0.0.1:8000

## 💡 Cara Menggunakan

1. Jalankan aplikasi Laravel.
2. Buka halaman chatbot di browser.
3. Ketik pertanyaan pada kolom pesan.
4. Tekan tombol kirim.
5. Tunggu AI memproses pesan dan menampilkan respons.

## 🐛 Troubleshooting

### Chatbot tidak memberikan respons

- Pastikan koneksi internet tersedia.
- Periksa apakah `GROQ_API_KEY` valid.
- Pastikan nama model yang digunakan masih tersedia di Groq.
- Periksa konfigurasi pada `config/services.php` jika digunakan oleh controller.
- Lihat pesan error pada browser Developer Tools dan log Laravel di `storage/logs/laravel.log`.

### Error konfigurasi `.env`

Pastikan setiap variabel ditulis pada baris terpisah. Jangan menambahkan perintah terminal seperti `php artisan config:clear` ke dalam file `.env`.

### Error HTTP 419

Periksa apakah request dari frontend mengirim token CSRF yang benar dan sesi Laravel berjalan dengan baik.

## 📁 Struktur Proyek

```text
chatbot/
├── app/
│   └── Http/
│       └── Controllers/
│           └── ChatController.php
├── config/
│   └── services.php
├── resources/
│   └── views/
│       └── chat.blade.php
├── routes/
│   └── web.php
├── storage/
│   └── logs/
├── .env.example
├── composer.json
└── README.md
```

*Struktur di atas merupakan gambaran umum. Sesuaikan dengan file yang benar-benar tersedia di repository.*

## 🔐 Keamanan

- Simpan API key di file `.env`.
- Jangan commit file `.env` ke repository publik.
- Gunakan `.env.example` untuk mendokumentasikan nama variabel konfigurasi tanpa menyertakan rahasia.
- Jangan menampilkan API key atau informasi sensitif dalam pesan error.

## 📄 Lisensi

Proyek ini dibuat untuk tujuan pembelajaran dan pengembangan aplikasi chatbot berbasis AI.

---

**Dibuat menggunakan Laravel dan Groq API.** 🤖
