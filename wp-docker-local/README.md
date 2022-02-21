# Minimalist dev setup using Docker, Docker compose and Traefik
1. run ´docker-compose up -d --remove-orphans´ in the root folder, to start our services.
2. run ´docker-compose up -d --remove-orphans --build' inside a /www/a folder, to spin up the app. (we need build, to make sure our custom container with apache has been build)
3. Go to [a.localhost](a.localhost)
4. See Traefiks dashboard at [http://localhost:8080/dashboard/#/](http://localhost:8080/dashboard/#/)
5. cd into the app, and root and run ´docker-compose stop` to stop the containers again


## Import DB Example
```
docker exec -i <container_name> sh -c 'exec mysql -uroot -proot <db_name>' < db_dumps/example.sql
```


What is installed globally.
1. traefik as a reverse-proxy
2. mysql:5.7 as database service
3. [http://phpmyadmin.localhost/](http://phpmyadmin.localhost)
4. Redis
5. [http://redis-commander.localhost](http://redis-commander.localhost)
6. redis-commander.localhost
7. @TODO elasticsearch
8. @TODO kibana for elasticsearch


What is installed per app.
1. webservice - running apache