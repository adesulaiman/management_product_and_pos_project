function popup(status, text, header){
	toastr.options = {
	  "closeButton": false,
	  "debug": false,
	  "newestOnTop": false,
	  "progressBar": false,
	  "positionClass": "toast-top-center",
	  "preventDuplicates": false,
	  "onclick": null,
	  "showDuration": "300",
	  "hideDuration": "500",
	  "timeOut": "3000",
	  "extendedTimeOut": "1000",
	  "showEasing": "swing",
	  "hideEasing": "linear",
	  "showMethod": "fadeIn",
	  "hideMethod": "fadeOut"
	}

	  toastr[status](text, header);
	  
}




function DownloadFile(fileUrl, fileName) {
    //Set the File URL.
    var url = fileUrl;
    $("#loading").removeClass("hide");

    $.ajax({
      url: url,
      cache: false,
      xhr: function() {
        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function() {
          if (xhr.readyState == 2) {
            if (xhr.status == 200) {
              xhr.responseType = "blob";
            } else {
              xhr.responseType = "text";
            }
          }
        };
        return xhr;
      },
      success: function(data) {
        $("#loading").addClass("hide");
        //Convert the Byte Data to BLOB object.
        var blob = new Blob([data], {
          type: "application/octetstream"
        });

        //Check the Browser type and download the File.
        var isIE = false || !!document.documentMode;
        if (isIE) {
          window.navigator.msSaveBlob(blob, fileName);
        } else {
          var url = window.URL || window.webkitURL;
          link = url.createObjectURL(blob);
          var a = $("<a />");
          a.attr("download", fileName);
          a.attr("href", link);
          $("body").append(a);
          a[0].click();
        }
      },
      error: function(e){
        $("#loading").addClass("hide");
        alert("error download, file not found !!");
        console.log(e);
      }
    });
  };
