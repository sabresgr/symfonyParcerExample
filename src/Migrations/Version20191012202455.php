<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191012202455 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE product_types (id INT AUTO_INCREMENT NOT NULL, str_type_name VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET latin1  ENGINE = InnoDB');
        //$this->addSql('DROP TABLE tblProductData');
        $this->addSql('CREATE INDEX IDX_ProductTypesName ON product_types (str_type_name)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        //$this->addSql('CREATE TABLE tblProductData (intProductDataId INT UNSIGNED AUTO_INCREMENT NOT NULL, strProductName VARCHAR(50) NOT NULL COLLATE latin1_swedish_ci, strProductDesc VARCHAR(255) NOT NULL COLLATE latin1_swedish_ci, strProductCode VARCHAR(10) NOT NULL COLLATE latin1_swedish_ci, dtmAdded DATETIME DEFAULT NULL, dtmDiscontinued DATETIME DEFAULT NULL, stmTimestamp DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, UNIQUE INDEX strProductCode (strProductCode), PRIMARY KEY(intProductDataId)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'Stores product data\' ');
        $this->addSql('DROP TABLE product_types');
    }
}
