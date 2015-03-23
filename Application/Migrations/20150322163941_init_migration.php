<?php

use Phinx\Migration\AbstractMigration;

class InitMigration extends AbstractMigration
{
    /**
     * Migrate Up.
     */
    public function up()
    {
        $table = $this->table('phinx_test');
        $table->create();
        $table->save();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {

    }
}