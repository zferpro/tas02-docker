# 🚀 Proyecto Nodo Sur - Plataforma Web Resiliente con Docker

Este `README.md` explica de forma clara y directa cómo está estructurado nuestro proyecto para la asignatura **Taller de Administración de Sistemas (TAS02)**. Está pensado para que cualquier integrante del equipo pueda entender la arquitectura, saber qué hace cada servidor y levantar todo el entorno de 0 a 100 sin complicaciones.

> 🔒 **Nota de Seguridad:** En este repositorio **no se incluyen contraseñas ni llaves privadas**. Todas las credenciales reales de la base de datos, accesos administradores y claves SSH se gestionan mediante variables de entorno local (`.env`) y se encuentran respaldadas en nuestro documento privado de equipo.
> 
> 

---

## 💡 ¿De qué se trata la arquitectura?

El objetivo es desplegar un sitio web en **WordPress** corriendo de forma distribuida sobre **4 Máquinas Virtuales (MVs)** para garantizar tolerancia a fallas y alta disponibilidad.

Toda la solución funciona en **contenedores Docker** organizados en 3 capas/repositorios principales:

1. **DNS y Proxy Inverso (`docker-dns-proxy`)**:


* **BIND9**: Resuelve de forma local el nombre de dominio (`nodosur.tas02.lab`).


* **HAProxy**: Recibe el tráfico seguro en el puerto HTTPS (443), gestiona los certificados SSL y distribuye las visitas entre los dos servidores web.




2. **Base de Datos y Almacenamiento Compartido (`docker-db`)**:


* **MariaDB 10.11**: Guarda de forma centralizada la base de datos (`wordpress_db`).


* **Servidor NFS**: Comparte la carpeta de archivos e imágenes (`wp-content/uploads`) entre ambos nodos web para que siempre estén sincronizados.




3. **Servidores Web CMS (`docker-cms`)**:


* Dos MVs independientes, cada una ejecutando un contenedor de **WordPress**.


* Ambos nodos se conectan a la misma base de datos remota y montan de forma nativa el mismo volumen NFS compartido.





---

## 🗺️ Mapa de Red e Infraestructura

La red privada asignada al proyecto es `10.33.199.16/29` y se distribuye así:

| Máquina Virtual | IP LAN | Rol en el Sistema | Contenedores Docker |
| --- | --- | --- | --- |
| **`dns-tas02`** | `10.33.199.17` | DNS Autoritativo y Proxy | `dns_bind9` (Puerto 53) + `cms_haproxy` (Puertos 80, 443)

 |
| **`cms-tas02` (CMS 1)** | `10.33.199.18` | Servidor Web #1 | `cms_backend_1` (WordPress en Puerto 80)

 |
| **`db-tas02`** | `10.33.199.19` | Base de Datos + NFS | `db_mariadb` (Puerto 3306) + `shared_nfs` (Puerto 2049)

 |
| **`cms2-tas02` (CMS 2)** | `10.33.199.20` | Servidor Web #2 | `cms_backend_2` (WordPress en Puerto 80)

 |

---

## 🛠️ Guía de Despliegue de 0 a 100%

Para asegurar que todo inicie correctamente y sin errores de dependencia, **debemos seguir estrictamente este orden**:

---

### Paso 1: Base de Datos y Servidor NFS (`docker-db`)

* **Servidor destino:** Máquina Virtual `db-tas02` (`10.33.199.19`).



1. Clonar el repositorio y entrar a la carpeta:


```bash
cd /home/tas02/
git clone <URL_REPO_DOCKER_DB>
cd docker-db

```


2. Crear el archivo `.env` basándose en la plantilla de ejemplo:


```bash
cp .env.example .env

```


Configuración del `.env` (completar con las claves acordadas del documento privado):


```ini
MYSQL_ROOT_PASSWORD=<CONTRASEÑA_ROOT_AQUI>
MYSQL_DATABASE=wordpress_db
MYSQL_USER=wp_user
MYSQL_PASSWORD=<CONTRASEÑA_USUARIO_AQUI>
```[cite: 1, 4]

```


3. Dar permisos y ejecutar el pre-setup:


```bash
chmod +x setup.sh post-setup.sh
./setup.sh
```[cite: 1]
*(Carga los módulos NFS en el kernel de la MV y prepara la carpeta de imágenes con permisos para el usuario `www-data` - UID `33:33`)[cite: 1, 4].*

```


4. Levantar los contenedores:


```bash
docker compose up -d
```[cite: 1]

```


5. Ejecutar el post-setup:


```bash
./post-setup.sh
```[cite: 1]
*(Espera a que MariaDB responda, importa automáticamente la base de datos `backup_wordpress.sql` y verifica la copia de los archivos multimedia)[cite: 1, 4].*


```



---

### Paso 2: DNS y Proxy Inverso (`docker-dns-proxy`)

* **Servidor destino:** Máquina Virtual `dns-tas02` (`10.33.199.17`).



1. Detener el servicio BIND nativo de la máquina para no ocupar el puerto 53:


```bash
sudo systemctl stop bind9
sudo systemctl disable bind9
```[cite: 1]

```


2. Acceder a la carpeta del proyecto y generar el certificado SSL autosignado:


```bash
cd /home/tas02/docker-dns-proxy
chmod +x setup.sh
./setup.sh
```[cite: 1]
*(Crea de forma local el archivo `nodosur.pem` para que HAProxy pueda recibir conexiones HTTPS protegidas)[cite: 1].*

```


3. Levantar los contenedores:


```bash
docker compose up -d
```[cite: 1]


```



---

### Paso 3: Servidores Web CMS (`docker-cms`)

* **Servidores destino:** Máquinas Virtuales `cms-tas02` (`10.33.199.18`) y `cms2-tas02` (`10.33.199.20`).



1. Instalar la herramienta de NFS en el sistema de la MV host:


```bash
sudo apt update && sudo apt install -y nfs-common
```[cite: 1, 3]

```


2. Clonar el repo y preparar el `.env` según corresponda:


```bash
cd /home/tas02/tas02-docker/docker-cms/
cp .env.example .env
```[cite: 4]
* **En la MV CMS 1 (`10.33.199.18`):**[cite: 3, 4]
  ```ini
  CONTAINER_NAME=cms_backend_1
  DOMAIN=nodosur.tas02.lab
  WORDPRESS_DB_HOST=db.tas02.lab:3306
  WORDPRESS_DB_NAME=wordpress_db
  WORDPRESS_DB_USER=wp_user
  WORDPRESS_DB_PASSWORD=<CONTRASEÑA_USUARIO_AQUI>
  ```[cite: 3, 4]
* **En la MV CMS 2 (`10.33.199.20`):**[cite: 3, 4]
  ```ini
  CONTAINER_NAME=cms_backend_2
  DOMAIN=nodosur.tas02.lab
  WORDPRESS_DB_HOST=db.tas02.lab:3306
  WORDPRESS_DB_NAME=wordpress_db
  WORDPRESS_DB_USER=wp_user
  WORDPRESS_DB_PASSWORD=<CONTRASEÑA_USUARIO_AQUI>
  ```[cite: 3, 4]

```


3. Ejecutar el script de despliegue:


```bash
chmod +x setup.sh
./setup.sh
```[cite: 1, 4]
*(Inicia WordPress, monta el volumen NFS, copia temas/plugins y ajusta los permisos para `www-data`)[cite: 1, 3, 4].*


```



---

## 🔍 Comprobaciones para Confirmar que Todo Funciona

* **Acceso HTTPS:**
Entrar desde el navegador a `[https://nodosur.tas02.lab](https://nodosur.tas02.lab)` o probar en consola:


```bash
curl -Ivk https://nodosur.tas02.lab
```[cite: 1]

```


* **Sincronización NFS en Tiempo Real:**
1. Crear un archivo desde el contenedor en CMS1 simulando al usuario web:


```bash
docker exec -u www-data -it cms_backend_1 touch /var/www/html/wp-content/uploads/test_equipo.txt
```[cite: 1]

```


2. Verificar que aparezca inmediatamente dentro del contenedor de CMS2:


```bash
docker exec -it cms_backend_2 ls -la /var/www/html/wp-content/uploads/test_equipo.txt
```[cite: 1]

```




(Si el archivo aparece listado, el almacenamiento compartido está 100% operativo).



---
