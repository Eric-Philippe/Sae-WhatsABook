#!/bin/bash

echo "****************************************************"
echo "* 1. Installer toutes les dépendances du projet    *"
echo "* 2. Lancer Symfony server                         *"
echo "* 3. Lancer le serveur de développement Angular    *"
echo "* 4. Lancer le nettoyage des réservations expirées *"
echo "* 5. Migrer la base de données et la remplir       *"
echo "* 6. Créer la Database MariaDB                     *"
echo "* 0. Quitter                                       *"
echo "****************************************************"

read -p "Entrez votre choix : " choice

case $choice in
  1)
    # Installer les dépendances dans chaque dossier
    echo "Installation des dépendances dans le dossier server..."
    (cd server && composer install)
    
    echo "Installation des dépendances dans le dossier frontend..."
    (cd frontend && npm install)
    
    echo "Installation des dépendances dans le dossier services..."
    (cd services && npm install)

    echo -e "\033[0;32mToutes les dépendances ont été installées avec succès.\033[0m"
    ;;
  
  2)
    # Lancer Symfony server
    (cd server && symfony server:start --port=8008)
    ;;
  
  3)
    # Lancer ng serve
    (cd frontend && ng serve)
    ;;
  
  4)
    # Lancer npm start
    (cd services && npm start)
    ;;

  5)
    # Migrer la base de données et la remplir
    echo "Migrer la base de données..."
    (cd server && php bin/console doctrine:migrations:migrate)
    
    echo "Remplir la base de données..."
    (cd server && php bin/console doctrine:fixtures:load)
    ;;
  6)
    DB_USER="root"
    DB_PASSWORD="root"
    DB_NAME="WhatsABook"

    # Create Database Schema
    mysql -u $DB_USER -p$DB_PASSWORD -e "CREATE DATABASE IF NOT EXISTS $DB_NAME;"

    echo "Database schema '$DB_NAME' created successfully!"
  ;;
  0)
    echo "Sortie du script"
    ;;
  
  *)
    echo "Choix invalide. Sortie du script."
    ;;
esac
