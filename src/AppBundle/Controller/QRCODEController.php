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
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use AppBundle\Entity\Track;

/**
 * QR controller.
 *
 * @Route("qr")
 */

class QRCODEController extends Controller
{


       /**
     * Finds and displays a user entity.
     *
     * @Route("/time", name="qr_show_time")
     * @Method("GET")
     */
    public function lauraAction()
    {
          $now=new \Datetime();
          var_dump($now);
          die();
        // $em = $this->getDoctrine()->getManager();

    }

       /**
     * Finds and displays a user entity.
     *
     * @Route("/", name="qr_show")
     * @Method("GET")
     */
    public function generateAction()
    {
         $em = $this->getDoctrine()->getManager();

        $tracks = $em->getRepository('AppBundle:Track')->findAll();
        $cache = new FilesystemAdapter();
        foreach ($tracks as $track) {
            $qrid= $cache->getItem('qrid.'.$track->getId());
            $qrcode= $cache->getItem('qrcode.'.$track->getCode());

            $response = new Response($this->serialize(['type'=>"sucess",'code'=>1,'data'=>$track->getCode()]), Response::HTTP_CREATED);
              return $this->setBaseHeaders($response);
        }
        $cache = new FilesystemAdapter();
        $numProducts = $cache->getItem('stats.num_products');
       
        $numProducts->set(["youll be fine"=>"kikis","BIG"=>'soft','time'=>time()]);
        $cache->save($numProducts);

        $response = new Response($this->serialize(['type'=>"sucess",'code'=>1,'data'=>$numProducts]), Response::HTTP_CREATED);
              return $this->setBaseHeaders($response);
    }
        /**
     * Finds and displays a user entity.
     *
     * @Route("/{id}", name="qr_show2")
     * @Method("POST")
     */
    public function show2Action(Track $track)
    {
        $cache = new FilesystemAdapter();
        $numProducts = $cache->getItem('stats.num_products');
        if ($numProducts->isHit()) {
            $total = $numProducts->get();
        }


        $response = new Response($this->serialize(['type'=>"sucess",'code'=>1,'data'=>$total]), Response::HTTP_CREATED);
              return $this->setBaseHeaders($response);
    }
    /**
     * @Route("/getQR/{id}")
     * @Method("GET")
     */
    public function getQRAction(Track $track)
    {
        

        $cache = new FilesystemAdapter();
        $refreshinterval=30;
        $mins=100;
        $now=new \Datetime();
        $start=new \Datetime('7:00:00');
        $end=clone $start;
        $end->add(new \Dateinterval('PT'.$mins.'M'));
      
        if ($now->getTimestamp()>=$start->getTimestamp() && $now->getTimestamp()<=$end->getTimestamp()) {
            $trackqrcode = $cache->getItem('qrcode.'.$track->getId());
            if (!$trackqrcode->isHit()) {
                $calc=( $now->getTimestamp()-$start->getTimestamp() ) ;
                $calc=$calc %$refreshinterval;
                $dateofbeg=clone $now;
                $dateofbeg->sub(new \Dateinterval('PT'.$calc.'S'));
                
                $currtrackcode=["time"=>$dateofbeg,"code"=>'abc'.rand(),"refresh_after"=>$refreshinterval-$calc];
                //  var_dump( $currtrackcode);
                //   die();
                $trackqrcode->set($currtrackcode);
                $cache->save($trackqrcode);
            } else {
                 $currtrackcode = $trackqrcode->get();
                //  $nowmin=$now->getTimestamp()/(1000);
                //  $starmin=$start->getTimestamp()/(1000);
                //  $endmin=$end->getTimestamp()/(1000);
                 $calc=( $now->getTimestamp()-$start->getTimestamp() ) ;
                $calc=$calc %$refreshinterval;
                $dateofbeg=clone $now;
                $dateofbeg->sub(new \Dateinterval('PT'.$calc.'S'));
               
                if ($now->getTimestamp()-$currtrackcode["time"]->getTimestamp() >=$refreshinterval) {
                    $currtrackcode=["time"=>$dateofbeg,"code"=>'abc'.rand(),"refresh_after"=>$refreshinterval-$calc];
                }

                $currtrackcode=["time"=>$dateofbeg,"code"=>$currtrackcode['code'],"refresh_after"=>$refreshinterval-$calc];
                $trackqrcode->set($currtrackcode);
                $cache->save($trackqrcode);
            }

// Create a QR code
            $qrCode = new QrCode($currtrackcode['code']);
            $qrCode->setSize(300);


            $qrCode
            ->setMargin(10)
            ->setEncoding('UTF-8')
            ->setErrorCorrectionLevel(ErrorCorrectionLevel::HIGH)
            ->setForegroundColor(['r' => 0, 'g' => 0, 'b' => 0])
            ->setBackgroundColor(['r' => 255, 'g' => 255, 'b' => 255])
            ->setLabel("Scan the code !")
 //  ->setLogoPath(__DIR__.'/../Resources/QRrelated/logo.png')
            ->setLogoSize(150)
            ->setValidateResult(true)
            ;
            $base64image=  base64_encode (
            $qrCode->writeString(PngWriter::class)
            );
            $fullimage='data:image/png;base64,'.$base64image;

            $response = new Response($this->serialize(['type'=>"sucess",'code'=>$currtrackcode['code'],'image'=>$fullimage,"expires"=>$currtrackcode['refresh_after']]), Response::HTTP_CREATED);
        } else {
            
            $response = new Response($this->serialize(['type'=>"error",'code'=>2,'image'=>'wrong time']), Response::HTTP_CREATED);
        }
        return $this->setBaseHeaders($response);
//if
//$response = new Response($this->serialize(['type'=>"QRcode",'code'=>1,'image'=>$fullimage]), Response::HTTP_CREATED);
  
        $cache = new FilesystemAdapter();
        $numProducts = $cache->getItem('stats.num_products');
        if ($numProducts->isHit()) {
            $total = $numProducts->get();
        }

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
        ->setLabel("Scan the code in $limit seconds !")
 //  ->setLogoPath(__DIR__.'/../Resources/QRrelated/logo.png')
        ->setLogoSize(150)
        ->setValidateResult(true)
        ;

// // Output the QR code
// header('Content-Type: '.$qrCode->getContentType(PngWriter::class));
        $base64image=  base64_encode (
        $qrCode->writeString(PngWriter::class)
//    die();//
        );
        $fullimage='data:image/png;base64,'.$base64image;


         $response = new Response($this->serialize(['type'=>"QRcode",'code'=>1,'image'=>$fullimage]), Response::HTTP_CREATED);
     
        return $this->setBaseHeaders($response);
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
    private function generateRandomQRcode()
    {

        return rand(1000000)."abcdefgh"[rand(7)].time();
    }
}
