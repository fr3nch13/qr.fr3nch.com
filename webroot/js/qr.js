$( document ).ready(function() {

    // Watch the Source select filter dropdown
    $('#filterSource').change(function(){
        // TODO: instead of redirecting, do an ajax call
        // labels: forms, jquery, ajax
        window.location.href = '/?s=' + $('#filterSource option:selected').text();
    })
    // Watch the Tag select filter dropdown
    $('#filterTag').change(function(){
        window.location.href = '/?t=' + $('#filterTag option:selected').text();
    })
});
