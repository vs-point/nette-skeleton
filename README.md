# VS-point

## Table of contents

- [Participating](#Participating)
- Environments
  - [development](.docs/env.dev.md)

## Participating

##### Vít Steklý
- jednatel
- stekly.vit@vs-point.cz

##### David Šolc
- programátor
- david.solc@vs-point.cz

## Technologies

### Infrastructure

- https://docs.docker.com
- https://docs.docker.com/compose/
- https://www.php.net/
- https://pnpm.io/
- https://getcomposer.org/

### Common

- https://editorconfig.org/

### FrontEnd - Javascript (TypeScript) + CSS

#### Lint / Format

- https://eslint.org/
- https://stylelint.io/
- https://prettier.io/

#### Build

- https://postcss.org/
- https://webpack.js.org/
- https://babeljs.io/
- https://github.com/browserslist/browserslist

### PHP

#### Tools

- https://phpstan.org/
- https://github.com/symplify/easy-coding-standard
  - https://github.com/squizlabs/PHP_CodeSniffer
  - https://github.com/FriendsOfPHP/PHP-CS-Fixer
- https://getrector.org/

#### Framework / Libraries

- https://nette.org/
- https://symfony.com/
- https://www.doctrine-project.org/
- https://phpunit.de/

## First run

```console
# copy `.env.example` to `.env` and fill in your values
cp .env.example .env

# install frontend dependencies
pnpm install

# build javascript + css
pnpm run production

# start containers via Docker
docker-compose up

# install php dependencies
docker-compose exec php composer install

docker-compose exec php ./console nette:cache:purge
docker-compose exec php ./console dbal:database:drop --force
docker-compose exec php ./console dbal:database:create
docker-compose exec php ./console migrations:migrate
docker-compose exec php ./console doctrine:fixtures:load

# go to http://localhost
```
