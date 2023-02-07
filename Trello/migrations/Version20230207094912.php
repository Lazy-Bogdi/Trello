<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230207094912 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_board (user_id INT NOT NULL, board_id INT NOT NULL, INDEX IDX_BA94D01FA76ED395 (user_id), INDEX IDX_BA94D01FE7EC5785 (board_id), PRIMARY KEY(user_id, board_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_board ADD CONSTRAINT FK_BA94D01FA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_board ADD CONSTRAINT FK_BA94D01FE7EC5785 FOREIGN KEY (board_id) REFERENCES board (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE board ADD owner_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE board ADD CONSTRAINT FK_58562B478FDDAB70 FOREIGN KEY (owner_id_id) REFERENCES `user` (id)');
        $this->addSql('CREATE INDEX IDX_58562B478FDDAB70 ON board (owner_id_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_board DROP FOREIGN KEY FK_BA94D01FA76ED395');
        $this->addSql('ALTER TABLE user_board DROP FOREIGN KEY FK_BA94D01FE7EC5785');
        $this->addSql('DROP TABLE user_board');
        $this->addSql('ALTER TABLE board DROP FOREIGN KEY FK_58562B478FDDAB70');
        $this->addSql('DROP INDEX IDX_58562B478FDDAB70 ON board');
        $this->addSql('ALTER TABLE board DROP owner_id_id');
    }
}
