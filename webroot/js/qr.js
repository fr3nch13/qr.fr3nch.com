$( document ).ready(function() {
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

    $('button.copy-text')
        .on( "click", async function() {
            value = $(this).parent().find('input[name=copy_text]').first().val();
            await navigator.clipboard.writeText(value);
            text = $(this).text();
            $(this).text('Copied');
            setTimeout(() => {
                $(this).text(text);
            }, 1000);
        });
});
