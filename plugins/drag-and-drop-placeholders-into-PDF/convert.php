<?php
$b64Doc = chunk_split(base64_encode(file_get_contents('resume.pdf')));
echo $b64Doc;
?>