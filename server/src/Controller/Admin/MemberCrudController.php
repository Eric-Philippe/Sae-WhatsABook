<?php

namespace App\Controller\Admin;

use App\Entity\Member;
use App\Utils\Utils;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\KeyValueStore;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class MemberCrudController extends AbstractCrudController
{

    private $requestStack;
    private $encoder;
    public function __construct(
        UserPasswordHasherInterface $encoder,
        RequestStack $requestStack
    ) {
        $this->encoder = $encoder;
        $this->requestStack = $requestStack;
    }

    public static function getEntityFqcn(): string
    {
        return Member::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        $resetPass = Action::new('resetPass', 'Réinitialiser le mot de passe')
            ->linkToCrudAction('resetPass');

        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->add(Crud::PAGE_INDEX, $resetPass)
            ;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Membre')
            ->setEntityLabelInPlural('Membres')
            ->setSearchFields(['lastname', 'firstname'])
            ->setDefaultSort(['lastname' => 'ASC'])
            ->setPaginatorPageSize(20)
            ;
    }

    public static function getSubscribedEvents()
    {
        return [
            BeforeEntityPersistedEvent::class => ['hashPassword'],
        ];
    }
    public function configureFields(string $pageName): iterable
    {
        return [
            EmailField::new('email'),
            TextField::new('firstname')
            ->setLabel('Prénom')
            ,
            TextField::new('lastname')
                ->setLabel('Nom')
            ,
            TextField::new('adress')
                ->setLabel('Adresse'),
            TextField::new('phoneNumber')
                ->setLabel('Téléphone'),
            TextField::new('photoLink')->onlyOnForms(),
            DateField::new('birthDate')
                    ->setLabel('Date de naissance')
                    ->hideOnIndex(),
            DateField::new('creationDate')
                ->setLabel('Date de création')
                ->onlyOnIndex()
                ->setFormTypeOption('disabled', true)
            ,
            TextField::new('password')
                ->onlyWhenCreating()
                ->setFormType(RepeatedType::class)
                ->setFormTypeOptions([
                    'type' => PasswordType::class,
                    'first_options' => ['label' => 'Mot de passe'],
                    'second_options' => ['label' => 'Répéter le mot de passe'],
                ])
                ->setRequired(true)
            ,
            ChoiceField::new('roles')
                ->setLabel('Rôles')
                ->setChoices([
                    'Utilisateur' => 'ROLE_USER',
                    'Responsable' => 'ROLE_ADMIN',
                    'Bibliothécaire' => 'ROLE_STAFF',
                ])
                ->allowMultipleChoices()
                ->renderExpanded(),
            AssociationField::new('loans')
                ->setLabel('Emprunts terminés')
                ->hideOnForm()
                ->formatValue(function ($value, $entity) {
                    return $value ? count($value) : '0';
                }),
        ];
    }
    
    public function createNewFormBuilder(EntityDto $entityDto, KeyValueStore $formOptions, AdminContext $context): FormBuilderInterface
    {
        $formBuilder = parent::createNewFormBuilder($entityDto, $formOptions, $context);
        return $this->addPasswordEventListener($formBuilder);
    }

    public function createEditFormBuilder(EntityDto $entityDto, KeyValueStore $formOptions, AdminContext $context): FormBuilderInterface
    {
        $formBuilder = parent::createEditFormBuilder($entityDto, $formOptions, $context);
        return $this->addPasswordEventListener($formBuilder);
    }

    private function addPasswordEventListener(FormBuilderInterface $formBuilder): FormBuilderInterface
    {
        return $formBuilder->addEventListener(FormEvents::POST_SUBMIT, $this->hashPassword());
    }

    private function hashPassword() {
        return function($event) {
            $form = $event->getForm();
            if (!$form->isValid()) {
                return;
            }
            if ($form->has('password') === false) {
                return;
            }
            $password = $form->get('password')->getData();
            if ($password === null) {
                return;
            }

            $hash = $this->encoder->hashPassword($event->getData(), $password);
            $form->getData()->setPassword($hash);
        };
    }    
    
    public function createEntity($entityFqcn) {
        $member = new Member();
        $member->setId(Utils::generateUuid());
        $member->setCreationDate(new \DateTime());

        return $member;
    }
}  