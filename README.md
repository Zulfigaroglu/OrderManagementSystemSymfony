## Setup

1. Run Docker

`` docker-compose up -d``

2. Install dependencies

``docker exec -it oms-app composer install``

3. Run database migrations

``docker exec -it oms-app php bin/console doctrine:migrations:migrate``

4. Visit the [HomePage](http://127.0.0.1:8000/)