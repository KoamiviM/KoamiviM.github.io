<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210626175246 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE listing_speciality (listing_id INT NOT NULL, speciality_id INT NOT NULL, INDEX IDX_85BBD7FAD4619D1A (listing_id), INDEX IDX_85BBD7FA3B5A08D7 (speciality_id), PRIMARY KEY(listing_id, speciality_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE speciality (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, tags VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE listing_speciality ADD CONSTRAINT FK_85BBD7FAD4619D1A FOREIGN KEY (listing_id) REFERENCES listing (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE listing_speciality ADD CONSTRAINT FK_85BBD7FA3B5A08D7 FOREIGN KEY (speciality_id) REFERENCES speciality (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE listing_speciality DROP FOREIGN KEY FK_85BBD7FA3B5A08D7');
        $this->addSql('DROP TABLE listing_speciality');
        $this->addSql('DROP TABLE speciality');
    }
}
