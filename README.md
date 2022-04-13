# Docker Synfony

### About

Docker compose for php 8 with Synfony 5, OAuth2, MySQL, Mongo, Redis

### Commands

```bash
docker-compose up -d
```

```bash
docker exec -it php bash

~ php bin/console make:migration
~ php bin/console doctrine:migrations:migrate
```
