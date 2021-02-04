php_container_id = $(shell docker ps --filter name="_php_" -q)

.PHONY: stop
stop:
	docker-compose down

.PHONY: build
build:
	docker-compose up --build -d

.PHONY: start
start:
	docker-compose up -d

.PHONY: bash
bash:
	docker exec -it $(php_container_id) sh

.PHONY: install
install:
# 	docker exec -it $(php_container_id) chmod +x /scripts/symfony_install
	docker exec -it $(php_container_id) sh /scripts/symfony_install