<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Utils\Utils;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class CategoryCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Category::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm()->hideOnIndex(),
            TextField::new('name'),
            TextEditorField::new('description'),
            AssociationField::new('books')
            ->setLabel('Livres avec cat.')
            ->onlyOnIndex()
            ->formatValue(function ($value, $entity) {
                return count($entity->getBooks());
            }),
        ];
    }

    public function createEntity($entityFqcn) {
        $cat = new Category();
        $cat->setId(Utils::generateUuid());
        return $cat;
    }

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */
}
