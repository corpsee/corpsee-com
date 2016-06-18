<?php

use Phinx\Migration\AbstractMigration;

class InitMigration extends AbstractMigration
{
    public function change()
    {
        if (!$this->hasTable('pages')) {
            $table = $this->table('pages');
            $table
                ->addColumn('alias', 'text')
                ->addIndex('alias', ['unique' => true])
                ->create();
        }

        if (!$this->hasTable('pages_content')) {
            $table = $this->table('pages_content');
            $table
                ->addColumn('language', 'text', ['default' => 'ru'])
                ->addColumn('page_id', 'integer')
                ->addColumn('title', 'text', ['default' => ''])
                ->addColumn('description', 'text', ['default' => ''])
                ->addColumn('keywords', 'text', ['default' => ''])
                ->create();
        }

        if (!$this->hasTable('pictures')) {
            $table = $this->table('pictures');
            $table
                ->addColumn('title', 'text')
                ->addColumn('image', 'text')
                ->addColumn('description', 'text', ['default' => ''])
                ->addColumn('create_date', 'timestamp', ['timezone' => true, 'null' => true])
                ->addColumn('post_date', 'timestamp', ['timezone' => true])
                ->addColumn('modify_date', 'timestamp', ['timezone' => true])
                ->addIndex('title', ['unique' => true])
                ->addIndex('image', ['unique' => true])
                ->create();
        }

        if (!$this->hasTable('tags')) {
            $table = $this->table('tags');
            $table
                ->addColumn('tag', 'text')
                ->addColumn('post_date', 'timestamp', ['timezone' => true])
                ->addColumn('modify_date', 'timestamp', ['timezone' => true])
                ->addIndex('tag', ['unique' => true])
                ->create();
        }

        if (!$this->hasTable('pictures_tags')) {
            $table = $this->table('pictures_tags');
            $table
                ->addColumn('picture_id', 'integer')
                ->addColumn('tag_id', 'integer')
                ->create();
        }

        if (!$this->hasTable('last_modify')) {
            $table = $this->table('last_modify');
            $table
                ->addColumn('table', 'text')
                ->addColumn('modify_date', 'timestamp', ['timezone' => true])
                ->create();
        }

        if (!$this->hasTable('pull_requests')) {
            $table = $this->table('pull_requests');
            $table
                ->addColumn('repository', 'text')
                ->addColumn('number', 'integer')
                ->addColumn('title', 'text', ['default' => ''])
                ->addColumn('body', 'text', ['default' => ''])
                ->addColumn('status', 'text')
                ->addColumn('commits', 'integer')
                ->addColumn('additions', 'integer')
                ->addColumn('deletions', 'integer')
                ->addColumn('files', 'integer')
                ->addColumn('create_date', 'timestamp', ['timezone' => true])
                ->create();
        }
    }
}
