FROM node:16-slim as node
RUN mkdir -p /app/public/dist
COPY . /app/
WORKDIR /app
RUN yarn install && yarn run production

# ---------------------------

FROM davidsolc/php:8.1-fpm

RUN chown www-data:www-data -R /srv
USER www-data
WORKDIR /srv

COPY --chown=www-data:www-data ./composer.json .
COPY --chown=www-data:www-data ./composer.lock .

RUN composer install --no-progress --no-scripts --prefer-dist

COPY --from=node --chown=www-data:www-data /app/public/dist/ /srv/public/dist/

COPY --chown=www-data:www-data . /srv/

RUN composer dump-autoload --optimize --no-scripts
