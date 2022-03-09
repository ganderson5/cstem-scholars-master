<?php

use Respect\Validation\ValidatorFunction as v;

class Review extends Model
{
    public $id, $comments, $q1, $q2, $q3, $q4, $q5, $q6, $fundingRecommended,
        $reviewerID, $applicationID, $periodID, $submitted;

    public const QUESTIONS = [
        'Does the project demonstrate experiential learning in a CSTEM discipline?',
        'Is the budget justified in the project description, including realistic?',
        'Are the proposed methods appropriate to achieve the goals?',
        'Is the timeline proposed reasonable? (Too little? Too much?)',
        'Is the project well explained (including rationale) and justified?',
        'Does the budget only include eligible activities (supplies, equipment, field travel, conference travel)?'
    ];

    public function __construct($form = [], $fillGuardedColumns = false)
    {
        $this->fillable = [
            'comments' => v::length(null, 2000)->setName('Comments'),
            'q1' => v::intVal()->between(0, 3)->setName('Question 1'),
            'q2' => v::intVal()->between(0, 3)->setName('Question 2'),
            'q3' => v::intVal()->between(0, 3)->setName('Question 3'),
            'q4' => v::intVal()->between(0, 3)->setName('Question 4'),
            'q5' => v::intVal()->between(0, 3)->setName('Question 5'),
            'q6' => v::intVal()->between(0, 3)->setName('Question 6'),
            'fundingRecommended' => v::boolVal()
        ];

        $this->guarded = [
            'reviewerID',
            'applicationID',
            'periodID',
            'submitted'
        ];

        parent::__construct($form, $fillGuardedColumns);
    }

    public function application()
    {
        return Application::get($this->applicationID);
    }

    public function reviewer()
    {
        return User::get($this->reviewerID);
    }
}
