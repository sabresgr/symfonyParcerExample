# Synfony csv parcer

Tested on:
- Ubuntu 18.04
- PHP 7.2.19


## Installation

```
git clone https://github.com/sabresgr/symfonyParcerExample.git
cd symfonyParcerExample
composer install
```

## Settings
 Change DB user and DB password in .env
```
DATABASE_URL=mysql://USER:PASSWORD@127.0.0.1:3306/importTest
```

## Call example

```
php bin/console app:parser  ~/Downloads/stock.csv
```
Output example (not full, because small screen)

![Alt text](output_example.png?raw=true "Title")

## Test mode call

```
php bin/console app:parser  ~/Downloads/stock.csv --test
```

## Tests Call

```
./bin/phpunit
```
