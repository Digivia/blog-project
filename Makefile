include .env
include .env.local
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

.PHONY: jwt
generate-jwt-certs:
ifeq ($(JWT_PASSPHRASE),)
	$(error Vous devez définir la variable JWT_PASSPHRASE dans votre fichier .env)
endif
	openssl genrsa -out var/jwt/private.pem -aes256 -passout pass:$(JWT_PASSPHRASE) 4096
	openssl rsa -pubout -in var/jwt/private.pem -passin pass:$(JWT_PASSPHRASE) -out var/jwt/public.pem
