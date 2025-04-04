# Projet PHP avec MySQL

Ce projet est une application web simple utilisant PHP et MySQL.

## Prérequis

- PHP 7.4 ou supérieur
- MySQL 5.7 ou supérieur
- Serveur web (Apache, Nginx, etc.)

## Installation

1. Clonez ce dépôt dans votre répertoire web :
```bash
git clone [URL_DU_REPO]
```

2. Créez une base de données MySQL :
```sql
CREATE DATABASE mon_projet;
```

3. Créez la table utilisateurs :
```sql
USE mon_projet;
CREATE TABLE utilisateurs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

4. Configurez les paramètres de connexion dans `config.php` :
- Modifiez les valeurs de `$host`, `$dbname`, `$username` et `$password` selon votre configuration

5. Assurez-vous que votre serveur web est configuré pour exécuter PHP

## Utilisation

1. Accédez à l'application via votre navigateur web
2. Remplissez le formulaire avec votre nom et email
3. Les données seront sauvegardées dans la base de données

## Structure du projet

- `index.php` : Page d'accueil avec le formulaire
- `config.php` : Configuration de la base de données
- `traitement.php` : Traitement du formulaire et sauvegarde des données 