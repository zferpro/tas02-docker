# 🚀 Proyecto Nodo Sur - Plataforma Web Resiliente con Docker

Este `README.md` explica de forma clara y directa cómo está estructurado nuestro proyecto para la asignatura **Taller de Administración de Sistemas (TAS02)**. La idea es que todo el equipo pueda entender qué hace cada servidor, cómo se conectan las piezas y cómo levantar todo el entorno de 0 a 100 sin complicaciones.

---

## 💡 ¿De qué se trata la arquitectura?

El objetivo del proyecto es desplegar una plataforma web en **WordPress** corriendo de forma distribuida sobre **4 Máquinas Virtuales (MVs)** para garantizar tolerancia a fallas y continuidad operacional.

Toda la solución está empaquetada en **contenedores Docker** y se organiza en 3 repositorios/capas principales:

1. **DNS y Proxy Inverso (`docker-dns-proxy`)**:


* Un contenedor con **BIND9** se encarga de la resolución de nombres de dominio locales (`nodosur.tas02.lab`).


* Un contenedor con **HAProxy** recibe el tráfico seguro HTTPS (puerto 443), gestiona los certificados SSL y distribuye las peticiones entre los dos servidores web mediante balanceo de carga.




2. **Base de Datos y Almacenamiento Compartido (`docker-db`)**:


* Un contenedor con **MariaDB 10.11** almacena de manera centralizada la base de datos del sitio (`wordpress_db`).


* Un contenedor con un **Servidor NFS** comparte la carpeta multimedia/subidas (`wp-content/uploads`).




3. **Servidores Web CMS (`docker-cms`)**:


* Dos MVs independientes ejecutan un contenedor de **WordPress** cada una.


* Ambos nodos se conectan a la misma base de datos centralizada y montan de forma nativa el mismo almacenamiento compartido NFS.





---

## 🗺️ Mapa de Red e Infraestructura

El segmento de red privado utilizado para el proyecto es `10.33.199.16/29` y la infraestructura se organiza de la siguiente manera:

| Máquina Virtual | IP LAN | Rol de la MV | Contenedores Docker Desplegados |
| --- | --- | --- | --- |
| **`dns-tas02`** | `10.33.199.17` | Servidor DNS y Proxy | `dns_bind9` (Puerto 53) + `cms_haproxy` (Puertos 80, 443)

 |
| **`cms-tas02` (CMS 1)** | `10.33.199.18` | Backend Web #1 | `cms_backend_1` (WordPress en Puerto 80)

 |
| **`db-tas02`** | `10.33.199.19` | Base de Datos + NFS | `db_mariadb` (Puerto 3306) + `shared_nfs` (Puerto 2049)

 |
| **`cms2-tas02` (CMS 2)** | `10.33.199.20` | Backend Web #2 | `cms_backend_2` (WordPress en Puerto 80)

 |

---

## 🛠️ Guía de Despliegue Paso a Paso

Para desplegar la infraestructura completa desde cero de manera ordenada, se recomienda seguir la secuencia listada a continuación.

---

### 1️⃣ Servidor de Base de Datos y Almacenamiento (`docker-db`)

* **Servidor destino:** Máquina Virtual `db-tas02` (`10.33.199.19`).


* **Función:** Provee el servicio de base de datos MariaDB y expone la carpeta de archivos mediante un servidor NFS contenerizado.



**Comandos para desplegar:**

1. Clonar el repositorio y acceder al directorio:


```bash
cd /home/tas02/
git clone <URL_REPO_DOCKER_DB>
cd docker-db

```


2. Crear el archivo de variables de entorno `.env`:


```ini
MYSQL_ROOT_PASSWORD=triplef
MYSQL_DATABASE=wordpress_db
MYSQL_USER=wp_user
MYSQL_PASSWORD=triplef
```[cite: 1, 4]

```


3. Ejecutar el script de pre-configuración:


```bash
chmod +x setup.sh post-setup.sh
./setup.sh
```[cite: 1]
*(Este script carga los módulos NFS en el kernel del host y ajusta los permisos de almacenamiento para el usuario `www-data` con UID `33:33`)[cite: 1, 4].*

```


4. Iniciar los contenedores:


```bash
docker compose up -d
```[cite: 1]

```


5. Ejecutar el script de post-configuración:


```bash
./post-setup.sh
```[cite: 1]
*(Valida el inicio de MariaDB, realiza la importación idempotente del respaldo `backup_wordpress.sql` y verifica la copia de los archivos multimedia)[cite: 1, 4].*


```



---

### 2️⃣ Servidor DNS y Proxy Inverso (`docker-dns-proxy`)

* **Servidor destino:** Máquina Virtual `dns-tas02` (`10.33.199.17`).


* **Función:** Resuelve el dominio local `nodosur.tas02.lab` y gestiona la terminación TLS/HTTPS balanceando el tráfico hacia los nodos CMS.



**Comandos para desplegar:**

1. Detener el servicio BIND nativo del host para liberar el puerto 53:


```bash
sudo systemctl stop bind9
sudo systemctl disable bind9
```[cite: 1]

```


2. Entrar a la carpeta del repositorio y generar el certificado SSL:


```bash
cd /home/tas02/docker-dns-proxy
chmod +x setup.sh
./setup.sh
```[cite: 1]
*(Genera automáticamente el certificado autosignado `nodosur.pem` para HAProxy)[cite: 1].*

```


3. Levantar la pila de contenedores con Docker Compose:


```bash
docker compose up -d
```[cite: 1]


```



---

### 3️⃣ Servidores Web CMS (`docker-cms`)

* **Servidores destino:** Máquinas Virtuales `cms-tas02` (`10.33.199.18`) y `cms2-tas02` (`10.33.199.20`).


* **Función:** Ejecutan el servicio de WordPress conectándose a la base de datos remota (`db.tas02.lab:3306`) y montan el volumen nativo NFS de Docker para los archivos subidos.



**Comandos para desplegar en cada servidor CMS:**

1. Instalar las utilidades cliente de NFS en el sistema operativo host:


```bash
sudo apt update && sudo apt install -y nfs-common
```[cite: 1, 3]

```


2. Acceder al repositorio y crear el archivo `.env`:


```bash
cd /home/tas02/tas02-docker/docker-cms/
cp .env.example .env
```[cite: 4]
* **Para la MV CMS 1 (`10.33.199.18`):**[cite: 3, 4]
  ```ini
  CONTAINER_NAME=cms_backend_1
  DOMAIN=nodosur.tas02.lab
  WORDPRESS_DB_HOST=db.tas02.lab:3306
  WORDPRESS_DB_NAME=wordpress_db
  WORDPRESS_DB_USER=wp_user
  WORDPRESS_DB_PASSWORD=triplef
  ```[cite: 3, 4]
* **Para la MV CMS 2 (`10.33.199.20`):**[cite: 3, 4]
  ```ini
  CONTAINER_NAME=cms_backend_2
  DOMAIN=nodosur.tas02.lab
  WORDPRESS_DB_HOST=db.tas02.lab:3306
  WORDPRESS_DB_NAME=wordpress_db
  WORDPRESS_DB_USER=wp_user
  WORDPRESS_DB_PASSWORD=triplef
  ```[cite: 3, 4]

```


3. Ejecutar el script de despliegue automatizado:


```bash
chmod +x setup.sh
./setup.sh
```[cite: 1, 4]
*(Inicia el contenedor, monta el volumen NFS, sincroniza temas y plugins y asegura la propiedad de archivos a `www-data`)[cite: 1, 3, 4].*


```



---

## 🔍 Comprobación y Verificación del Sistema

Para confirmar la correcta operación del entorno multicontenedor, se pueden ejecutar los siguientes comandos:

* **Prueba de acceso web HTTPS:**
Acceder desde un cliente a [https://nodosur.tas02.lab](https://www.google.com/search?q=https://nodosur.tas02.lab) o ejecutar en consola:


```bash
curl -Ivk https://nodosur.tas02.lab
```[cite: 1]

```


* **Prueba de sincronización en tiempo real del almacenamiento NFS:**
1. Crear un archivo de prueba desde la MV del primer backend con el usuario `www-data`:


```bash
docker exec -u www-data -it cms_backend_1 touch /var/www/html/wp-content/uploads/sincronia_test.txt
```[cite: 1]

```


2. Verificar la presencia inmediata del archivo desde el contenedor en la segunda MV:


```bash
docker exec -it cms_backend_2 ls -la /var/www/html/wp-content/uploads/sincronia_test.txt
```[cite: 1]

```




(La visualización del archivo confirma la coherencia de datos en tiempo real entre ambos backends).



---
