<?php

class setQR_kop_PDF
{
    public $pathPDFSource, $pathPDFOutput, $QRsetPage;
    public $tanggalSurat, $nomor, $jenisDok, $perihal;
    public $pathKop = __DIR__ . "/../../assets/img/kop.png", $pathFooter = __DIR__ . "/../../assets/img/footer_surat.png";
    public $outQR, $logoPath = __DIR__ . "/../../assets/img/logo_for_qrcode.png";
    public $contentQR;
    public $name, $nip, $QRPath, $embedPDF;

    function setEmbedPDF($embedPDF)
    {
        $this->embedPDF = $embedPDF;
    }

    function setName($name)
    {
        $this->name = $name;
    }

    function setQRPath($qrpath)
    {
        $this->QRPath = $qrpath;
    }

    function setNIP($nip)
    {
        $this->nip = $nip;
    }

    function setTanggalSurat($tanggalSurat)
    {
        $this->tanggalSurat = $this->tgl_indo($tanggalSurat);
    }

    function setContentQR($contentQR)
    {
        $this->contentQR = $contentQR;
    }

    function setOutQR($outQR)
    {
        $this->outQR = $outQR;
    }

    function generateQR()
    {
        try {

            include "../../plugins/phpqrcode/qrlib.php";
			include "../../config.php";

            $back_color = "#00a859";
            $fore_color = "#00a859";
			
			
			//send api to bitly.sampangkab.go.id
			$shortLink = '';
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $urlBitly);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS,
						"url=" . $this->contentQR);

			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$server_output = curl_exec($ch);
			curl_close ($ch);	
			$responBitly = json_decode($server_output);
			if($responBitly->status == 'success'){
				$shortLink = $responBitly->shortlink;
			}else{
				return false;
			}
			
			
			
            QRcode::png($shortLink, $this->outQR, QR_ECLEVEL_H, 4, 4, $back_color, $fore_color);


            // ambil file qrcode
            $QR = imagecreatefrompng($this->outQR);

            // memulai menggambar logo dalam file qrcode
            $logo = imagecreatefromstring(file_get_contents($this->logoPath));

            imagecolortransparent($logo, imagecolorallocatealpha($logo, 0, 0, 0, 127));
            imagealphablending($logo, false);
            imagesavealpha($logo, true);

            $QR_width = imagesx($QR);
            $QR_height = imagesy($QR);

            $logo_width = imagesx($logo);
            $logo_height = imagesy($logo);

            // Scale logo to fit in the QR Code
            $logo_qr_width = $QR_width / 4;
            $scale = $logo_width / $logo_qr_width;
            $logo_qr_height = $logo_height / $scale;

            imagecopyresampled($QR, $logo, $QR_width / 2.6, $QR_height / 2.8, 0, 0, $logo_qr_width, $logo_qr_height, $logo_width, $logo_height);

            // Simpan kode QR lagi, dengan logo di atasnya
            imagepng($QR, $this->outQR);
            return true;
        } catch (Exception $e) {
            error_log($e);
            return false;
        }
    }

    function tgl_indo($tanggal)
    {
        $bulan = array(
            1 =>   'Januari',
            'Februari',
            'Maret',
            'April',
            'Mei',
            'Juni',
            'Juli',
            'Agustus',
            'September',
            'Oktober',
            'November',
            'Desember'
        );
        $pecahkan = explode('-', $tanggal);

        return $pecahkan[2] . ' ' . $bulan[(int)$pecahkan[1]] . ' ' . $pecahkan[0];
    }


    function setNomor($nomor)
    {
        $this->nomor = $nomor;
    }

    function setjenisDok($jenisDok)
    {
        $this->jenisDok = $jenisDok;
    }

    function setperihal($perihal)
    {
        $this->perihal = $perihal;
    }

    function setDefaultKopPDF()
    {
        require_once(__DIR__ . '/../../plugins/fpdf/fpdf.php');
        require_once(__DIR__ . '/../../plugins/fpdi/src/autoload.php');

        try {
            $pdf = new \setasign\Fpdi\Fpdi();;
            //Set the source PDF file
            $pagecount = $pdf->setSourceFile($this->pathPDFSource);


            $space = 5;
            $startY = 65;
            $startYDate = 45;
            $startXValue = 45;
            $startXDate = 128;
            $startX = 15;

            for ($pageNo = 1; $pageNo <= $pagecount; $pageNo++) {
                // import a page
                $templateId = $pdf->importPage($pageNo);
                // get the size of the imported page
                $size = $pdf->getTemplateSize($templateId);

                // create a page (landscape or portrait depending on the imported page size)
                if ($size[0] > $size[1]) {
                    $pdf->AddPage('L', array($size[0], $size[1]));
                } else {
                    $pdf->AddPage('P', array($size[0], $size[1]));
                }

                // use the imported page
                $pdf->useTemplate($templateId);

                $pdf->SetFont('Times');
                $pdf->SetFontSize(12);
                $pdf->SetXY(5, 5);


                // set just in page 1
                if ($pageNo == 1) {
                    if ($this->embedPDF == 'kop-param') {
                        $pdf->Image($this->pathKop, 0, 0, $size[0], 40);

                        $pdf->SetXY($startXDate, $startYDate);
                        $pdf->Write(0, 'Sampang, ' . $this->tanggalSurat);


                        $pdf->SetXY($startX, $startY);
                        $pdf->Write(0, 'Nomor');
                        $pdf->SetXY($startXValue, $startY);
                        $pdf->Write(0, ': ' . $this->nomor);

                        $startY += $space;
                        $pdf->SetXY($startX, $startY);
                        $pdf->Write(0, 'Jenis Dokumen');
                        $pdf->SetXY($startXValue, $startY);
                        $pdf->Write(0, ': ' . $this->jenisDok);

                        $startY += $space;
                        $pdf->SetXY($startX, $startY);
                        $pdf->Write(0, 'Perihal');
                        $pdf->SetXY($startXValue, $startY);
                        $pdf->Write(0, ': ');
                        $pdf->SetXY($startXValue + 2, $startY - 2.5);
                        $pdf->MultiCell(80, 5, $this->perihal, 0, 'L');
                    }else if($this->embedPDF == 'kop'){
                        $pdf->Image($this->pathKop, 0, 0, $size[0], 40);
                    }
                }


                //footer
                $pdf->Image($this->pathFooter, 45, $size[1] - 10, 120, 10);
            }

            $pdf->Output($this->pathPDFOutput, "F");

            return true;
        } catch (Exception $e) {
            error_log($e);
            return false;
        }
    }


    function setNomorDoc()
    {
        require_once(__DIR__ . '/../../plugins/fpdf/fpdf.php');
        require_once(__DIR__ . '/../../plugins/fpdi/src/autoload.php');

        try {
            $pdf = new \setasign\Fpdi\Fpdi();;
            //Set the source PDF file
            $pagecount = $pdf->setSourceFile($this->pathPDFSource);


            $space = 7;
            $startY = 60;
            $startYDate = 45;
            $startXValue = 60;
            $startXDate = 140;
            $startX = 30;

            for ($pageNo = 1; $pageNo <= $pagecount; $pageNo++) {
                // import a page
                $templateId = $pdf->importPage($pageNo);
                // get the size of the imported page
                $size = $pdf->getTemplateSize($templateId);

                // create a page (landscape or portrait depending on the imported page size)
                if ($size[0] > $size[1]) {
                    $pdf->AddPage('L', array($size[0], $size[1]));
                } else {
                    $pdf->AddPage('P', array($size[0], $size[1]));
                }

                // use the imported page
                $pdf->useTemplate($templateId);

                $pdf->SetFont('Times');
                $pdf->SetFontSize(10);
                $pdf->SetXY(5, 5);


                // set just in page 1
                if ($pageNo == 1) {

                    $pdf->SetXY($startXValue, $startY);
                    $pdf->Write(0, ': ' . $this->nomor);
                }
            }

            $pdf->Output($this->pathPDFOutput, "F");

            return true;
        } catch (Exception $e) {
            error_log($e);
            return false;
        }
    }


    function setPathPDFSource($pathPDFSource)
    {
        $this->pathPDFSource = $pathPDFSource;
    }

    function setPathPDFOutput($pathPDFOutput)
    {
        $this->pathPDFOutput = $pathPDFOutput;
    }

    function setQRSetPage($QRsetPage)
    {
        $this->QRsetPage = $QRsetPage;
    }

    function combinePDFwithQR()
    {
        require_once(__DIR__ . '/../../plugins/fpdf/fpdf.php');
        require_once(__DIR__ . '/../../plugins/fpdi/src/autoload.php');

        try {
            $pdf = new \setasign\Fpdi\Fpdi();

            //Set the source PDF file
            $pagecount = $pdf->setSourceFile($this->pathPDFSource);

            for ($pageNo = 1; $pageNo <= $pagecount; $pageNo++) {
                // import a page
                $templateId = $pdf->importPage($pageNo);
                // get the size of the imported page
                $size = $pdf->getTemplateSize($templateId);

                // error_log("Size x " . $size[0] . " - Size y " . $size[1]);
                // create a page (landscape or portrait depending on the imported page size)
                if ($size[0] > $size[1]) {
                    $pdf->AddPage('L', array($size[0], $size[1]));
                } else {
                    $pdf->AddPage('P', array($size[0], $size[1]));
                }

                // use the imported page
                $pdf->useTemplate($templateId);

                $pdf->SetFont('Times');
                $pdf->SetXY(5, 5);

                foreach ($this->QRsetPage as $page) {
                    if (!empty($page->page)) {
                        if ($page->page == $pageNo) {

                            $xCalc = $page->xCanvas / $size[0];
                            $yCalc = $page->yCanvas / $size[1];

                            $x = ($page->x / $xCalc) + 1;
                            $y = ($page->y / $yCalc) + 1;
                            $w =  $page->w / $xCalc;
                            $h =  $page->h / $xCalc;
                            $pdf->Image("../../" . $page->srcImg, $x, $y, $w, $h);
                        }
                    }
                }
            }

            $pdf->Output($this->pathPDFOutput, "F");

            return true;
        } catch (Exception $e) {
            error_log($e);
            return false;
        }
    }


    function combinePDFwithQRStatic()
    {
        require_once(__DIR__ . '/../../plugins/fpdf/fpdf.php');
        require_once(__DIR__ .  '/../../plugins/fpdi/src/autoload.php');

        try {
            $pdf = new \setasign\Fpdi\Fpdi();

            //Set the source PDF file
            $pagecount = $pdf->setSourceFile($this->pathPDFSource);

            for ($pageNo = 1; $pageNo <= $pagecount; $pageNo++) {
                // import a page
                $templateId = $pdf->importPage($pageNo);
                // get the size of the imported page
                $size = $pdf->getTemplateSize($templateId);

                // error_log("Size x " . $size[0] . " - Size y " . $size[1]);
                // create a page (landscape or portrait depending on the imported page size)
                if ($size[0] > $size[1]) {
                    $pdf->AddPage('L', array($size[0], $size[1]));
                } else {
                    $pdf->AddPage('P', array($size[0], $size[1]));
                }

                // use the imported page
                $pdf->useTemplate($templateId);

                $pdf->SetFont('Times');
                $pdf->SetXY(5, 5);
            }

            $pdf->SetFont('Times', 'B', 10);

            $pdf->Image($this->QRPath, $size[0] - 58, $size[1] - 61, 25, 25);

            //nama
            $pdf->SetXY($size[0] - 75, $size[1] - 35);
            $pdf->MultiCell(60, 5, $this->name, 0, 'C');
            //nip
            $pdf->SetXY($size[0] - 75, $size[1] - 31);
            $pdf->MultiCell(60, 5, "NIP : " . $this->nip, 0, 'C');

            $pdf->Output($this->pathPDFOutput, "F");

            return true;
        } catch (Exception $e) {
            error_log($e);
            return false;
        }
    }
	
	function combinePDFwithQRStaticNoEmbed()
    {
        require_once(__DIR__ . '/../../plugins/fpdf/fpdf.php');
        require_once(__DIR__ .  '/../../plugins/fpdi/src/autoload.php');

        try {
            $pdf = new \setasign\Fpdi\Fpdi();

            //Set the source PDF file
            $pagecount = $pdf->setSourceFile($this->pathPDFSource);

            for ($pageNo = 1; $pageNo <= $pagecount; $pageNo++) {
                // import a page
                $templateId = $pdf->importPage($pageNo);
                // get the size of the imported page
                $size = $pdf->getTemplateSize($templateId);

                // error_log("Size x " . $size[0] . " - Size y " . $size[1]);
                // create a page (landscape or portrait depending on the imported page size)
                if ($size[0] > $size[1]) {
                    $pdf->AddPage('L', array($size[0], $size[1]));
                } else {
                    $pdf->AddPage('P', array($size[0], $size[1]));
                }

                // use the imported page
                $pdf->useTemplate($templateId);

                $pdf->SetFont('Times');
                $pdf->SetXY(5, 5);
            }

            $pdf->SetFont('Times', 'B', 10);

            // $pdf->Image($this->QRPath, $size[0] - 58, $size[1] - 61, 25, 25);

            //nama
            //$pdf->SetXY($size[0] - 75, $size[1] - 35);
            //$pdf->MultiCell(60, 5, $this->name, 0, 'C');
            //nip
            //$pdf->SetXY($size[0] - 75, $size[1] - 31);
            //$pdf->MultiCell(60, 5, "NIP : " . $this->nip, 0, 'C');

            $pdf->Output($this->pathPDFOutput, "F");

            return true;
        } catch (Exception $e) {
            error_log($e);
            return false;
        }
    }
}
