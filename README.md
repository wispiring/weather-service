# Weather serivce

The weather service provides weather information.

## Installation

#### Install dependencies
```
composer install --prefer-dist
```
#### Copy configuration files
make a copy of the configuration files, then change them accordingly
```
cp app/config/parameters.yml.dist app/config/parameters.yml
cp app/config/security.json.dist app/config/security.json
```
#### Initialize database
```
vendor/bin/dbtk-schema-loader schema:load app/config/schema.xml mysql://username:password@localhost/[dbname] --apply
```

## Run the web server via PHP
```
./bin/run.sh
```
## Get weather info via the command line
```
app/console weather:fetch "[city name]"
```
