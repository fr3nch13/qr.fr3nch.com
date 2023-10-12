$( document ).ready(function() {

    // if a tags input is detected, apply the bootstrap5-tags module.
    if ($('select.tags-input').length) {
        import Tags from "../assets/npm-asset/bootstrap5-tags/tags.js";
        $('select.tags-input').each(function( index, element ){
            Tags.init(element);
        });
    }
});
