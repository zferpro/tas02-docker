#!/usr/bin/env bash
set -e

echo "[1/4] Reiniciando reglas actuales de UFW..."
sudo ufw --force reset

echo "[2/4] Estableciendo políticas por defecto..."
sudo ufw default deny incoming
sudo ufw default allow outgoing

echo "[3/4] Aplicando reglas para dns-tas02..."

# --- Acceso SSH (Puerto 22) ---
sudo ufw allow from 10.33.199.16/29 to any port 22 proto tcp
sudo ufw allow from 10.30.0.0/16 to any port 22 proto tcp
sudo ufw allow from 10.33.195.0/24 to any port 22 proto tcp
sudo ufw allow from 10.33.0.0/16 to any port 22 proto tcp
sudo ufw allow from 10.33.21.0/24 to any port 22 proto tcp

# --- Servicios de acceso público / sin restricción ---
sudo ufw allow 53/tcp
sudo ufw allow 53/udp
sudo ufw allow 80/tcp
sudo ufw allow 443/tcp

# --- Métricas / Exporters (Solo subred privada LAN 10.33.199.16/29) ---
sudo ufw allow from 10.33.199.16/29 to 10.33.199.17 port 8080 proto tcp
sudo ufw allow from 10.33.199.16/29 to 10.33.199.17 port 9119 proto tcp
sudo ufw allow from 10.33.199.16/29 to 10.33.199.17 port 9100 proto tcp
sudo ufw allow from 10.33.199.16/29 to 10.33.199.17 port 8404 proto tcp 

echo "[4/4] Habilitando UFW..."
sudo ufw --force enable
sudo ufw status verbose