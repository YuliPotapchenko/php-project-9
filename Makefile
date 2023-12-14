PORT ?= 8000

start:
	php -S 0.0.0.0:$(PORT) -t public public/index.php

setup:
	composer install

migrate:
	php bin/console migrate