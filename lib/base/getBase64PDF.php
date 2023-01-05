<?php 
echo chunk_split(base64_encode(file_get_contents('../../assets/pdffiles/' . $_GET['dokumen']))); 
?>