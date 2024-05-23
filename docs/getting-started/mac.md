---
title: Windows Drivers
editLink: true
outline: deep
---

# SQL Server Drivers on macOS

To install the Microsoft ODBC 17 driver for SQL Server on macOS, follow these commands:

1. Open a terminal on your macOS.

2. Run the following command to install Homebrew if you haven't installed it yet:

```bash
/bin/bash -c "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/master/install.sh)"
```

3. Add the Microsoft SQL Server repository to Homebrew:

```bash
brew tap microsoft/mssql-release https://github.com/Microsoft/homebrew-mssql-release
```

4. Update Homebrew:

```bash
brew update
```

5. Make sure to accept the Microsoft License Agreement before installing the packages:

```bash
HOMEBREW_ACCEPT_EULA=Y brew install msodbcsql17 mssql-tools
```

This will install the ODBC 17 driver and SQL Server tools.

6. If you had previously installed the `msodbcsql` v17 package, ensure to uninstall it to avoid conflicts. You can uninstall it with the following command:

```bash
brew uninstall msodbcsql
```

The `msodbcsql17` package can be installed alongside the `msodbcsql` v13 package.

7. Verify that PHP is in your path. Run the following command to check that you are using the correct PHP version:

```bash
php -v
```

If PHP is not in your path or is not the correct version, run the following commands:

```bash
brew link --force --overwrite php@8.2
```

If you are using a Mac with Apple M1 ARM64, you may need to set the path:

```bash
export PATH="/opt/homebrew/bin:$PATH"
```

8. Additionally, you may need to install the GNU Make tools:

```bash
brew install autoconf automake libtool
```

9. Install the PHP drivers for Microsoft SQL Server:

```bash
sudo pecl install sqlsrv
sudo pecl install pdo_sqlsrv
```

If you are using a Mac with Apple M1 ARM64, use these commands instead:

```bash
sudo CXXFLAGS="-I/opt/homebrew/opt/unixodbc/include/" LDFLAGS="-L/opt/homebrew/lib/" pecl install sqlsrv
sudo CXXFLAGS="-I/opt/homebrew/opt/unixodbc/include/" LDFLAGS="-L/opt/homebrew/lib/" pecl install pdo_sqlsrv
```

10. Finally, restart Apache if you are using it:

```bash
brew services restart httpd
```

With these steps, you should have the Microsoft SQL Server ODBC 17 driver and the necessary PHP drivers installed on your macOS system. This will allow you to connect to a SQL Server database from your PHP application.
