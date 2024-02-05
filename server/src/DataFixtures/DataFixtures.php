<?php

namespace App\DataFixtures;

use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

use Faker\Factory;
use App\Entity\Author;
use App\Entity\Category;
use App\Entity\Book;
use App\Entity\Member;
use App\Entity\Role;
use App\Entity\Reservation;
use App\Entity\Loan;
use App\Entity\Suggestion;
use App\Utils\Utils;

class DataFixtures extends Fixture
{
    private $userPasswordHasher;
    
    public function __construct(UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->userPasswordHasher = $userPasswordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        // Génération des auteurs
        $auteurs = [];
        $AUTHORS_COUNT = 75;
        $uuids = Utils::generateUniqueUUIDs($AUTHORS_COUNT);

        for ($i = 0; $i < $AUTHORS_COUNT; $i++) {
            $isDead = $faker->boolean(75);
            $birthDate = $faker->dateTimeBetween("-200 years", "now");
            
            // Calculate the maximum possible age based on the birthDate
            $maxAge = new DateTimeImmutable();
            $maxAge->sub($birthDate->diff(new DateTimeImmutable()));
            
            // Convert DateTimeImmutable objects to strings
            $birthDateString = $birthDate->format('Y-m-d H:i:s');
            $maxAgeString = $maxAge->format('Y-m-d H:i:s');
            
            // Generate deathDate after birthDate
            $deathDate = !$isDead ? null : $faker->dateTimeBetween($birthDateString, $maxAgeString);
            $isABoy = $faker->boolean();
            $avatarType = $isABoy ? "men" : "women";
                        
            $auteur = (new Author())->setId($uuids[$i])
                                    ->setFirstname($faker->firstName())
                                    ->setLastname($faker->lastName())                                     
                                    ->setPhotoLink("https://randomuser.me/api/portraits/".$avatarType."/".$i.".jpg")
                                    ->setBirthDate($birthDate)
                                    ->setDeathDate($deathDate)
                                    ->setDescription($faker->paragraph())
                                    ->setNationality($faker->country());
                                    ;

            $manager->persist($auteur);
            $auteurs[] = $auteur;
        }

        $manager->flush();

        # Create Book Categories
        $categories_name = ["Littérature", "Essais", "Policier", "Science-fiction", "Fantastique", "Jeunesse", "BD", "Mangas", "Cuisine", "Voyage", "Histoire", "Biographie", "Autre"];
        $CATEGORY_COUNT = count($categories_name);
        $uuids = Utils::generateUniqueUUIDs($CATEGORY_COUNT);

        $categories = [];

        for ($i = 0; $i < $CATEGORY_COUNT; $i++) {
            $category = (new Category())->setName($categories_name[$i])
            ->setId($uuids[$i])
            ->setDescription($faker->paragraph(2));

            $categories[] = $category;
            $manager->persist($category);
        }

        $manager->flush();

        $langues = ["Français", "Anglais", "Allemand", "Espagnol", "Italien", "Portugais", "Chinois", "Japonais", "Russe", "Arabe", "Autre"];

        # Create Books
        $BOOKS_COUNT = 100;
        $uuids = Utils::generateUniqueUUIDs($BOOKS_COUNT);
        $livres = [];

 
        for( $i = 0; $i < $BOOKS_COUNT; $i++ ) {
            $livre = (new Book())->setId($uuids[$i])
            ->setCoverLink("https://picsum.photos/360/360?image=".($i+1))
            ->setReleaseDate($faker->dateTime())
            ->setTitle($faker->sentence(3))
            ->setSummary($faker->paragraph(6))
            ->setLanguage($faker->randomElement($langues))
            ->setPageNumber($faker->numberBetween(50, 1000))
            ->setCategories($faker->randomElements($categories, $faker->numberBetween(1, 3)))
            ->setAuthors($faker->randomElements($auteurs, $faker->numberBetween(1,2)))
            ;

        $livres[] = $livre;
        $manager->persist($livre);
        }

        $manager->flush();


        $roles = [];
        $roles_name = ["ROLE_USER", "ROLE_STAFF", "ROLE_ADMIN"];
        $ROLES_COUNT = count($roles_name);
        $uuids = Utils::generateUniqueUUIDs($ROLES_COUNT);
        for( $i = 0; $i < $ROLES_COUNT; $i++ ) {
            $role = (new Role())->setId($uuids[$i])->setName($roles_name[$i])->setPermissionRang($i);

            $roles[] = $role;
            $manager->persist($role);
        }
        
        $manager->flush();

        $member_admin = new Member();
        $member_admin->setPassword($this->userPasswordHasher->hashPassword($member_admin, "admin"))
                ->setId(Utils::generateUUID())
                ->setEmail("ericphlpp@gmail.com")
                ->setAdress("1 rue de la paix, 75000 Paris")
                ->setFirstname("Eric")
                ->setLastname("PHILIPPE")
                ->setPhoneNumber("0606060606")
                ->setRole($roles[2])
                ->setCreationDate(new \DateTime("now"))
                ->setPhotoLink("https://picsum.photos/360/360?image=".(89) ."&grayscale")
                ->setBirthDate(new \DateTime("2003-05-13"))
                ;
        $manager->persist($member_admin);

        $manager->flush();

        $me_member_member = new Member();
        $me_member_member->setPassword($this->userPasswordHasher->hashPassword($me_member_member, "admin"))
                ->setId(Utils::generateUUID())
                ->setEmail("member@member.com")
                ->setAdress("2 rue de la paix, 75000 Paris")
                ->setFirstname("Member")
                ->setLastname("Doe")
                ->setPhoneNumber("0606060606")
                ->setRole($roles[0])
                ->setCreationDate(new \DateTime("now"))
                ->setPhotoLink("https://picsum.photos/360/360?image=".(89) ."&grayscale")
                ->setBirthDate(new \DateTime("2003-05-13"))
                ;
        $manager->persist($me_member_member);

        $manager->flush();

        $staff_member = new Member();
        $staff_member->setPassword($this->userPasswordHasher->hashPassword($staff_member, "admin"))
                ->setId(Utils::generateUUID())
                ->setEmail("staff@staff.com")
                ->setAdress("3 rue de la paix, 75000 Paris")
                ->setFirstname("Staff")
                ->setLastname("Doe")
                ->setPhoneNumber("0606060606")
                ->setRole($roles[1])
                ->setCreationDate(new \DateTime("now"))
                ->setPhotoLink("https://picsum.photos/360/360?image=".(89) ."&grayscale")
                ->setBirthDate(new \DateTime("2003-05-13"))
                ;

                $manager->persist($staff_member);

                $manager->flush();

        $admin_member = new Member();
        $admin_member->setPassword($this->userPasswordHasher->hashPassword($admin_member, "admin"))
                ->setId(Utils::generateUUID())
                ->setEmail("admin@admin.com")
                ->setAdress("4 rue de la paix, 75000 Paris")
                ->setFirstname("Admin")
                ->setLastname("Doe")
                ->setPhoneNumber("0606060606")
                ->setRole($roles[2])
                ->setCreationDate(new \DateTime("now"))
                ->setPhotoLink("https://picsum.photos/360/360?image=".(89) ."&grayscale")
                ->setBirthDate(new \DateTime("2003-05-13"))
                ;

                $manager->persist($admin_member);

                $manager->flush();

        $members = [];
        $MEMBERS_COUNT = 30;
        $uuids = Utils::generateUniqueUUIDs($MEMBERS_COUNT);
        for ($i = 0; $i < $MEMBERS_COUNT; $i++) {
            $randomBirthDate = new \DateTime("");
            $randomBirthDate->sub(new \DateInterval("P".rand(0, 100)."Y".rand(0, 12)."M".rand(0, 30)."DT".rand(0, 23)."H".rand(0, 59)."M".rand(0, 59)."S"));
            $member = new Member();
                $member->setId($uuids[$i])
                ->setEmail($faker->email())
                ->setAdress($faker->address())
                ->setFirstname($faker->firstName())
                ->setLastname($faker->lastName())
                ->setPhoneNumber("0672825027")
                ->setPassword($this->userPasswordHasher->hashPassword($member, "member"))
                ->setRole($roles[0])
                ->setCreationDate(new \DateTime("now"))
                ->setPhotoLink("https://picsum.photos/360/360?image=".($i+1))
                ->setBirthDate($randomBirthDate)
            ;

            $members[] = $member;
            $manager->persist($member);
        }

        $manager->flush();

        $reservations =  [];
        $alreadyReservedBook = [];

        $myReservation = new Reservation();
        $myReservation->setId(Utils::generateUUID())
            ->setDateResa(new \DateTime("2024-02-02"))
            ->setBook($livres[0])
            ->setMember($member_admin);

        $manager->persist($myReservation);

        $alreadyReservedBook[] = $livres[0];


        $RESERVATIONS_COUNT = 19;
        $uuids = Utils::generateUniqueUUIDs($RESERVATIONS_COUNT);
        for ($i = 0; $i < $RESERVATIONS_COUNT; $i++) {
            // Get a random book from the list of books that is not in the alreadyReservedBook list
            $bookNotAlreayReserved = null;
            do {
                $bookNotAlreayReserved = $livres[$faker->numberBetween(0, $BOOKS_COUNT-1)];
            } while (in_array($bookNotAlreayReserved, $alreadyReservedBook));
            $reservation = (new Reservation())->setId($uuids[$i])
                ->setDateResa($faker->dateTimeBetween("-1 month", "now"))
                ->setBook($bookNotAlreayReserved)
                ->setMember($members[$faker->numberBetween(0, $MEMBERS_COUNT-1)])
            ;

            $reservations[] = $reservation;
            $manager->persist($reservation);
            $alreadyReservedBook[] = $bookNotAlreayReserved;
        }

        $manager->flush();

        $bookNotAlreayReserved = null;
        do {
            $bookNotAlreayReserved = $livres[$faker->numberBetween(0, $BOOKS_COUNT-1)];
        } while (in_array($bookNotAlreayReserved, $alreadyReservedBook));

        $myLoan = new Loan();
        $myLoan->setId(Utils::generateUUID())
            ->setBorrowedBook($bookNotAlreayReserved)
            ->setLoanDate(new \DateTime("2024-02-02"))
            ->setMember($member_admin)
            ;

        $manager->persist($myReservation);

        $alreadyReservedBook[] = $bookNotAlreayReserved;

        $loans =  [];
        $LOANS_COUNT = 10;
        $uuids = Utils::generateUniqueUUIDs($LOANS_COUNT);
        for ($i = 0; $i < $LOANS_COUNT; $i++) {
            // Get a random book from the list of books that is not in the alreadyReservedBook list
            $bookNotAlreayReserved = null;
            do {
                $bookNotAlreayReserved = $livres[$faker->numberBetween(0, $BOOKS_COUNT-1)];
            } while (in_array($bookNotAlreayReserved, $alreadyReservedBook));
            $loan = (new Loan())->setId($uuids[$i])
                ->setBorrowedBook($bookNotAlreayReserved)
                ->setLoanDate($faker->dateTimeBetween("-1 month", "now"))
                ->setMember($members[$faker->numberBetween(0, $MEMBERS_COUNT-1)])
            ;

            $loans[] = $loan;
            $manager->persist($loan);
            $alreadyReservedBook[] = $bookNotAlreayReserved;
        }

        $manager->flush();

        $suggestion = (new Suggestion())->setId(Utils::generateUUID())
            ->setTitle($faker->sentence(3))
            ->setEditor($faker->company())
            ->setReleaseDate($faker->dateTimeBetween("-1 year", "now"))
            ->setAuthors($faker->name())
            ->setDescription($faker->paragraph(6))
            ->setDetails($faker->paragraph(3))
            ->setMember($member_admin)
            ;

        $manager->persist($suggestion);
        $manager->flush();
    }
}