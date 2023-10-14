import Tags from '../assets/npm-asset/bootstrap5-tags/tags.js';
$( document ).ready(function() {

    // The tags form field
    Tags.init('select.tags-input');

    // watch for ajax modals
    $('.ajax-modal')
        .on( "click", function() {
            self = $(this);
            $.get($(this).attr('href'), function(data) {
                console.log(self.data('ajax-target'));
                $(self.data('ajax-target')).html(data);
            });
        });

    // Watch for multiple file inputs
    $("input[type=file].fileinput").fileinput({
        'theme': 'bi',
        'uploadUrl': '#',
        showRemove: false,
        showUpload: false,
        showZoom: false,
        showCaption: false,
        browseClass: "btn btn-danger",
        browseLabel: "",
        browseIcon: "<i class='bi bi-plus'></i>",
        overwriteInitial: false,
        initialPreviewAsData: true,
        fileActionSettings :{
        	showUpload: false,
        	showZoom: false,
          removeIcon: "<i class='bi bi-x-circle'></i>",
        }
    });
});
