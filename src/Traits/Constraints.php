<?php

/*
 * Copyright (c) [2023] [RAUL MAURICIO UÑATE CASTRO]
 *
 * This library is open source software licensed under the MIT license.
 * Permission is hereby granted, free of charge, to any person obtaining a copy of this library and associated
 * documentation files (the "Software"), to deal in the library without restriction, including without limitation
 * the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the library,
 * and to permit persons to whom the library is furnished to do so, subject to the following conditions:
 *
 * - Use the library for commercial or non-commercial purposes.
 * - Modify the library and adapt it to your own needs.
 * - Distribute copies of the library.
 * - Sublicense the library.
 *
 * When using or distributing this library, it is required to include the following attribution in all copies or
 * substantial portions of the library:
 *
 * "[RAUL MAURICIO UÑATE CASTRO], the copyright holder of this library, must
 * be acknowledged and mentioned in all copies or derivatives of the library."
 *
 * In addition, if modifications are made to the library, it is requested to include an additional note in the
 * documentation or in any other means of notifying the changes made, stating:
 *
 * "This library has been modified from the original library developed by [RAUL MAURICIO UÑATE CASTRO]."
 *
 * THE LIBRARY IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT
 * LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.
 * IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN
 * CONNECTION WITH THE LIBRARY OR THE USE OR OTHER DEALINGS IN THE LIBRARY.
 */
namespace Rmunate\SqlServerLite\Traits;

use PDO;
use Exception;
use Rmunate\SqlServerLite\Exceptions\Messages;

trait Constraints
{
    /**
     * Disable foreign key constraints for all tables or specified tables.
     *
     * @param array $tables The array of table names.
     * @return $this The current instance of the object.
     * @throws Exception If there is an error disabling foreign keys.
     */
    public function disableForeignKeys(array $tables = [])
    {
        try {
            if (!empty($tables)) {
                // Disable foreign keys for specified tables
                $disableForeigns = '';
                foreach ($tables as $key => $table) {
                    $disableForeigns .= "ALTER TABLE $table NOCHECK CONSTRAINT ALL;";
                }
                $this->pdo->exec($disableForeigns);
            } else {
                // Disable foreign keys for all tables
                $query = "EXEC sp_msforeachtable 'ALTER TABLE ? NOCHECK CONSTRAINT ALL';";
                $success = $this->pdo->exec($query);

                if ($success === false) {
                    // If the stored procedure doesn't exist, use the alternative option
                    $query = "SELECT 'ALTER TABLE ' + OBJECT_NAME(parent_object_id) + ' NOCHECK CONSTRAINT ' + name 
                            FROM sys.foreign_keys 
                            WHERE type = 'F' AND is_disabled = 0";

                    $result = $this->pdo->query($query);
                    if ($result) {
                        // Build an array of ALTER TABLE statements
                        $statements = $result->fetchAll(PDO::FETCH_COLUMN);

                        foreach ($statements as $statement) {
                            // Disable each foreign key constraint
                            $success = $this->pdo->exec($statement);
                            if ($success === false) {
                                throw new Exception(Messages::disableForeignKeysException());
                            }
                        }
                    } else {
                        throw new Exception(Messages::disableForeignKeysException());
                    }
                }
            }
        } catch (Exception $e) {
            // Throw the exception
            throw new Exception(Messages::disableForeignKeysException("Error disabling foreign keys: " . $e->getMessage()));
        }

        return $this;
    }

    /**
     * Enable foreign key constraints for all tables or specified tables.
     *
     * @param array $tables The array of table names.
     * @return $this The current instance of the object.
     * @throws Exception If there is an error enabling foreign keys.
     */
    public function enableForeignKeys(array $tables = [])
    {
        try {
            if (!empty($tables)) {
                // Enable foreign keys for specified tables
                $enableForeigns = '';
                foreach ($tables as $key => $table) {
                    $enableForeigns .= "ALTER TABLE $table CHECK CONSTRAINT ALL;";
                }
                $this->pdo->exec($enableForeigns);
            } else {
                // Enable foreign keys for all tables
                $query = "EXEC sp_msforeachtable 'ALTER TABLE ? WITH CHECK CHECK CONSTRAINT ALL';";
                $success = $this->pdo->exec($query);

                if ($success === false) {
                    // If the stored procedure doesn't exist, use the alternative option
                    $query = "SELECT 'ALTER TABLE ' + OBJECT_NAME(parent_object_id) + ' WITH CHECK CHECK CONSTRAINT ' + name 
                            FROM sys.foreign_keys 
                            WHERE type = 'F' AND is_disabled = 1";

                    $result = $this->pdo->query($query);
                    if ($result) {
                        // Build an array of ALTER TABLE statements
                        $statements = $result->fetchAll(PDO::FETCH_COLUMN);

                        foreach ($statements as $statement) {
                            // Enable each foreign key constraint
                            $success = $this->pdo->exec($statement);
                            if ($success === false) {
                                throw new Exception(Messages::enableForeignKeysException());
                            }
                        }
                    } else {
                        throw new Exception(Messages::enableForeignKeysException());
                    }
                }
            }
        } catch (Exception $e) {
            // Throw the exception
            throw new Exception(Messages::enableForeignKeysException("Error enabling foreign keys: " . $e->getMessage()));
        }

        return $this;
    }

    /**
     * Deprecated method: Disable foreign key constraints for all tables or specified tables.
     * Use disableForeignKeys() method instead.
     *
     * @param array $tables The array of table names.
     * @return $this The current instance of the object.
     * @throws Exception If there is an error disabling foreign keys.
     * @deprecated This method is deprecated and will be removed in the next major version.
     */
    public function noCheck(array $tables = [])
    {
        try {
            return $this->disableForeignKeys($tables);
        } catch (Exception $e) {
            // Throw the exception
            throw new Exception(Messages::disableForeignKeysException("Error disabling foreign keys: " . $e->getMessage()));
        }
    }

    /**
     * Deprecated method: Enable foreign key constraints for all tables or specified tables.
     * Use enableForeignKeys() method instead.
     *
     * @param array $tables The array of table names.
     * @return $this The current instance of the object.
     * @throws Exception If there is an error enabling foreign keys.
     * @deprecated This method is deprecated and will be removed in the next major version.
     */
    public function check(array $tables = [])
    {
        try {
            return $this->enableForeignKeys($tables);
        } catch (Exception $e) {
            // Throw the exception
            throw new Exception(Messages::enableForeignKeysException("Error enabling foreign keys: " . $e->getMessage()));
        }
    }
}