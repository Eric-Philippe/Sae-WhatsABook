<?php

namespace App\Controller\Admin;

use App\Entity\Author;
use App\Entity\Book;
use App\Utils\Utils;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\UrlField;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')]
class AuthorCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Author::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Auteur')
            ->setEntityLabelInPlural('Auteurs')
            ->setSearchFields(['id', 'lastname', 'firstname'])
            ->setDefaultSort(['id' => 'DESC'])
            ->setPaginatorPageSize(30)
            ;
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add('lastname')
            ->add('firstname')
            ->add('birthDate')
            ->add('deathDate')
            ->add('nationality');
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('lastname', 'Nom'),
            TextField::new('firstname', 'Prénom'),
            DateField::new('birthDate', 'Date de naissance')->setFormat('dd-MM-yyyy'),
            DateField::new('deathDate', 'Date de décès')->setFormat('dd-MM-yyyy')->hideOnIndex(),
            TextField::new('nationality', 'Nationalité')
                ->hideOnIndex()
                ,
            TextField::new('description')
            ->setMaxLength(20)
            ,
            AssociationField::new('books')
            ->setLabel('Livre(s) écrit(s)')
            ->onlyOnIndex()
            ->formatValue(function ($value, $entity) {
                return count($entity->getBooks());
            }),
            TextEditorField::new('description', 'Description')->hideOnIndex(),
        ];
    }

   public function createEntity($entityFqcn) {
        $author = new Author();
        $author->setId(Utils::generateUuid());
        return $author;
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linktoDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('Auteurs', 'fas fa-user', Author::class);
        yield MenuItem::linkToCrud('Livres', 'fas fa-book', Book::class);
    }
}
