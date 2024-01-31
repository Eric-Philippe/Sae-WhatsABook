<?php

namespace App\Controller\Admin;

use App\Entity\Author;
use App\Entity\Book;
use App\Entity\Category;
use App\Entity\Loan;
use App\Entity\Member;
use App\Entity\Reservation;
use App\Entity\Role;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        return $this->render('admin/dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Server');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');

        /* Section 1 */
        yield MenuItem::section('Bibliotheque');
        yield MenuItem::linkToCrud('Author', 'ffa fa-user', Author::class);
        yield MenuItem::linkToCrud('Book', 'fa-solid fa-book', Book::class);
        yield MenuItem::linkToCrud('Category', 'fa-solid fa-list', Category::class);

        /* Section 2 */
        yield MenuItem::section('Configuration');
        yield MenuItem::linkToCrud('Member', 'fa fa-user', Member::class);
        yield MenuItem::linkToCrud('Role', 'fa fa-user', Role::class);
        yield MenuItem::linkToCrud('Loan', 'fa-solid fa-bookmark', Loan::class);
        yield MenuItem::linkToCrud('Reservation', 'fa-solid fa-book-open', Reservation::class);
        
    }
}