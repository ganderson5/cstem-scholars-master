<!DOCTYPE HTML>
<html lang="en">
<head>
    <title>Application Form</title>
    <link href='http://fonts.googleapis.com/css?family=Bitter' rel='stylesheet' type='text/css'>
    <link href="../CSS/students.css" rel="stylesheet">
    <link rel="icon" href="../favicon.png"/>
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="script.js"></script>
    <script>
        $(document).on("focusout", "#advisorName, #advisorEmail", function () {
            var names = $("#advisorNames option").map(function () {
                return this.value;
            }).get();
            var emails = $("#advisorEmails option").map(function () {
                return this.value;
            }).get();

            var nameIndex = names.indexOf(this.value);
            var emailIndex = emails.indexOf(this.value);

            if (nameIndex != -1) {
                $("#advisorEmail").val(emails[nameIndex]);
            }

            if (emailIndex != -1) {
                $("#advisorName").val(names[emailIndex]);
            }
        });

        $(function () {
            updateTable($('#budget-table tr').eq(3));
        });

        $(document).on("focusout", "#budget-table input", function () {
            updateTable($('#budget-table tr').eq(3));
        });

        function updateTable(row) {
            if (row.length == 0) return true;

            var nextRow = row.next("tr");
            var isRowEmpty = true;

            row.find("input").each(function () {
                isRowEmpty &= !this.value;
            });

            if (updateTable(nextRow) && isRowEmpty) {
                nextRow.hide();
            } else {
                nextRow.show();
            }

            return isRowEmpty;
        }
    </script>
</head>
<body>

<form>
    <div class="logout">
        <div class="button-section">
            <button type="submit" class="button" name="logout" formaction="../logout.php">Log Out</button>
        </div>
    </div>
</form>

<form method="POST" enctype="multipart/form-data">
    <?= $form->csrf() ?>

    <div class="form">
        <h1>Grant Fund Application<span>Undergraduate Research</span><span>*All Fields Required</span></h1>
        <?php
        if (HTTP::isPost() && !$application->isValid()) { ?>
            <div class="error message">
                <h2>We were unable to submit your application.</h2>
                <p>Please review your application for errors and try again.</p>
            </div>
            <?php
        } ?>

        <?php
        if (HTTP::post('save') && $application->isValid()) { ?>
            <div class="success message">
                <h2>Your application has been saved!</h2>
                <p>You can come back any time before the deadline to submit your application. Be sure to have your
                    application submitted and approved by your advisor before
                    <strong><?= date("M j, Y", strtotime($period->deadline)) ?></strong>.
                    <strong>
                        Please note that your application is not submitted and will not be reviewed until you do so.
                    </strong>
                </p>
            </div>
            <?php
        } ?>

        <?php
        if (!HTTP::isPost() && $application->status == 'submitted') { ?>
            <div class="success message">
                <h2>Your application was submitted for review.</h2>
                <p>You may still make changes to it before your advisor accepts your application.</p>
            </div>
            <?php
        } ?>

        <div class="section"><span>1</span>Basic Details</div>
        <div class="inner-wrap">
            <label>Your Full Name: <?= $form->text('name', ['disabled']) ?></label>
            <label>Email Address: <?= $form->email('email', ['required']) ?></label>
            <label>Project Title: <?= $form->text('title', ['required']) ?></label>
        </div>

        <div class="section"><span>2</span>Major &amp; GPA</div>
        <div class="inner-wrap">
            <label>
                Your Major:
                <?= $form->select('major', array_combine(Application::DEPARTMENTS, Application::DEPARTMENTS)) ?>
            </label>

            <label>
                GPA:
                <?= $form->number('gpa', ['min' => 1, 'max' => 4, 'step' => 0.1, 'required']) ?>
            </label>

            <label>
                Expected Graduation Date:
                <?= $form->date('graduationDate', ['required']) ?>
            </label>
        </div>

        <div class="section"><span>3</span>Advisor Information</div>
        <div class="inner-wrap">
            <label>
                Advisor Name:
                <?= $form->text('advisorName', ['list' => 'advisorNames', 'required']) ?>
            </label>

            <label>
                Advisor Email:
                <?= $form->text('advisorEmail', ['list' => 'advisorEmails', 'required']) ?>
            </label>

            <datalist id="advisorNames">
                <?php
                foreach (User::advisors() as $advisor) {
                    echo tag('option', e($advisor->name), ['value' => e($advisor->name)]);
                }
                ?>
            </datalist>

            <datalist id="advisorEmails">
                <?php
                foreach (User::advisors() as $advisor) {
                    echo tag('option', e($advisor->email), ['value' => e($advisor->email)]);
                }
                ?>
            </datalist>
        </div>

        <div class="section"><span>4</span>Objective & Results</div>
        <div class="inner-wrap">
            <label>
                Provide a brief description of the project, including a statement of the problem and/or objective
                of the project, an explanation of the importance of the project, and a statement of work that
                briefly describes your methodology and expected outcomes. (6000 characters max)
                <?= $form->textarea('description', ['maxlength' => 6000, 'rows' => 12, 'required']) ?>
            </label>

            <label>
                Describe your estimated timeline (2000 characters max)
                <?= $form->textarea('timeline', ['maxlength' => 2000, 'rows' => 6, 'required']) ?>
            </label>
        </div>

        <div class="section"><span>7</span>Budget</div>
        <div class="inner-wrap">
            <label>
                Describe your budget and planned spending (2000 characters max)
                <?= $form->textarea('justification', ['maxlength' => 2000, 'rows' => 6, 'required']) ?>
            </label>

            <label>
                Total budget amount:
                <?= $form->money('totalBudget', ['required']) ?>
            </label>

            <label>
                Requested budget amount from EWU:
                <?= $form->money('requestedBudget', ['max' => 2000, 'required']) ?>
            </label>

            <label>
                Please list any other funding sources you have:
                <?= $form->text('fundingSources', ['required']) ?>
            </label>

            <br>
            <p>Please break down your funding into an itemized list (10 items max)</p>
            <?= $form->error('budgetTable') ?>

            <table id="budget-table">
                <tr>
                    <th style="width: 30%">Item</th>
                    <th style="width: 50%">Description</th>
                    <th style="width: 15%">Cost</th>
                </tr>

                <?php

                $table = $application->budgetTable();
                foreach (range(0, 9) as $i) {
                    ?>
                    <tr>
                        <td><?= input('text', "budgetTable[$i][item]", $table[$i]->item ?? '') ?></td>
                        <td><?= input('text', "budgetTable[$i][itemDesc]", $table[$i]->itemDesc ?? '') ?></td>
                        <td><?= input(
                                'number',
                                "budgetTable[$i][itemCost]",
                                $table[$i]->itemCost ?? '',
                                ['min' => 0, 'step' => 0.01]
                            ) ?></td>
                    </tr>
                    <?php
                } ?>
            </table>
        </div>

        <div class="section"><span>8</span>Terms and Conditions</div>
        <div class="inner-wrap">
            <p>
                Awards shall only be spent on allowable expenses as defined in the application. Receipts must be
                provided for all expenses including travel. Funds must be spent within one calendar year of
                dispersal. A brief two-page progress report must be submitted to the faculty advisor and associate
                dean by the end of the project year. Any academic integrity or student code of conduct violations
                will result in forfeiture of the award.
            </p>
            <label>
                <input type="checkbox" name="terms" id="terms" value="agree" required>
                I agree to the Terms & Conditions
            </label>
            <?= $form->error('terms') ?>
        </div>

        <div class="button-section">
            <button type="submit" class="button" name="submit" value="submit">Submit</button>

            <?php
            if ($application->status == 'draft') { ?>
                <button type="submit" class="button" name="save" value="save" formnovalidate>Save and Continue
                    Later
                </button>
                <?php
            } ?>
        </div>
    </div>
</form>

</body>
</html>
