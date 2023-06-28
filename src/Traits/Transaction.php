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

trait Transaction
{
    /**
     * Do not automatically save changes
     *
     * @return $this
     */
    public function beginTransaction()
    {
        $this->PDO->beginTransaction();
        return $this;
    }

    /**
     * Save changes
     *
     * @return $this
     */
    public function commit()
    {
        $this->PDO->commit();
        return $this;
    }

    /**
     * Not Save changes
     *
     * @return $this
     */
    public function rollback()
    {
        $this->PDO->rollback();
        return $this;
    }

    /**
     * Auto Commit in the transaction
     *
     * @param bool $status
     * @return $this
     */
    public function autoCommit(bool $status = true)
    {
        $this->PDO->setAttribute(PDO::ATTR_AUTOCOMMIT, $status);
        return $this;
    }
}