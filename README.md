# Knarf Media

[![forthebadge](http://forthebadge.com/images/badges/built-with-love.svg)](http://forthebadge.com)

Plateforme web de gestion de publications développée avec le framework Symfony 3.4

## Pour commencer

Un serveur local type LAMP

### Pré-requis

- PHP7
- Composer
- MySQL / PostGreSQL
- Node.js
- Yarn

### Installation

- Cloner le dépôt Github https://github.com/knarficious/knarfmedia.git
- Installer les dépendances avec Composer 'composer install'
- Créer la BDD et mettre à jour avec la commande 'php bin/console doctrine:schema:update --force'
- Installer ckeditor, webpack avec la commande 'yarn install'
- Installer ckeditor avec la commande 'php bin/console ckeditor:install'
- Installer les assets avec la commande 'php bin/console assets:install --symlink'
- (Optionnel) Installer les fixtures avec la commande 'php bin/console doctrine:fixtures:load'

## Utilisation

Pointer votre serveur local vers le dossier /web du projet


## Fabriqué avec

Symfony3.4
Composer
Bootstrap 3
Webpack
HTML5
CSS3
Javascript
JQuery


## Contributing


## Versions

1.2

## Auteur

* **Franck Ruer** _alias_ [@knarficious](https://github.com/knarficious)

## License

Ce projet est sous licence - voir le fichier [LICENSE.md](LICENSE.md) pour plus d'informations


