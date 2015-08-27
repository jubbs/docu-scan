var loaded = false;

$(window).load(function () {
    loaded = true;
    validateForm();
    $('[data-toggle="tooltip"]').tooltip();
    checkHide();
});

$("#typeSelect").on("change", function(){
	if ($(this).val() < 0) {
		$('#sourceSelect').hide();
	} else {
		$('#sourceSelect').show();
	}
	
	if ($(this).val() == -2) {
		$('#quarterSelect').show();
	} else {
		$('#quarterSelect').hide();
	}
});

$("#uploadButton").on('click', function() {
	$('.alert').slideUp();
});

function validateForm() {
	var valid = loaded && $("#customerId").val().length > 0;
	
	if (valid) {
		$("#uploadButton").removeAttr('disabled');
	} else {
		$("#uploadButton").attr('disabled', 'disabled');
	}
}

$('#customerLookup').on('input', function() {
	validateForm();
});

$( "#customerLookup" ).autocomplete({
    minLength: 0,
    source: customers,
    focus: function( event, ui ) {
        $( "#customerLookup" ).val( ui.item.label );
        return false;
    },
    select: function( event, ui ) {
        $( "#customerLookup" ).val( ui.item.label );
        $( "#customerId" ).val( ui.item.id );
		validateForm();
        return false;
    }
})
.data( "ui-autocomplete" )._renderItem = function( ul, item ) {
    return $( "<li>" ).append( "<a><strong>" + item.id + "</strong> - " + item.label + "</a>" )
    .appendTo( ul );
};

function checkHide() {
	if (typeof hideSpinner != 'undefined' && hideSpinner) {
		$('.alert-hideme').slideUp();
	}
}