#!/usr/bin/env bash

# Salir inmediatamente si ocurre un error
set -e

echo "========================================================="
echo "   [setup_db_NFS] Preparando entorno para MariaDB y NFS    "
echo "========================================================="

# 1. Cargar módulos del Kernel necesarios para el servidor NFS
echo "[1/5] Verificando y cargando módulos NFS en el Kernel..."
sudo modprobe nfs || true
sudo modprobe nfsd || true
sudo apt update && sudo apt install -y python3-pip
pip install gdown --break-system-packages 2>/dev/null || pip install gdown || pip3 install gdown
# Asegurar que se carguen automáticamente si la MV se reinicia
if ! grep -q "^nfs\b" /etc/modules 2>/dev/null; then
    echo "nfs" | sudo tee -a /etc/modules > /dev/null
fi

if ! grep -q "^nfsd\b" /etc/modules 2>/dev/null; then
    echo "nfsd" | sudo tee -a /etc/modules > /dev/null
    echo " -> Módulos NFS agregados a /etc/modules para persistencia en reinicios."
fi

# 2. Crear carpetas de persistencia si no existen
echo "[2/5] Creando estructura de directorios locales..."
mkdir -p ./data/mysql
mkdir -p ./data/uploads

# 3. Asignar propiedad a UID 33 (www-data de WordPress) y permisos
echo "[3/5] Asignando permisos de almacenamiento para WordPress (UID 33:www-data)..."
sudo chown -R 33:33 ./data/uploads
sudo chmod -R 775 ./data/uploads

# Asignar permisos para la carpeta de MariaDB (UID 999 es el estándar de MariaDB)
sudo chown -R 999:999 ./data/mysql 2>/dev/null || sudo chmod -R 777 ./data/mysql

# 4. Validar archivo .env
echo "[4/5] Verificando variables de entorno (.env)..."
if [ ! -f .env ]; then
    if [ -f .env.example ]; then
        cp .env.example .env
        echo " -> Creado archivo .env desde .env.example"
    else
        echo " -> ERROR: No se encontró el archivo .env ni .env.example"
        exit 1
    fi
fi

# 5. Validar archivo .my.cnf para mysqld_exporter
echo "[5/5] Verificando archivo de configuración (.my.cnf)..."
if [ ! -f .my.cnf ]; then
    if [ -f .my.cnf.example ]; then
        cp .my.cnf.example .my.cnf
        chmod 644 .my.cnf
        echo " -> Creado archivo .my.cnf desde .my.cnf.example (permisos 644 asegurados)."
    else
        echo " -> ERROR: No se encontró el archivo .my.cnf ni .my.cnf.example"
        exit 1
    fi
else
    chmod 644 .my.cnf
fi

echo "========================================================="
echo " ¡Setup listo completado exitosamente!                  "
echo " Verifica .env y .my.cnf, luego ejecuta ./deploy_db_NFS.sh"
echo "========================================================="