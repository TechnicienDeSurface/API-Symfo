# 🎮 API Jeux Vidéo

Une API développée avec Symfony pour gérer une base de données de jeux vidéo avec système d'authentification JWT et newsletter automatisée.

![Symfony](https://img.shields.io/badge/Symfony-7.x-black?style=for-the-badge&logo=symfony)
![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php)
![JWT](https://img.shields.io/badge/JWT-Auth-000000?style=for-the-badge&logo=jsonwebtokens)
![License](https://img.shields.io/badge/License-MIT-green?style=for-the-badge)

## 📋 Table des matières

- [Fonctionnalités](#-fonctionnalités)
- [Entités](#-entités)
- [Installation](#-installation)
- [Configuration](#%EF%B8%8F-configuration)
- [Utilisation](#-utilisation)
- [API Endpoints](#-api-endpoints)
- [Newsletter Automatique](#-newsletter-automatique)
- [Authentification](#-authentification)
- [Documentation API](#-documentation-api)

---

## ✨ Fonctionnalités

### 🔐 Authentification & Sécurité
- ✅ Authentification JWT (JSON Web Token)
- ✅ Gestion des rôles (USER, ADMIN)
- ✅ Protection des routes sensibles
- ✅ Validation des données (Asserts Symfony)

### 📊 Gestion des Entités
- ✅ CRUD complet pour VideoGame
- ✅ Relations entre entités (ManyToOne, OneToMany)
- ✅ Gestion des clés étrangères
- ✅ Cache avec TagAwareCacheInterface

### 📧 Système de Newsletter
- ✅ Abonnement à la newsletter (champ `newsletter` sur User)
- ✅ Email automatique tous les lundis à 8h30
- ✅ Template Twig personnalisé
- ✅ Liste des jeux sortant dans les 7 prochains jours
- ✅ Scheduler avec Cron Expression

### 🛠️ Outils & Technologies
- ✅ Symfony 7.x
- ✅ Doctrine ORM
- ✅ LexikJWTAuthenticationBundle
- ✅ Nelmio API Doc (Swagger)
- ✅ Symfony Messenger & Scheduler
- ✅ DataFixtures pour jeux de test

---

## 🗂️ Entités

### VideoGame
```
- id (int)
- title (string)
- releaseDate (datetime)
- description (text)
- coverImage (string) - URL de la jaquette
- category (ManyToOne → Category)
- editor (ManyToOne → Editor)
```

### Category
```
- id (int)
- name (string)
- videoGames (OneToMany → VideoGame)
```

### Editor
```
- id (int)
- name (string)
- country (string)
- videoGames (OneToMany → VideoGame)
```

### User
```
- id (int)
- email (string)
- password (string, hashé)
- roles (array)
- newsletter (boolean) - Abonnement newsletter
```

---

## 🚀 Installation

### Prérequis
- PHP 8.2 ou supérieur
- Composer
- MySQL/PostgreSQL
- Symfony CLI (optionnel)

### Étapes d'installation

```bash
# 1. Cloner le repository
git clone https://github.com/votre-username/videogame-api.git
cd videogame-api

# 2. Installer les dépendances
composer install

# 3. Configurer la base de données
# Modifier le fichier .env avec vos paramètres
DATABASE_URL

# 4. Créer la base de données
php bin/console doctrine:database:create

# 5. Exécuter les migrations
php bin/console doctrine:migrations:migrate

# 6. Générer les clés JWT
php bin/console lexik:jwt:generate-keypair

# 7. (Optionnel) Charger les fixtures
php bin/console doctrine:fixtures:load

# 8. Lancer le serveur
symfony server:start
# ou
php -S localhost:8000 -t public/
```

---

## ⚙️ Configuration

### Variables d'environnement (.env)

```env
# Base de données
DATABASE_URL="mysql://root:@127.0.0.1:3306/videogame_db"

# JWT
JWT_SECRET_KEY=%kernel.project_dir%/config/jwt/private.pem
JWT_PUBLIC_KEY=%kernel.project_dir%/config/jwt/public.pem
JWT_PASSPHRASE=votre_passphrase

# Mailer (pour la newsletter)
MAILER_DSN=smtp://user:pass@smtp.mailtrap.io:2525

# Messenger
MESSENGER_TRANSPORT_DSN=doctrine://default?auto_setup=0
```

### Lancer les workers (Newsletter)

```bash
# Worker pour le scheduler (tâches planifiées)
php bin/console messenger:consume scheduler -vv

# Worker pour les emails asynchrones
php bin/console messenger:consume async -vv
```

---

## 📖 Utilisation

### 1. Créer un utilisateur

```bash
POST /api/user/add
Content-Type: application/json

{
  "email": "user@example.com",
  "password": "SecurePass123!",
  "roles": ["ROLE_USER"],
  "newsletter": true
}
```

### 2. S'authentifier

```bash
POST /api/login_check
Content-Type: application/json

{
  "email": "user@example.com",
  "password": "SecurePass123!"
}

# Réponse
{
  "token": "eyJ0eXAiOiJKV1QiLCJhbGc..."
}
```

### 3. Utiliser l'API avec le token

```bash
GET /api/v1/users
Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGc...
```

---

## 🔌 API Endpoints

### 🔐 Authentification
| Méthode | Endpoint | Description | Auth |
|---------|----------|-------------|------|
| POST | `/api/login_check` | Obtenir un token JWT | ❌ |

### 👥 Users (ADMIN uniquement)
| Méthode | Endpoint | Description | Auth |
|---------|----------|-------------|------|
| GET | `/api/v1/users` | Liste paginée des utilisateurs | ✅ ADMIN |
| POST | `/api/user/add` | Créer un utilisateur | ✅ |
| PUT | `/api/user/edit/{id}` | Modifier un utilisateur | ✅ |
| DELETE | `/api/user/delete/{id}` | Supprimer un utilisateur | ✅ ADMIN |

### 🎮 VideoGames
| Méthode | Endpoint | Description | Auth |
|---------|----------|-------------|------|
| GET | `/api/v1/games` | Liste paginée des jeux | ✅ |
| GET | `/api/v1/game/{id}` | Détails d'un jeu | ✅ |
| POST | `/api/v1/game` | Créer un jeu | ✅ ADMIN |
| PUT | `/api/v1/game/{id}` | Modifier un jeu | ✅ ADMIN |
| DELETE | `/api/v1/game/{id}` | Supprimer un jeu | ✅ ADMIN |

### 📂 Categories
| Méthode | Endpoint | Description | Auth |
|---------|----------|-------------|------|
| GET | `/api/v1/categories` | Liste des catégories | ✅ |
| GET | `/api/v1/category/{id}` | Détails d'une catégorie | ✅ |
| POST | `/api/v1/category` | Créer une catégorie | ✅ ADMIN |
| PUT | `/api/v1/category/{id}` | Modifier une catégorie | ✅ ADMIN |
| DELETE | `/api/v1/category/{id}` | Supprimer une catégorie | ✅ ADMIN |

### 🏢 Editors
| Méthode | Endpoint | Description | Auth |
|---------|----------|-------------|------|
| GET | `/api/v1/editors` | Liste des éditeurs | ✅ |
| GET | `/api/v1/editor/{id}` | Détails d'un éditeur | ✅ |
| POST | `/api/v1/editor` | Créer un éditeur | ✅ ADMIN |
| PUT | `/api/v1/editor/{id}` | Modifier un éditeur | ✅ ADMIN |
| DELETE | `/api/v1/editor/{id}` | Supprimer un éditeur | ✅ ADMIN |

---

## 📧 Newsletter Automatique

### Fonctionnement

La newsletter est envoyée **automatiquement tous les lundis à 8h30** via Symfony Scheduler.

### Contenu de l'email
- 📅 Liste des jeux sortant dans les **7 prochains jours**
- 🎨 Template HTML personnalisé avec Twig
- 🖼️ Jaquettes des jeux (`coverImage`)

### Activer l'abonnement pour un utilisateur

```bash
PUT /api/user/edit/{id}
Authorization: Bearer token...
Content-Type: application/json

{
  "newsletter": true
}
```

### Tester manuellement la newsletter

```bash
# Via la commande console
php bin/console app:send-newsletter

# Voir les schedules configurés
php bin/console debug:scheduler
```

### Architecture Newsletter
```
Scheduler/
├── Message/
│   └── SendEmailMessage.php        # Message déclenché par le cron
├── Handler/
│   └── SendEmailMessageHandler.php # Logique d'envoi des emails
└── FirstSchedule.php               # Configuration du planning (8h30 lundis)
```

---

## 🔒 Authentification

### Obtenir un token JWT

```bash
curl -X POST http://localhost:8000/api/login_check \
  -H "Content-Type: application/json" \
  -d '{
    "email": "admin@example.com",
    "password": "password123"
  }'
```

**Réponse :**
```json
{
  "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9..."
}
```

### Utiliser le token

```bash
curl -X GET http://localhost:8000/api/v1/games \
  -H "Authorization: Bearer eyJ0eXAiOiJKV1Qi..."
```

---

## 📚 Documentation API

### Swagger / Nelmio API Doc

La documentation interactive de l'API est disponible à :

**👉 http://localhost:8080/api/doc**

### Fonctionnalités de la documentation
- ✅ Interface Swagger UI
- ✅ Test direct des endpoints
- ✅ Exemples de requêtes/réponses
- ✅ Authentification JWT intégrée
- ✅ OpenAPI 3.1.0

### Tester avec Swagger
1. Accédez à `/api/doc`
2. Cliquez sur **Authorize** (🔓)
3. Entrez votre token JWT
4. Testez les endpoints directement

---

## 🛠️ Commandes utiles

```bash
# Gestion de la base de données
php bin/console doctrine:database:create
php bin/console make:migration
php bin/console doctrine:migrations:migrate

# Gestion des utilisateurs
php bin/console doctrine:fixtures:load

# Cache
php bin/console cache:clear
php bin/console cache:pool:clear cache.app

# Newsletter
php bin/console app:send-newsletter
php bin/console debug:scheduler

# Messenger (Workers)
php bin/console messenger:consume scheduler async -vv
php bin/console messenger:stats
php bin/console messenger:failed:show

# Routes
php bin/console debug:router
```

---

## 📦 Dépendances principales

```json
{
  "require": {
    "symfony/framework-bundle": "^7.0",
    "doctrine/orm": "^3.0",
    "doctrine/doctrine-bundle": "^2.11",
    "lexik/jwt-authentication-bundle": "^3.0",
    "nelmio/api-doc-bundle": "^4.0",
    "symfony/mailer": "^7.0",
    "symfony/messenger": "^7.0",
    "symfony/scheduler": "^7.0",
    "dragonmantank/cron-expression": "^3.3"
  }
}
```

## 📄 License

Ce projet est sous licence MIT. Voir le fichier `LICENSE` pour plus d'informations.

---

## 👨‍💻 Auteur

**Votre Nom**
- GitHub: [@TechnicienDeSurface](https://github.com/TechnicienDeSurface)
- Email: votre.email@example.com

---

## 🙏 Remerciements

- Symfony pour ce framework incroyable
- La communauté PHP
- Tous les contributeurs

---