
# Aplicação Comerc Energia

### 1- Instalação do Docker
https://www.docker.com/products/docker-desktop/

### 2- Clone o projeto do git 
git clone https://github.com/ocdigital/comerc.git

### 3- Entre na pasta do projeto 
cd comerc  

### 4- Compilar a imagem do aplicativo
docker-compose build

### 5- Execute o ambiente em modo de segundo plano
docker-compose up -d

### 6- Instalar as dependências do aplicativo
composer install

### 7- Crie o arquivo .env
cp .env.example .env

### 8- Gere uma chave única para o aplicativo
php artisan key:generate
	
### 9- Teste no endereço
http://localhost:8000

### 10- Veja os containers
docker ps

### 11- Entre no container da aplicação
docker exec -it nomedocontainer /bin/bash

### 12- Rode a migrate para criar as tabelas
php artisan migrate

### 13-Execute a seeder para gerar os produtos
php artisan db:seed --class=ProdutoSeeder

php artisan db:seed --class=ClienteSeeder

### 14-Execute os testes
php artisan test

### Collection Postman
Na pasta raiz existe o arquivo Comerc.postman_collection.json
