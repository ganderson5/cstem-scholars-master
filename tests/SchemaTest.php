<?php

require_once __DIR__ . '/../src/init.php';

use PHPUnit\Framework\TestCase;

abstract class SchemaTest extends TestCase
{
    protected function setUp(): void
    {
        DB::configure('sqlite::memory:', null, null, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
        $schema = file_get_contents(__DIR__ . '/../setup.sql');
        $schema = preg_replace('/^CREATE DATABASE .+/im', '', $schema);
        $schema = preg_replace('/CHARACTER SET = .+/im', '', $schema);
        $schema = preg_replace('/COLLATE = .+/im', '', $schema);
        $schema = preg_replace('/^USE .+/im', '', $schema);
        DB::pdo()->exec($schema);
    }
}
