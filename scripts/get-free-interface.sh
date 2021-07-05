#!/usr/bin/env bash

# Ищет свободный loopback интерфейс который по сути будет использоваться как пространство для развертывания
# docker-ов проекта на стандартных для сервисов портах

# Ищем свободный интерфейс
for i in `seq 1 255`; do
	netstat -ln | grep "127.0.1.${i}:80" 1>/dev/null
	if [ $? -eq 1 ]; then
		echo -n "127.0.1.${i}"
		break
	fi
done
