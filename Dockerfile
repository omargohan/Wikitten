FROM php:7.2-cli
RUN mkdir -p /var/www
WORKDIR /var/www
COPY . /var/www/
EXPOSE 8080
CMD [ "php", "-S", "0.0.0.0:8080", "routing.php" ]
