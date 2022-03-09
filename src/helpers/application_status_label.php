<?php

function applicationStatus($application)
{
    $text = [
        'draft' => 'Draft',
        'submitted' => 'Submitted',
        'pending_review' => 'Pending Review',
        'reviewed' => 'Reviewed',
        'rejected' => 'Rejected',
        'awarded' => 'Awarded'
    ];

    return HTML::tag('span', $text[$application->status], ['class' => "app-status $application->status"]);
}
