DDD Starter Pack
=====

Questa libreria contiene "concetti" per lo sviluppo di micro servizi basati su architettura esagonale e DDD.

* Ramo v2: PHP < 8.0
* Ramo v3: PHP >= 8.0

## Sviluppo

```
git clone git@github.com:matiux/ddd-starter-pack.git && cd ddd-starter-pack
cp docker/docker-compose.override.dist.yml docker/docker-compose.override.yml
rm -rf .git/hooks && ln -s ../scripts/git-hooks .git/hooks
```

#### Entrare nel container PHP per lo sviluppo
```
./dc up -d
./dc enter
composer install
```

## test
Eseguire l'alias `test`

#### Concetti sviluppati

* [Domain](doc/domain.md)
* [Application](doc/application.md)
* [Infrastructure](doc/infrastructure.md)


#### TODO
* Prendere spunto da questi progetti per la gestione degli eventi nei modelli di dominio 
    * https://github.com/jkoudys/immutable.php
    * https://github.com/buttercup-php/protects
* Separare le parti infrastrutturali in altre librerie
