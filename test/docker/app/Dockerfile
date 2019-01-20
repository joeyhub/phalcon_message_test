# Note: This is not a trusted source.
FROM mileschou/phalcon:7.2-cli
COPY --from=composer:1.8 /usr/bin/composer /usr/bin/composer
WORKDIR /app
# Note: Was surprised to find build dependencies left in the parent image.
RUN pecl install mongodb && echo extension=mongodb.so > /usr/local/etc/php/conf.d/mongodb.ini
