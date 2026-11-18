<?php

declare(strict_types=1);

use Rmunate\SqlServerLite\Response\SQLServerResponse;
use Rmunate\SqlServerLite\Response\SQLServerRow;
use Rmunate\SqlServerLite\Singleton\SQLServerSingleton;
use Rmunate\SqlServerLite\SQLServer;

require dirname(__DIR__).'/vendor/autoload.php';

error_reporting(E_ALL);
set_error_handler(static function (int $severity, string $message, string $file, int $line): bool {
    if (($severity & error_reporting()) !== 0) {
        throw new ErrorException($message, 0, $severity, $file, $line);
    }

    return false;
});

function expectSame($expected, $actual, string $message): void
{
    if ($expected !== $actual) {
        throw new RuntimeException($message.' Expected '.var_export($expected, true).', got '.var_export($actual, true));
    }
}

$pdo = new PDO('sqlite::memory:', null, null, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
$pdo->exec('CREATE TABLE products (id INTEGER PRIMARY KEY, name TEXT, price TEXT)');
$pdo->exec("INSERT INTO products VALUES (1, 'Desk', '0'), (2, 'Chair', '200'), (3, 'Lamp', NULL)");

// Use the public query API without relying on a running SQL Server.
$connections = new ReflectionProperty(SQLServerSingleton::class, 'instance');
if (PHP_VERSION_ID < 80100) {
    $connections->setAccessible(true);
}
$connections->setValue(null, ['test' => $pdo]);

$query = new SQLServer(null, 'test');
$select = 'SELECT name, price FROM products ORDER BY id';

expectSame('0', $query->select($select)->value('price'), 'value() returns the first value even when falsy.');
expectSame(null, $query->select($select)->value('missing'), 'value() returns null for a missing column.');
expectSame(null, $query->select('SELECT price FROM products WHERE id = 3')->value('price'), 'value() preserves a null value.');
expectSame(null, $query->select('SELECT price FROM products WHERE id = 0')->value('price'), 'value() returns null for no rows.');

$row = $query->select($select)->first();
expectSame(SQLServerRow::class, get_class($row), 'first() returns a row object.');
expectSame(['name' => 'Desk', 'price' => '0'], get_object_vars($row), 'Row columns are public properties.');

$seen = [];
$chunks = $query->select($select)->chunk(2, static function (SQLServerResponse $chunk) use (&$seen): void {
    $seen[] = $chunk->pluck('name')->all();
});
expectSame([['Desk', 'Chair'], ['Lamp']], $seen, 'chunk() calls the callback for each chunk.');
expectSame([0, 1], $chunks[0]->keys()->all(), 'chunk() keeps the original row keys.');
expectSame([2], $chunks[1]->keys()->all(), 'chunk() keeps row keys in subsequent chunks.');
expectSame($chunks->toArray(), $query->select($select)->chunk(2)->toArray(), 'The callback does not change the returned chunks.');
expectSame([], $query->select($select)->chunk(0)->all(), 'chunk(0) can create an empty SQLServerResponse.');

$response = SQLServerResponse::make(new ArrayIterator(['name' => 'Desk']));
expectSame(['name' => 'Desk'], $response->all(), 'Inherited factories accept iterable data.');

echo 'Compatibility tests passed on PHP '.PHP_VERSION.PHP_EOL;
