jQuery.validator.addMethod("fileSize_max", function(value, element, param) {
    var isOptional = this.optional(element),
        file;

    if(isOptional) {
        return isOptional;
    }

    let size = param * 1024;

    if ($(element).attr("type") === "file") {

        if (element.files && element.files.length) {

            file = element.files[0];
            return ( file.size && file.size <= size );
        }
    }
    return false;
},jQuery.validator.format( "This file should not be larger than {0}KB."));

/**
 * trigger blur to hide validation class in select2
 *  or we can also re-validate
 */
$(".select-simple").select2().change(function() {
    $(this).trigger('blur');
    // $(this).valid();
});