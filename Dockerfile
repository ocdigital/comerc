# Use a imagem oficial do PHP com Apache
FROM php:8.1-apache

# Habilitar mod_rewrite para Laravel
RUN a2enmod rewrite

# Instalar extensões PHP necessárias
RUN docker-php-ext-install pdo pdo_mysql

# Instalar extensões MySQL
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Configurar as variáveis de ambiente do Apache
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Copiar os arquivos do aplicativo para o contêiner
COPY . /var/www/html

# Instalar as dependências do Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Instalar as dependências do Laravel
RUN composer install

# Definir o usuário do Apache como o proprietário dos arquivos Laravel
RUN chown -R www-data:www-data /var/www/html

# Expor a porta 80
EXPOSE 80

# Comando para iniciar o Apache
CMD ["apache2-foreground"]
