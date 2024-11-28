#!/bin/bash -e

cd `dirname $0`

if [ ! -e .env ]; then
  cp .env.example .env
fi

if [ ! -e application/.env ]; then
  cp application/.env.example app/.env
fi

docker compose up -d --build
