PORTS = -p 127.0.0.1:80:80
VOLUMES =
ENV =
NS = dynedjakartacontainers
VERSION ?= 1.0.0
ENV_FILE = .env

REPO = liveb2i
NAME = liveb2i
INSTANCE = default

LOCATION = us.gcr.io
PROJECT_ID = iron-hull-144620

.PHONY: build push shell run start stop rm release

default: build

build:
	docker build -t $(NS)/$(REPO):$(VERSION) -f Dockerfile .
	#docker build --no-cache=true -t $(NS)/$(REPO):$(VERSION) -f Dockerfile .

push:
	docker push $(NS)/$(REPO):$(VERSION)

gcloudpush:
	docker tag $(NS)/$(REPO):$(VERSION) $(LOCATION)/$(PROJECT_ID)/$(REPO):$(VERSION)
	gcloud docker -- push $(LOCATION)/$(PROJECT_ID)/$(REPO):$(VERSION)

shell:
	docker run --rm --name $(NAME)-$(INSTANCE) -i -t $(PORTS) $(VOLUMES) $(ENV) --env-file $(ENV_FILE) $(NS)/$(REPO):$(VERSION) /bin/bash

run:
	docker run --rm --name $(NAME)-$(INSTANCE) $(PORTS) $(VOLUMES) $(ENV) --env-file $(ENV_FILE) $(NS)/$(REPO):$(VERSION)

start:
	docker run -d --name $(NAME)-$(INSTANCE) $(PORTS) $(VOLUMES) $(ENV) --env-file $(ENV_FILE) $(NS)/$(REPO):$(VERSION)

stop:
	docker stop $(NAME)-$(INSTANCE)

rm:
	docker rm $(NAME)-$(INSTANCE)

release: builddocker
	make push -e VERSION=$(VERSION)
