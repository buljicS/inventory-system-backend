#!/bin/bash

start=$(date +%s)

source "$(dirname "$0")/build-vars.sh"
DEST_DIR='build'

cd ../

mkdir -p "$DEST_DIR"
cp -rpv app public src templates .env .htaccess composer.json composer.lock firebase.json "$DEST_DIR/"
cd "$DEST_DIR/"

composer install --no-dev --prefer-dist --no-interaction --no-progress  --optimize-autoloader
composer dump-autoload --optimize

sed -i "s/^${ENV_DB_USER}=.*/${ENV_DB_USER}='$(printf '%s\n' "$NEW_DB_USER" | sed 's/[\/&]/\\&/g')'/" ./.env
sed -i "s/^${ENV_DB_PASSWD}=.*/${ENV_DB_PASSWD}='$(printf '%s\n' "$NEW_DB_PASSWD" | sed 's/[\/&]/\\&/g')'/" ./.env
sed -i "s/^${ENV_URL_FE}=.*/${ENV_URL_FE}='$(printf '%s\n' "$NEW_URL_FE" | sed 's/[\/&]/\\&/g')'/" ./.env
sed -i "s/^${ENV_URL_BE}=.*/${ENV_URL_BE}='$(printf '%s\n' "$NEW_URL_BE" | sed 's/[\/&]/\\&/g')'/" ./.env
#sed -i "s|let baseURLBE = \"http://www.insystem-api.localhost\";|let baseURLBE = \"${NEW_URL_BE}\";|g" ./templates/pages/welcome_screen.html
#sed -i "s|let URL_FE = \"https://localhost:3000\";|let URL_FE = \"${NEW_URL_FE}\";|g" ./templates/pages/welcome_screen.html
#sed -i "s|iFrame.setAttribute(\"src\", baseURLBE + currentPath);|iFrame.setAttribute(\"src\", baseURLBE + \"/public/\" + currentPath);|g" ./templates/pages/welcome_screen.html
#sed -i "s|window.open(baseURLBE + currentPath, \"_blank\");|window.open(baseURLBE + \"/public/\" + currentPath, \"_blank\");|g" ./templates/pages/welcome_screen.html

end=$(date +%s)

echo Build done in $((end-start)) seconds
echo 'Project is ready for deployment'