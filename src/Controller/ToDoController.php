<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ToDoController extends AbstractController
{
    #[Route('/todo', name: 'app_todo')]
    public function index(Request $request): Response
    {
        $session = $request->getSession();
        // Afficher le tableau de ToDo
        if(!$session->has('todos')) {
            $todos = [
                'achat' => 'acheter une clé usb',
                'cours' => 'Finaliser mon cours',
                'correction' => 'Corriger mes examens'
            ];
            $session->set('todos', $todos);
            $this->addFlash(
               'info',
               "La liste des todos vient d'être initialisé"
            );
        }
        // si j'ai mon tableau de todo dans ma session, je ne fais que l'afficher

        return $this->render('to_do/index.html.twig');
    }

    #[Route('/todo/add/{name}/{content}', name: 'todo_add')]
    public function addTodo(Request $request, $name, $content) {
        $session = $request->getSession();
        // vérifier si j'ai une session de todo
        if($session->has('todos')) {
            $todos = $session->get('$todos');
            if(isset($todos[$name])) {
                $this->addFlash(
                    'error',
                    "Le todo d'id $name existe déjà dans la liste"
                 );
            } else {
                $todos[$name] = $content;
                $session->set('todos', $todos);
                $this->addFlash(
                    'success',
                    "Le todo d'id $name a été ajouté avec succès dans la liste"
                );
            }
        } else {
            $this->addFlash(
               'error',
               "La liste des todos n'est pas encore initialisée"
            );
        }
        return $this->redirectToRoute('app_todo');
    }
    #[Route(
        'multi/{entier1<\d+>}/{entier2<\d+>}',
        name: 'multiplication',
    )]
    public function multiplication($entier1, $entier2) {
        $resultat = $entier1 * $entier2;
        return new Response("<h1>$resultat</h1>");
    }
}
