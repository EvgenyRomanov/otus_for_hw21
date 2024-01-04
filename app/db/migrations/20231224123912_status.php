<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;
use App\Domain\Entity\Status as StatusEntity;

final class Status extends AbstractMigration
{
    private string $tableName = "status";

    /**
     * Migrate Up.
     */
    public function up()
    {
        $createTableSql = "
            CREATE TABLE IF NOT EXISTS {$this->tableName} (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(255) NOT NULL,
                UNIQUE KEY name (name)
            ) ENGINE=INNODB
        ";

        $insertRowSql = "
            INSERT INTO {$this->tableName} (`name`) VALUES (?), (?)
        ";

        $this->execute($createTableSql);
        $this->execute($insertRowSql, [StatusEntity::IN_WORK, StatusEntity::DONE]);
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $dropTableSql = "DROP TABLE {$this->tableName}";
        $this->execute($dropTableSql);
    }
}
