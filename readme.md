DDD Starter Pack
=====

![check dependencies](https://github.com/matiux/ddd-starter-pack/actions/workflows/check-dependencies.yml/badge.svg)
![check deps vulnerability](https://github.com/matiux/ddd-starter-pack/actions/workflows/dependencies-vulnerability.yml/badge.svg)
![test](https://github.com/matiux/ddd-starter-pack/actions/workflows/tests.yml/badge.svg)
[![codecov](https://codecov.io/gh/matiux/ddd-starter-pack/branch/v3/graph/badge.svg)](https://codecov.io/gh/matiux/ddd-starter-pack)
[![type coverage](https://shepherd.dev/github/matiux/ddd-starter-pack/coverage.svg)](https://shepherd.dev/github/matiux/ddd-starter-pack)
[![psalm level](https://shepherd.dev/github/matiux/ddd-starter-pack/level.svg)](https://shepherd.dev/github/matiux/ddd-starter-pack)
![Security analysis status](https://github.com/matiux/ddd-starter-pack/actions/workflows/security-analysis.yml/badge.svg)
![Coding standards status](https://github.com/matiux/ddd-starter-pack/actions/workflows/coding-standards.yml/badge.svg)

This library contains "concepts" to development of microservices based on hexagonal architecture and DDD.

* Branch v2: PHP < 8.0

## Development

```bash
git clone git@github.com:matiux/ddd-starter-pack.git && cd ddd-starter-pack
cp docker/docker-compose.override.dist.yml docker/docker-compose.override.yml
rm -rf .git/hooks && ln -s ../scripts/git-hooks .git/hooks
```

```bash
make build-php ARG=--no-cache
make upd
make composer ARG=install
```

## test
```bash
make build-php ARG=--no-cache
make upd
make test
```

This repository uses GitHub actions to perform some checks. If you want to test the actions locally you can
use [act](https://github.com/nektos/act). For example if you want to check the action for static analysis
```bash
act -P ubuntu-latest=shivammathur/node:latest --job static-analysis
```

#### Modules

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
