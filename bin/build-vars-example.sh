#!/bin/bash

# template for creating production parameters that are gonna replace current ones in .env by running build.sh

# vars with prefix ENV_ are names of environment variables to be replaced
# vars with prefix NEW_ are actual values of environment variables to be replaced
# they are gonna be parsed as ENV_MY_VAR=NEW_MY_VALUE so watch out for naming conventions

ENV_DB_USER='DB_USER'
NEW_DB_USER='production_db_user' # user name of production database

ENV_DB_PASSWD='DB_PASSWORD'
NEW_DB_PASSWD='production_db_password' # password for production database

ENV_URL_FE='MAIN_URL_FE'
NEW_URL_FE='production_fe_url' # production frontend url

ENV_URL_BE='MAIN_URL_BE'
NEW_URL_BE='production_be_url' # production website url

ENV_IS_SECURE='IS_SECURE'
NEW_IS_SECURE='does_production_env_uses_https' # bool (true - https, false - http)