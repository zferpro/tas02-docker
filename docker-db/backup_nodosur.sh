#!/usr/bin/env bash
set -e

# 1. Cargar contraseñas desde .env (sin exponer claves en el repositorio)
source /home/tas02/tas02-docker/docker-db/.env

# 2. Variables principales
FECHA=$(date -u +%Y%m%d_%H%M)
BASE_BACKUPS="/home/tas02/backups"
CARPETA_LOCAL="$BASE_BACKUPS/$FECHA"
CARPETA_REMOTA="/home/tas02/backups"
IP_MONITORING="10.33.199.21"
LLAVE_SSH="/home/tas02/.ssh/id_ed25519_backup"
ARCHIVO_PROM="/home/tas02/node_exporter/textfile_collector/nodosur_backup.prom"

echo "=== [1/6] Creando carpeta de respaldo ($FECHA) ==="
mkdir -p "$CARPETA_LOCAL"

echo "=== [2/6] Respaldando Base de Datos, Uploads y Configuraciones ==="
docker exec db_mariadb mariadb-dump -u root -p"$MYSQL_ROOT_PASSWORD" "$MYSQL_DATABASE" | gzip > "$CARPETA_LOCAL/db_$FECHA.sql.gz"
tar -czf "$CARPETA_LOCAL/uploads_$FECHA.tar.gz" -C /home/tas02/tas02-docker/docker-db/data uploads
tar -czf "$CARPETA_LOCAL/configs_$FECHA.tar.gz" --exclude='docker-db/data' --exclude='.git' -C /home/tas02/tas02-docker .
chmod 600 "$CARPETA_LOCAL"/*

echo "=== [3/6] Verificando integridad con SHA-256 ==="
cd "$CARPETA_LOCAL"
sha256sum *.gz > SHA256SUMS.txt
sha256sum -c SHA256SUMS.txt

echo "=== [4/6] Enviando respaldo a monitoring-tas02 ($IP_MONITORING) ==="
scp -i "$LLAVE_SSH" -r "$CARPETA_LOCAL" tas02@"$IP_MONITORING":"$CARPETA_REMOTA/"
ssh -i "$LLAVE_SSH" tas02@"$IP_MONITORING" "cd $CARPETA_REMOTA/$FECHA && sha256sum -c SHA256SUMS.txt"

echo "=== [5/6] Aplicando retencion (7 dias local / 30 dias remoto) ==="
find "$BASE_BACKUPS" -mindepth 1 -maxdepth 1 -type d -mtime +7 -exec rm -rf {} +
ssh -i "$LLAVE_SSH" tas02@"$IP_MONITORING" "find $CARPETA_REMOTA -mindepth 1 -maxdepth 1 -type d -mtime +30 -exec rm -rf {} +"

echo "=== [6/6] Actualizando metrica en Prometheus ==="
AHORA=$(date -u +%s)
ssh -i "$LLAVE_SSH" tas02@"$IP_MONITORING" "printf 'nodosur_backup_status 1\nnodosur_backup_last_success_timestamp %s\n' '$AHORA' > $ARCHIVO_PROM"

echo "=== Respaldo finalizado correctamente ==="