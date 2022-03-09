<?php

$title = 'Review Application';
$layout = 'admin/_layout.php';
$application = $review->application();
?>

<h1><?= e($application->title) ?></h1>
<?= template('application_details.php', $application) ?>

<br>
<h2> REVIEW FORM </h2>

<?= $form->errors() ?>

<form method="POST" enctype="multipart/form-data">
    <?= $form->csrf() ?>
    <div class="form">
        <p>Does the project demonstrate experiential learning in a CSTEM discipline?</p>
        <p>
            <label><?= $form->radio('q1', 0) ?> 0</label>
            <label><?= $form->radio('q1', 1) ?> 1</label>
            <label><?= $form->radio('q1', 2) ?> 2</label>
            <label><?= $form->radio('q1', 3) ?> 3</label>
            <br>
        </p>

        <p>Is the budget justified in the project description, including realistic?</p>
        <p>
            <label><?= $form->radio('q2', 0) ?> 0</label>
            <label><?= $form->radio('q2', 1) ?> 1</label>
            <label><?= $form->radio('q2', 2) ?> 2</label>
            <label><?= $form->radio('q2', 3) ?> 3</label>
            <br>
        </p>

        <p>Are the proposed methods appropriate to achieve the goals?</p>
        <p>
            <label><?= $form->radio('q3', 0) ?> 0</label>
            <label><?= $form->radio('q3', 1) ?> 1</label>
            <label><?= $form->radio('q3', 2) ?> 2</label>
            <label><?= $form->radio('q3', 3) ?> 3</label>
            <br>
        </p>

        <p>Is the timeline proposed reasonable?(Too little? Too much?)</p>
        <p>
            <label><?= $form->radio('q4', 0) ?> 0</label>
            <label><?= $form->radio('q4', 1) ?> 1</label>
            <label><?= $form->radio('q4', 2) ?> 2</label>
            <label><?= $form->radio('q4', 3) ?> 3</label>
            <br>
        </p>

        <p>Is the project well explained (including rationale) and justified?</p>
        <p>
            <label><?= $form->radio('q5', 0) ?> 0</label>
            <label><?= $form->radio('q5', 1) ?> 1</label>
            <label><?= $form->radio('q5', 2) ?> 2</label>
            <label><?= $form->radio('q5', 3) ?> 3</label>
            <br>
        </p>

        <p>Does the budget only include eligible activities (supplies, equipment, field travel,
            conference travel)?</p>
        <p>
            <label><?= $form->radio('q6', 0) ?> 0</label>
            <label><?= $form->radio('q6', 1) ?> 1</label>
            <label><?= $form->radio('q6', 2) ?> 2</label>
            <label><?= $form->radio('q6', 3) ?> 3</label>
            <br>
        </p>

        <p>Based on eligibility and quality scores, would you recommend funding this project?</p>
        <p>
            <label><?= $form->radio('fundingRecommended', 1) ?> Yes</label>
            <label><?= $form->radio('fundingRecommended', 0) ?> No</label>
            <br>
        </p>

        <div class="form-group">
            <label for="comments">Quality Assessment Comments:</label><br>
            <?= $form->textarea('comments', ['maxlength' => 2000, 'rows' => 8, 'cols' => 80]) ?>
        </div>

        <div class="button-section">
            <button type="submit" class="button" name="submit" value="submit">Submit</button>
        </div>
    </div>
</form>
