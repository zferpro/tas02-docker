#!/bin/bash
# Exit inmediatamente si un comando falla
set -e
echo "=================================================="
echo " Iniciando Setup automizado CMS"
echo "=================================================="

if [ ! -f .env ]; then
    echo "ERROR: No se encontro el archivo .env"
    echo "Asegurese de crear el archivo .env antes de ejecutar este script"
    exit 1
fi

export $(grep -v '^#' .env | xargs)
TARGET_CONTAINER="${CONTAINER_NAME}"

#Levantando contenedores
echo "[1/4] Levantando contenedores con docker compose"
docker compose up -d

#Esperar que se inicien los contenedores
echo "[2/4] Esperando Inializacion de los servicios"
sleep 10

echo "[3/4] Sincronizando archivos de wordpress en ${TARGET_CONTAINER} (themes,plugins)"

#copiar los temas bases al backend
docker cp wp-content/themes/. "${TARGET_CONTAINER}:/var/www/html/wp-content/themes/"

#copiar los plugins bases al backend
docker cp wp-content/plugins/. "${TARGET_CONTAINER}:/var/www/html/wp-content/plugins/"


echo "[4/4] Asegurando permisos del usuario (www-data) para acceder a los archivos"

#Asignar permisos ara que www-data pueda leer los archivos de wp-content
docker exec "${TARGET_CONTAINER}" chown -R www-data:www-data /var/www/html/wp-content


echo "=================================================="
echo " Completado correctamente! "
echo " Access URL: https://nodosur.tas02.lab"
echo "=================================================="