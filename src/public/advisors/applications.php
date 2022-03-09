<?php

require_once '../../init.php';
require_once '../../helpers/html.php';

$email = User::current()->email;
$c = new ModelController(Application::class);

User::authorize(
    'advisor',
    $c->action() == 'index' ||
    ($c->model()->advisorEmail == $email &&
        $c->model()->status == 'submitted')
);

function UniqueRandomNumbersWithinRange($min, $max, $quantity)
{
    $numbers = range($min, $max);
    shuffle($numbers);
    return array_slice($numbers, 0, $quantity);
}

$applications = Application::all('advisorEmail = ? AND status = \'submitted\'', $email);


// grabbing all applications assigned to an advisor that are only submitted
$c->index('advisors/applications.php', ['applications' => $applications, 'period' => Period::current()]);
$c->read();

// update block
if ($c->action() == 'update' && HTTP::post('buttonName') == "accept") {
    $application = $c->model();
    $reviewers = User::reviewersNotCurrentUser()->fetchAll();

    if (User::current()->isReviewer() && count($reviewers) <= 2) {
        $reviewers = User::reviewers()->fetchAll();
    }
    if (count($reviewers) == 0) {
        // TODO: error message for less then three reviewers
        HTTP::error("There are no reviewers in the system", 200);
    }
    $period = Period::current();
    // we should always have at least one reviwer here
    $reviews = array();
    $numReviews = count($reviewers);
    if ($numReviews > 3) {
        $numReviews = 3;
    }
    $x = UniqueRandomNumbersWithinRange(0, count($reviewers) - 1, $numReviews);
    foreach ($x as $i) {
        $review = new Review(
            [
                'periodID' => $period->id,
                'reviewerID' => $reviewers[$i]->email,
                'applicationID' => $application->id,
            ], true
        );
        $review->save(false);

        $reviewerComment = HTTP::post('reviewerComment');
        if ($reviewerComment !== '') {
            $reviewerComment = "<p>Your advisor left the following comment on it: " . e($reviewerComment) . "</p>";
        }
        Mail::send(
            $review->reviewerID,
            'CSTEM Scholars Grant Application In need of Review',
            HTML::template(
                'emails/application_advisor_accepted.php',
                [
                    'application' => $application,
                    'period' => $period,
                    'review' => $review,
                    'reviewerComment' => $reviewerComment
                ]
            )
        );
    }

    $studentComment = HTTP::post('studentComment');
    if ($studentComment !== '') {
        $studentComment = "<p>Your advisor left the following comment on it: " . e($studentComment) . "</p>";
    }

    Mail::send(
        $application->email,
        'Your CSTEM Scholars Grant Application Status Update',
        HTML::template(
            'emails/application_advisor_student_accepted.php',
            ['application' => $application, 'period' => $period, 'studentComment' => $studentComment]
        )
    );

    $application->status = 'pending_review';
    $application->save(false);

    // TODO: handle errors with a message instead of just redirecting no matter what
    HTTP::redirect('../advisors/applications.php');
} // end update block
elseif ($c->action() == 'update' && HTTP::post('buttonName') == "reject") {
    $application = $c->model();
    $period = Period::current();

    $studentComment = HTTP::post('studentComment');
    if ($studentComment !== '') {
        $studentComment = "<p>Your advisor left the following comment on it: " . e($studentComment) . "</p>";
    }

    Mail::send(
        $application->email,
        'Your CSTEM Scholars Grant Application Status Update',
        HTML::template(
            'emails/application_advisor_student_rejected.php',
            ['application' => $application, 'period' => $period, 'studentComment' => $studentComment]
        )
    );

    $application->status = 'rejected';
    $application->save(false);

    HTTP::redirect('../advisors/applications.php');
}

echo HTML::template(
    'advisors/application.php',
    ['application' => $c->model(), 'form' => $c->form(), 'period' => Period::current()]
);
