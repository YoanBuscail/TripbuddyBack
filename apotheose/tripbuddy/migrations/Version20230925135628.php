<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230925135628 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category_step (category_id INT NOT NULL, step_id INT NOT NULL, INDEX IDX_C822847B12469DE2 (category_id), INDEX IDX_C822847B73B21E9C (step_id), PRIMARY KEY(category_id, step_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE itinerary (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, start_date DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', end_date DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', favorite TINYINT(1) DEFAULT NULL, INDEX IDX_FF2238F6A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE itinerary_step (itinerary_id INT NOT NULL, step_id INT NOT NULL, INDEX IDX_449A946815F737B2 (itinerary_id), INDEX IDX_449A946873B21E9C (step_id), PRIMARY KEY(itinerary_id, step_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE step (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, latitude DOUBLE PRECISION DEFAULT NULL, longitude DOUBLE PRECISION DEFAULT NULL, description LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(255) NOT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE category_step ADD CONSTRAINT FK_C822847B12469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE category_step ADD CONSTRAINT FK_C822847B73B21E9C FOREIGN KEY (step_id) REFERENCES step (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE itinerary ADD CONSTRAINT FK_FF2238F6A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE itinerary_step ADD CONSTRAINT FK_449A946815F737B2 FOREIGN KEY (itinerary_id) REFERENCES itinerary (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE itinerary_step ADD CONSTRAINT FK_449A946873B21E9C FOREIGN KEY (step_id) REFERENCES step (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE category_step DROP FOREIGN KEY FK_C822847B12469DE2');
        $this->addSql('ALTER TABLE category_step DROP FOREIGN KEY FK_C822847B73B21E9C');
        $this->addSql('ALTER TABLE itinerary DROP FOREIGN KEY FK_FF2238F6A76ED395');
        $this->addSql('ALTER TABLE itinerary_step DROP FOREIGN KEY FK_449A946815F737B2');
        $this->addSql('ALTER TABLE itinerary_step DROP FOREIGN KEY FK_449A946873B21E9C');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE category_step');
        $this->addSql('DROP TABLE itinerary');
        $this->addSql('DROP TABLE itinerary_step');
        $this->addSql('DROP TABLE step');
        $this->addSql('DROP TABLE user');
    }
}
