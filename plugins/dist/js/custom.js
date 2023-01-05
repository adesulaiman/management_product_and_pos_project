function imageInit(cssimg, cssInptFile) {
  cssimg.after("<br><p class='nameFile'></p>");

  cssimg.on("click", function () {
    cssInptFile.click();
    var img = $(this);
    
    cssInptFile.on("change", function (e) {
      var file = e.originalEvent.srcElement.files[0];
      var ext = file.name.split(".");
      ext = ext[ext.length - 1];

      var reader = new FileReader();
      reader.onloadend = function () {
        if (
          ext == 'jpg' ||
          ext == 'jpeg' ||
          ext == 'png'
        ) {
          img.attr("src", reader.result);
        }else if(
          ext == 'doc' ||
          ext == 'docx'){
            img.attr("src", "assets/img/word.png");
          }
        else if(
          ext == 'xls' ||
          ext == 'xlsx'
        ){
          img.attr("src", "assets/img/excel.png");
        }else if(
          ext == 'p12' ||
          ext == 'pfx' ||
          ext == 'crt'
        ){
          img.attr("src", "assets/img/cert.png");
        }else if(
          ext == 'pdf'
        ){
          img.attr("src", "assets/img/pdf.png");
        }else{
          img.attr("src", "assets/img/file.png");
        }

        $(".nameFile").html(file.name);
      }
      reader.readAsDataURL(file);
    });
  });
}