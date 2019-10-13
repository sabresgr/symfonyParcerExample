<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191013134427 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE tblProductData MODIFY intProductDataId INT UNSIGNED NOT NULL');
        $this->addSql('ALTER TABLE tblProductData DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE tblProductData DROP intProductDataId');
        $this->addSql('ALTER TABLE tblProductData ADD PRIMARY KEY (strProductCode)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE tblProductData DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE tblProductData ADD intProductDataId INT UNSIGNED AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE tblProductData ADD PRIMARY KEY (intProductDataId)');
    }
}
