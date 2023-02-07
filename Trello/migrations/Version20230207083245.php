<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230207083245 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE board_user (board_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_57058F6AE7EC5785 (board_id), INDEX IDX_57058F6AA76ED395 (user_id), PRIMARY KEY(board_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE board_user ADD CONSTRAINT FK_57058F6AE7EC5785 FOREIGN KEY (board_id) REFERENCES board (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE board_user ADD CONSTRAINT FK_57058F6AA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE board DROP FOREIGN KEY FK_58562B478FDDAB70');
        $this->addSql('DROP INDEX IDX_58562B478FDDAB70 ON board');
        $this->addSql('ALTER TABLE board DROP owner_id_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE board_user DROP FOREIGN KEY FK_57058F6AE7EC5785');
        $this->addSql('ALTER TABLE board_user DROP FOREIGN KEY FK_57058F6AA76ED395');
        $this->addSql('DROP TABLE board_user');
        $this->addSql('ALTER TABLE board ADD owner_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE board ADD CONSTRAINT FK_58562B478FDDAB70 FOREIGN KEY (owner_id_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_58562B478FDDAB70 ON board (owner_id_id)');
    }
}
