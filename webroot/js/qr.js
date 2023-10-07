$( document ).ready(function() {

    // Watch the Source select filter dropdown

    $('#filterSource').change(function(){
        alert($('#filterSource option:selected').text());
    })
});
