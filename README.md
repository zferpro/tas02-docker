# 🚀 Proyecto Nodo Sur - Plataforma Web Resiliente con Docker
COSAS QUE FALTAN:
- Item 1 rubrica, Realizar y verificar TODOS los graficos, generar el codigo json de los dashboards y ponerlos en "docker-prometheus/grafana/provisioning/nodosur-operativo.json" aparte realizar matriz de desicion/significado por metrica para cumplir con el "vinculadas a desiciones" de la pauta FRAN

- Item 2 rubrica ,revisar reglas del alertmanager  y realizar prueba de falla:
DOCUMENTAR CON IMAGENES EN EL DOCUMENTO
1. mostrar que todo esta bien , las alertas estan inactivas
2. generar una falla y detectar la hora donde se produjo
3. debe llegar un e-mail , al mail.tas02.lab , con correos dirigidos , en grafana se debe mostrar la falla, como un cmsdown
4. recuperar el servicio o arreglar la falla , deben llegar correos con aviso a los responsables de que se arreglo la falla

# git add .
# git commit -m "Docker cms , db , dns , prometheus v3,1 , optimizado , reglas ufw automatizadas, descargas de archivos complementarios automatizado"  
# git push -u origin main
