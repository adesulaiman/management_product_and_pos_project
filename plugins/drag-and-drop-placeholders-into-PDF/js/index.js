
var maxPDFx = 0;
var maxPDFy = 0;
var offsetY = 7;
var heightQr = 100;
var widthtQr = 100;
var currPage = 1;
var maxPage = 0;
var parametri = "";
var currSetQr = new Array(); //{page:1, src:pathImg, coordinate:0,0,0,0}

flagDisplay = 0;

function qrSet(className) {
	if (flagDisplay == 0) {
		$(".drag-drop").css("display", "block");
		$(".drag-drop").attr("data-x", "0");
		$(".drag-drop").attr("data-y", "0");
		$("." + className).css("border", "2px solid green");
		flagDisplay = 1;
	} else {
		$(".drag-drop").css("display", "none");
		$(".drag-drop").attr("data-x", "-100");
		$(".drag-drop").attr("data-y", "-100");
		$("." + className).css("border", "unset");
		flagDisplay = 0;
	}
}

function clearQR (pdfAtob){
	var pdfData = atob(pdfAtob);

	// The workerSrc property shall be specified.
	//
	pdfjsLib.GlobalWorkerOptions.workerSrc =
		'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.0.943/pdf.worker.min.js';

	//
	// Asynchronous download PDF
	//
	var loadingTask = pdfjsLib.getDocument({ data: pdfData });
	currSetQr = [];
	loadingTask.promise.then(function (pdf) {
		//
		// Fetch the first page
		//
		pdf.getPage(currPage).then(function (page) {
			var scale = 1.0;
			var viewport = page.getViewport(scale);
			//
			// Prepare canvas using PDF page dimensions
			//
			var canvas = document.getElementById('the-canvas');
			var context = canvas.getContext('2d');
			canvas.height = viewport.height;
			canvas.width = viewport.width;
			//
			// Render PDF page into canvas context
			//
			var renderContext = {
				canvasContext: context,
				viewport: viewport
			};
			//page.render(renderContext);

			page.render(renderContext).then(function () {
				$(document).trigger("pagerendered");


			}, function () {
				console.log("ERROR");
			});

		});
	});
	return currSetQr;
};


function showPdf(pdfAtob, qr_code, QRCoor) {
	currSetQr = QRCoor;
	currPage = 1;
	parametri = JSON.parse(`[{"idParametro":480,"srcImg":"assets/qrcode/` + qr_code + `.png","descrizione":"<img class='qrCodeDrag' src='assets/qrcode/` + qr_code + `.png' style='width:` + widthtQr + `px;height:` + heightQr + `px' />","valore":"X","nota":"cek nota"}]`);
	// var pdfData = atob($('#pdfBase64').val());
	var pdfData = atob(pdfAtob);
	/*
	*  costanti per i placaholder 
	*/

	'use strict';

	var img = parametri[0].srcImg;
	// The workerSrc property shall be specified.
	//
	pdfjsLib.GlobalWorkerOptions.workerSrc =
		'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.0.943/pdf.worker.min.js';

	//
	// Asynchronous download PDF
	//
	var loadingTask = pdfjsLib.getDocument({ data: pdfData });


	loadingTask.promise.then(function (pdf) {
		//
		// Fetch the first page
		//

		maxPage = pdf._pdfInfo.numPages;
		pdf.getPage(currPage).then(function (page) {
			var scale = 1.0;

			var viewport = page.getViewport(scale);
			//
			// Prepare canvas using PDF page dimensions
			//
			var canvas = document.getElementById('the-canvas');
			var context = canvas.getContext('2d');
			canvas.height = viewport.height;
			canvas.width = viewport.width;
			maxPDFy = viewport.height;
			maxPDFx = viewport.width;




			$("#pdfContainer").css("width", maxPDFx);
			$("#pdfContainer").css("height", maxPDFy);
			// $("#pdfContainer").css("margin", "12px 0");
			//
			// Render PDF page into canvas context
			//
			var renderContext = {
				canvasContext: context,
				viewport: viewport
			};
			//page.render(renderContext);


			page.render(renderContext).then(function () {
				$(document).trigger("pagerendered");

				setTimeout(
					function () {
						//do something special
						var containerWidth = $("#pageContainer").width();
						var marginCenter = containerWidth - maxPDFx;
						marginCenter = marginCenter / 2;
						$("#pdfContainer").css("margin", "0px " + marginCenter + "px");
					}, 100);

				$.each(currSetQr, function(key, value){
					if (value.page == currPage) {

						base_image = new Image();
						base_image.src = value.srcImg;
						base_image.onload = function () {
							context.drawImage(base_image, value.x, value.y, value.w, value.h);
						}

					}
				});




			}, function () {
				console.log("ERROR");
			});

		});
	});



	$(".nextPage").on("click", function () {

		loadingTask.promise.then(function (pdf) {
			//
			// Fetch the first page
			//
			pdf.getPage(currPage).then(function (page) {
				var scale = 1.0;
				var viewport = page.getViewport(scale);
				//
				// Prepare canvas using PDF page dimensions
				//
				var canvas = document.getElementById('the-canvas');
				var context = canvas.getContext('2d');
				canvas.height = viewport.height;
				canvas.width = viewport.width;
				//
				// Render PDF page into canvas context
				//
				var renderContext = {
					canvasContext: context,
					viewport: viewport
				};
				//page.render(renderContext);

				page.render(renderContext).then(function () {
					$(document).trigger("pagerendered");

					$.each(currSetQr, function(key, value){
						if (value.page == currPage) {

							base_image = new Image();
							base_image.src = value.srcImg;
							base_image.onload = function () {
								context.drawImage(base_image, value.x, value.y, value.w, value.h);
							}

						}
					});


				}, function (e) {
					console.log(e);
				});

			});
		});

		console.log(currPage);
		if (currPage < maxPage) {
			currPage++;
		}

		console.log(currSetQr);

	});


	$(".prePage").on("click", function () {
		loadingTask.promise.then(function (pdf) {
			//
			// Fetch the first page
			//
			pdf.getPage(currPage).then(function (page) {
				var scale = 1.0;
				var viewport = page.getViewport(scale);
				//
				// Prepare canvas using PDF page dimensions
				//
				var canvas = document.getElementById('the-canvas');
				var context = canvas.getContext('2d');
				canvas.height = viewport.height;
				canvas.width = viewport.width;
				//
				// Render PDF page into canvas context
				//
				var renderContext = {
					canvasContext: context,
					viewport: viewport
				};
				//page.render(renderContext);

				page.render(renderContext).then(function () {
					$(document).trigger("pagerendered");

					$.each(currSetQr, function(key, value){
						if (value.page == currPage) {

							base_image = new Image();
							base_image.src = value.srcImg;
							base_image.onload = function () {
								context.drawImage(base_image, value.x, value.y, value.w, value.h);
							}

						}
					});
				}, function () {
					console.log("ERROR");
				});

			});
		});
		if (currPage > 1) {
			currPage--;
		}
	});


	
	

	interact('.drag-drop').unset();


	/* The dragging code for '.draggable' from the demo above
	 * applies to this demo as well so it doesn't have to be repeated. */

	// enable draggables to be dropped into this
	interact('.dropzone').dropzone({
		// only accept elements matching this CSS selector
		accept: '.drag-drop',
		// Require a 100% element overlap for a drop to be possible
		overlap: 1,

		// listen for drop related events:

		ondropactivate: function (event) {
			// add active dropzone feedback
			event.target.classList.add('drop-active');
		},
		ondragenter: function (event) {
			var draggableElement = event.relatedTarget,
				dropzoneElement = event.target;

			// feedback the possibility of a drop
			dropzoneElement.classList.add('drop-target');
			draggableElement.classList.add('can-drop');
			draggableElement.classList.remove('dropped-out');
			//draggableElement.textContent = 'Dragged in';
		},
		ondragleave: function (event) {
			// remove the drop feedback style
			event.target.classList.remove('drop-target');
			event.relatedTarget.classList.remove('can-drop');
			event.relatedTarget.classList.add('dropped-out');
			//event.relatedTarget.textContent = 'Dragged out';
		},
		ondrop: function (event) {
			//event.relatedTarget.textContent = 'Dropped';
		},
		ondropdeactivate: function (event) {
			// remove active dropzone feedback
			event.target.classList.remove('drop-active');
			event.target.classList.remove('drop-target');
		}
	});

	interact('.drag-drop')
		.resizable({
			// resize from all edges and corners
			edges: { left: true, right: true, bottom: false, top: true },

			listeners: {
				move(event) {
					var target = event.target
					var x = (parseFloat(target.getAttribute('data-x')) || 0)
					var y = (parseFloat(target.getAttribute('data-y')) || 0)

					// update the element's style
					target.style.width = event.rect.width + 'px'
					target.style.height = event.rect.height + 'px'

					// translate when resizing from top or left edges
					x += event.deltaRect.left
					y += event.deltaRect.top

					target.style.transform = 'translate(' + x + 'px,' + y + 'px)'
					$(".qrCodeDrag").css("width", event.rect.width + 'px');
					$(".qrCodeDrag").css("height", event.rect.height + 'px');

					target.setAttribute('data-x', x)
					target.setAttribute('data-y', y)
					// target.textContent = Math.round(event.rect.width) + '\u00D7' + Math.round(event.rect.height)
				}
			},
			modifiers: [
				// keep the edges inside the parent
				interact.modifiers.restrictEdges({
					outer: 'parent'
				}),

				// minimum size
				interact.modifiers.restrictSize({
					min: { width: 50, height: 50 }
				})
			],

			inertia: true
		})
		.draggable({
			inertia: true,
			restrict: {
				restriction: "#selectorContainer",
				endOnly: true,
				elementRect: { top: 0, left: 0, bottom: 1, right: 1 }
			},
			autoScroll: true,
			// dragMoveListener from the dragging demo above
			onmove: dragMoveListener,
		});




	// this is used later in the resizing demo
	window.dragMoveListener = dragMoveListener;

	$(document).bind('pagerendered', function (e) {
		$('#pdfManager').show();
		$('#parametriContainer').empty();
		renderizzaPlaceholder(0, parametri);
	});
}



function showPdfAndroid(pdfAtob, qr_code) {
	parametri = JSON.parse(`[{"idParametro":480,"srcImg":"assets/qrcode/` + qr_code + `.png","descrizione":"<img class='qrCodeDrag' src='assets/qrcode/` + qr_code + `.png' style='width:` + widthtQr + `px;height:` + heightQr + `px' />","valore":"X","nota":"cek nota"}]`);
	// var pdfData = atob($('#pdfBase64').val());
	var pdfData = atob(pdfAtob);
	/*
	*  costanti per i placaholder 
	*/

	'use strict';

	var img = parametri[0].srcImg;
	// The workerSrc property shall be specified.
	//
	pdfjsLib.GlobalWorkerOptions.workerSrc =
		'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.0.943/pdf.worker.min.js';

	//
	// Asynchronous download PDF
	//
	var loadingTask = pdfjsLib.getDocument({ data: pdfData });


	loadingTask.promise.then(function (pdf) {
		//
		// Fetch the first page
		//
		currSetQr = new Array();

		maxPage = pdf._pdfInfo.numPages;
		pdf.getPage(currPage).then(function (page) {
			var scale = 1.0;

			var viewport = page.getViewport(scale);
			//
			// Prepare canvas using PDF page dimensions
			//
			var canvas = document.getElementById('the-canvas');
			var context = canvas.getContext('2d');
			canvas.height = viewport.height;
			canvas.width = viewport.width;
			maxPDFy = viewport.height;
			maxPDFx = viewport.width;




			$("#pdfContainer").css("width", maxPDFx);
			$("#pdfContainer").css("height", maxPDFy);
			// $("#pdfContainer").css("margin", "12px 0");
			//
			// Render PDF page into canvas context
			//
			var renderContext = {
				canvasContext: context,
				viewport: viewport
			};
			//page.render(renderContext);


			page.render(renderContext).then(function () {
				$(document).trigger("pagerendered");

				setTimeout(
					function () {
						//do something special
						var containerWidth = $("#pageContainer").width();
						var marginCenter = containerWidth - maxPDFx;
						marginCenter = marginCenter / 2;
						//$("#pdfContainer").css("margin", "0px " + marginCenter + "px");
					}, 100);




			}, function () {
				console.log("ERROR");
			});

		});
	});



	$(".nextPage").on("click", function () {


		loadingTask.promise.then(function (pdf) {
			//
			// Fetch the first page
			//
			pdf.getPage(currPage).then(function (page) {
				var scale = 1.0;
				var viewport = page.getViewport(scale);
				//
				// Prepare canvas using PDF page dimensions
				//
				var canvas = document.getElementById('the-canvas');
				var context = canvas.getContext('2d');
				canvas.height = viewport.height;
				canvas.width = viewport.width;
				//
				// Render PDF page into canvas context
				//
				var renderContext = {
					canvasContext: context,
					viewport: viewport
				};
				//page.render(renderContext);

				page.render(renderContext).then(function () {
					$(document).trigger("pagerendered");

					$.each(currSetQr, function(key, value){
						if (value.page == currPage) {

							base_image = new Image();
							base_image.src = value.srcImg;
							base_image.onload = function () {
								context.drawImage(base_image, value.x, value.y, value.w, value.h);
							}

						}
					});


				}, function () {
					console.log("ERROR");
				});

			});
		});
		if (currPage < maxPage) {
			currPage++;
		}

		console.log(currSetQr);

	});


	$(".prePage").on("click", function () {
		loadingTask.promise.then(function (pdf) {
			//
			// Fetch the first page
			//
			pdf.getPage(currPage).then(function (page) {
				var scale = 1.0;
				var viewport = page.getViewport(scale);
				//
				// Prepare canvas using PDF page dimensions
				//
				var canvas = document.getElementById('the-canvas');
				var context = canvas.getContext('2d');
				canvas.height = viewport.height;
				canvas.width = viewport.width;
				//
				// Render PDF page into canvas context
				//
				var renderContext = {
					canvasContext: context,
					viewport: viewport
				};
				//page.render(renderContext);

				page.render(renderContext).then(function () {
					$(document).trigger("pagerendered");

					$.each(currSetQr, function(key, value){
						if (value.page == currPage) {

							base_image = new Image();
							base_image.src = value.srcImg;
							base_image.onload = function () {
								context.drawImage(base_image, value.x, value.y, value.w, value.h);
							}

						}
					});
				}, function () {
					console.log("ERROR");
				});

			});
		});
		if (currPage > 1) {
			currPage--;
		}
	});


	
	

	interact('.drag-drop').unset();


	/* The dragging code for '.draggable' from the demo above
	 * applies to this demo as well so it doesn't have to be repeated. */

	// enable draggables to be dropped into this
	interact('.dropzone').dropzone({
		// only accept elements matching this CSS selector
		accept: '.drag-drop',
		// Require a 100% element overlap for a drop to be possible
		overlap: 1,

		// listen for drop related events:

		ondropactivate: function (event) {
			// add active dropzone feedback
			event.target.classList.add('drop-active');
		},
		ondragenter: function (event) {
			var draggableElement = event.relatedTarget,
				dropzoneElement = event.target;

			// feedback the possibility of a drop
			dropzoneElement.classList.add('drop-target');
			draggableElement.classList.add('can-drop');
			draggableElement.classList.remove('dropped-out');
			//draggableElement.textContent = 'Dragged in';
		},
		ondragleave: function (event) {
			// remove the drop feedback style
			event.target.classList.remove('drop-target');
			event.relatedTarget.classList.remove('can-drop');
			event.relatedTarget.classList.add('dropped-out');
			//event.relatedTarget.textContent = 'Dragged out';
		},
		ondrop: function (event) {
			//event.relatedTarget.textContent = 'Dropped';
		},
		ondropdeactivate: function (event) {
			// remove active dropzone feedback
			event.target.classList.remove('drop-active');
			event.target.classList.remove('drop-target');
		}
	});

	interact('.drag-drop')
		.resizable({
			// resize from all edges and corners
			edges: { left: true, right: true, bottom: false, top: true },

			listeners: {
				move(event) {
					var target = event.target
					var x = (parseFloat(target.getAttribute('data-x')) || 0)
					var y = (parseFloat(target.getAttribute('data-y')) || 0)

					// update the element's style
					target.style.width = event.rect.width + 'px'
					target.style.height = event.rect.height + 'px'

					// translate when resizing from top or left edges
					x += event.deltaRect.left
					y += event.deltaRect.top

					target.style.transform = 'translate(' + x + 'px,' + y + 'px)'
					$(".qrCodeDrag").css("width", event.rect.width + 'px');
					$(".qrCodeDrag").css("height", event.rect.height + 'px');

					target.setAttribute('data-x', x)
					target.setAttribute('data-y', y)
					// target.textContent = Math.round(event.rect.width) + '\u00D7' + Math.round(event.rect.height)
				}
			},
			modifiers: [
				// keep the edges inside the parent
				interact.modifiers.restrictEdges({
					outer: 'parent'
				}),

				// minimum size
				interact.modifiers.restrictSize({
					min: { width: 50, height: 50 }
				})
			],

			inertia: true
		})
		.draggable({
			inertia: true,
			restrict: {
				restriction: "#selectorContainer",
				endOnly: true,
				elementRect: { top: 0, left: 0, bottom: 1, right: 1 }
			},
			autoScroll: true,
			// dragMoveListener from the dragging demo above
			onmove: dragMoveListener,
		});




	// this is used later in the resizing demo
	window.dragMoveListener = dragMoveListener;

	$(document).bind('pagerendered', function (e) {
		$('#pdfManager').show();
		$('#parametriContainer').empty();
		renderizzaPlaceholder(0, parametri);
	});
}


function dragMoveListener(event) {
	var target = event.target,
		// keep the dragged position in the data-x/data-y attributes
		x = (parseFloat(target.getAttribute('data-x')) || 0) + event.dx,
		y = (parseFloat(target.getAttribute('data-y')) || 0) + event.dy;

	// translate the element
	target.style.webkitTransform =
		target.style.transform = 'translate(' + x + 'px, ' + y + 'px)';

	// update the posiion attributes
	target.setAttribute('data-x', x);
	target.setAttribute('data-y', y);
}

function renderizzaPlaceholder(currentPage, parametri) {
	var maxHTMLx = $('#the-canvas').width();
	var maxHTMLy = $('#the-canvas').height();

	var paramContainerWidth = $('#parametriContainer').width();
	var yCounterOfGenerated = 0;
	var numOfMaxItem = 25;
	var notValidHeight = 120;
	var y = 0;
	var x = 0;
	var page = 12;


	var totalPages = Math.ceil(parametri.length / numOfMaxItem);

	for (i = 0; i < parametri.length; i++) {
		var param = parametri[i];
		var page = Math.floor(i / numOfMaxItem);

		if (i > 0 && i % numOfMaxItem == 0) {
			yCounterOfGenerated = 0;
		}

		var classStyle = "";
		var valore = param.valore;
		/*il placeholder non è valido: lo incolonna a sinistra*/

		if (i > 0 && i % numOfMaxItem == 0) {
			yCounterOfGenerated = 0;
		}

		var classStyle = "";
		var valore = param.valore;
		/*il placeholder non è valido: lo incolonna a sinistra*/
		y = yCounterOfGenerated;
		yCounterOfGenerated += notValidHeight;
		classStyle = "drag-drop dropped-out";



		$("#parametriContainer").append('<div class="' + classStyle + '" data-id="-1" data-page="' + page + '" data-toggle="' + valore + '" data-valore="' + valore + '" data-x="' + x + '" data-y="' + y + '" style=" transform: translate(' + x + 'px, ' + y + 'px); display:none">  </span><span class="descrizione">' + param.descrizione + ' </span></div>');
	}

	y = notValidHeight * (numOfMaxItem + 1);
	var prevStyle = "";
	var nextStyle = "";
	var prevDisabled = false;
	var nextDisabled = false;
	if (currentPage == 0) {
		prevStyle = "disabled";
		prevDisabled = true;
	}

	if (currentPage >= totalPages - 1 || totalPages == 1) {
		nextDisabled = true;
		nextStyle = "disabled";
	}

	//Aggiunge la paginazione
	$("#parametriContainer").append('<ul id="pager" class="pager" style="transform: translate(' + x + 'px, ' + y + 'px); width:200px;"><li onclick="changePage(' + prevDisabled + ',' + currentPage + ',-1)" class="page-item ' + prevStyle + '"><span>«</span></li><li onclick="changePage(' + nextDisabled + ',' + currentPage + ',1)" class="page-item ' + nextStyle + '" style="margin-left:10px;"><span>&raquo;</span></li></ul>');

}

function renderizzaInPagina(parametri) {
	var maxHTMLx = $('#the-canvas').width();
	var maxHTMLy = $('#the-canvas').height();

	var paramContainerWidth = $('#parametriContainer').width();
	var yCounterOfGenerated = 0;
	var numOfMaxItem = 26;
	var notValidHeight = 30;
	var y = 0;
	var x = 6;
	for (i = 0; i < parametri.length; i++) {
		var param = parametri[i];

		var classStyle = "drag-drop can-drop";
		var valore = param.valore;
		/*il placeholder non è valido: lo incolonna a sinistra*/

		var pdfY = maxPDFy - param.posizioneY - offsetY;
		y = (pdfY * maxHTMLy) / maxPDFy;
		x = ((param.posizioneX * maxHTMLx) / maxPDFx) + paramContainerWidth;

		$("#parametriContainer").append('<div class="' + classStyle + '" data-id="' + param.idParametroModulo + '" data-toggle="' + valore + '" data-valore="' + valore + '" data-x="' + x + '" data-y="' + y + '" style="transform: translate(' + x + 'px, ' + y + 'px);">  <span class="circle"></span><span class="descrizione">' + param.descrizione + ' </span></div>');
	}
}


function changePage(disabled, currentPage, delta) {
	if (disabled) {
		return;
	}

	/*recupera solo i parametri non posizionati in pagina*/
	var parametri = [];
	$(".drag-drop.dropped-out").each(function () {
		var valore = $(this).data("valore");
		var descrizione = $(this).find(".descrizione").text();
		parametri.push({ valore: valore, descrizione: descrizione, posizioneX: -1000, posizioneY: -1000 });
		$(this).remove();
	});

	//svuota il contentitore
	$('#pager').remove();
	currentPage += delta;
	renderizzaPlaceholder(currentPage, parametri);
	//aggiorna lo stato dei pulsanti
	//aggiorna gli elementi visualizzati
}


function showCoordinates() {
	var validi = [];
	var nonValidi = [];

	var maxHTMLx = $('#the-canvas').width();
	var maxHTMLy = $('#the-canvas').height();
	var paramContainerWidth = $('#parametriContainer').width();
	var size_diff_fpdf = 2.9;

	var canvas = document.getElementById('the-canvas');
	var context = canvas.getContext('2d');

	//recupera tutti i placholder validi
	$('.drag-drop.can-drop').each(function (index) {
		var x = parseFloat($(this).attr("data-x"));
		var y = parseFloat($(this).attr("data-y"));
		var w = $(this).find(".descrizione img").css("width");
		w = w.replace("px", "");
		var h = $(this).find(".descrizione img").css("height");
		h = h.replace("px", "");
		var valore = $(this).data("valore");
		var src = $(this).find(".descrizione img").attr("src");
		var descrizione = $(this).find(".descrizione").text();

		// var pdfY = (y / size_diff_fpdf) - 1;
		var pdfY = (y / size_diff_fpdf) - 1;
		var pdfYHtml = y;
		var posizioneY = maxPDFy - offsetY - pdfY;
		var posizioneX = (((x * maxPDFx / maxHTMLx) - paramContainerWidth) / size_diff_fpdf) + 5;
		var posizioneXHtml = ((x * maxPDFx / maxHTMLx) - paramContainerWidth) + 10;

		var val = { "descrizione": descrizione, "posizioneX": posizioneX, "posizioneY": pdfY, "html-Y": pdfYHtml, "html-x": posizioneXHtml, "valore": valore };

		if (x > 0) {
			validi.push(val);
			currSetQr.push({ "page": currPage, "srcImg": src, "x": x, "y": y, "w": w, "h": h, "xCanvas": maxHTMLx, "yCanvas": maxHTMLy });

			base_image = new Image();
			base_image.src = src;
			base_image.onload = function () {
				context.drawImage(base_image, x + 1, y, w, h);
			}
		}

	});

	if (currSetQr.length == 0) {
		alert('Please select QR Code for set !!');
	}
	else {
		console.log(currSetQr);
		alert("QR Set Successfully !!");
		return currSetQr;
	}
}