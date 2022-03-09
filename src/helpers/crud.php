<?php

function editButton($url, $key)
{
    return HTML::link(
        modelURL($url, $key),
        '<i class="icon edit-white">Edit</i>',
        ['class' => 'edit button', 'title' => 'Edit']
    );
}

function deleteButton($url, $key)
{
    $action = modelURL($url, $key);
    $csrf = Form::csrfToken();

    return "
        <form method=\"POST\" action=\"$action\">
            <input type=\"hidden\" name=\"csrfToken\" value=\"$csrf\">
            <button type=\"submit\" name=\"_method\" value=\"DELETE\" class=\"delete\"
                    onclick=\"return confirm('Are you sure?')\" title=\"Delete\">
                <i class=\"icon delete-white\">Delete</i>
            </button>
        </form>
    ";
}

function actionButtons($url, $key)
{
    return editButton($url, $key) . deleteButton($url, $key);
}

function modelURL($url, $key)
{
    $params = [];

    foreach ($key as $k => $v) {
        $params[] = urlencode($k) . '=' . urlencode($v);
    }

    return $url . '?' . implode('&', $params);
}
