# BookDirectory
The project is an interpretation of the MVC architecture

DB<->Repository<->Service<->Controller->View

The project uses docker install: (https://docs.docker.com/desktop/install/linux-install)

Install docker
Open docker directory on the terminal at the root of the project
Run the command - docker-compose up -d.
run composer install inside the docker container php-fpm
run php bin/console doctrine:migration:migrate inside the docker container php-fpm to install Database tables 
than get this url http://localhost/welcome to your browser
