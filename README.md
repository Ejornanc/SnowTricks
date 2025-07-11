# SnowTricks

SnowTricks est une application web Symfony dédiée à la communauté de snowboard, permettant de partager et découvrir des figures de snowboard.

## Prérequis

- PHP 8.2 ou supérieur
- Composer
- MySQL 9.2 ou supérieur
- Node.js et npm
- Symfony CLI (recommandé pour le développement local)

## Installation

### 1. Cloner le dépôt

```bash
git clone https://github.com/Ejornanc/SnowTricks.git
cd SnowTricks
```

### 2. Installer les dépendances PHP

```bash
composer install
```

### 3. Installer les dépendances JavaScript

```bash
npm install
```

### 4. Configurer l'environnement

Copiez le fichier `.env` en `.env.local` et modifiez les variables selon votre environnement :

```bash
cp .env .env.local
```

Modifiez ou vérifier notamment la variable `DATABASE_URL` pour correspondre à votre configuration MySQL :

```
DATABASE_URL="mysql://username:password@127.0.0.1:3306/snowtricks?serverVersion=9.2&charset=utf8mb4"
```

### 5. Créer les containers Docker (Mailhog, MySQL, phpMyAdmin)

```bash
docker compose -f BDD/docker-compose.yaml -p snowtricks up -d
```

### 6. Démarrer le serveur de développement

Avec Symfony CLI :

```bash
symfony server:start
```

## Fonctionnalités

- Gestion des figures de snowboard (création, modification, suppression)
- Système d'authentification utilisateur
- Commentaires sur les figures
- Gestion des médias (images et vidéos)


## Déploiement en production

Pour préparer l'application pour la production :

```bash
APP_ENV=prod APP_DEBUG=0 php bin/console cache:clear
```
Pour Lancer le serveur en mode production
```bash
APP_ENV=prod APP_DEBUG=0 symfony server:start
```
