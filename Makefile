.DEFAULT_GOAL = help
port ?= 80


serv: ## Lance le serveur PHP sur le port 80
	@sudo php artisan serve --port=$(port) --host=0.0.0.0	

test: ## Lancer les test unitaires
	@php ./vendor/bin/phpunit

db: ## Lance mysql
	sudo systemctl start mysqld

help: 
	@grep -E '(^[a-zA-Z_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[32m%-10s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'


