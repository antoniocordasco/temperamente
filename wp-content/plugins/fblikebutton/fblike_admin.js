/*
* fblike_admin.js
* By Dean Peters (http://healYourChurchWebsite.com)
*/
var $fbla = jQuery.noConflict();
(function($jf) {
    $fbla.fn.fbLikeButton = function(options) {
        // Set the options.
        options = $jf.extend({}, $.fn.fbLikeButton.defaults, options);

        // Go through the matched elements and return the jQuery object.
        return this.each(function() {
        });
    };
    // Public defaults.
    $fbla.fn.fbLikeButton.defaults = {
        property: 'value'
    };
    // Private functions.
    function func() {
        return;
    };


    $fbla.fn.fbLikeButton.Preview = function() {

	var FBLmyurl	   = 'http%3A%2F%2Fhealyourchurchwebsite.com%2F2010%2F04%2F22%2Fthe-facebook-like-button-plugin-for-wordpress%2F';

        var FBLclass       = $fbla('#fblike_settings_cssclass').val();
        var FBLlayout      = $fbla('#fblike_param_layout :selected').val();
        var FBLfaces       = $fbla('#fblike_param_show_faces').is(':checked') ? true : false;
        var FBLwidth       = $fbla('#fblike_param_width').val();
        var FBLheight      = $fbla('#fblike_param_height').val();
        var FBLverb        = $fbla('#fblike_param_action :selected').val();
        var FBLfont        = $fbla('#fblike_param_font :selected').val();
        var FBLcolor       = $fbla('#fblike_param_colorscheme :selected').val();


        var FBLbutton =  '<div class="fblike_button" style="height: 115px;overflow-y:auto;">';
        FBLbutton += '<iframe src="http://www.facebook.com/plugins/like.php?href='+ FBLmyurl;
        FBLbutton += '&amp;layout='+ FBLlayout +'&amp;show_faces='+ FBLfaces;
        FBLbutton += '&amp;width='+ FBLwidth +'&amp;action='+ FBLverb
        FBLbutton += '&amp;font='+ FBLfont +'&amp;colorscheme='+ FBLcolor +'" ';
        FBLbutton += ' scrolling="no" frameborder="0" allowTransparency="true" ';
        FBLbutton += ' class="' + FBLclass + '" ';
        FBLbutton += ' style="border:none; overflow:hidden; ';
        FBLbutton += ' width:' + FBLwidth +'px; height:'+ FBLheight +'px"></iframe></div>';

        $fbla('#fblike_preview').html(FBLbutton);

        return false;
    };

})(jQuery);

/* ---------------- document read ------------------ */

$fbla(document).ready(function(){
        $fbla.fn.fbLikeButton.Preview();

	$fbla('#fbLikeParameters select').change(function() {
        	$fbla.fn.fbLikeButton.Preview();
	        return false;
	});



	$fbla('#fbLikeParameters input').blur(function() {
        	$fbla.fn.fbLikeButton.Preview();
	        return false;
	});


});

