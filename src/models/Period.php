<?php

use Respect\Validation\ValidatorFunction as v;

class Period extends Model
{
    public $id, $beginDate, $deadline, $advisorDeadline, $budget;

    public function __construct($form = [], $fillGuardedColumns = false)
    {
        $this->fillable = [
            'beginDate' => v::date('Y-m-d')->setName('Start date'),
            'deadline' => v::date('Y-m-d')->setName('Student deadline'),
            'advisorDeadline' => v::date('Y-m-d')->setName('Review deadline'),
            'budget' => v::number()->min(0)->setName('Budget')
        ];

        parent::__construct($form, $fillGuardedColumns);
    }

    public static function current()
    {
        return self::first('beginDate <= :date AND deadline >= :date', ['date' => date('Y-m-d')]);
    }

    public static function currentForAdvisors()
    {
        return self::first('beginDate <= :date AND advisorDeadline >= :date', ['date' => date('Y-m-d')]);
    }

    public static function mostRecent()
    {
        return self::first('beginDate <= :date ORDER BY beginDate DESC', ['date' => date('Y-m-d')]);
    }

    public function errors()
    {
        $errors = parent::errors();

        if ($this->beginDate > $this->deadline) {
            $errors['beginDate'] = 'Begin date must be earlier than the application deadline';
        }

        if ($this->deadline > $this->advisorDeadline) {
            $errors['advisorDeadline'] = 'Advisor deadline cannot precede the application deadline';
        }

        $query = 'beginDate <= :date AND advisorDeadline >= :date AND id != :id';

        if (Period::exists($query, ['date' => $this->beginDate, 'id' => $this->id ?? 0])) {
            $errors['beginDate'] = 'Start date cannot be within an existing period window';
        }

        if (Period::exists($query, ['date' => $this->advisorDeadline, 'id' => $this->id ?? 0])) {
            $errors['advisorDeadline'] = 'Review deadline cannot be within an existing period window';
        }

        return $errors;
    }

    public function applications($where = '', ...$params)
    {
        return Application::all("periodID = ? AND ($where)", $where, $this->id, ...$params);
    }
}
