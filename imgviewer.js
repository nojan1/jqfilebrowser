$(document).on("pagebeforeshow", "#image-page", function(){ 
    $('#theImage').attr("src", "api/file.php?p=" + currentPath);
            
    var maxHeight = $( window ).height() - 60 + "px";
    $( "#theImage" ).css( "max-height", maxHeight );
});
