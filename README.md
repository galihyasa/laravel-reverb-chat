# Mini Real-Time Chat Laravel Reverb

Aplikasi mini chat real-time menggunakan Laravel dan Laravel Reverb.

## Deskripsi

Project ini dibuat untuk memenuhi tugas individu Laravel Reverb. Aplikasi ini memungkinkan dua user saling mengirim pesan secara real-time tanpa perlu refresh halaman. Pesan yang dikirim juga disimpan ke database.

## Fitur

- Menampilkan daftar user
- User dapat memilih pengirim dan penerima pesan
- User dapat mengirim pesan
- Pesan tampil secara real-time tanpa refresh halaman
- Pesan tersimpan ke database
- Menggunakan Laravel Reverb sebagai WebSocket server

## Teknologi yang Digunakan

- Laravel
- PHP
- MySQL
- Laravel Reverb
- Laravel Echo
- Pusher JS
- Vite
- JavaScript
- CSS

## Struktur Folder Utama

- app/Events/MessageSent.php
- app/Http/Controllers/ChatController.php
- app/Models/Message.php
- database/migrations/create_messages_table.php
- database/seeders/DatabaseSeeder.php
- resources/views/chat.blade.php
- resources/js/app.js
- resources/js/echo.js
- resources/css/app.css
- routes/web.php

## Cara Menjalankan

Install dependency:

```bash
composer install
npm install
