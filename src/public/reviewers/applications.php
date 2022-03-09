<?php

require_once '../../init.php';

$c = new ModelController(Review::class);
$review = $c->model();
$application = $review->application();

User::authorize(
    'reviewer',
    $c->action() == 'index' ||
    ($review->reviewerID == User::current()->email && !$review->submitted)
);

#This will take us to applications_layout, transferring over reviews as $reviews and apps as $apps
$c->index(
    'reviewer/applications_layout.php',
    ['reviews' => Review::all('reviewerID = ? AND submitted = 0', User::current()->email)]
);

# if we are updating the block [submit]
if ($c->action() == 'update') {
    #get the filled out review form and change its submit status from 0 to 1
    $review->submitted = 1;

    #if the review is successfully saved in the review table in DB
    if ($review->save()) {
        #update the applications status, save, then redirect back to the list of applications
        $application->status = 'reviewed';
        $application->save(false);
        HTTP::redirect('../reviewers/applications.php');
    }
}

#if we aren't updating, then we are filling out a form so go to form_layout
#transferring over a black review form, and a list of all applications so we can find the current
#application by it's applicationID
echo HTML::template(
    'reviewer/form_layout.php',
    [
        'review' => $c->model(),
        'form' => $c->form()->disableInlineErrors()
    ]
);


