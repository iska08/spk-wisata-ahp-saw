# Instalasi

1. **Clone repositori:**

    ```sh
    git https://github.com/iska08/spk-wisata-ahp-saw.git
    cd spk-wisata-ahp-saw
    ```

2. **Instal dependensi PHP:**

    Pastikan Anda telah menginstal [Composer](https://getcomposer.org/).

    ```sh
    composer install
    ```

3. **Siapkan file lingkungan:**

    Salin file `.env.example` ke `.env`:

    ```sh
    cp .env.example .env
    ```

    Buka file `.env` dan atur kredensial database Anda serta variabel lingkungan lainnya.

4. **Hasilkan kunci aplikasi:**

    ```sh
    php artisan key:generate
    ```

5. **Jalankan migrasi database:**

    Pastikan database Anda sudah dikonfigurasi di file `.env`.

    ```sh
    php artisan migrate
    ```

6. **(Opsional) Isi database dengan data awal:**

    Jika proyek Anda termasuk data seed, Anda dapat mengisi database.

    ```sh
    php artisan db:seed
    ```

7. **Jalankan server pengembangan:**

    ```sh
    php artisan serve
    ```

    Aplikasi Anda akan dapat diakses di `http://localhost:8000`.
