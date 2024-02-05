<?php

namespace App\Controller\Admin;

use App\Entity\Book;
use App\Entity\Loan;
use App\Utils\Utils;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\CrudDto;

use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\KeyValueStore;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\Test\FormBuilderInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;

class LoanCrudController extends AbstractCrudController
{
    private $entityManager;
    private $requestStack;

    public function __construct(EntityManagerInterface $entityManager, RequestStack $requestStack)
    {
        $this->entityManager = $entityManager;
        $this->requestStack = $requestStack;
    }

    public static function getEntityFqcn(): string
    {
        return Loan::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Emprunt')
            ->setEntityLabelInPlural('Emprunts')
            ->setSearchFields(['book.title'])
            ->setDefaultSort(['loanDate' => 'ASC'])
            ->setPaginatorPageSize(20)
            ;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            DateField::new('loanDate')
            ->setLabel('Date d\'emprunt')
            ->hideOnForm(),
            DateField::new('returnDate')
            ->setLabel('Date de retour')
            ->hideOnForm()
            ->formatValue(function ($value, $entity) {
                return $value ? "Rendu le ".$value->format('d/m/Y') : 'Non-Rendu';
            }),
            AssociationField::new('book')
            ->setLabel('Livre')
            ->setVirtual(true)
            ->onlyWhenCreating()
            ->setFormTypeOptions([
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('b')
                        ->leftJoin('b.loans', 'l')
                        ->where('l.returnDate IS NOT NULL OR l.id IS NULL');
                },
                'multiple' => true,
            ])
            ->formatValue(function ($value, $entity) {
                return $value ? $value->getTitle() : 'Non disponible';
            }),
            AssociationField::new('book')
            ->setLabel('Livre')
            ->setVirtual(true)
            ->hideWhenCreating()
            ->formatValue(function ($value, $entity) {
                return $value ? $value->getTitle() : 'Non disponible';
            }),
            AssociationField::new('member')
            ->setLabel('Nom Adhérent')
            ->formatValue(function ($value, $entity) {
                return $value ? $value->getFirstname().' '.$value->getLastname() : 'Non disponible';
            }),
            AssociationField::new('member')
            ->setLabel('Contact Adhérent')
            ->formatValue(function ($value, $entity) {
                return $value ? $value->getEmail() : 'Non disponible';
            }),
            TextField::new('delay')
            ->onlyOnIndex()
            ->setLabel('Délai')
            ->setHelp("Le délai maximum d'emprunt est de 21 jours.")
            ->formatValue(function ($value, $entity) {
                $loanDate = $entity->getLoanDate();
                $returnDate = $entity->getReturnDate();
        
                if ($loanDate && $returnDate) {
                    $interval = $loanDate->diff($returnDate);
                    $delay = $interval->days;
        
                    if ($delay <= 21) {
                        return "<span style='color: white;'>".$delay." jours</span>";
                    } else {
                        return "<span style='color: orange;'>".$delay." jours</span>";
                    }
                } elseif ($loanDate) {
                    $interval = $loanDate->diff(new \DateTime());
                    $delay = $interval->days;
        
                    if ($delay > 21) {
                        return "<span style='color: red;'>".$delay." jours</span>";
                    } else {
                        return "<span style='color: white;'>".$delay." jours</span>";
                    }
                }
        
                return 'Non disponible';
            })            
        ];
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $loans = [];
        $loanTemplate = $entityInstance;

        $loanBooks = $loanTemplate->getBooks();
        $memberLoans = $loanTemplate->getMember()->getLoans();

        if (count($memberLoans) + count($loanBooks) > 5) {
            $this->addFlash('error', 'Vous ne pouvez pas emprunter plus de 5 livres.');
            return;
        }

        foreach ($loanBooks as $book) {
            $loan = clone $loanTemplate;
            $loan->setBook($book);
            $loan->setLoanDate(new \DateTime());
            $loan->setId(Utils::generateUuid());
    
            $loans[] = $loan;
        }
    
        foreach ($loans as $loan) {
            $this->entityManager->persist($loan);
        }
    
        $this->entityManager->flush();
    }

    public function configureActions(Actions $actions): Actions
    {
        $finishLoan = Action::new('finishLoan', 'Terminer l\'emprunt')
            ->linkToCrudAction('finishLoan');

        return $actions
            ->add(Crud::PAGE_INDEX, $finishLoan)
            ->add(Crud::PAGE_DETAIL, $finishLoan)
            ->add(Crud::PAGE_INDEX, Action::DETAIL);
    }

    public function finishLoan(AdminContext $context, EntityManagerInterface $entityManager)
    {
        $loan = $context->getEntity()->getInstance();
        $loan->setReturnDate(new \DateTime());
    
        $entityManager->flush();
    
        $this->addFlash('success', 'Emprunt terminé avec succès.');
    
        return $this->redirect("https://localhost:8008/admin?crudAction=index&crudControllerFqcn=App%5CController%5CAdmin%5CLoanCrudController");
    }
    
    // public function configureFilters(\EasyCorp\Bundle\EasyAdminBundle\Config\Filters $filters): \EasyCorp\Bundle\EasyAdminBundle\Config\Filters
    // {
    //     return $filters
    //     ->add(NullFilter::new('isReturnDateNull'));
    // }
}