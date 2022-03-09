<?php

use Respect\Validation\ValidatorFunction as v;

class User extends Model
{
    public $id, $email, $name, $isAdvisor = 0, $isReviewer = 0, $isAdmin = 0;
    protected static $primaryKey = 'email';

    public function __construct($form = [], $fillGuardedColumns = false)
    {
        $this->fillable = [
            'email' => v::email()->length(null, 50)->setName('Email address'),
            'name' => v::length(2, 50)->setName('Name'),
            'isAdvisor',
            'isReviewer',
            'isAdmin'
        ];

        parent::__construct($form, $fillGuardedColumns);
    }

    public static function current()
    {
        if (in_array(null, HTTP::session(['id', 'name', 'email']))) {
            return null;
        }

        $user = self::get(HTTP::session('email')) ?? new User();

        $user->id = HTTP::session('id');
        $user->name = HTTP::session('name');
        $user->email = HTTP::session('email');

        return $user;
    }

    public static function advisors()
    {
        return self::select('isAdvisor = 1');
    }

    public static function reviewers()
    {
        return self::select('isReviewer = 1');
    }

    public static function reviewersNotCurrentUser()
    {
        return self::select('isReviewer = 1 AND email != ?', User::current()->email);
    }

    public static function authorize($role, $allow = true)
    {
        if (!is_array($role)) {
            $role = [$role];
        }

        if (!self::current() || !array_intersect($role, self::current()->roles()) || !$allow) {
            HTTP::error(
                'You are not authorized to access this page.',
                401,
                'Unauthorized Access'
            );
        }
    }

    public static function login($id, $name, $email)
    {
        if (!$id || !$name || !$email) {
            throw new InvalidArgumentException('All arguments for User::login must be non-empty');
        }

        $_SESSION['id'] = $id;
        $_SESSION['name'] = $name;
        $_SESSION['email'] = $email;

        $user = self::current();

        if (!self::exists('email = ?', $email)) {
            if (ADMIN_EMAIL == $email && !User::exists("isAdmin = 1")) {
                $user->isAdmin = 1;
            }
            $user->save();
        }

        return $user;
    }

    public static function logout()
    {
        unset($_SESSION);
        session_destroy();
    }

    public function save($withValidations = true)
    {
        $this->isAdmin = ($this->isAdmin) ? 1 : 0;
        $this->isAdvisor = ($this->isAdvisor) ? 1 : 0;
        $this->isReviewer = ($this->isReviewer) ? 1 : 0;

        return parent::save($withValidations);
    }

    public function isAdmin()
    {
        return (bool)$this->isAdmin;
    }

    public function isAdvisor()
    {
        return (bool)$this->isAdvisor;
    }

    public function isReviewer()
    {
        return (bool)$this->isReviewer;
    }

    public function isStudent()
    {
        return !($this->isAdvisor() || $this->isReviewer() || $this->isAdmin());
    }

    public function roles()
    {
        $roles = [];

        if ($this->isStudent()) {
            $roles[] = 'student';
        }

        if ($this->isAdvisor()) {
            $roles[] = 'advisor';
        }

        if ($this->isReviewer()) {
            $roles[] = 'reviewer';
        }

        if ($this->isAdmin()) {
            $roles[] = 'admin';
        }

        return $roles;
    }

    public function hasRole($role)
    {
        return in_array($role, $this->roles());
    }
}
