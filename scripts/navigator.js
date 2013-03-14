$(document).ready(function (){
    
    $('div.navigator button').click(function () {
        $('#navigate').val($(this).val());
        $('#limesurvey').submit();
    })
});    