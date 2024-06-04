# Install Magento By docker

-   Steps to install:
    -   You make sure that you have installed docker on your computer
    -   Move dump with db into folder `db_dumps`
    -   Copy `.env` file to your own project
        -   If needed change configuration inside `.env` file, db connection for example
    -   Check `docker-compose.yaml` file in root directory and make sure that you don't use ports that wrote in the file
    -   Go to your folder with the project and run command `docker compose up`
    -   Go to docker container, run command `docker compose exec app bash` and then run commands step by step:
        -   `sudo -u www-data bin/magento setup:config:set --cache-backend=redis --cache-backend-redis-server=/var/run/redis/redis-server.sock --cache-backend-redis-db=0`
        -   `sudo -u www-data bin/magento setup:config:set --page-cache=redis --page-cache-redis-server=/var/run/redis/redis-server.sock --page-cache-redis-db=1`
        -   `sudo -u www-data bin/magento setup:config:set --session-save=redis --session-save-redis-host=/var/run/redis/redis-server.sock --session-save-redis-db=2`
    -   Go to docker container with your project and run the command `build`
        -   If the command not running, run before `sudo -u www-data composer install`
    -   Go to your browser and try to open project `http://localhost:81/`
        -   Check your ports if needed
