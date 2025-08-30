# Name des Docker-Images
IMAGE=tturkowski/fruits-and-vegetables
WORKDIR=/app
VOLUME=$(PWD):/app

.PHONY: connect tests server_up server_down server_logs composer dumpautoload

connect:
	docker run -it -w $(WORKDIR) -v $(VOLUME) $(IMAGE) sh

tests:
	docker run -it -w $(WORKDIR) -v $(VOLUME) $(IMAGE) bin/phpunit

server_up:
	docker run -d -w $(WORKDIR) -v $(VOLUME) -p 8080:8080 --name fruits_dev $(IMAGE) \
		php -S 0.0.0.0:8080 -t /app/public

server_down:
	docker stop fruits_dev || true
	docker rm fruits_dev || true

server_logs:
	docker logs -f fruits_dev

composer:
	docker run --rm -it $(USER_FLAGS) -w $(WORKDIR) -v $(VOLUME) \
		-e COMPOSER_HOME=/tmp -e COMPOSER_CACHE_DIR=/tmp/composer \
		$(IMAGE) sh -c "composer install && composer update"

dumpautoload:
	docker run --rm -it $(USER_FLAGS) -w $(WORKDIR) -v $(VOLUME) \
		-e COMPOSER_HOME=/tmp -e COMPOSER_CACHE_DIR=/tmp/composer \
		$(IMAGE) sh -c "composer dump-autoload -o"