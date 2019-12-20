Pando environment
==================================


## Overview

1. [Install prerequisites](#install-prerequisites)

    Before installing project make sure the following prerequisites have been met.

3. [Run the application](#run-the-application)

    By this point we’ll have all the project pieces in place.

4. [Use Docker Commands](#use-docker-commands)

    When running, you can use docker commands for doing recurrent operations.
    
___

## Install prerequisites

For now, this project has been mainly created for Unix `(Linux/MacOS)`. Perhaps it could work on Windows.

All requisites should be available for your distribution. The most important are :

* [Git](https://git-scm.com/downloads)
* [Docker](https://docs.docker.com/engine/installation/)
* [Docker Compose](https://docs.docker.com/compose/install/)

Check if `docker-compose` is already installed by entering the following command : 

```sh
which docker-compose
```

On Ubuntu and Debian these are available in the meta-package build-essential. On other distributions, you may need to install the GNU C++ compiler separately.

```sh
sudo apt install build-essential
```
### Images to use

* [Nginx](https://hub.docker.com/_/nginx)
* [MariaDB](https://hub.docker.com/_/mariadb)
* [PHP](https://hub.docker.com/_/php)
* [PHPMyAdmin](https://hub.docker.com/r/phpmyadmin/phpmyadmin/)
* [composer](https://hub.docker.com/_/composer)

This project use the following ports :

| Server     | Port |
|------------|------|
| mariadb    | 3306 |
| PHPMyAdmin | 8080     |
| nginx     | 80|
| composer   | 15672 |



___

## Clone the project

To install [Git](http://git-scm.com/book/en/v2/Getting-Started-Installing-Git), download it and install following the instructions :

```sh
git clone https://github.com/MarshallJamesRaynor/pando.git
```

Go to the project directory :

```sh
cd pando
```

___

## Run the application
### Makefile

One of the main features of this project is the ability to manage the environment from the project directory itself.
The starter includes a Makefile to simplify the basic tasks:

1. Start the application :

    ```sh
    make start
    ```

    **Please wait this might take a several minutes...**
    ```sh
    make hosts
    ```


3. Open your favorite browser :

    * [http://pando.pando](http://pando.pando/)
    * [http://phpmyadmin.pando](http://phpmyadmin.pando/) phpmyadmin (server:mariadb, username: tree, password: tree)

4. Stop and clear services

    ```sh
     make stop
    ```

Here are  other several commands that you can now use from your project directory.

 ----------------------------------------------------------------------------
   Environment
 ----------------------------------------------------------------------------

- `build`                          Build the environment
- `rebuild`                        force a rebuild by passing --no-cache
- `logs`                           Follow logs generated by all containers
- `logs-full`                      Follow logs generated by all containers from the containers creation
- `php`                            Open a terminal in the "php" container
- `nginx`                          Open a terminal in the "nginx" container
- `ps`                             List all containers managed by the environment
- `start`                          Start the environment
- `stats`                          Print real-time statistics about containers ressources usage
- `stop`                           Stop the environment
- `hosts`                          Create value in the hosts
- `destroy`                        destroy the environment
- `prune`                          clean all that is not actively used
- `help`                           show list command


## Help me

Any thought, feedback or (hopefully not!) issue.

# Recommendations #

It's hard to avoid file permission issues when fiddling about with containers due to the fact that,
 from your OS point of view, any files created within the container are owned by the process that runs the docker engine (this is usually root).
  Different OS will also have different problems, for instance you can run stuff in containers using `docker exec -it -u $(id -u):$(id -g) CONTAINER_NAME COMMAND`
   to force your current user ID into the process, but this will only work if your host OS is Linux, not mac. Follow a couple of simple rules and save yourself a world of hurt.

  * Run composer outside of the php container, as doing so would install all your dependencies owned by `root` within your vendor folder.
  * Run commands straight inside of your container. You can easily open a shell as described above and do your thing from there.
  * Run composer command using the composer container.you can run stuff in containers using `docker-compose run --rm composer install`