FROM jmoati/php8-cli

RUN apk --update add exiftool bash git postgresql-dev

RUN wget https://raw.githubusercontent.com/composer/getcomposer.org/76a7060ccb93902cd7576b67264ad91c8a2700e2/web/installer -O - -q | php -- --quiet
RUN mv composer.phar /usr/local/bin/composer

RUN docker-php-ext-install pdo_pgsql

#ENTRYPOINT ./bin/console server:start
