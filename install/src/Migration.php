<?php

namespace Install;

use App\Database;
use App\MigrationFiles;
use InvalidArgumentException;

abstract class Migration
{
    /** @var Database */
    protected $db;

    /** @var MigrationFiles */
    protected $migrationFiles;

    public function __construct(Database $db, MigrationFiles $migrationFiles)
    {
        $this->db = $db;
        $this->migrationFiles = $migrationFiles;
    }

    abstract public function up();

    protected function executeQueries($queries)
    {
        foreach ($queries as $query) {
            $this->db->query($query);
        }
    }

    protected function executeSqlFile($file)
    {
        $path = $this->migrationFiles->path($file);
        $queries = $this->splitSQLFile($path);
        $this->executeQueries($queries);
    }

    protected function splitSQLFile($path, $delimiter = ';')
    {
        $queries = [];

        $path = fopen($path, 'r');

        if (is_resource($path) !== true) {
            throw new InvalidArgumentException('Invalid path to queries');
        }

        $query = [];

        while (feof($path) === false) {
            $query[] = fgets($path);

            if (preg_match('~'.preg_quote($delimiter, '~').'\s*$~iS', end($query)) === 1) {
                $query = trim(implode('', $query));
                $queries[] = $query;
            }

            if (is_string($query) === true) {
                $query = [];
            }
        }

        fclose($path);

        return array_filter($queries, function ($query) {
            return strlen($query);
        });
    }
}
