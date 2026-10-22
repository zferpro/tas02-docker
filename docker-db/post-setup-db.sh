#!/usr/bin/env bash
#Exit inmediatamente si un comando falla
set -e
echo "========================================================="
echo "   [POST-SETUP] Verificando estado y permisos de servicios "
echo "========================================================="
# 1. Verificar si los contenedores están arriba
if [ -f .env ]; then 
   export $(grep -v '^#' .env | grep -v '^$' | xargs)
fi

echo "[1/5] Estado de los contenedores Docker:"
docker compose ps

SQL_FILE="backup_wordpress.sql"
ROOT_PASS="${MYSQL_ROOT_PASSWORD}"
if [ -f "$SQL_FILE" ]; then
    echo "[2/5] Verificando si la base de datos requiere importación..."
    
    # Comprobar si la tabla esencial de WordPress 'wp_options' ya existe en 'wordpress_db'
    HAS_TABLES=$(docker exec -i db_mariadb mariadb -u root -p"${ROOT_PASS}" -D wordpress_db -e "SHOW TABLES LIKE 'wp_options';" 2>/dev/null | grep wp_options || true)
    
    if [ -z "$HAS_TABLES" ]; then
        echo " -> Importando $SQL_FILE a la base de datos 'wordpress_db'..."
        docker exec -i db_mariadb mariadb -u root -p"${ROOT_PASS}" wordpress_db < "$SQL_FILE"
        echo " -> ¡Importación realizada con éxito!"
    else
        echo " -> La base de datos ya contiene información (tabla wp_options detectada). Omitiendo importación."
    fi
else
    echo "[2/5] OMITIDO: No se encontró el archivo '$SQL_FILE' en el directorio actual. Se mantendrá la BD limpia."
fi

if [ -d "./uploads" ] && [ "$(ls -A ./uploads 2>/dev/null)" ]; then
    echo "[3/5] Copiando archivos iniciales desde ./uploads a ./data/uploads/..."
    sudo cp -r ./uploads/* ./data/uploads/
    echo " -> Archivos multimedia copiados exitosamente."
else
    echo "[3/5] No se detectaron archivos previos en ./uploads. Se creará una carpeta limpia."
fi


echo "[4/5] Corrigiendo recursivamente propiedad UID 33 (www-data) en uploads..."
sudo chown -R 33:33 ./data/uploads
sudo chmod -R 775 ./data/uploads

# 3. Prueba rápida de escucha en los puertos clave (3306 para MariaDB y 2049 para NFS)
echo "[5/5] Verificando escucha de puertos en la red:"
if command -v ss &> /dev/null; then
    sudo ss -tulpn | grep -E ':3306|:2049'
else
    sudo netstat -tulpn | grep -E ':3306|:2049'
fi

echo "========================================================="
echo " Verificación completada. La base de datos y el NFS     "
echo " están listos para recibir conexiones de los CMS.       "
echo "========================================================="
