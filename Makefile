.DEFAULT_GOAL = help
port ?= 80

# the -m info of git commit
info ?= "Updated Makefile"


serv: ## Start the PHP server on port 80
	@sudo php artisan serve --port=$(port) --host=0.0.0.0

db: ## Start MariaDB
	sudo systemctl start mariadb

git: ## Add, Commit and Push
	git add -A && git commit -m $(info) && git push

link:
	php artisan storage:link

help:
	@grep -E '(^[a-zA-Z_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[32m%-10s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'


