#!/bin/bash

export COMPOSE_PROJECT_NAME=pando

NETWORK=${COMPOSE_PROJECT_NAME}_pando

HOST=pando

nginx_cntr=`docker-compose ps -q nginx`
nginx_ip=`docker inspect -f "{{.NetworkSettings.Networks.${NETWORK}.IPAddress}}" $nginx_cntr`

phpmyadmin_cntr=`docker-compose ps -q phpmyadmin`
phpmyadmin_ip=`docker inspect -f "{{.NetworkSettings.Networks.${NETWORK}.IPAddress}}" $phpmyadmin_cntr`

sudo -p "[sudo] Need admin privileges to write to /etc/hosts: " -s -- <<EOF

# Remove existing host mappings
sed -i "/${HOST}/d" /etc/hosts

echo "${nginx_ip} ${COMPOSE_PROJECT_NAME}.${HOST} " >> /etc/hosts
echo "${phpmyadmin_ip} phpmyadmin.${HOST}" >> /etc/hosts

EOF