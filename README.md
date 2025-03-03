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
docker compose up

# install php dependencies
docker compose exec php composer install

# run commands
docker compose exec php ./console nette:cache:purge
docker compose exec php ./console migrations:migrate -n
docker compose exec php ./console doctrine:fixtures:load -n

# run tests
docker compose exec php vendor/bin/phpunit

# show code coverage
docker compose exec php php -d xdebug.mode=coverage ./vendor/bin/phpunit --coverage-text
docker compose exec php php -d xdebug.mode=coverage ./vendor/bin/phpunit --coverage-html ./tests/.coverage

# go to http://localhost
```

## Tools

### PHP Parallel Lint

- https://github.com/php-parallel-lint/PHP-Parallel-Lint

```shell
docker compose exec php ./vendor/bin/parallel-lint --exclude ./.git --exclude ./vendor --exclude ./var .
```

### ECS

- https://github.com/easy-coding-standard/easy-coding-standard

```shell
docker compose exec php ./vendor/bin/ecs
docker compose exec php ./vendor/bin/ecs --fix
```

### PHPStan

- https://phpstan.org/

```shell
docker compose exec php ./vendor/bin/phpstan
```

### Rector

- https://getrector.com/find-rule

```shell
docker compose exec php ./vendor/bin/rector --dry-run
docker compose exec php ./vendor/bin/rector
```

### Class leak

- https://github.com/TomasVotruba/class-leak

```shell
docker compose exec php ./vendor/bin/class-leak check src
```

### Swiss knife

- https://github.com/rectorphp/swiss-knife

```shell
docker compose exec php ./vendor/bin/swiss-knife check-conflicts ./src
docker compose exec php ./vendor/bin/swiss-knife check-commented-code ./src --line-limit 10
docker compose exec php ./vendor/bin/swiss-knife find-multi-classes ./src
docker compose exec php ./vendor/bin/swiss-knife namespace-to-psr-4 ./src/VsPoint --namespace-root "VsPoint\\"
docker compose exec php ./vendor/bin/swiss-knife namespace-to-psr-4 ./tests --namespace-root "VsPoint\\Test\\"
docker compose exec php ./vendor/bin/swiss-knife finalize-classes ./src ./tests
docker compose exec php ./vendor/bin/swiss-knife privatize-constants ./src ./test
```
