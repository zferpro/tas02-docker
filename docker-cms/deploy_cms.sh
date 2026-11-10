#!/usr/bin/env bash

# Exit inmediatamente si algún comando falla
set -e

echo "=================================================="
echo "   [DEPLOY-CMS] Despliegue Automatizado CMS       "
echo "=================================================="

# 1. Cargar y verificar el archivo .env
if [ ! -f .env ]; then
    echo " -> [ERROR] No se encontró el archivo .env en el directorio actual."
    exit 1
fi

export $(grep -v '^#' .env | grep -v '^$' | xargs)
TARGET_CONTAINER="${CONTAINER_NAME}"

# Función helper para descargar desde Google Drive superando la pantalla de virus
download_from_drive() {
    local url="$1"
    local output_file="$2"
    
    # Extraer el FILE_ID de la URL de Drive (soporta formatos con id=... o /d/ID)
    local file_id=$(echo "$url" | grep -oP '(id=|\/d\/)\K[a-zA-Z0-9_-]+')
    
    if [ -z "$file_id" ]; then
        echo " -> [ERROR] No se pudo extraer el ID de Google Drive desde la URL facilitada."
        return 1
    fi

    # 1. Intentar obtener el token de confirmación de descarga por si pesa más del límite de virus
    local confirm=$(curl -sc /tmp/gcookie "https://drive.google.com/uc?export=download&id=${file_id}" | grep -oP 'confirm=\K[^&]*' || true)

    # 2. Si existe token, descargar con confirm; si no, hacer descarga directa normal
    if [ -n "$confirm" ]; then
        curl -Lb /tmp/gcookie "https://drive.google.com/uc?export=download&confirm=${confirm}&id=${file_id}" -o "$output_file"
    else
        curl -Lb /tmp/gcookie "https://drive.google.com/uc?export=download&id=${file_id}" -o "$output_file"
    fi

    rm -f /tmp/gcookie
}

# 2. Iniciar y construir contenedores
echo "[1/5] Levantando y construyendo contenedores con Docker Compose..."
docker compose up -d --build

echo "[2/5] Esperando inicialización de los servicios (10s)..."
sleep 10

# Validar que el contenedor destino esté realmente activo
if ! docker ps --format '{{.Names}}' | grep -q "^${TARGET_CONTAINER}$"; then
    echo " -> [ERROR] El contenedor '${TARGET_CONTAINER}' no está en ejecución."
    docker compose ps
    exit 1
fi

# 3. Preparación y descarga de Temas (Themes)
echo "[3/5] Verificando y descargando temas de WordPress..."
mkdir -p wp-content/themes

if [ -z "$(ls -A wp-content/themes 2>/dev/null)" ]; then
    if [ -n "$LINK_DOWNLOAD_THEMES" ]; then
        echo " -> La carpeta wp-content/themes está vacía. Descargando temas desde Google Drive..."
        download_from_drive "$LINK_DOWNLOAD_THEMES" "themes.tar.gz"
        
        echo " -> Descomprimiendo themes.tar.gz..."
        tar -xzf themes.tar.gz -C wp-content/themes/
        rm -f themes.tar.gz
        echo " -> Temas descomprimidos con éxito."
    else
        echo " -> [AVISO] wp-content/themes está vacío y no se definió LINK_DOWNLOAD_THEMES."
    fi
fi

# 4. Preparación y descarga de Plugins
echo "[4/5] Verificando y descargando plugins de WordPress..."
mkdir -p wp-content/plugins

if [ -z "$(ls -A wp-content/plugins 2>/dev/null)" ]; then
    if [ -n "$LINK_DOWNLOAD_PLUGINS" ]; then
        echo " -> La carpeta wp-content/plugins está vacía. Descargando plugins desde Google Drive..."
        download_from_drive "$LINK_DOWNLOAD_PLUGINS" "plugins.tar.gz"
        
        echo " -> Descomprimiendo plugins.tar.gz..."
        tar -xzf plugins.tar.gz -C wp-content/plugins/
        rm -f plugins.tar.gz
        echo " -> Plugins descomprimidos con éxito."
    else
        echo " -> [AVISO] wp-content/plugins está vacío y no se definió LINK_DOWNLOAD_PLUGINS."
    fi
fi

# 5. Sincronización hacia el contenedor y asignación de permisos
echo "[5/5] Copiando temas y plugins al contenedor '${TARGET_CONTAINER}'..."

if [ "$(ls -A wp-content/themes 2>/dev/null)" ]; then
    docker cp wp-content/themes/. "${TARGET_CONTAINER}:/var/www/html/wp-content/themes/"
fi

if [ "$(ls -A wp-content/plugins 2>/dev/null)" ]; then
    docker cp wp-content/plugins/. "${TARGET_CONTAINER}:/var/www/html/wp-content/plugins/"
fi

echo " -> Asignando propiedad www-data:www-data dentro del contenedor..."
docker exec "${TARGET_CONTAINER}" chown -R www-data:www-data /var/www/html/wp-content

echo "=================================================="
echo " Despliegue completado correctamente!             "
echo " Access URL: https://${DOMAIN} "
echo "=================================================="