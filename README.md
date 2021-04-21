# API - Data importer
## Installation

```sh
clone repository
run docker-compose up -d
create two databases, for testing and for working
run composer install
prepare .env and .env.test
run php bin/console doctrine:migrations:migrate

for testing:
prepare phpunit.xml.dist > phpunit.xml 
run php bin/phpunit
```

## API Endpoints

METHOD | Url | Description |
| ----- | ------ | ------ |
| GET | api/customers | Retrieve the list of all customers from the database.
| GET | api/customers/{id} | Retrieve more details of a single customer.

## Commands
Import customers
```sh
php bin/console app:randomuser-import 
```