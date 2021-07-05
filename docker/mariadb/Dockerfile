FROM mariadb:10.5

ARG UID
ARG GID
RUN usermod -u ${UID} mysql
RUN groupmod -g ${GID} mysql
