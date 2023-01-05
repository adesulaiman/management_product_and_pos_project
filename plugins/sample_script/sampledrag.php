<!DOCTYPE html>
<html lang="en" >

<head>
  <meta charset="UTF-8">
  <title>Drag and drop placeholder on PDF documents with mozilla pdf.js, interact.js, boostrap 3 and jQuery</title>
  
  
  <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.5/css/bootstrap.min.css'>

      <link rel="stylesheet" href="plugins/drag-and-drop-placeholders-into-PDF/css/style.css">

  
</head>

<body>

  <div class="container"> 
  <div class="row">
      <div class="col-md-12" style="padding:10px">
          <button class="btn btn-primary btn-block" onClick="showCoordinates()">Show PDF Placeholders Coordinates</button>
      </div>
  </div>   
  <div class="row">
        <button class="prePage">Previous Page</button>
        <button class="nextPage">Next Page</button>
					<div class="col-md-12" id="pdfManager" style="display:none">	
						<div class="row" id="selectorContainer">
							<div class="col-fixed-240" id="parametriContainer">
							</div>
							<div class="col-fixed-605">
								<div id="pageContainer" class="pdfViewer singlePageView dropzone nopadding" style="background-color:transparent">
									<canvas id="the-canvas" style="border:1px  solid black"></canvas>
								</div>
							</div>
						</div>
					</div>
				</div>
</div>

<!-- parameters showed on the left sidebar -->

<!-- Below the pdf base 64 rapresentation -->
<input id="pdfBase64" type="hidden" value="<?php echo chunk_split(base64_encode(file_get_contents('assets/upload/61739dd02c549_BAB I.pdf'))); ?>" />
  <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js'></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.5/js/bootstrap.min.js'></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.0.943/pdf.min.js'></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/interact.js/1.2.9/interact.min.js'></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.0.943/pdf.worker.min.js'></script>

  

    <script  src="plugins/drag-and-drop-placeholders-into-PDF/js/index.js?v=4"></script>




</body>

</html>
