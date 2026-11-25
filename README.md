#  FinTrack aplikacija

Web aplikacija urađena u Laravelu za praćenje ličnih finansija sa podrškom za transakcije, izvještaje, analitiku i admin panel.

**Dokumentacija** se nalazi na About Us strani.


##  Pokretanje projekta i kredencijali

Slijedite korake ispod kako biste pokrenuli aplikaciju lokalno:


## 1. Kloniranje repozitorijuma

```bash
git clone https://github.com/nevenadjo/fin-track.git
cd fin-track
```


## 2. Instalacija dependencija

```bash
composer install
```


## 3. Kreiranje .env fajla

```bash
cp .env.example .env
```


## 4. Generisanje aplikacionog ključa

```bash
php artisan key:generate
```


## 5. Podešavanje baze (SQLite)

U .env fajlu dodati:

```bash
DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite
```

Zatim kreirati fajl baze:

```bash
touch database/database.sqlite
```


## 6. Migracije i seederi

```bash
php artisan migrate --seed
```


## 7. Mail konfiguracija

Za slanje emailova (contact forma), u .env fajlu:

```bash
MAIL_MAILER=smtp
MAIL_HOST=://gmail.com
MAIL_PORT=465
MAIL_USERNAME=tvoj.mejl@gmail.com
MAIL_PASSWORD=tvoja_lozinka_aplikacije  # OVO NIJE TVOJA OBIČNA LOZINKA
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="contact@fintrack.com"
MAIL_FROM_NAME="FinTrack"
```

Alternativa (bez slanja mailova):

```bash
MAIL_MAILER=log
```


## 8. Pokretanje aplikacije

```bash
php artisan serve
```

Aplikacija će biti dostupna na:
http://127.0.0.1:8000


##  Test nalozi

### Admin:
email: admin@gmail.com

password: qwerty123

### Korisnici:
email: user1@fintrack.com

password: password


##  Napomene
Aplikacija koristi SQLite bazu (nije potreban dodatni DB server)

