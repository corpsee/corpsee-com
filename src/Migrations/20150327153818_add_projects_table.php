<?php

use Phinx\Migration\AbstractMigration;

class AddProjectsTable extends AbstractMigration
{
    public function change()
    {
        if (!$this->hasTable('projects')) {
            $table = $this->table('projects');
            $table
                ->addColumn('title', 'text')
                ->addColumn('description', 'text', ['default' => ''])
                ->addColumn('link', 'text', ['default' => ''])
                ->addColumn('role', 'text', ['default' => ''])
                ->addColumn('image', 'text', ['default' => ''])
                ->addColumn('post_date', 'timestamp', ['timezone' => true])
                ->addColumn('modify_date', 'timestamp', ['timezone' => true])
                ->addIndex('title', ['unique' => true])
                ->create();
        }
    }
}
