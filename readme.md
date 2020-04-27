DDD Starter Pack
=====

Questa libreria contiene "concetti" per lo sviluppo di micro servizi basati su architettura esagonale e DDD.

## Sviluppo

#### Entrare nel container PHP per lo sviluppo
```
./dc up -d
./dc enter
composer install
```

## test
Eseguire l'alias `test` per lanciare `vendor/bin/phpunit`

#### Gruppi di test
TODO

#### Concetti sviluppati

* [Domain](doc/domain.md)
* [Application](doc/application.md)
* [Infrastructure](doc/infrastructure.md)


#### TODO
* Prendere spunto da questi progetti per la gestione degli eventi nei modelli di dominio 
    * https://github.com/jkoudys/immutable.php
    * https://github.com/buttercup-php/protects
* Test per i modelli Criteria
* Test per il salvataggio bulk degli eventi
* Test per i data transformer
* Refactoring `MessageProducerFactory` con dettagli infrastrutturali
* Refactoring `MessageConsumerFactory` con dettagli infrastrutturali
