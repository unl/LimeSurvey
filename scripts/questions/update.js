 $(document).ready(function() {
    initQuestionUpdate();
});

function initQuestionUpdate()
{
    $('#questiontype').bind('change', function() {
       var data = $('form#updateform').serializeArray();
       var questiontype = $(this).val();
       $('#content').load(location.href, $.param({
           'questiontype' : questiontype
       }), function(responseText, textStatus, XMLHttpRequest) {
           refreshForm(data);
       }
       );
    });
}

// This function will fill the form based on json data.
function refreshForm(data)
{
    initQuestionUpdate();
    $(data).each(function(index, value) {
        var t = {};
        t[value.name] = value.value
        $('form').populate(t, {'identifier': 'name', 'resetForm' : false});
    })
    initializeHtmlEditors();
    
}

