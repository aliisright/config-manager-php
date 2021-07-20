FROM php:7.4-cli

COPY ./src /src
COPY ./.env /

WORKDIR /src
CMD [ "php", "./test.php" ]
