<?php

namespace App\Controller;

use App\Entity\Reader;
use App\Form\ReaderType;
use App\Repository\ReaderRepository;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class ReaderController extends AbstractController
{
    #[Route('/reader', name: 'app_reader')]
    public function index(): Response
    {
        return $this->render('book/index.html.twig', [
            'controller_name' => 'ReaderController',
        ]);
    }
    #[Route('/listReader', name: 'listReader')]
    public function fetchReader(ReaderRepository $repo){
        $reader=$repo->findAll();
        return $this->render('reader/listReader.html.twig',[
            'r'=>$reader]);
    }
  
    #[Route('/addR', name: 'addR')]
    public function new(Request $req, ManagerRegistry $mr)
    {
        $reader = new Reader();
        $form = $this->createForm(ReaderType::class, $reader);
        $form->handleRequest($req);

        if ($form->isSubmitted() ) {
           $em=$mr->getManager();
            $em->persist($reader);
            $em->flush();

            return $this->redirectToRoute('listReader');
        }

        return $this->render('reader/new.html.twig', [
            
            'f'=> $form->createView()
        ]);
    }

   

    #[Route('/updateReader/{id}', name: 'updateReader')]
    public function updateReader(  ManagerRegistry $mr, Request $req, ReaderRepository $repo, $id)
    {    $r=$repo->find($id);
        $form = $this->createForm(ReaderType::class, $r);
        $form->handleRequest($req);

        if ($form->isSubmitted()) {
            $em=$mr->getManager();
            $em->flush();

            return $this->redirectToRoute('listReader' );
        }

        return $this->renderForm('reader/edit.html.twig', [
            
            'f' => $form
        ]);
    }

    
    #[Route('/delete/{id}', name: 'deleteReader')]
    public function delete(Request $request,  ManagerRegistry $mr,$id,ReaderRepository $repo)
    {   $r=$repo->find($id);
        if ($r!=null) {
           $em=$mr->getManager();
            $em->remove($r);
            $em->flush();
            return $this->redirectToRoute('listReader');
        }

        return new Response("error id doesn't exist!");
    }
    #[Route('/show/{id}', name: 'show')]
    public function show(int $id, ReaderRepository $repo): Response
    { $reader=$repo->find($id);

        return $this->render('reader/show.html.twig', [
            'r' => $reader,
        
        ]);
        
    }
}
