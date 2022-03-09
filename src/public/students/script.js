/**
 *  Variables:
 *      generalRow            - String w/ HTML code for table row. Each row and cols will have unique id's
 *      tblBody               - JQuery Object generated HTML Table that will have rows/cols appended to it on click events
 *      currentRow            - Integer that the current row number for assigning unique id's to rows/cols
 *
 *  Constants:
 *      DISPLAY_TEXT          - String holding instructions for the student filling the table
 *      MAX_ROWS              - Integer used to display max rows for prompt.
 *      MAX_ADDITIONAL_ROWS   - constant Integer for the maximum number of rows a student can add to the form. Dependant
 *                              on the value entered for MAX_ROWS
 *      D_TOOLTIP             - String that tells student they can't remove anymore rows from the table
 *      I_TOOLTIP             - String that tells student they can't add anymore rows to the table
 *      HEADER_ROW            - String with HTML code for table header
 *      COLGROUP              - String with HMTL code for setting column group of table
 *
 *  Click Events:
 *      increment  - Adds a row to the table by calling addRow function
 *      decrement  - Remove a row from the table by calling removeRow function. Will remove rows up to the default
 *                   first row that is provided on page load
 *
 *  Functions:
 *      addRow     - Adds a generalRow to the table. Each row will have it's own unique id [used for decrementing] as
 *                   well as having unique id's for each column for accessing their values in PHP
 *                   [item0, itemDesc0, itemCost0, item1, itemDesc1, itemCost1, ... , itemN, itemDescN, itemCostN]
 *      removeRow  - Removes a generalRow from the table. If there is only the default row, then it will do nothing.
 */
$(document).ready(function () {


    const MAX_ROWS = 5;
    const MAX_ADDITIONAL_ROWS = MAX_ROWS - 1;
    const D_TOOLTIP = 'Must include at least ONE item';
    const I_TOOLTIP = 'Maximum number of items reached [ ' + MAX_ROWS + ' items ]';
    const DISPLAY_TEXT = 'Please break down your funding into an itemized list [ Maximum Items: ' + MAX_ROWS + ' ]';
    const HEADER_ROW = '<tr><th>Item</th>' +
        '<th>Description</th>' +
        '<th>Cost</th></tr>';

    const COLGROUP = '<colgroup>' +
        '<col width="25%">' +
        '<col width="50%">' +
        '<col width="25%">' +
        '</colgroup>';

    //tracks currentRow for assigning unique id's to rows and columns
    let currentRow = 0;

    // add 'instructions' text above the table
    $('#tblText').append(DISPLAY_TEXT);

    // generate table
    let tblBody = $(document.createElement('table')).attr("align", "left")
        .append(COLGROUP)
        .appendTo('#table');

    // set the styling for the table
    tblBody.css({
        "width": "100%",
        "border-collapse": "collapse",
        "margin": "auto",
        "background-color": "f7f7f7"
    });

    // append the headerRow and a single default generalRow to the table
    $('#table').append(tblBody);
    tblBody.append(HEADER_ROW);
    addRow(tblBody, currentRow);

    // click event to generate new rows
    $('#increment').click(function (event) {
        event.preventDefault(); // make button independent from the form
        if (currentRow < MAX_ADDITIONAL_ROWS) {
            currentRow++;
            addRow(tblBody, currentRow);
        } else {
            notification(I_TOOLTIP, 1500);
        }
    });

    // click event to decrement rows up to default
    $('#decrement').click(function (event) {
        event.preventDefault();
        if (currentRow > 0) {
            removeRow(currentRow);
            currentRow--;
        } else {
            notification(D_TOOLTIP, 1500);
        }

    });
});

function addRow(tblBody, rowNum) {
    let generalRow = '<tr id="rowNum' + rowNum + '">' +
        '<td><input name="budgetTable[' + rowNum + '][item]" type="text" width="25%"/></td>' +
        '<td><input name="budgetTable[' + rowNum + '][itemDesc]" type="text" width="50%"/></td>' +
        '<td><input name="budgetTable[' + rowNum + '][itemCost]" type="text" width="25%"/></td>' +
        '</tr>';

    tblBody.append(generalRow);
}

function removeRow(rowNum) {
    $('#rowNum' + rowNum).remove();
}

function notification(tooltip_text, time) {
    $("<p>" + tooltip_text + "</p>").appendTo('#notification-box').fadeTo(time, 1, function () {
        $(this).fadeTo(1000, 0, function () {
            $(this).remove()
        });
    });
}