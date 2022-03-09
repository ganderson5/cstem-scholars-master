<?php

function messageFlash()
{
    $flash = HTTP::flash();

    if (isset($flash['success'])) {
        return HTML::tag('div', $flash['success'], ['class' => 'message success']);
    }

    if (isset($flash['error'])) {
        return HTML::tag('div', $flash['error'], ['class' => 'message error']);
    }
}
