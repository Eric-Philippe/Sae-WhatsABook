<?php

namespace App\Controller\Admin;

use App\Entity\Author;
use App\Entity\Book;
use App\Entity\Category;
use App\Entity\Loan;
use App\Entity\Member;
use App\Entity\Reservation;
use App\Entity\Suggestion;
use App\Entity\Role;
use App\Repository\LoanRepository;
use App\Repository\MemberRepository;
use App\Repository\ReservationRepository;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class DashboardController extends AbstractDashboardController
{
    // Consturctor
    private $memberRepository;
    private $loanRepository;
    private $reservationRepository;

    public function __construct(MemberRepository $memberRepository, LoanRepository $loanRepository, ReservationRepository $reservationRepository)
    {
        $this->memberRepository = $memberRepository;
        $this->loanRepository = $loanRepository;
        $this->reservationRepository = $reservationRepository;
    }

    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
       $memberCount = $this->memberRepository->count([]);
       $fullLoanCount = $this->loanRepository->count([]);
       // Only get the one that got the returnDate not null
        $notReturned = $this->loanRepository->count(['returnDate' => null]);
        $loanCount = $fullLoanCount - $notReturned;

       $reservationsCount = $this->reservationRepository->count([]);
        $userRole = $this->getUser()->getRoles()[0];
        if ($userRole == 'ROLE_ADMIN') {
            $userRole = 'Responsable Bibliothèque';
        } else {
            $userRole = 'Bibliothécaire';
        }

        return $this->render('admin/dashboard.html.twig', [
            'memberCount' => $memberCount,
            'loanCount' => $loanCount,
            'fullLoanCount' => $fullLoanCount,
            'reservationsCount' => $reservationsCount,
            'userRole' => $userRole
        ]);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle("What's a Book . Bask Office")
            ->setFaviconPath('assets/Logo.png')
            ->setTranslationDomain('fr');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Accueil', 'fa fa-home');

        if ($this->isGranted('ROLE_ADMIN')) {
            yield MenuItem::section('Section bibliotheque');
            yield MenuItem::linkToCrud('Auteurs', 'ffa fa-user', Author::class);
            yield MenuItem::linkToCrud('Livres', 'fa-solid fa-book', Book::class);
            yield MenuItem::linkToCrud('Categories', 'fa-solid fa-list', Category::class);
        }

        /* Section 2 */
        yield MenuItem::section('Configuration interne');
        if ($this->isGranted('ROLE_ADMIN')) {
            yield MenuItem::linkToCrud('Voir les adhérents', 'fa fa-user', Member::class);
        }
        yield MenuItem::linkToCrud('Gérer les emprunts', 'fa-solid fa-bookmark', Loan::class);
        yield MenuItem::linkToCrud('Consulter les réservations', 'fa-solid fa-book-open', Reservation::class);
        yield MenuItem::linkToCrud('Voir les suggestions', 'fa-solid fa-lightbulb', Suggestion::class);
    }
}
