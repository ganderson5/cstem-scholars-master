<?php

class Form
{
    private $renderInlineErrors = true;
    private $model;

    /**
     * Form constructor. The model will be filled with values from a POST request at this point.
     *
     * @param Model &$model A reference to the model used to pre-fill inputs. The original model object will be
     *                         filled with POST form data using magic of references.
     * @param array $unescaped List of columns that WILL NOT be HTML-escaped before saving to database.
     */
    public function __construct(&$model, $unescaped = [])
    {
        $this->model =& $model;

        if (HTTP::isPost()) {
            self::assertCsrfToken();
            $form = HTTP::post();

            foreach ($form as $k => $v) {
                if (!in_array($k, $unescaped) && is_string($v)) {
                    $form[$k] = HTML::escape($v);
                }
            }

            $this->model->fill($form);
        }
    }

    /**
     * Disable automatically rendering inline errors along with form elements. Inline errors can also be
     * rendered manually using {@see Form::error()}.
     *
     * @return $this
     * @see Form::errors() Render a list of all errors.
     *
     * @see Form:error() Render inline errors manually.
     */
    public function disableInlineErrors()
    {
        $this->renderInlineErrors = false;
        return $this;
    }

    public static function csrfToken()
    {
        return md5(session_id());
    }

    public static function assertCsrfToken()
    {
        if (HTTP::post('csrfToken') != self::csrfToken()) {
            throw new UnexpectedValueException('Invalid CSRF token');
        }
    }

    public function csrf()
    {
        return HTML::tag('input', null, ['type' => 'hidden', 'name' => 'csrfToken', 'value' => self::csrfToken()]);
    }

    public function label($for, $text, $attributes = [])
    {
        $attributes['for'] = $for;
        $attributes = $this->appendErrorClass($for, $attributes);

        return HTML::tag('label', $text, $attributes);
    }

    public function input($type, $name, $attributes = [])
    {
        $attributes['type'] = $type;
        $attributes['name'] = $name;
        $attributes['id'] = $name;
        $attributes['value'] = $attributes['value'] ?? $this->value($name);
        $attributes = $this->appendErrorClass($name, $attributes);

        return HTML::tag('input', null, $attributes) . $this->errorIfEnabled($name);
    }

    public function text($name, $attributes = [])
    {
        return $this->input('text', $name, $attributes);
    }

    public function email($name, $attributes = [])
    {
        return $this->input('email', $name, $attributes);
    }

    public function date($name, $attributes = [])
    {
        $attributes['pattern'] = '\d{4}-\d{2}-\d{2}';
        $attributes['placeholder'] = 'YYYY-MM-DD';
        $attributes['title'] = 'YYYY-MM-DD';

        return $this->input('date', $name, $attributes);
    }

    public function number($name, $attributes = [])
    {
        return $this->input('number', $name, $attributes);
    }

    public function money($name, $attributes = [])
    {
        $attributes['min'] = 0;
        $attributes['step'] = 0.01;

        return $this->number($name, $attributes);
    }

    public function textarea($name, $attributes = [])
    {
        $attributes['name'] = $name;
        $attributes['id'] = $name;
        $attributes = $this->appendErrorClass($name, $attributes);

        return HTML::tag('textarea', $this->value($name), $attributes) . $this->errorIfEnabled($name);
    }

    public function checkbox($name, $value = '1', $attributes = [])
    {
        $attributes['value'] = $value;

        if ($value == $this->value($name)) {
            $attributes[] = 'checked';
        }

        return $this->input('checkbox', $name, $attributes);
    }

    public function radio($name, $value, $attributes = [])
    {
        $attributes['value'] = $value;

        if ($value == $this->value($name)) {
            $attributes[] = 'checked';
        }

        return $this->input('radio', $name, $attributes);
    }

    public function select($name, $options, $attributes = [])
    {
        $attributes['name'] = $name;
        $attributes['id'] = $name;
        $attributes = $this->appendErrorClass($name, $attributes);
        $opts = '';

        foreach ($options as $k => $v) {
            $attrs = ['value' => $k];

            if ($this->value($name) == $k) {
                $attrs[] = 'selected';
            }

            $opts .= HTML::tag('option', $v, $attrs);
        }

        return HTML::tag('select', $opts, $attributes);
    }

    public function error($name, $tag = 'div')
    {
        $error = $this->model->errors()[$name] ?? null;
        return (HTTP::isPost() && $error) ?
            HTML::tag($tag, '<i class="warning-sign"></i> ' . $error, ['class' => 'inline error']) : '';
    }

    public function errors()
    {
        if (!HTTP::isPost() || $this->model->isValid()) {
            return null;
        }

        $res = '<ul class="error">';

        foreach ($this->model->errors() as $column => $error) {
            $res .= HTML::tag('li', $error, ['class' => $column]);
        }

        $res .= '</ul>';

        return $res;
    }

    private function value($name)
    {
        $value = HTTP::post($name, $this->model->$name);
        return HTML::escape($value);
    }

    private function errorIfEnabled($name)
    {
        return $this->renderInlineErrors ? $this->error($name) : '';
    }

    private function appendErrorClass($name, $attributes)
    {
        if ($this->error($name)) {
            $attributes['class'] = trim(($attributes['class'] ?? '') . ' error');
        }

        return $attributes;
    }
}
