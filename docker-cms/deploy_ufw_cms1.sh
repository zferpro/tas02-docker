#!/usr/bin/env bash
set -e

echo "[1/4] Reiniciando reglas actuales de UFW..."
sudo ufw --force reset

echo "[2/4] Estableciendo políticas por defecto..."
sudo ufw default deny incoming
sudo ufw default allow outgoing

echo "[3/4] Aplicando reglas para cms-tas02..."

# --- Acceso SSH (Puerto 22) ---
sudo ufw allow from 10.33.199.16/29 to any port 22 proto tcp
sudo ufw allow from 10.30.0.0/16 to any port 22 proto tcp
sudo ufw allow from 10.33.195.0/24 to any port 22 proto tcp
sudo ufw allow from 10.33.0.0/16 to any port 22 proto tcp
sudo ufw allow from 10.33.21.0/24 to any port 22 proto tcp

# --- Servicios Web y Exporters (Restringidos a subred privada LAN 10.33.199.16/29) ---
sudo ufw allow from 10.33.199.16/29 to 10.33.199.18 port 80 proto tcp
sudo ufw allow from 10.33.199.16/29 to 10.33.199.18 port 8080 proto tcp
sudo ufw allow from 10.33.199.16/29 to 10.33.199.18 port 9117 proto tcp
sudo ufw allow from 10.33.199.16/29 to 10.33.199.18 port 9100 proto tcp

# --- Puertos Cliente NFS (Sin restricción de firewall) ---
sudo ufw allow 111
sudo ufw allow 42841
sudo ufw allow 46607

echo "[4/4] Habilitando UFW..."
sudo ufw --force enable
sudo ufw status verbose