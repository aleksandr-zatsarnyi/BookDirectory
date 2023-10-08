# BookDirectory
The project is an interpretation of the MVC architecture

DB<->Repository<->Service<->Controller->View

The project uses docker install: (https://docs.docker.com/desktop/install/linux-install)

1. Install docker
2. Open docker directory on the terminal at the root of the project
3. Run the command - 'docker-compose up -d'.
4. run 'composer install' inside the docker container php-fpm
5. run 'php bin/console doctrine:migration:migrate' inside the docker container php-fpm to install Database tables 
6. get this url http://localhost/welcome to your browser
