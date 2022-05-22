# How to install

````bash
bin/console dbal:run-sql "$(cat data/db.sql)" #install initial database
#install sulu page settings
composer sulu:webspace:import

#run database migration
bin/console doctrine:migrations:migrate --no-interaction
````


