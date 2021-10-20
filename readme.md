DDD Starter Pack
=====

![check dependencies](https://github.com/matiux/ddd-starter-pack/actions/workflows/check-dependencies.yml/badge.svg)
![test](https://github.com/matiux/ddd-starter-pack/actions/workflows/test.yml/badge.svg)
[![codecov](https://codecov.io/gh/matiux/ddd-starter-pack/branch/develop/graph/badge.svg)](https://codecov.io/gh/matiux/ddd-starter-pack)
[![type coverage](https://shepherd.dev/github/matiux/ddd-starter-pack/coverage.svg)](https://shepherd.dev/github/matiux/ddd-starter-pack)
[![psalm level](https://shepherd.dev/github/matiux/ddd-starter-pack/level.svg)](https://shepherd.dev/github/matiux/ddd-starter-pack)

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

#### Moduli

* [Aggregate](doc/aggregate.md)
* [Command](doc/command.md)
* [Data transformer](doc/data_transformer.md)
* [Event](doc/event.md)
* [Exception](doc/excpetion.md)
* [Message](doc/message.md)
* [Service](doc/service.md)
* [Util](doc/util.md)

#### TODO
* Prendere spunto da questi progetti per la gestione degli eventi nei modelli di dominio 
    * https://github.com/jkoudys/immutable.php
    * https://github.com/buttercup-php/protects
* Separare le parti infrastrutturali in altre librerie
