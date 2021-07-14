<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210625174002 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE company_amenity (company_id INT NOT NULL, amenity_id INT NOT NULL, INDEX IDX_8B3263B4979B1AD6 (company_id), INDEX IDX_8B3263B49F9F1305 (amenity_id), PRIMARY KEY(company_id, amenity_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE company_amenity ADD CONSTRAINT FK_8B3263B4979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE company_amenity ADD CONSTRAINT FK_8B3263B49F9F1305 FOREIGN KEY (amenity_id) REFERENCES amenity (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE company CHANGE monday_start monday_start VARCHAR(5) NOT NULL, CHANGE monday_end monday_end VARCHAR(5) NOT NULL, CHANGE tuesday_start tuesday_start VARCHAR(5) NOT NULL, CHANGE tuesday_end tuesday_end VARCHAR(5) NOT NULL, CHANGE wednesday_start wednesday_start VARCHAR(5) NOT NULL, CHANGE wednesday_end wednesday_end VARCHAR(5) NOT NULL, CHANGE thursday_start thursday_start VARCHAR(5) NOT NULL, CHANGE thursday_end thursday_end VARCHAR(5) NOT NULL, CHANGE friday_start friday_start VARCHAR(5) NOT NULL, CHANGE friday_end friday_end VARCHAR(5) NOT NULL, CHANGE saturday_start saturday_start VARCHAR(5) NOT NULL, CHANGE saturday_end saturday_end VARCHAR(5) NOT NULL, CHANGE sunday_start sunday_start VARCHAR(5) NOT NULL, CHANGE sunday_end sunday_end VARCHAR(5) NOT NULL');
        $this->addSql('ALTER TABLE location ADD CONSTRAINT FK_5E9E89CB7E3C61F9 FOREIGN KEY (owner_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_5E9E89CB7E3C61F9 ON location (owner_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE company_amenity');
        $this->addSql('ALTER TABLE company CHANGE monday_start monday_start VARCHAR(4) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE monday_end monday_end VARCHAR(4) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE tuesday_start tuesday_start VARCHAR(4) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE tuesday_end tuesday_end VARCHAR(4) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE wednesday_start wednesday_start VARCHAR(4) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE wednesday_end wednesday_end VARCHAR(4) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE thursday_start thursday_start VARCHAR(4) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE thursday_end thursday_end VARCHAR(4) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE friday_start friday_start VARCHAR(4) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE friday_end friday_end VARCHAR(4) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE saturday_start saturday_start VARCHAR(4) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE saturday_end saturday_end VARCHAR(4) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE sunday_start sunday_start VARCHAR(4) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE sunday_end sunday_end VARCHAR(4) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE location DROP FOREIGN KEY FK_5E9E89CB7E3C61F9');
        $this->addSql('DROP INDEX IDX_5E9E89CB7E3C61F9 ON location');
    }
}
