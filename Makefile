install:
	composer install
gendiff:
	./bin/gendiff -h
validate:
	composer validate
lint:
	composer exec --verbose phpcs -- --standard=PSR12 src bin
test:
	vendor/bin/phpunit tests/Differ/DifferTest


