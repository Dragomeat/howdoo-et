FROM php:7.4.1-cli-alpine as base

RUN apk add --no-cache \
    libzip-dev \
    postgresql-dev \
    unzip \
    procps \
    inotify-tools \
  && docker-php-ext-install zip pdo pdo_pgsql \
  && docker-php-ext-install opcache \
  && docker-php-ext-enable opcache

WORKDIR /app

EXPOSE 80

FROM base as composer

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
  && php -r "if (hash_file('SHA384', 'composer-setup.php') === rtrim(file_get_contents('https://composer.github.io/installer.sig'))) { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;" \
  && php composer-setup.php \
  && php -r "unlink('composer-setup.php');" \
  && mv composer.phar /usr/local/bin/composer

ENV COMPOSER_ALLOW_SUPERUSER 1

COPY composer.json .
COPY composer.lock .

RUN composer install -o --no-dev --prefer-dist \
  && vendor/bin/rr get-binary

FROM base as production

COPY --from=composer /app/vendor /app/
COPY --from=composer /app/rr /usr/local/bin/

COPY . .

CMD ["/usr/local/bin/rr", "serve", "-c", ".rr.yaml"]

FROM base as development

COPY --from=composer /app/rr /usr/local/bin/
COPY --from=composer /usr/local/bin/composer /usr/local/bin/
COPY ./docker-dev-entrypoint.sh /usr/local/bin/entrypoint

CMD ["entrypoint"]
