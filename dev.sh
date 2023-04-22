#!/bin/bash
set -e 

# to assign current folder name to a variable
current_dir=${PWD##*/}

# to correct for the case where PWD=/
current_dir=${current_dir:-/}

# project container names
main_container_name=coop-auth
db_container_name=db-coop-auth

function removeContainers {
    echo "[+] Cleaning up stopped containers..."
    # this trap is like docker ps -q --filter "name=rabbitmq" | grep -q . && echo Found || echo Not Found
    docker ps -a -q --filter "name=${current_dir}_" | grep -q . && \
    # remove containers in Linux host
    (docker rm -v ${current_dir}_${main_container_name}_1 ; docker rm -v ${current_dir}_${db_container_name}_1) || \

    # remove containers in Linux in WSL
    (docker rm -v ${current_dir}-${main_container_name}-1 ; docker rm -v ${current_dir}-${db_container_name}-1)  
}

trap removeContainers EXIT RETURN

# get from composer
docker run --rm --interactive --tty --volume $PWD:/app composer:2.3.10 install

echo "[+] Creating coop-auth-net if not exists"
docker network ls|grep coop-auth-net > /dev/null || docker network create coop-auth-net

echo "[+] Running containers"
docker-compose up ${@}

