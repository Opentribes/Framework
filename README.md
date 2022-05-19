# How to install

````bash
bin/console dbal:run-sql "$(cat data/db.sql)" #install initial database
#install sulu page settings
bin/adminconsole doctrine:phpcr:workspace:import -p / data/cmf.xml
bin/websiteconsole doctrine:phpcr:workspace:import -p / data/cmf_live.xml
bin/adminconsole doctrine:phpcr:workspace:import -p / data/jcr.xml

#run database migration
bin/console doctrine:migrations:migrate --no-interaction

#load fixtures
doctrine:fixture:load
````
