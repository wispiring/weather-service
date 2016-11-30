#!/bin/sh
PORT=${1:-'8080'}
php -S localhost:$PORT -t web web/index.php
