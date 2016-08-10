<?php

use Phinx\Migration\AbstractMigration;

class AddProjectsOrder extends AbstractMigration
{
    public function change()
    {
        if ($this->hasTable('projects')) {
            $table = $this->table('projects');

            if (!$table->hasColumn('order')) {
                $table
                    ->addColumn('order', 'integer', ['default' => 0])
                    ->save();
            }
        }
    }
}
