#!/usr/bin/env bash
# Exit inmediatamente si algún comando falla
set -e

echo "========================================================="
echo "   [DEPLOY-DB-NFS] Despliegue de DB y Almacenamiento NFS  "
echo "========================================================="

# 1. Cargar variables de entorno desde el archivo .env primero
if [ -f .env ]; then 
   export $(grep -v '^#' .env | grep -v '^$' | xargs)
else
   echo " -> [ERROR] No se encontró el archivo .env en el directorio actual."
   exit 1
fi

# Función helper para descargar desde Google Drive superando la pantalla de confirmación de virus
download_from_drive() {
    local url="$1"
    local output_file="$2"
    
    # Extraer el FILE_ID de la URL de Drive (soporta formatos id=... o /d/ID)
    local file_id=$(echo "$url" | grep -oP '(id=|\/d\/)\K[a-zA-Z0-9_-]+')
    
    if [ -z "$file_id" ]; then
        echo " -> [ERROR] No se pudo extraer el ID de Google Drive desde la URL facilitada."
        return 1
    fi

    # 1. Intentar obtener el token de confirmación por si excede el límite de escaneo de virus
    local confirm=$(curl -sc /tmp/gcookie "https://drive.google.com/uc?export=download&id=${file_id}" | grep -oP 'confirm=\K[^&]*' || true)

    # 2. Si existe token, descargar con confirm; si no, hacer descarga directa normal
    if [ -n "$confirm" ]; then
        curl -Lb /tmp/gcookie "https://drive.google.com/uc?export=download&confirm=${confirm}&id=${file_id}" -o "$output_file"
    else
        curl -Lb /tmp/gcookie "https://drive.google.com/uc?export=download&id=${file_id}" -o "$output_file"
    fi

    rm -f /tmp/gcookie
}

# 2. Iniciar contenedores con build. Si la construcción falla, set -e aborta la ejecución
echo "[1/5] Iniciar y construir contenedores con Docker Compose..."
docker compose up -d --build

echo " -> Estado actual de los servicios:"
docker compose ps

# Definición de variables clave
SQL_FILE="${MYSQL_NAME_BACKUP:-backup_wordpress.sql}"
ROOT_PASS="${MYSQL_ROOT_PASSWORD}"
DB_NAME="${MYSQL_DATABASE}"

# 3. Verificar si la base de datos requiere importación
echo "[2/5] Verificando si la base de datos requiere importación..."
HAS_TABLES=$(docker exec db_mariadb mariadb -u root -p"${ROOT_PASS}" -D "${DB_NAME}" -e "SHOW TABLES LIKE 'wp_options';" 2>/dev/null | grep wp_options || true)

if [ -z "$HAS_TABLES" ]; then
    echo " -> Base de datos vacía o sin la tabla 'wp_options'."
    
    # Descargar el archivo .sql si no se encuentra presente localmente
    if [ ! -f "$SQL_FILE" ]; then
        if [ -n "$LINK_DOWNLOAD_DB" ]; then
            echo " -> Descargando archivo SQL desde Google Drive ($LINK_DOWNLOAD_DB)..."
            download_from_drive "$LINK_DOWNLOAD_DB" "$SQL_FILE"
        else
            echo " -> [ERROR] No se encontró $SQL_FILE localmente y LINK_DOWNLOAD_DB no está definido."
            exit 1
        fi
    fi

    echo " -> Importando $SQL_FILE a la base de datos '$DB_NAME'..."
    docker exec -i db_mariadb mariadb -u root -p"${ROOT_PASS}" "$DB_NAME" < "$SQL_FILE"
    echo " -> ¡Importación de BD realizada con éxito!"
else
    echo " -> La base de datos ya contiene información (tabla wp_options detectada). Omitiendo importación."
fi

# 4. Verificar y preparar el volumen NFS para los archivos multimedia
echo "[3/5] Verificando directorio de archivos multimedia en ./uploads..."
mkdir -p ./uploads
sudo mkdir -p ./data/uploads

# Si no existe la carpeta ./uploads o está vacía, descargar y descomprimir
if [ -z "$(ls -A ./uploads 2>/dev/null)" ]; then
    if [ -n "$LINK_DOWNLOAD_UPLOADS" ]; then
        echo " -> ./uploads está vacío. Descargando paquete multimedia desde Google Drive..."
        download_from_drive "$LINK_DOWNLOAD_UPLOADS" "uploads.tar.gz"
        
        echo " -> Descomprimiendo uploads.tar.gz en ./uploads..."
        tar -xzf uploads.tar.gz -C ./uploads
        rm -f uploads.tar.gz
        echo " -> Descompresión realizada con éxito."
    else
        echo " -> [AVISO] ./uploads está vacío y no se definió LINK_DOWNLOAD_UPLOADS."
    fi
fi

# Copiar archivos hacia la ruta compartida montada en el contenedor NFS
if [ "$(ls -A ./uploads 2>/dev/null)" ]; then
    echo " -> Copiando archivos desde ./uploads a ./data/uploads/..."
    sudo cp -r ./uploads/* ./data/uploads/
    echo " -> Archivos multimedia copiados exitosamente a ./data/uploads/."
fi

# 5. Corregir recursivamente los permisos para el usuario www-data (UID 33)
echo "[4/5] Corrigiendo recursivamente propiedad UID 33 (www-data) en ./data/uploads..."
sudo chown -R 33:33 ./data/uploads
sudo chmod -R 775 ./data/uploads

# 6. Comprobación de escucha en los puertos 3306 (MariaDB) y 2049 (NFS)
echo "[5/5] Verificando escucha de puertos en la red (3306 y 2049):"
if command -v ss &> /dev/null; then
    sudo ss -tulpn | grep -E ':3306|:2049'
else
    sudo netstat -tulpn | grep -E ':3306|:2049'
fi

echo "========================================================="
echo " Verificación y despliegue completados con éxito.       "
echo " La base de datos y el servicio NFS están operativos.   "
echo "========================================================="