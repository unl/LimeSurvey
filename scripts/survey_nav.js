$(document).ready(function () {
    $('div.navigator button[type=button]').on('click', navButtonClick);
    $('#index li li').on('click', navIndexClick);
});

/**
 * Function called when a navigation button is clicked.
 * This function is responsible for submitting the form and setting the correct submit value.
 */
function navButtonClick(e)
{
    var val = $(this).val();
    $('form#limesurvey input[name="navigate"]').val(val);
    $('form#limesurvey').submit();
    
}

/**
 * Function called when an index row is clicked.
 * This function is responsible for submitting the form and setting the correct submit value.
 */
function navIndexClick(e)
{
    var val = $(this).attr('data-step');
    $('form#limesurvey input[name="navigate"]').val(val);
    $('form#limesurvey').submit();
    
}
