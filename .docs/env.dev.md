# Environment - development

## First run

```
# copy `.env.example` to `.env` and fill in your values
cp .env.example .env

# install frontend dependencies
pnpm install

# build javascript + css
pnpm run production

# install php dependencies
composer install

docker-compose exec php ./console nette:cache:purge
docker-compose exec php ./console dbal:database:drop --force
docker-compose exec php ./console dbal:database:create
docker-compose exec php ./console migrations:migrate
docker-compose exec php ./console doctrine:fixtures:load

# go to http://localhost
```

## Make new database migration file

```
docker-compose exec php ./console migrations:diff
```
