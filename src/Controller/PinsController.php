<?php

namespace App\Controller;

use App\Entity\Pin;
use App\Repository\PinRepository;
use App\Form\PinType;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Id;

Request::enableHttpMethodParameterOverride();
class PinsController extends AbstractController
{
    #[Route('/', name: 'app_home', methods: "GET")]
    public function index(PinRepository $pinRepository): Response
    {
        $pins = $pinRepository->findBy([], ['createdAt' => 'DESC']);
        return $this->render('pins/index.html.twig', compact('pins'));
    }

    #[Route('/pins/{id<[0-9]+>}', name: 'app_pins_show', methods : "GET")]
    public function show(Pin $pin): Response
    {
        return $this->render('pins/show.html.twig', compact('pin'));
    }

    #[Route('/pins/create', name: 'app_pins_create', methods:"GET|POST")]
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        $pin = new Pin;

        $form = $this->createForm(PinType::class, $pin);

        $form->handleRequest($request);


        if($form->isSubmitted() && $form->isValid()) {
            $pin = $form->getData();
            $em->persist($pin);
            $em->flush();
            
            return $this->redirectToRoute('app_home');
        }
        return $this->render('pins/create.html.twig', [
            'form' => $form -> createView()
        ]);
    }

    #[Route('/pins/{id<[0-9]+>}/edit', name: 'app_pins_edit', methods:"GET|POST")]
    public function edit(Request $request, Pin $pin, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(PinType::class, $pin);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            
            return $this->redirectToRoute('app_home');
        }

        return $this->render('pins/edit.html.twig', [
            'pin' => $pin,
            'form' => $form->createView()
        ]);
    }

    #[Route('/pins/{id<[0-9]+>}/delete', name: 'app_pins_delete', methods:"DELETE")]
    public function delete(Request $request, Pin $pin, EntityManagerInterface $em): Response
    {
        if($this->isCsrfTokenValid('pin_deletion_'. $pin->getId(), $request->request->get('csrf_token'))) {
            $em->remove($pin);
            $em->flush();
        }

        return $this->redirectToRoute('app_home');
    }
}
