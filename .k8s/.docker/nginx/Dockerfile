FROM node:16-slim as node
RUN mkdir -p /app/public/dist
COPY . /app/
WORKDIR /app
RUN yarn install && yarn run production

# ---------------------------

FROM library/nginx:1.19-alpine

WORKDIR /srv/public

COPY --from=node --chown=nginx:nginx /app/public/dist/ /srv/public/dist/

COPY --chown=nginx:nginx ./public /srv/public
