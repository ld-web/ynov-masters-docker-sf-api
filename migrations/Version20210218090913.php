<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210218090913 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE circ_product_configuration (id INT NOT NULL, diameter INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_configuration (id INT AUTO_INCREMENT NOT NULL, product_id INT NOT NULL, depth INT NOT NULL, d_b1 DOUBLE PRECISION NOT NULL, d_b2 DOUBLE PRECISION NOT NULL, d_b5 DOUBLE PRECISION NOT NULL, d_b10 DOUBLE PRECISION NOT NULL, discr VARCHAR(255) NOT NULL, INDEX IDX_7F0FB9254584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE rect_product_configuration (id INT NOT NULL, width INT NOT NULL, height INT NOT NULL, thickness INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE circ_product_configuration ADD CONSTRAINT FK_1A8FC4C4BF396750 FOREIGN KEY (id) REFERENCES product_configuration (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product_configuration ADD CONSTRAINT FK_7F0FB9254584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE rect_product_configuration ADD CONSTRAINT FK_9F3E49FBF396750 FOREIGN KEY (id) REFERENCES product_configuration (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE circ_product_configuration DROP FOREIGN KEY FK_1A8FC4C4BF396750');
        $this->addSql('ALTER TABLE rect_product_configuration DROP FOREIGN KEY FK_9F3E49FBF396750');
        $this->addSql('DROP TABLE circ_product_configuration');
        $this->addSql('DROP TABLE product_configuration');
        $this->addSql('DROP TABLE rect_product_configuration');
    }
}
