FROM mariadb:10.5

RUN apt update && apt install pv && rm -rf /var/lib/apt/lists/*

ARG CURRENT_USER
ARG CURRENT_GROUP

RUN if [ -n "${CURRENT_USER}" ]; then usermod -u "${CURRENT_USER}" mysql; fi &&\
    if [ -n "${CURRENT_GROUP}" ]; then groupmod -g "${CURRENT_GROUP}" mysql; fi