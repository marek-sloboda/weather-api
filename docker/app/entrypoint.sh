#!/bin/sh
composer install
chmod 777 -R var/*
exec "$@"