var valid = true;

    function validChamps() {
        jQuery("input.required").each(function(){
            valid = true;
            if(jQuery(this).val().length == 0){
                jQuery(this).siblings("span.error").css("display", "block");
                valid = false;
                return valid;
            }
        })
    }

    function removeSpan() {
        jQuery("input.required").siblings(".error").css("display", "none");
    }

jQuery(document).ready(function () {
   
    jQuery("#location-form input.envoi").click(function (e) { 
        e.preventDefault();
        validChamps();
        if(valid) {
            jQuery("#location-form").submit();
        }
    });

    jQuery("input.required").click(removeSpan());

    jQuery("#deleteLoc").click(function(e) {
        e.preventDefault();
        if(confirm("Souhaitez-vous vraiment supprimer cet emplacement?")) {
            jQuery("#deleteform").submit();
        }
    })

    jQuery("#shortcode").click(function(e) {
        e.preventDefault();
        var shortcode = jQuery(this).text();
        var textarea = jQuery('<textarea/>').val(shortcode).appendTo('body').select();
        document.execCommand('copy');
        textarea.remove();
        jQuery(this).text('Copié !');
    })

});

  