# 🛒 API Picard - Distributeurs Picard

## 📋 Description

API développée avec **Symfony** et **API Platform** pour les distributeurs automatiques Picard. Cette API permet aux distributeurs de gérer les produits, les paniers et les commandes des utilisateurs.

### 🎯 Fonctionnalités

-   **Gestion des produits** : Liste, détail, notation
-   **Gestion des paniers** : Ajout, suppression, validation
-   **API REST complète** avec documentation interactive
-   **Base de données MySQL** avec Doctrine ORM
-   **Données de test** générées avec Faker

---

## 🚀 Installation

### Prérequis

-   PHP 8.1+
-   Composer
-   MySQL/MariaDB
-   Symfony CLI (optionnel)

### Étapes d'installation

1. **Cloner le projet**

    ```bash
    git clone https://github.com/maxencevdg/b3-rattrapages-vandeghen-maxence.git
    cd 11-architecture-des-donnees-creations-d-apis
    ```

2. **Installer les dépendances**

    ```bash
    composer install
    ```

3. **Configurer la base de données**

    - Modifier le fichier `.env` :

    ```env
    DATABASE_URL="mysql://root:root@127.0.0.1:8889/picard_api?serverVersion=8.0"
    ```

4. **Créer la base de données**

    ```bash
    php bin/console doctrine:database:create
    ```

5. **Appliquer les migrations**

    ```bash
    php bin/console doctrine:migrations:migrate
    ```

6. **Charger les données de test**

    ```bash
    php bin/console doctrine:fixtures:load
    ```

7. **Lancer le serveur**
    ```bash
    symfony server:start
    ```

---

## 📚 Documentation API

L'API est accessible sur : `http://localhost:8000/api`

### 🔗 Endpoints disponibles

#### **Produits**

-   `GET /api/products` - Liste des produits
-   `GET /api/products/{id}` - Détail d'un produit
-   `POST /api/products/{id}/rate` - Noter un produit

#### **Paniers**

-   `GET /api/carts` - Liste des paniers
-   `GET /api/carts/{id}` - Détail d'un panier
-   `POST /api/carts` - Créer un panier
-   `POST /api/cart/{id}/add-product` - Ajouter un produit au panier
-   `DELETE /api/cart/{id}/remove-product/{productId}` - Supprimer un produit du panier
-   `POST /api/cart/{id}/validate` - Valider le panier

#### **Lignes de panier**

-   `GET /api/cart_items` - Liste des lignes de panier
-   `GET /api/cart_items/{id}` - Détail d'une ligne
-   `POST /api/cart_items` - Créer une ligne de panier
-   `PUT /api/cart_items/{id}` - Modifier une ligne
-   `DELETE /api/cart_items/{id}` - Supprimer une ligne

---

## 🧪 Tests avec Postman

### Collection Postman

Une collection Postman complète est disponible avec tous les endpoints de test.

---

## 🏗️ Architecture

### **Entités**

-   **Product** : Produits avec nom, image, description, prix, note, disponibilité
-   **Cart** : Paniers avec statut et date de création
-   **CartItem** : Lignes de panier avec quantité et prix unitaire

### **Relations**

-   Un `Cart` contient plusieurs `CartItem` (OneToMany)
-   Un `CartItem` appartient à un `Cart` et référence un `Product` (ManyToOne)

### **Contrôleurs personnalisés**

-   **CartController** : Gestion des opérations panier (ajout, suppression, validation)
-   **ProductController** : Notation des produits

---

## 📊 Base de données

### **Tables**

-   `product` : Produits Picard
-   `cart` : Paniers utilisateurs
-   `cart_item` : Lignes de panier

### **Données de test**

-   8 produits générés automatiquement avec Faker
-   Données réalistes (noms, prix, descriptions, images)

---

## 🎥 Vidéo Explicative

**[VIDÉO](https://youtu.be/gmafDkfvyEk)**

_Lien vers la vidéo explicative du code et du projet_

---

## 🛠️ Technologies utilisées

-   **Symfony 7.3** - Framework PHP
-   **API Platform 4.1** - Génération d'API REST
-   **Doctrine ORM** - Gestion de la base de données
-   **MySQL** - Base de données
-   **Faker** - Génération de données de test
-   **Postman** - Tests d'API

---

## 📝 Auteur

**Maxence Vandeghen** - B3 Architecture des données et création d'APIs

