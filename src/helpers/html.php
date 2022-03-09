<?php

function e($text)
{
    return HTML::escape($text);
}

function tag($tag, $text = null, $attributes = [])
{
    return HTML::tag($tag, $text, $attributes);
}

function input($type, $name, $value, $attributes = [])
{
    $attributes['type'] = $type;
    $attributes['name'] = $name;
    $attributes['id'] = $name;
    $attributes['value'] = e($value);

    return tag('input', null, $attributes);
}

function textarea($name, $value, $attributes = [])
{
    $attributes['name'] = $name;
    $attributes['id'] = $name;

    return tag('textarea', e($value), $attributes);
}

function linkTo($url, $text, $attributes = [])
{
    return HTML::link($url, $text, $attributes);
}

function mailTo($email, $text = null, $attributes = [])
{
    $text = $text ?? $email;
    return HTML::link("mailto:$email", $text, $attributes);
}

function template($template, $v = null)
{
    return HTML::template($template, $v);
}

function url($path)
{
    return HTTP::url($path);
}
