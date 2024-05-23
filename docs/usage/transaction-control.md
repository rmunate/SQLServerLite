---
title: Transaction Control
editLink: true
outline: deep
---

# Transaction Control

You may need manual control over Rollback or Commit in database transactions, so if you need to do so, you should use the following syntax.

```php
SQLServer::beginTransaction();

try {

    // Ejecutar acciones con la base de datos
    SQLServer::connection("example")
               ->update("UPDATE users SET name = :fullname WHERE id = :id")
               ->params([
                    'fullname' => $newName,
                    'id' => $searchId
               ]);

    SQLServer::commit();

} catch (\Throwable $th) {
    
    // throw $th;

    SQLServer::rollback();
}

```