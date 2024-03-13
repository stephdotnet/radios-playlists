.PHONY: init
init:
	cp .env.example .env

rights:
	sudo chmod o+w ./storage/ -R
	sudo chown www-data:www-data -R ./storage
