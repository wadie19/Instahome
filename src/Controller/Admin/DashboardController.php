<?php

namespace App\Controller\Admin;

use App\Entity\Categories;
use App\Entity\Articles;
use App\Entity\City;
use App\Entity\Users;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;

class DashboardController extends AbstractDashboardController
{   
    public function __construct(
        private AdminUrlGenerator $adminUrlGenerator
        ){
    }
    #[Route('/admindash', name: 'admindash')]
    public function index(): Response
    {
        $url = $this->adminUrlGenerator
            ->setController(ArticlesCrudController::class)
            ->generateUrl();

        return $this->redirect($url);

    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Instahome');
    }

    public function configureMenuItems(): iterable
    {
    
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');

        yield MenuItem::section('MAISON&TERRAIN');
        yield MenuItem::subMenu('Articles', 'fa fa-bars')->setSubItems([
            MenuItem::linkToCrud('Create Article', 'fas fa-plus', Articles::class)->setAction(Crud::PAGE_NEW),
            MenuItem::linkToCrud('Show Articles', 'fas fa-eye', Articles::class)
        ]);
        yield MenuItem::subMenu('Categories', 'fa fa-bars')->setSubItems([
            MenuItem::linkToCrud('Add Category', 'fas fa-plus', Categories::class)->setAction(Crud::PAGE_NEW),
            MenuItem::linkToCrud('Show All Categories', 'fas fa-eye', Categories::class)
        ]);
        yield MenuItem::subMenu('Cities', 'fa fa-bars')->setSubItems([
            MenuItem::linkToCrud('Add City', 'fas fa-plus', City::class)->setAction(Crud::PAGE_NEW),
            MenuItem::linkToCrud('Show All Cities', 'fas fa-eye', City::class)
        ]);

        yield MenuItem::subMenu('Utilisateurs', 'fa fa-user')->setSubItems([
            MenuItem::linkToCrud('Show All Users', 'fas fa-eye', Users::class)
        ]);
        
       
    }
}
