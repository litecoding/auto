<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250211212901 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(
            'CREATE TABLE vehicle (id INT AUTO_INCREMENT NOT NULL, person VARCHAR(1) NOT NULL, reg_addr_koatuu BIGINT NOT NULL, oper_code INT NOT NULL, oper_name VARCHAR(255) NOT NULL, d_reg DATETIME NOT NULL, dep_code INT NOT NULL, dep VARCHAR(50) NOT NULL, brand VARCHAR(50) NOT NULL, model VARCHAR(100) NOT NULL, vin VARCHAR(50) NOT NULL, make_year INT NOT NULL, color VARCHAR(20) NOT NULL, kind VARCHAR(50) NOT NULL, body VARCHAR(50) NOT NULL, purpose VARCHAR(50) NOT NULL, fuel VARCHAR(50) NOT NULL, capacity DOUBLE PRECISION DEFAULT NULL, own_weight INT NOT NULL, total_weight INT NOT NULL, n_reg_new VARCHAR(20) NOT NULL, UNIQUE INDEX UNIQ_1B80E486B1085141 (vin), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB'
        );
        $this->addSql(
            'CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB'
        );
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE vehicle');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
