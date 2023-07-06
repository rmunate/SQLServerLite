<?php

namespace Rmunate\SqlServerLite\Traits;

use Exception;
use PDO;
use Rmunate\SqlServerLite\Exceptions\Messages;

trait Constraints
{
    /**
     * Disable foreign key constraints for all tables or specified tables.
     *
     * @param array $tables The array of table names.
     *
     * @throws Exception If there is an error disabling foreign keys.
     *
     * @return $this The current instance of the object.
     */
    public function disableForeignKeys(...$tables)
    {

        // Ensure the connection is established before executing the trait method
        $this->connectionPDO();

        try {

            if (!empty($tables)) {

                // Disable foreign keys for specified tables
                $disableForeigns = '';

                foreach ($tables as $key => $table) {
                    $disableForeigns .= "ALTER TABLE $table NOCHECK CONSTRAINT ALL;";
                }

                $this->PDO->exec($disableForeigns);

            } else {

                // Disable foreign keys for all tables
                $query = "EXEC sp_msforeachtable 'ALTER TABLE ? NOCHECK CONSTRAINT ALL';";
                $success = $this->PDO->exec($query);

                if ($success === false) {

                    // If the stored procedure doesn't exist, use the alternative option
                    $query = "SELECT 'ALTER TABLE ' + OBJECT_NAME(parent_object_id) + ' NOCHECK CONSTRAINT ' + name 
                            FROM sys.foreign_keys 
                            WHERE type = 'F' AND is_disabled = 0";

                    $result = $this->PDO->query($query);

                    if ($result) {

                        // Build an array of ALTER TABLE statements
                        $statements = $result->fetchAll(PDO::FETCH_COLUMN);

                        foreach ($statements as $statement) {

                            // Disable each foreign key constraint
                            $success = $this->PDO->exec($statement);

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

            throw new Exception(Messages::disableForeignKeysException('Error disabling foreign keys: '.$e->getMessage()));

        }

        return $this;
    }

    /**
     * Enable foreign key constraints for all tables or specified tables.
     *
     * @param array $tables The array of table names.
     *
     * @throws Exception If there is an error enabling foreign keys.
     *
     * @return $this The current instance of the object.
     */
    public function enableForeignKeys(...$tables)
    {
        // Ensure the connection is established before executing the trait method
        $this->connectionPDO();
        
        try {

            if (!empty($tables)) {

                // Enable foreign keys for specified tables
                $enableForeigns = '';

                foreach ($tables as $key => $table) {
                    $enableForeigns .= "ALTER TABLE $table CHECK CONSTRAINT ALL;";
                }

                $this->PDO->exec($enableForeigns);

            } else {

                // Enable foreign keys for all tables
                $query = "EXEC sp_msforeachtable 'ALTER TABLE ? WITH CHECK CHECK CONSTRAINT ALL';";
                $success = $this->PDO->exec($query);

                if ($success === false) {

                    // If the stored procedure doesn't exist, use the alternative option
                    $query = "SELECT 'ALTER TABLE ' + OBJECT_NAME(parent_object_id) + ' WITH CHECK CHECK CONSTRAINT ' + name 
                            FROM sys.foreign_keys 
                            WHERE type = 'F' AND is_disabled = 1";

                    $result = $this->PDO->query($query);

                    if ($result) {

                        // Build an array of ALTER TABLE statements
                        $statements = $result->fetchAll(PDO::FETCH_COLUMN);

                        foreach ($statements as $statement) {

                            // Enable each foreign key constraint
                            $success = $this->PDO->exec($statement);

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

            throw new Exception(Messages::enableForeignKeysException('Error enabling foreign keys: '.$e->getMessage()));

        }

        return $this;
    }
}
