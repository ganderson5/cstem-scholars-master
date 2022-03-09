<?php

/**
 *
 * Action      Method      URL         Form Body
 * create      POST        /           yes
 * read        GET         /?id={}     no
 * update      POST        /?id={}     yes
 * delete      DELETE      /?id={}     yes (csrfToken)
 */
class ModelController
{
    private $modelClass;
    private $model;
    private $done = false;
    private $error = false;

    public function __construct($modelClass)
    {
        $this->modelClass = $modelClass;
        $this->model = $this->hasKey() ? $modelClass::get($this->key()) : new $modelClass(HTTP::post());

        if (!$this->model) {
            HTTP::error('Resource does not exist', 404);
        }

        $this->model->fill(HTTP::post());
    }

    public function action()
    {
        if (HTTP::isGet() && !$this->hasKey()) {
            return 'index';
        }

        if (HTTP::isPost() && !$this->hasKey()) {
            return 'create';
        }

        if (HTTP::isGet() && $this->hasKey()) {
            return 'read';
        }

        if (HTTP::isPost() && $this->hasKey()) {
            return 'update';
        }

        if (HTTP::method() == 'DELETE') {
            return 'delete';
        }

        return null;
    }

    public function index($template = null, $v = null)
    {
        if ($this->action() == 'index') {
            if (!$template) {
                return true;
            }

            echo HTML::template($template, $v);
            exit();
        }

        return false;
    }

    public function create()
    {
        if ($this->action() == 'create') {
            Form::assertCsrfToken();
            $this->done = $this->model->save();
            $this->error = !$this->done();
            return $this->done;
        }

        return false;
    }

    public function read()
    {
        if ($this->action() == 'read') {
            $this->model = $this->modelClass::get($this->key());
            $this->error = ($this->model == null);
            return true;
        }

        return false;
    }

    public function update()
    {
        if ($this->action() == 'update') {
            Form::assertCsrfToken();
            $this->done = $this->model->fill($this->key(), true)->save();
            $this->error = !$this->done();
            return $this->done;
        }

        return false;
    }

    // TODO: Allow multiple deletes
    public function delete()
    {
        if ($this->action() == 'delete') {
            Form::assertCsrfToken();
            $res = $this->model->deleteByKey($this->key());
            $this->done = true;
            $this->error = ($res == false);
            return true;
        }

        return false;
    }

    public function done()
    {
        return $this->done;
    }

    public function error()
    {
        return $this->error;
    }

    public function &model()
    {
        return $this->model;
    }

    public function form()
    {
        return new Form($this->model);
    }

    private function key()
    {
        return HTTP::get($this->modelClass::primaryKey());
    }

    private function hasKey()
    {
        return !in_array(null, $this->key());
    }
}
