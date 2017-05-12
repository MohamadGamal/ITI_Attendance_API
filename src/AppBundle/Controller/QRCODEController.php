<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\LabelAlignment;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Writer\SvgWriter;
use Symfony\Component\HttpFoundation\Response;
use JMS\Serializer\SerializationContext;
class QRCODEController extends Controller
{
    /**
     * @Route("/getQR")
     */
    public function getQRAction()
    {

$limit=30;
// Create a QR code
$qrCode = new QrCode('Testing strings and other shmulk'.time());
$qrCode->setSize(300);

// Set advanced options
// var_dump(__DIR__.'/../Resources/QRrelated/logo.png');
// die();
$qrCode
    ->setMargin(10)
    ->setEncoding('UTF-8')
    ->setErrorCorrectionLevel(ErrorCorrectionLevel::HIGH)
->setForegroundColor(['r' => 0, 'g' => 0, 'b' => 0])
    ->setBackgroundColor(['r' => 255, 'g' => 255, 'b' => 255])
    ->setLabel("Scan the code in $limit secounds !")
 //  ->setLogoPath(__DIR__.'/../Resources/QRrelated/logo.png')
    ->setLogoSize(150)
    ->setValidateResult(true)
;

// Output the QR code
header('Content-Type: '.$qrCode->getContentType(PngWriter::class));
//$base64image=  base64_encode ( 
    echo $qrCode->writeString(PngWriter::class);
    die();//
 //   );
//$fullimage='data:image/png;base64,'.$base64image;


  //       $response = new Response($this->serialize(['type'=>"QRcode",'code'=>1,'image'=>$fullimage]), Response::HTTP_CREATED);
     
    //    return $this->setBaseHeaders($response);
//         die();
// // Save it to a file (guesses writer by file extension)
// $qrCode->writeFile(__DIR__.'/qrcode.png');

// // Create a response object
// $response = new Response(
//     $qrCode->writeString(SvgWriter::class),
//     Response::HTTP_OK,
//     ['Content-Type' => $qrCode->getContentType(SvgWriter::class)])
// ;

// // Work via the writer
// $writer = new PngWriter($qrCode);
// $pngData = $writer->writeString();



    }





    
        private function serialize($data)
    {
        $context = new SerializationContext();
        $context->setSerializeNull(true);
        return $this->get('jms_serializer')->serialize($data, 'json', $context);
    }

       private function setBaseHeaders(Response $response)
    {
        $response->headers->set('Content-Type', 'application/json');
        $response->headers->set('Access-Control-Allow-Origin', '*');
        return $response;
    }

}
