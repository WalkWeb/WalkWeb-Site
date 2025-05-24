PHP_BIN := php

migration:
	migrations/bin/run

fixture:
	migrations/bin/fixtures

cs:
	$(PHP_BIN) vendor/bin/php-cs-fixer fix src

stan:
	$(PHP_BIN) vendor/bin/phpstan analyse src

test:
	$(PHP_BIN) vendor/bin/phpunit

coverage:
	$(PHP_BIN) vendor/bin/phpunit --coverage-html html

add-migration:
	migrations/bin/create
