$( document ).ready(function() {

    // Watch the Source select filter dropdown

    $('#filterSource').change(function(){
        window.location.href = '/?s=' + $('#filterSource option:selected').text();
    })
});
