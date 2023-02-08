<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230208135931 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_board DROP FOREIGN KEY FK_BA94D01FA76ED395');
        $this->addSql('ALTER TABLE user_board DROP FOREIGN KEY FK_BA94D01FE7EC5785');
        $this->addSql('DROP TABLE user_board');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_board (user_id INT NOT NULL, board_id INT NOT NULL, INDEX IDX_BA94D01FA76ED395 (user_id), INDEX IDX_BA94D01FE7EC5785 (board_id), PRIMARY KEY(user_id, board_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE user_board ADD CONSTRAINT FK_BA94D01FA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_board ADD CONSTRAINT FK_BA94D01FE7EC5785 FOREIGN KEY (board_id) REFERENCES board (id) ON DELETE CASCADE');
    }
}
