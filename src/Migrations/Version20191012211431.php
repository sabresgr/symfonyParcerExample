<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191012211431 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE tblProductData ADD id_product_type_id INT NOT NULL, ADD int_stock INT NOT NULL, ADD float_cost DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE tblProductData ADD CONSTRAINT FK_2C112486FDCB54AB FOREIGN KEY (id_product_type_id) REFERENCES product_types (id)');
        $this->addSql('CREATE INDEX IDX_2C112486FDCB54AB ON tblProductData (id_product_type_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE tblProductData DROP FOREIGN KEY FK_2C112486FDCB54AB');
        $this->addSql('DROP INDEX IDX_2C112486FDCB54AB ON tblProductData');
        $this->addSql('ALTER TABLE tblProductData DROP id_product_type_id, DROP int_stock, DROP float_cost');
    }
}
