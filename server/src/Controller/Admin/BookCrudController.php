<?php

namespace App\Controller\Admin;

use App\Entity\Book;
use App\Utils\Utils;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Query\Filter\SQLFilter;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\UrlField;

class BookCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Book::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm()->hideOnIndex(),
            TextField::new('title'),
            TextEditorField::new('summary'),
            DateField::new('releaseDate'),
            ChoiceField::new('language')
                ->setChoices([
                    'Anglais' => 'Anglais',
                    'Français' => 'Français',
                    'Espagnol' => 'Espagnol',
                    'Italien' => 'Italien',
                    'Allemand' => 'Allemand',
                    'Portugais' => 'Portugais',
                    'Néerlandais' => 'Néerlandais',
                    'Suédois' => 'Suédois',
                    'Danois' => 'Danois',
                    'Arabe' => 'Arabe',
                    'Russe' => 'Russe',
                    'Japonais' => 'Japonais',
                ])
            ,
            UrlField::new('coverLink')->hideOnIndex(),
            IntegerField::new('pageNumber')->setLabel('Pages'),
            AssociationField::new('categories')
            ->setLabel('Catégorie(s)')
            ->formatValue(function ($value, $entity) {
                return implode(', ', $entity->getCategories()->map(function ($category) {
                    return $category->getName();
                })->toArray());
            }),
            AssociationField::new('authors')
            ->setLabel('Auteur(s)')
            ->formatValue(function ($value, $entity) {
                return implode(', ', $entity->getAuthors()->map(function ($author) {
                    return $author->getFirstname() . ' ' . $author->getLastname();
                })->toArray());
            }),
            BooleanField::new('reservation')
                ->setLabel('Réservé')
                ->onlyOnIndex()
                ->renderAsSwitch(false)
                ->formatValue(function ($value, $entity) {
                    $reservation = $entity->getReservation();
                    return $reservation !== null;
                }),
            AssociationField::new('loans')
            ->setLabel('Emprunté')
            ->onlyOnIndex()
            ->formatValue(function ($value, $entity) {
                return count($entity->getLoans()) == 0 ? 'Non' : 'Oui';
            }),
            ];
    }


    public function configureFilters(\EasyCorp\Bundle\EasyAdminBundle\Config\Filters $filters): \EasyCorp\Bundle\EasyAdminBundle\Config\Filters
    {
        return $filters
            ->add('title')
            ->add('language')
            ->add('pageNumber')
            ->add('releaseDate')
            ->add('categories')
            ;
    }
    
    public function createEntity($entityFqcn) {
        $book = new Book();
        $book->setId(Utils::generateUuid());
        return $book;
    }
}
