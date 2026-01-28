# NETFISH - Authenticatie Platform

Een veilig authenticatie systeem met gebruikersbeheer.

## 🚀 Features

- ✅ Gebruikersregistratie en authenticatie
- ✅ Wachtwoord reset functionaliteit
- ✅ Admin beheerpaneel
- ✅ Gebruikersbeheer
- ✅ Veilige wachtwoord opslag (bcrypt)
- ✅ Session management
- ✅ Responsive design

## 📋 Installatie

### 1. Database opzetten

```sql
-- Importeer config/database.sql in phpMyAdmin
```

### 2. Database credentials

Pas `config/database.php` aan met je lokale database gegevens:

```php
$host = 'localhost';
$dbname = 'netfish';
$username = 'root';
$password = '';
```

### 3. Test account

- **Username:** admin
- **Password:** admin123

## 🔧 Gebruik

### Registreren:
1. Ga naar `/register.php`
2. Vul gebruikersnaam, e-mail en wachtwoord in
3. Account wordt aangemaakt

### Inloggen:
1. Ga naar `/login.php`
2. Vul inloggegevens in
3. Je wordt doorgestuurd naar het dashboard

### Admin functies:
1. Login als admin
2. Ga naar Admin Panel
3. Bekijk gebruikersstatistieken
4. Beheer gebruikers

### Wachtwoord vergeten:
1. Ga naar `/forgot-password.php`
2. Vul je e-mailadres in
3. Ontvang reset link
4. Reset je wachtwoord via `/reset.php`

## 📁 Project Structuur