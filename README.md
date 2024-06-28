<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

## Installation
Prérequis
Composer
```php
PHP >= 8.2
```
```sql
MySQL
```

## Étapes d'installation

### Clonez le repository :

```bash
git clone https://github.com/jiordiviera/TestJiordi.git
```

### Accédez au répertoire du projet :

```bash
cd TestJiordi
```

### Installez les dépendances PHP avec Composer :

```bash
composer install
```

### Copiez le fichier d'environnement et configurez votre base de données :

```bash
cp .env.example .env
```

#### Éditez le fichier .env pour configurer les paramètres de votre base de données :

```dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=test
DB_USERNAME=root
DB_PASSWORD=
```

### Générez la clé d'application Laravel :

```bash
php artisan key:generate
```

#### Exécutez les migrations pour créer les tables de la base de données :

```bash
php artisan migrate
```

## Exécution du projet

### Pour démarrer le serveur de développement Laravel :

```bash
php artisan serve
```
Accédez à votre application dans votre navigateur à l'adresse [http://localhost:8000](http://localhost:8000.)

# Fonctionnalités

<b>Bootstrap</b> : Utilisé pour le design et la mise en page responsive.
<b>JQuery</b> : Pour les interactions dynamiques côté client.
<b>DataTables</b> : Pour la gestion et l'affichage avancé des tableaux.
