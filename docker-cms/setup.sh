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


mkdir -p haproxy/certs

# Generar certificado para HAproxy
if [ ! -f haproxy/certs/nodosur.pem ]; then
    echo "[1/5] Generando certificado SSL autosignado para HAProxy"
    openssl req -x509 -nodes -days 365 -newkey rsa:2048 \
        -keyout /tmp/nodosur.key \
        -out /tmp/nodosur.crt \
        -subj "/C=CL/ST=BioBio/L=Hualpen/O=TAS/OU=NodoSur/CN=nodosur.tas02.lab"
    
    # HAProxy requiere la llave privada y el certificado dentro del mismo archivo .pem
    cat /tmp/nodosur.key /tmp/nodosur.crt > haproxy/certs/nodosur.pem
    rm /tmp/nodosur.key /tmp/nodosur.crt
    echo "[1/5] Certificado haproxy/certs/nodosur.pem generado"
fi



#Levantando contenedores
echo "[2/5] Levantando contenedores con docker compose"
docker compose up -d

#Esperar que se inicien los contenedores
echo "[3/5] Esperando Inializacion de los servicios"
sleep 5

echo "[4/5] Sincronizando archivos de wordpress en ambos backends (themes,plugins,uploads)"

#copiar los temas bases a los 2 backends
docker cp wp-content/themes/. cms_backend_1:/var/www/html/wp-content/themes/
docker cp wp-content/themes/. cms_backend_2:/var/www/html/wp-content/themes/

#copiar los plugins bases a los 2 backends
docker cp wp-content/plugins/. cms_backend_1:/var/www/html/wp-content/plugins/
docker cp wp-content/plugins/. cms_backend_2:/var/www/html/wp-content/plugins/

#copiar los uploads al volumen compartido de los 2 backends
docker cp wp-content/uploads/. cms_backend_1:/var/www/html/wp-content/uploads/

echo "[5/5] Asegurando permisos del usuario (www-data) para acceder a los archivos"

#Asignar permisos a ambos backends para que www-data pueda leer los archivos de wp-content
docker exec cms_backend_1 chown -R www-data:www-data /var/www/html/wp-content
docker exec cms_backend_2 chown -R www-data:www-data /var/www/html/wp-content

echo "=================================================="
echo " Completado correctamente! "
echo " Access URL: https://nodosur.tas02.lab"
echo "=================================================="