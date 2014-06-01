function listFiles(path){
  $.getJSON("api/listing.php?p=" + path, function(data){
        $("#path").text("Folder: " + $("<div/>").html(path).text());
        $("title").text("Folder: " + path);

        var output = '';
        $.each(data, function(index, value){
	    output += '<li><a href="';

	    if(value.isdir){
		output += '#listing-page';
	    }else{
			if(value.ext == "jpg" || value.ext == "png" || value.ext == "gif" || value.ext == "jpeg"){
				output += '#image-page';
			}else{
				output += '#download-page';
			}
            }
	    
	    output += '" data-path="' + value.path + '" rel="external">';
            output += '<img src="' + value.thumbnail + '" class="ui-li-thumb">';
	    output += '<p>' + value.filename + '</p>';
            output += '<p class="ui-li-aside">' + value.ext + '</p></a></li>';

        });

        $('#lstListing').html(output).listview("refresh");

      $("a").on("click", function(event){
	  event.preventDefault();

	  currentPath = $(this).attr("data-path");
	  if($(this).attr("href") == "#listing-page"){
	      doListing();
	  }else{
	      $.mobile.navigate( $(this).attr("href") );
	  }
      });
  });
}

function doListing(){
    if(currentPath == ""){
	listFiles("/");
    }else{
	listFiles(currentPath);
    }
}

$(document).on("pagecreate", "#listing-page", function(){ doListing(); });

currentPath = "";
