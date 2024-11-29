<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240424131421 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE panier (id INT AUTO_INCREMENT NOT NULL, id_art INT DEFAULT NULL, id_int INT DEFAULT NULL, num_panier INT NOT NULL, quantite INT NOT NULL, emballage VARCHAR(255) NOT NULL, INDEX IDX_24CC0DF262913E0E (id_art), INDEX IDX_24CC0DF28AF532EB (id_int), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE panier ADD CONSTRAINT FK_24CC0DF262913E0E FOREIGN KEY (id_art) REFERENCES article (id)');
        $this->addSql('ALTER TABLE panier ADD CONSTRAINT FK_24CC0DF28AF532EB FOREIGN KEY (id_int) REFERENCES internaute (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE panier DROP FOREIGN KEY FK_24CC0DF262913E0E');
        $this->addSql('ALTER TABLE panier DROP FOREIGN KEY FK_24CC0DF28AF532EB');
        $this->addSql('DROP TABLE panier');
    }
}
