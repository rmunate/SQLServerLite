
# Manual Instalación SQLServer Linux Ubuntu (16.04 | 18.04 | 20.04 | 22.04)
## PHP-FPM (7.4 - 8.0 - 8.1)

A continuación el manual de Instalación de SQLServer en Linux Ubuntu 20.04

## Documentacion Oficial Microsoft
- https://learn.microsoft.com/en-us/sql/connect/odbc/linux-mac/installing-the-microsoft-odbc-driver-for-sql-server?view=sql-server-ver16&tabs=alpine18-install%2Calpine17-install%2Cdebian8-install%2Credhat7-13-install%2Crhel7-offline
- https://learn.microsoft.com/en-us/sql/connect/php/installation-tutorial-linux-mac?view=sql-server-ver16

## Instalación

PRE-REQUISITOS:
Garantice tener CURL instalada en su PHP 

Para validar si tiene la extensión:
```bash
dpkg -l | grep 'php8.1-curl'
```

De no tenerla instalada, podrá instalarla con el siguiente comando:
```bash
apt-get install php8.1-curl
```

Debe contar con OpenSSL instalado en su servidor Linux:
- https://www.openssl.org/source/

Adicional debe configurar el archivo `/etc/ssl/openssl.cnf`

Agregando:
```bash
openssl_conf = openssl_init 
# Lo anterior debe estar en la primera linea del archivo.

# Lo siguiente debe estar al final del archivo.
[openssl_init]
ssl_conf = ssl_sect

[ssl_sect]
system_default = system_default_sect

[system_default_sect]
CipherString = DEFAULT@SECLEVEL=1 
#Si llega a generar problemas de SSL la conexion, cambiar a DEFAULT@SECLEVEL=0
```

Ahora revisaremos que nuestra version de PHP con FPM este corriendo correctamente en el servidor.
Cambie la versión de PHP en los comandos por las posibles a usar con este manual (7.4 - 8.0 - 8.1)

```bash
systemctl status php8.1-fpm
```

Garantizando que el PHP-FPM se está ejecutando correctamente.

Ahora Instalaremos los Drivers de ODBC (Microsoft ODBC 17) necesarios para que la conexión con SQLServer sea correcta. Copie y pegue todo el bloque siguiente con permisos de Super Usuario (root), sobre la terminal que use para administrar el Servidor.

```bash
if ! [[ "16.04 18.04 20.04 22.04" == *"$(lsb_release -rs)"* ]];
then
    echo "Ubuntu $(lsb_release -rs) is not currently supported.";
    exit;
fi

sudo su
curl https://packages.microsoft.com/keys/microsoft.asc | apt-key add -

curl https://packages.microsoft.com/config/ubuntu/$(lsb_release -rs)/prod.list > /etc/apt/sources.list.d/mssql-release.list

exit
sudo apt-get update
sudo ACCEPT_EULA=Y apt-get install -y msodbcsql17
# optional: for bcp and sqlcmd
sudo ACCEPT_EULA=Y apt-get install -y mssql-tools
echo 'export PATH="$PATH:/opt/mssql-tools/bin"' >> ~/.bashrc
source ~/.bashrc
# optional: for unixODBC development headers
sudo apt-get install -y unixodbc-dev
```

Asegúrese de instalar también el `unixodbc-dev` paquete. Es utilizado por el peclcomando para instalar los controladores de PHP.

Luego, instalaremos los controladores de PHP para Microsoft SQL Server (Ubuntu con PHP-FPM), 

```bash
sudo pecl config-set php_ini /etc/php/8.1/fpm/php.ini
sudo pecl install sqlsrv
sudo pecl install pdo_sqlsrv
sudo su
printf "; priority=20\nextension=sqlsrv.so\n" > /etc/php/8.1/mods-available/sqlsrv.ini
printf "; priority=30\nextension=pdo_sqlsrv.so\n" > /etc/php/8.1/mods-available/pdo_sqlsrv.ini
exit
sudo phpenmod -v 8.1 sqlsrv pdo_sqlsrv
```

Ahora verifique que `sqlsrv.ini` y `pdo_sqlsrv.ini` se encuentran en `/etc/php/8.1/fpm/conf.d/`:

```bash
ls /etc/php/8.1/fpm/conf.d/*sqlsrv.ini
```

Con estos pasos el servicio deberia quedan en funcionamiento.
Luego procedemos a Reiniciar el PHP y el APACHE.

```bash
service php8.1-fpm restart
```

```bash
systemctl restart apache2
```

------------------------------------------------------------

Si despues de ejecutar los anteriores pasos, no funciona la conexión o salen las alertas de la siguiente imagen, corra los comandos del siguiente bloque:

![Error al cargar la biblioteca dinamica](https://i.ibb.co/3yPxFrz/Captura-de-pantalla-2023-02-23-a-la-s-8-12-42-a-m.png)

Comandos para reparar el error:

```bash
sudo apt install php8.1-dev
sudo update-alternatives --set php /usr/bin/php8.1
sudo update-alternatives --set php-config /usr/bin/php-config8.1
sudo update-alternatives --set phpize /usr/bin/phpize8.1
sudo pecl install -f sqlsrv
sudo pecl install -f pdo_sqlsrv
sudo phpenmod -v 8.1 sqlsrv pdo_sqlsrv
```

Ahora volveremos a correr todos los pasos previos desde revisar que PHP esta corriendo correctamente hasta reiniciar el servidor Apache2.

¡Listo!, Ya podemos conectarnos a SQLServer desde Linux.

## Mantenedores Manual
- Ingeniero, Raúl Mauricio Uñate Castro (raulmauriciounate@gmail.com)