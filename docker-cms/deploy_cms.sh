#!/usr/bin/env bash

# Exit inmediatamente si algún comando falla
set -e

echo "=================================================="
echo "   [DEPLOY-CMS] Despliegue Automatizado CMS       "
echo "=================================================="

# Asegurar instalación de gdown
sudo apt update && sudo apt install -y python3-pip
pip install gdown --break-system-packages 2>/dev/null || pip install gdown || pip3 install gdown

# 1. Cargar y verificar el archivo .env
if [ ! -f .env ]; then
    echo " -> [ERROR] No se encontró el archivo .env en el directorio actual."
    exit 1
fi

export $(grep -v '^#' .env | grep -v '^$' | xargs)
TARGET_CONTAINER="${CONTAINER_NAME:-cms_backend}"

# Función helper para descargar desde Google Drive
download_from_drive() {
    local url="$1"
    local output_file="$2"
    local file_id=$(echo "$url" | grep -oP '(id=|\/d\/)\K[a-zA-Z0-9_-]+')
    
    if [ -z "$file_id" ]; then
        echo " -> [ERROR] No se pudo extraer el ID de Google Drive desde la URL facilitada."
        return 1
    fi

    echo " -> Descargando archivo con gdown (ID: ${file_id})..."
    python3 -m gdown "${file_id}" -O "${output_file}"
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
        
        # Desanidar en host si la carpeta .tar.gz contenía una subcarpeta 'themes'
        if [ -d "wp-content/themes/themes" ]; then
            echo " -> Aplanando estructura de carpetas en temas..."
            mv wp-content/themes/themes/* wp-content/themes/
            rm -rf wp-content/themes/themes
        fi
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
        
        # Desanidar en host si la carpeta .tar.gz contenía una subcarpeta 'plugins'
        if [ -d "wp-content/plugins/plugins" ]; then
            echo " -> Aplanando estructura de carpetas en plugins..."
            mv wp-content/plugins/plugins/* wp-content/plugins/
            rm -rf wp-content/plugins/plugins
        fi
        echo " -> Plugins descomprimidos con éxito."
    else
        echo " -> [AVISO] wp-content/plugins está vacío y no se definió LINK_DOWNLOAD_PLUGINS."
    fi
fi

# 5. Sincronización hacia el contenedor, desanidamiento interno, Apache Exporter y permisos
echo "[5/5] Configurando archivos y servicios en el contenedor '${TARGET_CONTAINER}'..."

if [ "$(ls -A wp-content/themes 2>/dev/null)" ]; then
    docker cp wp-content/themes/. "${TARGET_CONTAINER}:/var/www/html/wp-content/themes/"
fi

if [ "$(ls -A wp-content/plugins 2>/dev/null)" ]; then
    docker cp wp-content/plugins/. "${TARGET_CONTAINER}:/var/www/html/wp-content/plugins/"
fi

echo " -> Verificando y aplanando carpetas anidadas en el contenedor..."
docker exec "${TARGET_CONTAINER}" bash -c '
if [ -d "/var/www/html/wp-content/themes/themes" ]; then
    mv /var/www/html/wp-content/themes/themes/* /var/www/html/wp-content/themes/
    rm -rf /var/www/html/wp-content/themes/themes
fi
if [ -d "/var/www/html/wp-content/plugins/plugins" ]; then
    mv /var/www/html/wp-content/plugins/plugins/* /var/www/html/wp-content/plugins/
    rm -rf /var/www/html/wp-content/plugins/plugins
fi
'

echo " -> Habilitando endpoint /server-status para Apache Exporter..."
docker exec "${TARGET_CONTAINER}" bash -c 'cat <<EOF > /etc/apache2/conf-available/server-status.conf
<Location /server-status>
    SetHandler server-status
    Require all granted
    RewriteEngine Off
</Location>
EOF
a2enmod status >/dev/null 2>&1 || true
a2enconf server-status >/dev/null 2>&1 || true
apache2ctl graceful >/dev/null 2>&1 || true'

echo " -> Asignando propiedad www-data:www-data dentro del contenedor..."
docker exec "${TARGET_CONTAINER}" chown -R www-data:www-data /var/www/html/wp-content

echo "=================================================="
echo " Despliegue completado correctamente!             "
echo " Access URL: https://${DOMAIN:-nodosur.tas02.lab} "
echo "=================================================="