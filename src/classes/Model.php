<?php

abstract class Model
{
    protected static $table = null;
    protected static $primaryKey = 'id';
    protected $fillable = [];
    protected $guarded = [];

    public function __construct($form = [], $fillGuardedColumns = false)
    {
        // Initialize the fields inside the object to null
        foreach ($this->columns() as $f) {
            $this->$f = $this->$f ?? null;
        }

        // Fill out the fields from the provided form
        $this->fill($form, $fillGuardedColumns);
    }

    public static function table()
    {
        return static::$table ?? static::class;
    }

    public static function primaryKey()
    {
        return is_array(static::$primaryKey) ? static::$primaryKey : [static::$primaryKey];
    }

    public static function query($query = '', ...$params)
    {
        $stmt = DB::query($query, ...$params);
        $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, static::class);
        return $stmt;
    }

    /**
     * Returns a PDOStatement object. Use PDOStatement::fetch() or
     * PDOStatement::fetchAll() to fetch objects one-by-one or all at once.
     */
    public static function select($query = '', ...$params)
    {
        $stmt = DB::select(static::table(), $query, ...$params);
        $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, static::class);
        return $stmt;
    }

    /**
     * Returns an array of Model objects.
     */
    public static function all($query = '', ...$params)
    {
        return static::select($query, ...$params)->fetchAll();
    }

    /**
     * Fetches a single object.
     * @return Model object instance or NULL if there wasn't one found.
     */
    public static function first($query = '', ...$params)
    {
        $model = static::select($query, ...$params)->fetch();

        if (!$model) {
            $model = null;
        }

        return $model;
    }

    /**
     * Gets a single object by its primary key.
     *
     * @param $key mixed Primary key of the desired object. If the key is composite, it should be passed in the form of
     *                   an associative array.
     *
     * @return Model
     */
    public static function get($key)
    {
        return static::first(...static::byKey($key));
    }

    public static function delete($where = '', ...$params)
    {
        return DB::delete(static::table(), $where, ...$params);
    }

    public static function deleteByKey($key)
    {
        $numDeleted = static::delete(...static::byKey($key));
        assert($numDeleted <= 1, 'At most one record should have been deleted');
        return $numDeleted > 0;
    }

    public static function count($where = '', ...$params)
    {
        return DB::count(static::table(), $where, ...$params);
    }

    public static function exists($where = '', ...$params)
    {
        return static::count($where, ...$params) > 0;
    }

    public static function insert($values)
    {
        return DB::insert(static::table(), $values);
    }

    public static function update($values, $where, ...$params)
    {
        return DB::update(static::table(), $values, $where, ...$params);
    }

    public function key()
    {
        $key = [];

        foreach (static::primaryKey() as $k) {
            $key[$k] = $this->$k;
        }

        return $key;
    }

    public function errors()
    {
        $errors = [];

        foreach ($this->fillableColumns() as $column) {
            $validator = $this->fillable[$column] ?? null;

            if (is_callable($validator)) {
                $error = $validator($this->$column);

                if ($error) {
                    $errors[$column] = $error;
                }
            }
        }

        return $errors;
    }

    public function isValid()
    {
        return empty($this->errors());
    }

    public function fill($form, $fillGuardedColumns = false)
    {
        $columns = ($fillGuardedColumns) ? $this->columns() : $this->fillableColumns();
        $columns = array_intersect($columns, array_keys($form));

        foreach ($columns as $f) {
            $this->$f = $form[$f];
        }

        return $this;
    }

    public function save($withValidations = true)
    {
        if ($withValidations && !$this->isValid()) {
            return false;
        }

        $key = $this->key();

        if (!static::exists(...static::byKey($key))) {
            $res = static::insert($this->values(false));

            // Try to get an auto_increment key
            if (count(static::primaryKey()) == 1) {
                $lastInsertID = DB::pdo()->lastInsertID(static::primaryKey()[0]);


                if ($lastInsertID != 0) {
                    $this->{static::primaryKey()[0]} = $lastInsertID;
                }
            }

            return $res;
        } else {
            static::update($this->values(false), ...static::byKey($key));
            return true;
        }
    }

    public function values($includeNulls = true)
    {
        $values = [];

        foreach ($this->columns() as $column) {
            if ($this->$column !== null || $includeNulls) {
                $values[$column] = $this->$column;
            }
        }

        return $values;
    }

    public function columns()
    {
        return array_merge(static::primaryKey(), $this->fillableColumns(), $this->guarded);
    }

    public function fillableColumns()
    {
        $fillable = [];

        foreach ($this->fillable as $k => $v) {
            $fillable[] = is_int($k) ? $v : $k;
        }

        assert(empty(array_intersect($fillable, $this->guarded)), 'Fillable and guarded columns must not intersect');
        return array_diff($fillable, $this->guarded);
    }

    protected static function byKey($key)
    {
        if (!is_array($key)) {
            $key = [static::primaryKey()[0] => $key];
        }

        // Remove extra columns that don't belong in the primary key
        $key = array_intersect_key($key, array_flip(static::primaryKey()));

        if (count($key) != count(static::primaryKey())) {
            throw new InvalidArgumentException('Incomplete key');
        }

        $keys = array_map(fn($key) => "$key = ?", array_keys($key));
        $query = implode(' AND ', $keys);

        return [$query, ...array_values($key)];
    }
}
