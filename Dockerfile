FROM laravelsail/php82-composer

# Instala as dependências necessárias e habilita o pdo_mysql
RUN apt-get update && apt-get install -y default-mysql-client \
    && docker-php-ext-install pdo_mysql
