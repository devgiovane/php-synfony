<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220413152017 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE login (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(255) NOT NULL, username VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_AA08CB10E7927C74 (email), UNIQUE INDEX UNIQ_AA08CB10F85E0677 (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE login_access_token (id INT AUTO_INCREMENT NOT NULL, login_id INT DEFAULT NULL, appclient_id INT DEFAULT NULL, token VARCHAR(200) NOT NULL, expires_in DATETIME NOT NULL, scope VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_7D711E585F37A13B (token), INDEX IDX_7D711E585CB2E05D (login_id), INDEX IDX_7D711E583FAB4CEC (appclient_id), INDEX token_idx (token), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE login_appclient (id INT AUTO_INCREMENT NOT NULL, client_identifier VARCHAR(255) NOT NULL, client_secret VARCHAR(255) NOT NULL, redirect_uri VARCHAR(255) DEFAULT NULL, UNIQUE INDEX client_identifier_unique (client_identifier), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE login_refresh_token (id INT AUTO_INCREMENT NOT NULL, login_id INT DEFAULT NULL, appclient_id INT DEFAULT NULL, refresh_token VARCHAR(200) NOT NULL, expires_in DATETIME NOT NULL, scope VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_E15DC2FAC74F2195 (refresh_token), INDEX IDX_E15DC2FA5CB2E05D (login_id), INDEX IDX_E15DC2FA3FAB4CEC (appclient_id), INDEX refresh_idx (refresh_token), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE login_scope (id INT AUTO_INCREMENT NOT NULL, scope VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_6DF9247AF55D3 (scope), INDEX scope_idx (scope), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE login_scope_appclient (scope_id INT NOT NULL, appclient_id INT NOT NULL, INDEX scope_idx (scope_id), INDEX appclient_idx (appclient_id), PRIMARY KEY(scope_id, appclient_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE login_user (id INT AUTO_INCREMENT NOT NULL, login_id INT DEFAULT NULL, user_id INT DEFAULT NULL, INDEX IDX_4BE15D0C5CB2E05D (login_id), INDEX IDX_4BE15D0CA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, cpf VARCHAR(14) NOT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at DATETIME ON UPDATE CURRENT_TIMESTAMP, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), UNIQUE INDEX UNIQ_8D93D6493E3E11F0 (cpf), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE login_access_token ADD CONSTRAINT FK_7D711E585CB2E05D FOREIGN KEY (login_id) REFERENCES login (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE login_access_token ADD CONSTRAINT FK_7D711E583FAB4CEC FOREIGN KEY (appclient_id) REFERENCES login_appclient (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE login_refresh_token ADD CONSTRAINT FK_E15DC2FA5CB2E05D FOREIGN KEY (login_id) REFERENCES login (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE login_refresh_token ADD CONSTRAINT FK_E15DC2FA3FAB4CEC FOREIGN KEY (appclient_id) REFERENCES login_appclient (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE login_scope_appclient ADD CONSTRAINT FK_F2DBE3F2682B5931 FOREIGN KEY (scope_id) REFERENCES login_scope (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE login_scope_appclient ADD CONSTRAINT FK_F2DBE3F23FAB4CEC FOREIGN KEY (appclient_id) REFERENCES login_appclient (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE login_user ADD CONSTRAINT FK_4BE15D0C5CB2E05D FOREIGN KEY (login_id) REFERENCES login (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE login_user ADD CONSTRAINT FK_4BE15D0CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE login_access_token DROP FOREIGN KEY FK_7D711E585CB2E05D');
        $this->addSql('ALTER TABLE login_refresh_token DROP FOREIGN KEY FK_E15DC2FA5CB2E05D');
        $this->addSql('ALTER TABLE login_user DROP FOREIGN KEY FK_4BE15D0C5CB2E05D');
        $this->addSql('ALTER TABLE login_access_token DROP FOREIGN KEY FK_7D711E583FAB4CEC');
        $this->addSql('ALTER TABLE login_refresh_token DROP FOREIGN KEY FK_E15DC2FA3FAB4CEC');
        $this->addSql('ALTER TABLE login_scope_appclient DROP FOREIGN KEY FK_F2DBE3F23FAB4CEC');
        $this->addSql('ALTER TABLE login_scope_appclient DROP FOREIGN KEY FK_F2DBE3F2682B5931');
        $this->addSql('ALTER TABLE login_user DROP FOREIGN KEY FK_4BE15D0CA76ED395');
        $this->addSql('DROP TABLE login');
        $this->addSql('DROP TABLE login_access_token');
        $this->addSql('DROP TABLE login_appclient');
        $this->addSql('DROP TABLE login_refresh_token');
        $this->addSql('DROP TABLE login_scope');
        $this->addSql('DROP TABLE login_scope_appclient');
        $this->addSql('DROP TABLE login_user');
        $this->addSql('DROP TABLE user');
    }
}
