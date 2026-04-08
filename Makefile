build:
	docker compose build

composer-install:
	docker compose run --rm app composer install

test:
	docker compose run --rm app ./vendor/bin/phpunit

shell:
	docker compose run --rm app bash
