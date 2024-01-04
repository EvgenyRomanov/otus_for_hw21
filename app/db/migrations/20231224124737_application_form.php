<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class ApplicationForm extends AbstractMigration
{
    private string $tableName = "application_form";

    /**
     * Migrate Up.
     */
    public function up()
    {
        $createTableSql = "
            CREATE TABLE IF NOT EXISTS {$this->tableName} (
                id INT AUTO_INCREMENT PRIMARY KEY,
                email VARCHAR(255) NOT NULL,
                message VARCHAR(255) NOT NULL,
                status_id INT NOT NULL,
                FOREIGN KEY (status_id) REFERENCES status(id)
            ) ENGINE=INNODB
        ";

        $this->execute($createTableSql);
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
