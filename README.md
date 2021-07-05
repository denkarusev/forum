## <a name="requirements"></a> Системные требования

* Ubuntu 20.04.2 LTS
* make
* [Docker ≥ 18.06.0](https://docs.docker.com/engine/install/)
* [Docker-compose ≥ 1.25.4](https://docs.docker.com/compose/install/)

## <a name="install"></a> Установка
Инсталяция проходит разово выполнением действий:
1. Создаем файл с настройками приложения:
    ```bash
    make env
    ```
   который создаст `.env` и который в случае необходимости следует отредактировать.

2. Выполняем установку и сборку проекта (требуется `sudo`):
    ```bash
    make install
    make build
    make up
    make prepare-app
    ```

## <a name="up"></a> Запуск
Если "[Установка](#install)" прошла корректно, то запускаем командой:
```bash
make up
```
