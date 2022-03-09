<?php

use Respect\Validation\ValidatorFunction as v;

class Application extends Model
{
    public const DEPARTMENTS = [
        'Biology',
        'Chemistry',
        'Computer Science',
        'Design',
        'Engineering',
        'Environmental Science',
        'Geology',
        'Mathematics',
        'Natural Science',
        'Physics'
    ];

    public const VALID_STATES = [
        'draft',                   // application was saved but not submitted for review
        'submitted',               // application was submitted; waiting for advisor sign-off
        'pending_review',          // advisor signed off on the application
        'reviewed',                // at least one reviewer reviewed the application
        'rejected',                // application was rejected
        'awarded'                  // application received funding
    ];

    public $name, $email, $title, $major, $gpa, $graduationDate, $advisorName, $advisorEmail, $description,
        $timeline, $justification, $totalBudget, $requestedBudget, $fundingSources, $studentID, $periodID,
        $status, $budgetTable, $amountAwarded;

    private $hasAgreedToTerms = false;

    public function __construct($form = [], $fillGuardedColumns = false)
    {
        $this->fillable = [
            // Basic Details
            'email' => v::email()->length(null, 50)->setName('Email address'),
            'title' => v::length(3, 140)->setName('Project title'),

            // Major & GPA
            'major' => v::in(self::DEPARTMENTS)->setTemplate('Invalid major'),
            'gpa' => v::number()->min(1.0)->max(4.0)->setName('GPA'),
            'graduationDate' => v::date()->between('today', '+3 years')->setName('Expected Graduation Date'),

            // Advisor Information
            'advisorName' => v::length(3, 50)->setName('Advisor name'),
            'advisorEmail' => 'Application::validateAdvisorEmail',

            // Objective & Results
            'description' => v::length(3, 6000)->setName('Objective'),
            'timeline' => v::length(3, 2000)->setName('Timeline'),

            // Budget
            'justification' => v::length(3, 2000)->setName('Budget description'),
            'totalBudget' => v::number()->min(1)->setName('Budget amount'),
            'requestedBudget' => v::number()->min(1)->max(2000)->setName('Requested amount'),
            'fundingSources' => v::length(3, 140)->setName('Funding sources')
        ];

        $this->guarded = [
            'name',
            'studentID',
            'periodID',
            'status',
            'attachment',
            'budgetTable',
            'amountAwarded'
        ];

        parent::__construct($form, $fillGuardedColumns);
    }

    public static function all($query = '', ...$params)
    {
        $query = ($query) ? "($query) AND status != 'draft'" : "status != 'draft'";
        return parent::all($query, ...$params);
    }

    public function fill($form, $fillGuardedColumns = [])
    {
        $this->hasAgreedToTerms = (($form['terms'] ?? '') == 'agree');
        parent::fill($form, $fillGuardedColumns);
    }

    public function errors()
    {
        $errors = parent::errors();

        if ($this->status == 'draft') {
            // Make all fields optional
            foreach ($this->fillableColumns() as $column) {
                if (!$this->$column && $column != 'advisorEmail') {
                    unset($errors[$column]);
                }
            }
        } elseif (!$this->hasAgreedToTerms) {
            $errors['terms'] = 'You must agree to Terms and Conditions';
        }

        foreach ($this->budgetTable() as $row) {
            if (empty($row->item) && empty($row->itemDesc) && empty($row->itemCost)) {
                continue;
            }

            $error = (v::length(3, 140)->setName('Item'))($row->item) ??
                (v::length(null, 140)->setName('Item Description'))($row->itemDesc) ??
                (v::number()->min(0)->setName('Item Cost'))($row->itemCost);

            if ($error) {
                $errors['budgetTable'] = $error;
            }
        }

        if (count($this->budgetTable()) > 10) {
            $errors['budgetTable'] = 'Too many entries in the budget table';
        }

        if ($this->status != 'draft' && !$this->budgetTable()) {
            $errors['budgetTable'] = 'Budget table must contain at least one item';
        }

        return $errors;
    }

    public function save($withValidations = true)
    {
        if (!in_array($this->status, self::VALID_STATES)) {
            throw new InvalidArgumentException("Invalid application status: \"{$this->status}\"");
        }

        return parent::save($withValidations);
    }

    public function reviews()
    {
        return Review::all('applicationID = ?', $this->id);
    }

    public function budgetTable()
    {
        return json_decode($this->budgetTable) ?? [];
    }

    public function studentID()
    {
        return str_pad($this->studentID, 8, '0', STR_PAD_LEFT);
    }

    public static function validateAdvisorEmail($email)
    {
        if (User::count('email = ? AND isAdvisor = 1', $email)) {
            return null;
        } else {
            return 'There doesn\'t appear to be an advisor associated with that email';
        }
    }
}
