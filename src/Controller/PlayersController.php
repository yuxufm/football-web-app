<?php

namespace App\Controller;

use App\Entity\Players;
use App\Entity\Teams;
use App\Form\PlayersType;
use App\Repository\PlayersRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/players')]
class PlayersController extends AbstractController
{
    #[Route('/', name: 'app_players_index', methods: ['GET'])]
    public function index(Request $request, EntityManagerInterface $entityManager, PlayersRepository $playersRepository): Response
    {
        $teamId = $request->query->get('team_id');
        $team = $entityManager->getRepository(Teams::class)->find($teamId);

        // pagination
        $currentPage = $request->query->getInt('page', 1);
        $limit = 2;
        $players = $playersRepository->paginate($currentPage, $limit, $teamId); // Returns $limit per page
        $totalPlayers = $playersRepository->count([]);
        $maxPages = ceil($totalPlayers / $limit);
        // end of pagination

        return $this->render('players/index.html.twig', [
            'team' => $team,
            'players' => $players,
            'max_pages' => $maxPages,
            'limit' => $limit
        ]);
    }

    #[Route('/new', name: 'app_players_new', methods: ['GET', 'POST'])]
    public function new(Request $request, PlayersRepository $playersRepository, EntityManagerInterface $entityManager): Response
    {
        $player = new Players();
        $form = $this->createForm(PlayersType::class, $player);
        $form->handleRequest($request);

        $teamId = $request->query->get('team_id');
        $team = $entityManager->getRepository(Teams::class)->find($teamId);

        if ($form->isSubmitted() && $form->isValid()) {
            $player->setIsOpenForTransfer($request->request->get('is_open_for_transfer'));
            $player->setTeam($team);
            $playersRepository->save($player, true);

            return $this->redirectToRoute('app_players_index', ['team_id' => $teamId], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('players/new.html.twig', [
            'team' => $team,
            'player' => $player,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_players_show', methods: ['GET'])]
    public function show(Players $player): Response
    {
        return $this->render('players/show.html.twig', [
            'player' => $player,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_players_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Players $player, PlayersRepository $playersRepository, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PlayersType::class, $player);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $playersRepository->save($player, true);

            return $this->redirectToRoute('app_players_index', ['team_id' => $player->getTeam()->getId()], Response::HTTP_SEE_OTHER);
        }

        $teamId = $request->query->get('team_id');
        $team = $entityManager->getRepository(Teams::class)->find($teamId);

        return $this->renderForm('players/edit.html.twig', [
            'team' => $team,
            'player' => $player,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_players_delete', methods: ['POST'])]
    public function delete(Request $request, Players $player, PlayersRepository $playersRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $player->getId(), $request->request->get('_token'))) {
            $playersRepository->remove($player, true);
        }

        return $this->redirectToRoute('app_players_index', ['team_id' => $player->getTeam()->getId()], Response::HTTP_SEE_OTHER);
    }
}
