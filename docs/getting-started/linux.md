---
title: Linux Drivers
editLink: true
outline: deep
---

# SQL Server Drivers on Linux Ubuntu (16.04 | 18.04 | 20.04 | 22.04)

## PHP-FPM (7.4 - 8.0 - 8.1)

Here is the manual for installing SQL Server on Linux Ubuntu 20.04. The manual covers installation for PHP 8.1, but you can adjust it as needed.

## Official Microsoft Documentation
- [Install the Microsoft ODBC driver for SQL Server (Linux)](https://learn.microsoft.com/en-us/sql/connect/odbc/linux-mac/installing-the-microsoft-odbc-driver-for-sql-server?view=sql-server-ver16&tabs=alpine18-install%2Calpine17-install%2Cdebian8-install%2Credhat7-13-install%2Crhel7-offline)
- [Linux and macOS Installation Tutorial for the Microsoft Drivers for PHP for SQL Server]( https://learn.microsoft.com/en-us/sql/connect/php/installation-tutorial-linux-mac?view=sql-server-ver16)

## Installation

**PRE-REQUISITES:**
Ensure that CURL is installed on your PHP.

To check if the extension is installed:

```bash
dpkg -l | grep 'php8.1-curl'
```

If not installed, you can install it with the following command:

```bash
apt-get install php8.1-curl
```

You must have OpenSSL installed on your Linux server:

- [openssl](https://www.openssl.org/source/)

Additionally, configure the `/etc/ssl/openssl.cnf` file by adding:

```bash
# At the beginning of the file.
openssl_conf = openssl_init

# At the end of the file.
[openssl_init]
ssl_conf = ssl_sect

[ssl_sect]
system_default = system_default_sect

[system_default_sect]
CipherString = DEFAULT@SECLEVEL=1
# Change to DEFAULT@SECLEVEL=0 if SSL connection issues arise.
```

Now, verify that your PHP version with FPM is running correctly on the server. Change the PHP version in the commands according to the versions mentioned in this manual (7.4 - 8.0 - 8.1 - 8.2).

```bash
systemctl status php8.1-fpm
```

Ensure that PHP-FPM is running correctly.

Next, install the necessary ODBC Drivers (Microsoft ODBC 17) for a successful connection to SQL Server. Copy and paste the following block with Super User (root) permissions into the terminal used to manage the server.

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
# Optional: for bcp and sqlcmd
sudo ACCEPT_EULA=Y apt-get install -y mssql-tools
echo 'export PATH="$PATH:/opt/mssql-tools/bin"' >> ~/.bashrc
source ~/.bashrc
# Optional: for unixODBC development headers
sudo apt-get install -y unixodbc-dev
```

Make sure to install the `unixodbc-dev` package as well. It is used by the `pecl` command to install PHP drivers.

Then, install the PHP drivers for Microsoft SQL Server (Ubuntu with PHP-FPM),

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

Now, verify that `sqlsrv.ini` and `pdo_sqlsrv.ini` are in `/etc/php/8.1/fpm/conf.d/`:

```bash
ls /etc/php/8.1/fpm/conf.d/*sqlsrv.ini
```

With these steps, the service should be up and running.
Next, proceed to restart PHP and Apache.

```bash
service php8.1-fpm restart
```

```bash
systemctl restart apache2
```

---

If, after executing the previous steps, the connection doesn't work or you encounter alerts like the one in the following image, run the commands in the next block:

![Dynamic Library Load Error](https://i.ibb.co/3yPxFrz/Captura-de-pantalla-2023-02-23-a-la-s-8-12-42-a-m.png)

Commands to fix the error:

```bash
sudo apt install php8.1-dev
sudo update-alternatives --set php /usr/bin/php8.1
sudo update-alternatives --set php-config /usr/bin/php-config8.1
sudo update-alternatives --set phpize /usr/bin/phpize8.1
sudo pecl install -f sqlsrv
sudo pecl install -f pdo_sqlsrv
sudo phpenmod -v 8.1 sqlsrv pdo_sqlsrv
```

Now, rerun all previous steps from verifying that PHP is running correctly to restarting the Apache2 server.

All done! You can now connect to SQL Server from Linux.