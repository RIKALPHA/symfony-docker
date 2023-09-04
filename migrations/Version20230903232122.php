<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230903232122 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE pornstar_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE pornstar (id INT NOT NULL, attributes TEXT DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, license VARCHAR(255) DEFAULT NULL, wl_status INT DEFAULT NULL, aliases TEXT DEFAULT NULL, link VARCHAR(255) DEFAULT NULL, thumbnails TEXT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN pornstar.attributes IS \'(DC2Type:array)\'');
        $this->addSql('COMMENT ON COLUMN pornstar.aliases IS \'(DC2Type:array)\'');
        $this->addSql('COMMENT ON COLUMN pornstar.thumbnails IS \'(DC2Type:array)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE pornstar_id_seq CASCADE');
        $this->addSql('DROP TABLE pornstar');
    }
}
