<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230922142032 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE category_step (category_id INT NOT NULL, step_id INT NOT NULL, INDEX IDX_C822847B12469DE2 (category_id), INDEX IDX_C822847B73B21E9C (step_id), PRIMARY KEY(category_id, step_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE category_step ADD CONSTRAINT FK_C822847B12469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE category_step ADD CONSTRAINT FK_C822847B73B21E9C FOREIGN KEY (step_id) REFERENCES step (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE category_step DROP FOREIGN KEY FK_C822847B12469DE2');
        $this->addSql('ALTER TABLE category_step DROP FOREIGN KEY FK_C822847B73B21E9C');
        $this->addSql('DROP TABLE category_step');
    }
}
