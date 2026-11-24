# 🚀 Proyecto Nodo Sur - Plataforma Web Resiliente con Docker

## 📋 Descripción General

Este repositorio contiene la infraestructura como código (Docker Compose, configuraciones de servicios y scripts de despliegue/respaldo) para la plataforma **Nodo Sur** bajo el dominio interno **`tas02.lab`**.

Toda la comunicación interna entre servicios, bases de datos, almacenamiento compartido y monitoreo opera de forma aislada dentro de la subred privada **`10.33.199.16/29`** y protegida mediante reglas de firewall (`UFW`) en cada máquina virtual.

---

## 🖥️ Topología y Distribución de Nodos

| Hostname | IP LAN Privada | Carpeta en el Repo | Rol y Servicios Principales |
| :--- | :--- | :--- | :--- |
| **`dns-tas02`** | `10.33.199.17` | `docker-dns-proxy/` | **DNS y Reverse Proxy:** BIND9 (`:53`), HAProxy (`:80/:443` con TLS y balanceo), exporters y agente de logs. |
| **`cms-tas02`** | `10.33.199.18` | `docker-cms/` | **Backend Web 1:** WordPress + Apache (`:80` solo LAN), cliente NFSv4, exporters y agente de logs. |
| **`db-tas02`** | `10.33.199.19` | `docker-db/` | **Datos, Storage y Backups:** MariaDB 10.11 (`:3306`), servidor NFSv4 (`:2049`), cron de respaldos y exporters. |
| **`cms2-tas02`** | `10.33.199.20` | `docker-cms/` | **Backend Web 2:** Réplica de WordPress + Apache (`:80` solo LAN) compartiendo BD y carpeta `uploads` vía NFS. |
| **`monitoring-tas02`** | `10.33.199.21` | `docker-prometheus/` | **Observabilidad y Respaldos Remotos:** Prometheus, Grafana, Alertmanager, Loki, Mailpit y destino de backups. |

---

## 📂 Estructura del Repositorio

```text
tas02-docker/
├── README.md
├── docker-dns-proxy/        # DNS BIND9, HAProxy (SSL + Balanceo), exporters y Alloy
├── docker-db/               # MariaDB, Servidor NFS, scripts de despliegue/backup y Alloy
├── docker-cms/              # WordPress + Apache (usado en cms1 y cms2), exporters y Alloy
└── docker-prometheus/       # Prometheus, Alertmanager, Grafana, Loki, Mailpit y Alloy
```

---

## ⚙️ ¿Qué hace cada parte del repositorio?

### 1. `docker-dns-proxy/` (`dns-tas02` — `10.33.199.17`)
Es la puerta de entrada a toda la plataforma:
* **BIND9 (`dns_bind9`):** Resuelve los nombres internos del dominio `tas02.lab` (`dns`, `cms`, `db`, `cms2`, `prom`) y los subdominios públicos/de laboratorio (`nodosur`, `grafana`, `prometheus`, `mail`).
* **HAProxy (`cms_haproxy`):**
  * Redirige automáticamente el tráfico HTTP (`:80`) hacia HTTPS (`:443`) usando un certificado SSL autofirmado (`haproxy/certs/nodosur.pem`).
  * Enruta el tráfico según el dominio solicitado (`nodosur.tas02.lab`, `grafana.tas02.lab`, `prometheus.tas02.lab`, `mail.tas02.lab`).
  * Balancea las peticiones web en modo *round-robin* con persistencia de sesión (`cookie SERVERID`) y *health checks* cada 2 segundos hacia `cms1` y `cms2`.
  * Expone métricas en el puerto `:8404/metrics`.
* **Scripts:** `deploy_ufw.sh` cierra puertos innecesarios dejando abiertos solo SSH, DNS (`53`) y Web (`80/443`); `deploy_proxy_dns.sh` genera el certificado SSL si no existe y levanta los contenedores.

### 2. `docker-db/` (`db-tas02` — `10.33.199.19`)
Centraliza la persistencia de datos y la ejecución de respaldos:
* **MariaDB (`db_mariadb`):** Base de datos relacional (`wordpress_db`) accesible únicamente desde la red privada LAN (`10.33.199.16/29`).
* **Servidor NFS (`shared_nfs`):** Comparte el directorio `./data/uploads` mediante NFSv4 (`:2049`) para que ambos nodos WordPress lean y escriban las mismas imágenes sin desincronizarse.
* **Despliegue automatizado (`setup_db_NFS.sh` y `deploy_db_NFS.sh`):** Carga los módulos NFS en el kernel, asigna permisos de usuario `www-data` (`UID 33`), levanta los contenedores, importa la base de datos inicial y descarga los archivos multimedia si la carpeta está vacía.
* **Respaldos automatizados (`backup_nodosur.sh`):** Script ejecutado diariamente mediante `cron` (`03:00 UTC`) que:
  1. Empaqueta un volcado de MariaDB (`.sql.gz`), la carpeta compartida `uploads` (`.tar.gz`) y las configuraciones del repositorio (`.tar.gz`).
  2. Genera y valida huellas de integridad `SHA256SUMS.txt`.
  3. Envía los paquetes vía `scp` (usando llave SSH dedicada sin contraseña) hacia `/home/tas02/backups` en `monitoring-tas02` (`10.33.199.21`).
  4. Aplica rotación automática (conserva **7 días** en local y **30 días** en el servidor remoto).
  5. Actualiza el archivo `nodosur_backup.prom` para que Prometheus/Grafana monitoreen el estado del último respaldo.

### 3. `docker-cms/` (`cms-tas02` — `.18` y `cms2-tas02` — `.20`)
Corresponde a los dos nodos de aplicación web (stateless en almacenamiento):
* **WordPress + Apache (`cms_backend_1` / `cms_backend_2`):** Contenedores web que se conectan a `db.tas02.lab:3306` y montan el volumen remoto NFS en `/var/www/html/wp-content/uploads`.
* **Despliegue (`deploy_cms.sh`):** Levanta el stack, descarga y descomprime temas y plugins automáticamente, habilita el endpoint `/server-status` en Apache para recolección de métricas y ajusta permisos de `www-data`.
* **Firewalls dedicados:** `deploy_ufw_cms1.sh` y `deploy_ufw_cms2.sh` restringen el puerto `80` y los exporters exclusivamente a la red privada LAN.

### 4. `docker-prometheus/` (`monitoring-tas02` — `10.33.199.21`)
Centraliza la observabilidad (métricas, alertas, logs y correo de pruebas):
* **Prometheus (`prometheus_server`):** Recolecta métricas cada 5 segundos de las 5 MVs (`node_exporter` en `:9100` y `cadvisor` en `:8080`) y de cada servicio específico (`haproxy :8404`, `bind9 :9119`, `mariadb :9104`, `apache :9117`), además de leer el estado de los respaldos vía *textfile collector*.
* **Alertmanager (`alertmanager`) + Mailpit (`mailpit_smtp`):** Evalúa las 7 reglas definidas en `alert.rules.yml` y envía notificaciones por correo SMTP (`:1025`) a los responsables (`sysadmin`, `dba`, `storage_admin`, `guardia-critica`), visibles desde la interfaz web de Mailpit (`:8025`).
* **Grafana (`grafana_server`):** Visualización unificada de métricas y logs con *datasources* y *dashboards* aprovisionados desde `grafana/provisioning/`.
* **Logs Centralizados (`loki_server` + `alloy_agent`):** Cada una de las 5 MVs ejecuta un contenedor **Grafana Alloy** (`alloy/config.alloy`) que recolecta los logs de todos los contenedores locales y los envía a **Loki** (`:3100`), donde se almacenan con retención de **30 días (`720h`)**.

---

## 🔐 Gestión de Credenciales y Orden de Despliegue

Por seguridad, **ninguna contraseña o llave privada está almacenada en este repositorio**. Antes de levantar cada nodo se deben crear los archivos locales a partir de sus plantillas `.example`:

* En `docker-db/`: copiar `.env.example` $\rightarrow$ `.env` y `.my.cnf.example` $\rightarrow$ `.my.cnf` con las credenciales de MariaDB y enlaces de descarga.
* En `docker-cms/`: copiar `.env.example` $\rightarrow$ `.env` definiendo `CONTAINER_NAME`, `CONTAINER_IP`, `MV_HOSTNAME` (`cms-tas02` o `cms2-tas02`) y datos de conexión a BD.
* En `docker-prometheus/`: copiar `.env.example` $\rightarrow$ `.env` con las credenciales de acceso inicial a Grafana.

**Orden recomendado de despliegue:**
1. **`dns-tas02`** (`docker-dns-proxy/`): aplicar UFW y ejecutar `./deploy_proxy_dns.sh`.
2. **`db-tas02`** (`docker-db/`): aplicar UFW, ejecutar `./setup_db_NFS.sh`, configurar `.env`/`.my.cnf` y ejecutar `./deploy_db_NFS.sh`.
3. **`cms-tas02` y `cms2-tas02`** (`docker-cms/`): instalar `nfs-common`, aplicar UFW correspondiente (`cms1` o `cms2`), configurar `.env` y ejecutar `./deploy_cms.sh`.
4. **`monitoring-tas02`** (`docker-prometheus/`): aplicar UFW, configurar `.env` y levantar con `docker compose up -d`.

---

## 🌐 Accesos Web (Red Interna / VPN)

Una vez desplegada la plataforma y configurado el DNS (`10.33.199.17`), los servicios web están disponibles en:
* **Sitio Web Principal (WordPress):** `https://nodosur.tas02.lab`
* **Paneles y Logs (Grafana):** `https://grafana.tas02.lab`
* **Métricas y Alertas (Prometheus):** `https://prometheus.tas02.lab`
* **Bandeja de Alertas y Correos (Mailpit):** `https://mail.tas02.lab`
