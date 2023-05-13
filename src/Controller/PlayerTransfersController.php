<?php

namespace App\Controller;

use App\Entity\Players;
use App\Entity\PlayerTransfers;
use App\Entity\Teams;
use App\Repository\PlayersRepository;
use App\Repository\PlayerTransfersRepository;
use App\Repository\TeamsRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PlayerTransfersController extends AbstractController
{
    #[Route('/player-transfer', name: 'app_player_transfers_index', methods: ['GET'])]
    public function index(Request $request, EntityManagerInterface $entityManager, PlayersRepository $playersRepository): Response
    {
        $teamId = $request->query->get('buying_team_id');
        $team = $entityManager->getRepository(Teams::class)->find($teamId);

        // pagination
        $currentPage = $request->query->getInt('page', 1);
        $limit = 2;
        $players = $playersRepository->paginate($currentPage, $limit, $teamId, 'player_transfers'); // Returns $limit per page
        $totalPlayers = $playersRepository->countPlayersExcludingTeam($teamId);
        $maxPages = ceil($totalPlayers / $limit);
        // end of pagination

        return $this->render('player_transfers/index.html.twig', [
            'team' => $team,
            'players' => $players,
            'max_pages' => $maxPages,
            'limit' => $limit
        ]);
    }

    #[Route('/player-transfer/buy', name: 'app_player_transfers_buy', methods: ['GET'])]
    public function buy(Request $request, EntityManagerInterface $entityManager, PlayersRepository $playersRepository): Response
    {
        $teamId = $request->query->get('buying_team_id');
        $playerId = $request->query->get('player_id');

        $team = $entityManager->getRepository(Teams::class)->find($teamId);
        $player = $playersRepository->find($playerId);

        return $this->render('player_transfers/buy.html.twig', [
            'team' => $team,
            'player' => $player
        ]);
    }

    #[Route('/player-transfer/buy-now', name: 'app_player_transfers_buy_now', methods: ['GET'])]
    public function buyPlayer(Request $request, TeamsRepository $teamsRepository, PlayersRepository $playersRepository, PlayerTransfersRepository $playerTransfersRepository): Response
    {
        $teamId = $request->query->get('buying_team_id');
        $playerId = $request->query->get('player_id');

        $player = $playersRepository->find($playerId);
        $buyingTeam = $teamsRepository->find($teamId);
        $sellingTeam = $teamsRepository->find($player->getTeam()->getId());

        // inserting new data to player transfers table
        $playerTransfers = new PlayerTransfers();
        $playerTransfers->setPlayer($player);
        $playerTransfers->setBuyingTeam($buyingTeam);
        $playerTransfers->setSellingTeam($sellingTeam);
        $playerTransfers->setTransferAmount($player->getTransferFee());
        $playerTransfers->setTransferDate(new DateTimeImmutable());
        $playerTransfersRepository->save($playerTransfers, true);

        // updating player data after purchase
        $transferedPlayer = $player;
        $transferedPlayer->setTeam($buyingTeam);
        $transferedPlayer->setIsOpenForTransfer(0); // closed to transfer
        $playersRepository->save($transferedPlayer, true);

        // updating money balance of buying team
        $newMoneyBalance = $buyingTeam->getMoneyBalance() - $player->getTransferFee();
        if ($newMoneyBalance < 0) {
            return $this->redirectToRoute('app_player_transfers_buy', ['player_id' => $player->getId(), 'buying_team_id' => $buyingTeam->getId()], Response::HTTP_SEE_OTHER);
        }
        $buyingTeam->setMoneyBalance($newMoneyBalance);
        $teamsRepository->save($buyingTeam, true);

        return $this->render('player_transfers/success.html.twig', [
            'team' => $buyingTeam,
            'player' => $player
        ]);
    }
}
