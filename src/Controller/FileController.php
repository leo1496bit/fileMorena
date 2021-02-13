<?php

namespace App\Controller;

use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FileController extends AbstractController
{
    /**
     * @Route("/", name="file")
     */
    public function index(Request $request): Response
    {
        $error=0;
        if($request->files->count()>0 ){
             $file=$request->files->get("myfile");
             if($file==null){
                return $this->render('file/index.html.twig', [
                    'nom' => 'rien',
                    'error'=>$error,
                ]);
             }
              $nommage=fopen("../src/Repository/nommage.txt",'r+');
              $nom=fread($nommage,filesize('../src/Repository/nommage.txt'));
              $type=pathinfo($file->getClientOriginalName(),PATHINFO_EXTENSION);
              if($type=="jpg" || $type=="JPEG" || $type=="gif" || $type=="png"){
                try{
                    $file->move('images/','image'.$nom.'.jpg');
                rewind($nommage);
                $nom++;
                fwrite($nommage,$nom);
                }  
                catch(Exception $e) {
                    $error=1;
                }
                
              }
              else{
                  $error=1;
              }

              fclose($nommage);
             return $this->render('file/index.html.twig', [
                'nom' => $nom,
                'error'=>$error,

            ]);
        }
        return $this->render('file/index.html.twig', [
            'nom' => 'rien',
            'error'=>$error,
        ]);
    }
}
