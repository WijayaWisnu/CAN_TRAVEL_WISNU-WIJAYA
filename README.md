# PO CAN Travel - REST API Sistem Pemesanan Tiket Bus

## Teknologi yang Digunakan
* **Framework:** Laravel 10
* **Database:** PostgreSQL (pgAdmin 4)
* **Autentikasi:** Laravel Sanctum
* **Testing:** Postman

---

## Dokumentasi API (Endpoints)

**PERHATIAN:** Semua request **WAJIB** menyertakan Header berikut:
* `Accept: application/json`
* `X-API-KEY: rahasia_po_can_travel_123`

---

### 1. Public Routes (Hanya butuh API Key)

* **`POST /api/register`**
  * **Fungsi:** Mendaftarkan user baru.
  * **Body (form-data/json):** `name`, `email`, `password` (min 8 karakter).

* **`POST /api/login`**
  * **Fungsi:** Login dan mendapatkan Bearer Token.
  * **Body:** `email`, `password`.
  * **Response:** Mengembalikan `access_token` yang harus dicopy untuk fitur selanjutnya.

* **`GET /api/schedules`**
  * **Fungsi:** Melihat jadwal bus yang tersedia.
  * **Query Params (Opsional):** `?origin=Jakarta` atau `?destination=Bandung` untuk mencari jadwal spesifik.

---

### 2. Protected Routes (Butuh API Key + Bearer Token)

**Tambahkan Header tambahan:** `Authorization: Bearer <token_anda_dari_login>`

* **`GET /api/user`**
  * **Fungsi:** Melihat profil user yang sedang login.

* **`POST /api/logout`**
  * **Fungsi:** Logout dan menghapus token yang aktif.

* **`POST /api/order-ticket`**
  * **Fungsi:** Memesan tiket bus.
  * **Body:** `schedule_id` (Masukkan angka ID jadwal dari endpoint `/api/schedules`).

* **`GET /api/my-bookings`**
  * **Fungsi:** Melihat riwayat pemesanan tiket dari user yang sedang login (Lengkap dengan data relasi bus dan rute).

* **`POST /api/bookings/{id}/pay`**
  * **Fungsi:** Simulasi membayar tiket (Ubah `{id}` di URL dengan ID Booking Anda).

* **`POST /api/bookings/{id}/cancel`**
  * **Fungsi:** Membatalkan pesanan tiket yang berstatus pending.
