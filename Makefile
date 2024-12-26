SHELL:=/bin/bash
include .env
export

deploy-image:
	docker save karlhillcom-app | bzip2 | pv | ssh karl@$$PRODUCTION docker load
	

ssh:
	ssh karl@$$PRODUCTION
