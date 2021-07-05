#!/usr/bin/env bash

for PORT in 80 3306 8090; do
    netstat -ln | grep "0.0.0.0:${PORT} " 1>/dev/null
    if [ $? -eq 0 ]; then
        echo "Работа невозможна, остановите службу висящую на 0.0.0.0:${PORT} порту"
        exit 1
        break
     fi
    netstat -ln | grep "${PROJECT_INTERFACE}:${PORT}" 1>/dev/null
    if [ $? -eq 0 ]; then
        echo "Работа невозможна, остановите службу висящую на ${PROJECT_INTERFACE}:${PORT} порту"
        exit 1
        break
     fi
done
