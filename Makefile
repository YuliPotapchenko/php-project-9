PORT ?= 8000

start:
	php -S 0.0.0.0:$(PORT) -t public public/index.php
setup:
	composer install
lint:
	composer exec --verbose phpcs -- --standard=PSR12 src config
