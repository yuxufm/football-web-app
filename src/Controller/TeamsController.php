<?php

namespace App\Controller;

use App\Entity\Teams;
use App\Form\TeamsType;
use App\Repository\TeamsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/teams')]
class TeamsController extends AbstractController
{
    #[Route('/', name: 'app_teams_index', methods: ['GET'])]
    public function index(Request $request, TeamsRepository $teamsRepository): Response
    {
        // pagination
        $currentPage = $request->query->getInt('page', 1);
        $limit = 5;
        $teams = $teamsRepository->paginate($currentPage, $limit); // Returns $limit per page
        $totalTeams = $teamsRepository->count([]);
        $maxPages = ceil($totalTeams / $limit);
        // end of pagination

        return $this->render('teams/index.html.twig', [
            'teams' => $teams,
            'max_pages' => $maxPages,
            'limit' => $limit
        ]);
    }

    #[Route('/new', name: 'app_teams_new', methods: ['GET', 'POST'])]
    public function new(Request $request, TeamsRepository $teamsRepository): Response
    {
        $team = new Teams();
        $form = $this->createForm(TeamsType::class, $team);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $teamsRepository->save($team, true);

            return $this->redirectToRoute('app_teams_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('teams/new.html.twig', [
            'team' => $team,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_teams_show', methods: ['GET'])]
    public function show(Teams $team): Response
    {
        return $this->render('teams/show.html.twig', [
            'team' => $team,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_teams_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Teams $team, TeamsRepository $teamsRepository): Response
    {
        $form = $this->createForm(TeamsType::class, $team);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $teamsRepository->save($team, true);

            return $this->redirectToRoute('app_teams_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('teams/edit.html.twig', [
            'team' => $team,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_teams_delete', methods: ['POST'])]
    public function delete(Request $request, Teams $team, TeamsRepository $teamsRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $team->getId(), $request->request->get('_token'))) {
            $teamsRepository->remove($team, true);
        }

        return $this->redirectToRoute('app_teams_index', [], Response::HTTP_SEE_OTHER);
    }
}
