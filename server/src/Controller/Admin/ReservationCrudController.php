<?php

namespace App\Controller\Admin;

use App\Entity\Reservation;
use App\Utils\Utils;
use Doctrine\ORM\EntityRepository;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ReservationCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Reservation::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Réservation')
            ->setEntityLabelInPlural('Réservations')
            ->setSearchFields(['book.title', 'member.firstname', 'member.lastname'])
            ->setPaginatorPageSize(20)
            ;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
          DateField::new('dateResa')
            ->setLabel('Date de réservation')
            ->onlyOnDetail()
            ->onlyOnIndex(),
            AssociationField::new('book')
            ->setLabel('Livre')
            ->setRequired(true)
            ->setFormTypeOptions([
                'query_builder' => function (EntityRepository $er) {
                    // Where we cant find a reservation for the book or just can't find a reservation for the book
                    // Where we cant find a loan with returnDate = null for the book or just can't find a loan for the book
                    return $er->createQueryBuilder('b')
                        ->leftJoin('b.reservation', 'r')
                        ->where('r.id IS NULL')
                        ->orWhere('r.id IS NOT NULL')
                        ->leftJoin('b.loans', 'l')
                        ->where('l.returnDate IS NULL')
                        ->orWhere('l.id IS NULL');
                },
            ])
            ->formatValue(function ($value, $entity) {
                return $value ? $value->getTitle() : 'Non disponible';
            }),
            AssociationField::new('member')
            ->setLabel('Membre')
            ->setRequired(true),
            NumberField::new('daysLeft')
            ->setLabel('Jours restants')
            ->onlyOnIndex()
            ->onlyOnDetail(),
        ];
    }

    public function configureActions(Actions $actions): Actions
    {
        $createLoanFromResa = Action::new('createLoanFromResa', 'Créer un emprunt', 'fa fa-check')
            ->linkToCrudAction('createLoanFromResa');
        
        return $actions
            ->add(Crud::PAGE_INDEX, $createLoanFromResa)
            ->add(Crud::PAGE_DETAIL, $createLoanFromResa)
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ;
    }

    public function createEntity($entityFqcn) {
        $res = new Reservation();
        $res->setDateResa(new \DateTime());
        $res->setId(Utils::generateUuid());
        return $res;
    }
}
