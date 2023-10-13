
import "../assets/npm-asset/bootstrap5-tags/tags.js";
$( document ).ready(function() {
    Tags.init('select.tags-input');
    console.log($('select.tags-input').length);
});
