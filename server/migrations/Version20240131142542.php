<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240131142542 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE author (id VARCHAR(36) NOT NULL, lastname VARCHAR(50) NOT NULL, firstname VARCHAR(50) NOT NULL, birth_date DATE DEFAULT NULL, death_date DATE DEFAULT NULL, nationality VARCHAR(50) DEFAULT NULL, photo_link VARCHAR(150) NOT NULL, description LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE book (id VARCHAR(36) NOT NULL, title VARCHAR(100) NOT NULL, summary LONGTEXT NOT NULL, release_date DATE NOT NULL, language VARCHAR(50) NOT NULL, cover_link VARCHAR(150) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE book_category (book_id VARCHAR(36) NOT NULL, category_id VARCHAR(36) NOT NULL, INDEX IDX_1FB30F9816A2B381 (book_id), INDEX IDX_1FB30F9812469DE2 (category_id), PRIMARY KEY(book_id, category_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE book_author (book_id VARCHAR(36) NOT NULL, author_id VARCHAR(36) NOT NULL, INDEX IDX_9478D34516A2B381 (book_id), INDEX IDX_9478D345F675F31B (author_id), PRIMARY KEY(book_id, author_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category (id VARCHAR(36) NOT NULL, name VARCHAR(50) NOT NULL, description VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE loan (id VARCHAR(36) NOT NULL, member_id VARCHAR(36) DEFAULT NULL, loan_date DATE NOT NULL, return_date DATE NOT NULL, max_date_return_limit DATE NOT NULL, INDEX IDX_C5D30D037597D3FE (member_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE member (id VARCHAR(36) NOT NULL, role_id VARCHAR(36) DEFAULT NULL, creation_date DATE NOT NULL, lastname VARCHAR(50) NOT NULL, firstname VARCHAR(50) NOT NULL, birth_date DATE NOT NULL, email VARCHAR(50) NOT NULL, adress VARCHAR(80) NOT NULL, phone_number VARCHAR(10) NOT NULL, photo_link VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, INDEX IDX_70E4FA78D60322AC (role_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reservation (id VARCHAR(36) NOT NULL, member_id VARCHAR(36) DEFAULT NULL, date_resa DATE NOT NULL, INDEX IDX_42C849557597D3FE (member_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE role (id VARCHAR(36) NOT NULL, name VARCHAR(50) NOT NULL, permission_rank INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE book_category ADD CONSTRAINT FK_1FB30F9816A2B381 FOREIGN KEY (book_id) REFERENCES book (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE book_category ADD CONSTRAINT FK_1FB30F9812469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE book_author ADD CONSTRAINT FK_9478D34516A2B381 FOREIGN KEY (book_id) REFERENCES book (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE book_author ADD CONSTRAINT FK_9478D345F675F31B FOREIGN KEY (author_id) REFERENCES author (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE loan ADD CONSTRAINT FK_C5D30D03BF396750 FOREIGN KEY (id) REFERENCES book (id)');
        $this->addSql('ALTER TABLE loan ADD CONSTRAINT FK_C5D30D037597D3FE FOREIGN KEY (member_id) REFERENCES member (id)');
        $this->addSql('ALTER TABLE member ADD CONSTRAINT FK_70E4FA78D60322AC FOREIGN KEY (role_id) REFERENCES role (id)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C84955BF396750 FOREIGN KEY (id) REFERENCES book (id)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C849557597D3FE FOREIGN KEY (member_id) REFERENCES member (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE book_category DROP FOREIGN KEY FK_1FB30F9816A2B381');
        $this->addSql('ALTER TABLE book_category DROP FOREIGN KEY FK_1FB30F9812469DE2');
        $this->addSql('ALTER TABLE book_author DROP FOREIGN KEY FK_9478D34516A2B381');
        $this->addSql('ALTER TABLE book_author DROP FOREIGN KEY FK_9478D345F675F31B');
        $this->addSql('ALTER TABLE loan DROP FOREIGN KEY FK_C5D30D03BF396750');
        $this->addSql('ALTER TABLE loan DROP FOREIGN KEY FK_C5D30D037597D3FE');
        $this->addSql('ALTER TABLE member DROP FOREIGN KEY FK_70E4FA78D60322AC');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C84955BF396750');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C849557597D3FE');
        $this->addSql('DROP TABLE author');
        $this->addSql('DROP TABLE book');
        $this->addSql('DROP TABLE book_category');
        $this->addSql('DROP TABLE book_author');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE loan');
        $this->addSql('DROP TABLE member');
        $this->addSql('DROP TABLE reservation');
        $this->addSql('DROP TABLE role');
    }
}
