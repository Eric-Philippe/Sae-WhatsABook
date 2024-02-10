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

        $albert = (new Author())->setId(Utils::generateUUID())
            ->setFirstname("Albert")
            ->setLastname("Camus")
            ->setPhotoLink("https://randomuser.me/api/portraits")
            ->setBirthDate(new \DateTime("1913-11-07"))
            ->setDeathDate(new \DateTime("1960-01-04"))
            ->setDescription("Albert Camus, né le 7 novembre 1913 à Mondovi, en Algérie, et mort le 4 janvier 1960 à Villeblevin, en France, est un écrivain, philosophe, romancier, dramaturge, essayiste et nouvelliste français. Il est aussi journaliste militant engagé dans la Résistance française et, dans les dernières années de sa vie, un intellectuel engagé dans les combats moraux de l'après-guerre.")
            ->setNationality("Française")
            ;

        $manager->persist($albert);
        $manager->flush();

        // Create foor books (La Peste, La Chute L'étranger, Noces)
        $laPeste = (new Book())->setId(Utils::generateUUID())
            ->setCoverLink("https://picsum.photos/360/360?image=1")
            ->setReleaseDate(new \DateTime("1947-06-10"))
            ->setTitle("La Peste")
            ->setSummary("La Peste est un roman d'Albert Camus publié en 1947 qui rapporte les événements de la ville d'Oran en Algérie, frappée par une épidémie de peste. Le roman est à la fois une allégorie et une réflexion sur la condition humaine.")
            ->setLanguage("Français")
            ->setPageNumber(308)
            ->setAuthors([$albert])
            ;

        $laChute = (new Book())->setId(Utils::generateUUID())
            ->setCoverLink("https://picsum.photos/360/360?image=2")
            ->setReleaseDate(new \DateTime("1956-05-03"))
            ->setTitle("La Chute")
            ->setSummary("La Chute est un roman d'Albert Camus publié en 1956. Il s'agit du dernier roman achevé par l'auteur. Le roman est construit sous la forme d'un monologue, celui de Jean-Baptiste Clamence, un ancien avocat parisien reconverti en clochard.")
            ->setLanguage("Français")
            ->setPageNumber(156)
            ->setAuthors([$albert])
            ;

        $letranger = (new Book())->setId(Utils::generateUUID())
            ->setCoverLink("https://picsum.photos/360/360?image=3")
            ->setReleaseDate(new \DateTime("1942-06-10"))
            ->setTitle("L'étranger")
            ->setSummary("L'Étranger est un roman d'Albert Camus, paru en 1942. Il prend place dans la tétralogie que Camus nomme « cycle de l'absurde », qui comprend Le Mythe de Sisyphe, Caligula, et Le Malentendu.")
            ->setLanguage("Français")
            ->setPageNumber(123)
            ->setAuthors([$albert])
            ;

        $noces = (new Book())->setId(Utils::generateUUID())
            ->setCoverLink("https://picsum.photos/360/360?image=4")
            ->setReleaseDate(new \DateTime("1939-06-10"))
            ->setTitle("Noces")
            ->setSummary("Noces est un recueil de quatre essais d'Albert Camus, publié en 1939. Il s'agit du premier ouvrage de l'auteur, qui a alors 26 ans. Il est composé de quatre textes : Noces à Tipasa, Le Vent à Djémila, L'Été à Alger et Le Désert.")
            ->setLanguage("Français")
            ->setPageNumber(123)
            ->setAuthors([$albert])
            ;

        $manager->persist($laPeste);
        $manager->persist($laChute);
        $manager->persist($letranger);
        $manager->persist($noces);
        $manager->flush();
    }
}