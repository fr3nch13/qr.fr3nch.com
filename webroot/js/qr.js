
import Tags from "../assets/npm-asset/bootstrap5-tags/tags.js";
$( document ).ready(function() {
    // if a tags input is detected, apply the bootstrap5-tags module.
    if ($('select.tags-input').length) {
        Tags.init('select.tags-input');
    }
});
