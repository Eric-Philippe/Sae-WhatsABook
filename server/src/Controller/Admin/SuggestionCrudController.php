<?php

namespace App\Controller\Admin;

use App\Entity\Suggestion;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class SuggestionCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Suggestion::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Suggestion')
            ->setEntityLabelInPlural('Suggestions')
            ->setSearchFields(['title', 'description'])
            ->setPaginatorPageSize(20)
            ;
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add('title')
            ->add('editor')
            ;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
          TextField::new('title')
            ->setLabel('Titre'),
            TextField::new('editor')
            ->setLabel('Editeur'),
            TextField::new('authors')
            ->setLabel('Auteurs'),
            DateField::new('releaseDate')
            ->setLabel('Date de parution'),
            TextEditorField::new('description')
            ->setLabel('Description'),
            TextEditorField::new('details')
            ->setLabel('Détails'),
        ];
    }

    public function configureActions(Actions $actions): Actions
    {
        $acceptSugg = Action::new('acceptSugg', 'Accepter la suggestion')
            ->linkToCrudAction('acceptSugg');
        $refuseSugg = Action::new('refuseSugg', 'Refuser la suggestion')
            ->linkToCrudAction('refuseSugg');
        

        return $actions
            ->add(Crud::PAGE_INDEX, $acceptSugg)
            ->add(Crud::PAGE_DETAIL, $acceptSugg)
            ->add(Crud::PAGE_INDEX, $refuseSugg)
            ->add(Crud::PAGE_DETAIL, $refuseSugg)
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            // Remove the edit part 
            ->disable(Action::EDIT)
            ->disable(Action::NEW)
            ;
    }
}
