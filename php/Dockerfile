FROM php:7.4-cli

COPY ./php/index.php /usr/src/app/index.php

WORKDIR /usr/src/app

# web-server port
EXPOSE 8000

# install PDO MySQL extension
RUN docker-php-ext-install pdo_mysql

CMD ["php","-S","0.0.0.0:8000"]
