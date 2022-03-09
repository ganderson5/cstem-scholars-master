<?php

/**
 * "Lazy" database class. Database connection will not be estabilished until it is needed.
 */
class DB
{
    private static $source;
    private static $username;
    private static $password;
    private static $options;
    private static $pdo;

    public static function configure($source, $username, $password, $options = [])
    {
        self::$source = $source;
        self::$username = $username;
        self::$password = $password;
        self::$options = $options;
        self::$pdo = null;
    }

    public static function pdo()
    {
        if (!isset(self::$pdo) || self::$pdo == null) {
            self::$pdo = new PDO(self::$source, self::$username, self::$password, self::$options);
        }

        return self::$pdo;
    }

    /**
     * Query database and return a PDOStatement object.
     *
     * Example:
     *    DB::query('SELECT * FROM Advisor WHERE AEmail = ? OR AName = ?', 'alex@ewu.edu', 'Alex');
     *    DB::query('SELECT * FROM Advisor WHERE AEmail = :email', ['email' => 'alex@ewu.edu']); // Named parameters
     */
    public static function query($query, ...$params)
    {
        // Allow associative arrays
        if (sizeof($params) == 1 && is_array($params[0])) {
            $params = $params[0];
        }

        $stmt = self::pdo()->prepare($query);
        $stmt->execute($params);

        return $stmt;
    }

    /**
     * Prepare a select query and return a PDOStatement object.
     *
     * Example: DB::select('Advisor, 'AEmail = ? OR AName = ?', 'alex@ewu.edu', 'Alex');
     */
    public static function select($table, $conditions = '', ...$params)
    {
        $where = empty($conditions) ? '' : 'WHERE';
        return self::query("SELECT * FROM $table $where $conditions", ...$params);
    }

    /**
     * Select a single row (all columns) from $table satisfying $conditions.
     *
     * Example: DB::selectSingle('Advisor, 'AEmail = ? OR AName = ?', 'alex@ewu.edu', 'Alex');
     */
    public static function selectSingle($table, $conditions = '', ...$params)
    {
        return self::select($table, $conditions, ...$params)->fetch();
    }

    /**
     * Count number of rows satisfying $conditions.
     * WARNING: Do not allow unescaped user input in $table or $conditions.
     *
     * Example: DB::count("Advisor", "AEmail = ?", 'email@ewu.edu');
     */
    public static function count($table, $conditions = '', ...$params)
    {
        $where = empty($conditions) ? '' : 'WHERE';
        return (int)self::query("SELECT COUNT(*) FROM $table $where $conditions", ...$params)->fetchColumn();
    }

    /**
     * Check if there are any rows in $table satisfying $conditions.
     *
     * Example: if (DB::contains("Advisor", "AEmail = ?", 'email@ewu.edu')) { ... }
     */
    public static function contains($table, $conditions = '', ...$params)
    {
        return self::count($table, $conditions, ...$params) > 0;
    }

    /**
     * Execute a query and return number of affected rows. Use this function for DELETE, INSERT, or UPDATE statements.
     */
    public static function execute($query, ...$params)
    {
        return self::query($query, ...$params)->rowCount();
    }

    /**
     * Insert a row into $table. $values is an associative array where each key is a column name in the table.
     * Returns true if the row was inserted successfully; false otherwise.
     *
     * Example: DB::insert('advisor', ['AEmail' => 'advisor@ewu.edu', 'AName' => 'Advisor']);
     */
    public static function insert($table, $values)
    {
        $valuesFragment = self::makeValueList($values);
        return self::execute("INSERT INTO $table $valuesFragment", array_values($values)) > 0;
    }

    public static function update($table, $values, $where, ...$params)
    {
        $columns = [];

        foreach (array_keys($values) as $k) {
            $columns[] = "`$k` = ?";
        }

        $columns = implode(', ', $columns);

        return self::execute(
                "UPDATE $table SET $columns WHERE $where",
                array_merge(array_values($values), $params)
            ) > 0;
    }

    // Returns number of deleted records
    public static function delete($table, $where, ...$params)
    {
        return self::execute("DELETE FROM $table WHERE $where", ...$params);
    }

    public static function beginTransaction()
    {
        self::pdo()->beginTransaction();
    }

    public static function commit()
    {
        self::pdo()->commit();
    }

    public static function rollback()
    {
        self::pdo()->rollback();
    }

    private static function makeValueList($values)
    {
        // Keys = column names
        $columns = array_keys($values);

        // Wrap each column in backticks to allow spaces or reserved keywords in column names
        $columns = array_map(
            function ($k) {
                return "`$k`";
            },
            $columns
        );

        $columns = implode(', ', $columns);

        // Fill $valuePlaceholders with '?, ?, ?, ... , ?'
        $valuePlaceholders = implode(', ', array_fill(0, count($values), '?'));

        return "($columns) VALUES ($valuePlaceholders)";
    }

}
