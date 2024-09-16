<?php

namespace App\Controllers;

use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\CapabilityProfile;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;
use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;
use Mike42\Escpos\Printer;
use Mike42\Escpos\EscposImage;

class Home extends BaseController
{
    public function index(): string
    {
        return view('index');
    }
    public function print()
    {
        $formData = $this->request->getJSON();


        $profile = CapabilityProfile::load("simple");
        // $connector = new NetworkPrintConnector("/dev/rfcomm0");
        $connector = new FilePrintConnector("/dev/rfcomm0");
        $printer = new Printer($connector);
        // Load the image
        $imgLogo = EscposImage::load("assets/boga.png", false);
        $imgTwitter = EscposImage::load("assets/twitter.png", false);
        $imgInstagram = EscposImage::load("assets/ig.png", false);
        $imgFacebook = EscposImage::load("assets/fb.png", false);
        $imgWebsite = EscposImage::load("assets/website.png", false);


        // $printer->setUnderline(false);
        // $printer->setJustification(Printer::JUSTIFY_CENTER); // Center text
        // $printer->bitImage($imgLogo);
        // $printer->text("\n");
        // $printer->text("\n");
        // $printer->text("\n");
        // $printer->setEmphasis(true); // Enable bold text
        // $printer->text("Foodcourt UMB Boga\n");
        // $printer->setEmphasis(false);
        // $printer->text("Gedung Plaza Bintang, Kampus Terpadu Universitas Muhammadiyah Yogyakarta. Jl. Brawijaya, Tamantirto, Kab. Bantul, DI Yogyakarta, 55183\n 081327211306 \n\n");

        // $printer->text(str_repeat('-', 32) . "\n");
        // $printer->setEmphasis(true);
        // $printer->text("Queue No:" . $formData->queue . "\n");
        // $printer->setEmphasis(false);
        // $printer->text(str_repeat('-', 32) . "\n");
        // $printer->text("\n");
        // $this->justifyLeftRight($printer, date_format(date_create($formData->date), "d M Y"), date_format(date_create($formData->date), "H:i"), 32);
        // $this->justifyLeftRight($printer, 'Receipt Number', $formData->receipt, 32);
        // $this->justifyLeftRight($printer, 'Order ID', $formData->order_id, 32);
        // $this->justifyLeftRight($printer, 'Collected By', $formData->collected_by, 32);
        // $printer->text(str_repeat('-', 32) . "\n");
        // $printer->setJustification(Printer::JUSTIFY_LEFT);
        // $total = 0;
        // foreach ($formData->items as $item) {
        //     $printer->setEmphasis(true);
        //     $printer->text($item->item . "\n");
        //     $printer->setEmphasis(false);
        //     $this->justifyLeftRight($printer, $item->quantity . 'X   ' . '@' . number_format($item->price, 0, ',', '.'), number_format($item->quantity * $item->price, 0, ',', '.'), 32);
        //     $total += $item->quantity * $item->price;
        // }
        // $printer->text(str_repeat('-', 32) . "\n");
        // $this->justifyLeftRight($printer, 'Subtotal', 'Rp ' . number_format($total, 0, ',', '.'), 32);
        // $printer->text(str_repeat('-', 32) . "\n");
        // $printer->setEmphasis(true);
        // $this->justifyLeftRight($printer, 'Total', 'Rp ' . number_format($total, 0, ',', '.'), 32);
        // $printer->setEmphasis(false);
        // $printer->text(str_repeat('-', 32) . "\n");
        // $this->justifyLeftRight($printer, 'Cash', 'Rp ' . number_format($formData->cash, 0, ',', '.'), 32);
        // $this->justifyLeftRight($printer, 'Change', 'Rp ' . number_format($formData->cash - $total, 0, ',', '.'), 32);
        // $printer->text(str_repeat('-', 32) . "\n");

        $printer->setFont(Printer::FONT_C);
        $spaces = str_repeat(' ', 5);
        $printer->setJustification(Printer::JUSTIFY_LEFT);
        // $printer->bitImage($imgWebsite);
        // $printer->bitImage($imgFacebook);
        $inlineImageWebsite = $this->inlineImage2($imgWebsite, $connector);
        $inlineImageWebsite;
        $printer->text($spaces . "www.umb-boga.com\n");

        // $inlineImageFacebook = $this->inlineImage($imgFacebook, $connector);
        // $inlineImageFacebook;
        // $printer->text($spaces . "umb.boga\n");

        // $inlineImageTwitter = $this->inlineImage($imgTwitter, $connector);
        // $inlineImageTwitter;
        // $printer->text($spaces . "umb_boga\n");

        // $inlineImageInstagram = $this->inlineImage($imgInstagram, $connector);
        // $inlineImageInstagram;
        // $printer->text($spaces . "umb_boga&cafe_1912_\n");

        // $printer->bitImage($imgInstagram);
        $printer->setFont(Printer::FONT_A);
        $printer->text(str_repeat('-', 32) . "\n");

        $printer->setEmphasis(true);
        $printer->text("Notes \n");
        $printer->text("Beyond The Taste\n");
        $printer->text("Kritik & Saran : 081228288032 \n");
        $printer->text("\n");
        $printer->cut();
        $printer->close();

        // return view('index');
    }
    function justifyLeftRight($printer, $leftText, $rightText, $lineWidth)
    {
        // Calculate the number of spaces to add between left and right text
        $leftTextLength = strlen($leftText);
        $rightTextLength = strlen($rightText);

        // Ensure that the total length fits within the line width
        if ($leftTextLength + $rightTextLength > $lineWidth) {
            // If it exceeds, cut the right text
            $rightText = substr($rightText, 0, $lineWidth - $leftTextLength);
        }

        // Calculate the number of spaces to pad
        $spaces = $lineWidth - $leftTextLength - $rightTextLength;

        // Combine left text, spaces, and right text
        $line = $leftText . str_repeat(' ', $spaces) . $rightText;

        // Print the line
        $printer->text($line . "\n");
    }

    function drawTextWithNegativeMargin($imagePath, $text)
    {
        // Load the existing image
        $img = imagecreatefrompng($imagePath);

        // Get the dimensions of the image
        $imgWidth = imagesx($img);
        $imgHeight = imagesy($img);

        // Create a new blank image with the same dimensions
        $combinedImg = imagecreatetruecolor($imgWidth, $imgHeight);

        // Set a white background
        $white = imagecolorallocate($combinedImg, 255, 255, 255);
        imagefill($combinedImg, 0, 0, $white);

        // Copy the original image onto the combined image
        imagecopy($combinedImg, $img, 0, 0, 0, 0, $imgWidth, $imgHeight);

        // Set the text color to black
        $black = imagecolorallocate($combinedImg, 0, 0, 0);

        // Define built-in font and simulate negative margin
        $font = 5; // Size 5 (largest built-in font)
        $textX = 10; // X position for the text
        $textY = 50; // Y position for the text

        // Apply negative margin (move text up)
        $negativeMargin = -10; // Move text 10 pixels upwards
        $adjustedTextY = $textY + $negativeMargin;

        // Draw the text with adjusted Y position
        imagestring($combinedImg, $font, $textX, $adjustedTextY, $text, $black);

        // Output or save the combined image
        header('Content-Type: image/png');
        imagepng($combinedImg);

        // Free up memory
        imagedestroy($img);
        imagedestroy($combinedImg);
    }

    public function inlineImage(EscposImage $img, $printer)
    {
        $connector = $printer;
        $size = Printer::IMG_DEFAULT;
        $highDensityVertical = ($size & Printer::IMG_DOUBLE_HEIGHT) != Printer::IMG_DOUBLE_HEIGHT;
        $highDensityHorizontal = ($size & Printer::IMG_DOUBLE_WIDTH) != Printer::IMG_DOUBLE_WIDTH;
        // Header and density code (0, 1, 32, 33) re-used for every line
        $densityCode = ($highDensityHorizontal ? 1 : 0) + ($highDensityVertical ? 32 : 0);
        $colFormatData = $img->toColumnFormat($highDensityVertical);
        $header = Printer::dataHeader(array($img->getWidth()), true);
        foreach ($colFormatData as $line) {
            // Print each line, double density etc for printing are set here also
            $connector->write("\x1B" . "*" . chr($densityCode) . $header . $line);
            break;
        }
    }

    public function inlineImage2(EscposImage $img, $connector, $size = Printer::IMG_DEFAULT)
    {
        $connector = $connector;
        $highDensityVertical = ! (($size & Printer::IMG_DOUBLE_HEIGHT) == Printer::IMG_DOUBLE_HEIGHT);
        $highDensityHorizontal = ! (($size & Printer::IMG_DOUBLE_WIDTH) == Printer::IMG_DOUBLE_WIDTH);
        // Header and density code (0, 1, 32, 33) re-used for every line
        $densityCode = ($highDensityHorizontal ? 1 : 0) + ($highDensityVertical ? 32 : 0);
        $colFormatData = $img->toColumnFormat($highDensityVertical);
        $header = Printer::dataHeader(array($img->getWidth()), true);
        foreach ($colFormatData as $line) {
            // Print each line, double density etc for printing are set here also
            $connector->write(Printer::ESC . "*" . chr($densityCode) . $header . $line);
            break;
        }
    }
}
