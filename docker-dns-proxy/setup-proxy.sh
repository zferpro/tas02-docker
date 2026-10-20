#!/usr/bin/env bash

# Obtener la ruta absoluta del directorio del script
SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
CERTS_DIR="$SCRIPT_DIR/haproxy/certs"
PEM_FILE="$CERTS_DIR/nodosur.pem"

# 1. Asegurar que el directorio de certificados exista
mkdir -p "$CERTS_DIR"

# 2. Generar el certificado si no existe
if [ ! -f "$PEM_FILE" ]; then
    echo "Generando certificado SSL autosignado para HAProxy (nodosur.tas02.lab)"
    
    TMP_KEY=$(mktemp)
    TMP_CRT=$(mktemp)
    
    openssl req -x509 -nodes -days 365 -newkey rsa:2048 \
        -keyout "$TMP_KEY" \
        -out "$TMP_CRT" \
        -subj "/C=CL/ST=BioBio/L=Hualpen/O=TAS/OU=NodoSur/CN=nodosur.tas02.lab" \
        2>/dev/null
    
    # HAProxy requiere combinar el certificado y la clave privada en un unico archivo .pem
    cat "$TMP_CRT" "$TMP_KEY" > "$PEM_FILE"
    chmod 600 "$PEM_FILE"
    
    # Limpieza de archivos temporales
    rm -f "$TMP_KEY" "$TMP_CRT"
    
    echo "Certificado $PEM_FILE generado con exito."
else
    echo "El certificado $PEM_FILE ya existe. Omitiendo generacion."
fi