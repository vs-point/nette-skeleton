<?php

declare(strict_types=1);

namespace VsPoint\Database\Migration;

use Override;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220919203502 extends AbstractMigration
{
  #[Override]
  public function getDescription(): string
  {
    return '';
  }

  public function up(Schema $schema): void
  {
    // this up() migration is auto-generated, please modify it to your needs
    $this->addSql(
      'CREATE TABLE acl_user (id UUID NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, expiration TIMESTAMP(6) WITH TIME ZONE DEFAULT NULL, created_at TIMESTAMP(6) WITH TIME ZONE NOT NULL, gdpr TIMESTAMP(6) WITH TIME ZONE DEFAULT NULL, PRIMARY KEY(id))'
    );
    $this->addSql('CREATE UNIQUE INDEX UNIQ_AA9FB75AE7927C74 ON acl_user (email)');
    $this->addSql('COMMENT ON COLUMN acl_user.id IS \'(DC2Type:uuid)\'');
    $this->addSql('COMMENT ON COLUMN acl_user.expiration IS \'(DC2Type:brick_zoneddatetime)\'');
    $this->addSql('COMMENT ON COLUMN acl_user.created_at IS \'(DC2Type:brick_zoneddatetime)\'');
    $this->addSql('COMMENT ON COLUMN acl_user.gdpr IS \'(DC2Type:brick_zoneddatetime)\'');
    $this->addSql(
      'CREATE TABLE acl_userrole (id UUID NOT NULL, user_id UUID NOT NULL, role VARCHAR(255) NOT NULL, PRIMARY KEY(id))'
    );
    $this->addSql('CREATE INDEX IDX_F312511AA76ED395 ON acl_userrole (user_id)');
    $this->addSql('COMMENT ON COLUMN acl_userrole.id IS \'(DC2Type:uuid)\'');
    $this->addSql('COMMENT ON COLUMN acl_userrole.user_id IS \'(DC2Type:uuid)\'');
    $this->addSql('CREATE TABLE locale_locale (id VARCHAR(3) NOT NULL, PRIMARY KEY(id))');
    $this->addSql(
      'ALTER TABLE acl_userrole ADD CONSTRAINT FK_F312511AA76ED395 FOREIGN KEY (user_id) REFERENCES acl_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE'
    );
  }

  #[Override]
  public function down(Schema $schema): void
  {
    // this down() migration is auto-generated, please modify it to your needs
    $this->addSql('CREATE SCHEMA public');
    $this->addSql('ALTER TABLE acl_userrole DROP CONSTRAINT FK_F312511AA76ED395');
    $this->addSql('DROP TABLE acl_user');
    $this->addSql('DROP TABLE acl_userrole');
    $this->addSql('DROP TABLE locale_locale');
  }
}
