#!/usr/bin/env bash

# Salir inmediatamente si ocurre un error
set -e

echo "========================================================="
echo "   [PRE-SETUP] Preparando entorno para MariaDB y NFS    "
echo "========================================================="

# 1. Cargar módulos del Kernel necesarios para el servidor NFS
echo "[1/4] Verificando y cargando módulos NFS en el Kernel..."
sudo modprobe nfs || true
sudo modprobe nfsd || true

# Asegurar que se carguen automáticamente si la MV se reinicia
if ! grep -q "^nfsd" /etc/modules 2>/dev/null; then
    echo "nfs" | sudo tee -a /etc/modules > /dev/null
    echo "nfsd" | sudo tee -a /etc/modules > /dev/null
    echo " -> Módulos agregados a /etc/modules para persistencia en reinicios."
fi

# 2. Crear carpetas de persistencia si no existen
echo "[2/4] Creando estructura de directorios locales..."
mkdir -p ./data/mysql
mkdir -p ./data/uploads

# 3. Asignar propiedad a UID 33 (www-data de WordPress) y permisos
echo "[3/4] Asignando permisos de almacenamiento para WordPress (UID 33:www-data)..."
sudo chown -R 33:33 ./data/uploads
sudo chmod -R 775 ./data/uploads

# Asignar permisos para la carpeta de MariaDB (UID 999 es el estándar de MariaDB)
sudo chown -R 999:999 ./data/mysql 2>/dev/null || sudo chmod -R 777 ./data/mysql

# 4. Validar archivo .env
echo "[4/4] Verificando variables de entorno (.env)..."
if [ ! -f .env ]; then
    if [ -f .env.example ]; then
        cp .env.example .env
        echo " -> Creado archivo .env desde .env.example"
    else
        echo " -> ERROR: No se encontró el archivo .env ni .env.example"
        exit 1
    fi
fi

echo "========================================================="
echo " ¡Pre-configuración completada exitosamente!            "
echo " Ahora puedes ejecutar: docker compose up -d             "
echo "========================================================="