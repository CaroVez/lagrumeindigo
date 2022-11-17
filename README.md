# ÉVALUATION EN COURS DE FORMATION
## l'agrume indigo
Dans le cadre de ma formation à distance, j’ai développé une application web de gestion de droit d’accès. Je devais donc créer différents rôles et réaliser une interface adaptable à tous les formats d’écran, personnalisée au type d’utilisateur. Le sujet imposé : Une grande Marque de salle de sport souhaite la création d’une application web pour son équipe qui gère leurs différentes franchises et leur donner accès aux outils en ligne.


## Déploiement en local

Cloner le projet
```bash
  git clone https://github.com/CaroVez/lagrumeindigo.git
```

Installer les composants
```bash
  composer install
```

Après avoir créer votre base de données à l'aide de XAMPP, créer un fichier .env.local à la racine du projet, puis indiquer vos informations
```bash  
  DATABASE_URL="mysql://USER:PASSWORD@127.0.0.1:3306/DATABASE?serverVersion=mariadb-10.4.11"
```
Ensuite, faites les migrations
```bash  
  symfony console doctrine:migrations:migrate
```


## Déploiement en ligne à l'aide d'Heroku

Cloner le projet et créer un nouveau dossier sur GitHub
```bash
  git clone https://github.com/CaroVez/lagrumeindigo.git
```

Créer le projet sur Heroku et installer les packages (nécéssite d'avoir un compte)
```bash
  hekoru login
  heroku create NomDuProjet
  heroku buildpacks:set heroku/php
  heroku addons:create jawsdb:kitefin
```

Pour avoir les informations de votre nouvelle base de données
```bash
  heroku config:get JAWSDB_URL
```

Puis, envoyer le tout sur Heroku
```bash
  git push heroku master
```


## Liens

- [Heroku](https://www.heroku.com)

