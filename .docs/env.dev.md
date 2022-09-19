# Environment - development

## First run

```
docker-compose exec php ./console nette:cache:purge
docker-compose exec php ./console dbal:database:drop --force
docker-compose exec php ./console dbal:database:create
docker-compose exec php ./console migrations:migrate
docker-compose exec php ./console doctrine:fixtures:load
```

```
docker-compose exec php ./console migrations:diff
```
