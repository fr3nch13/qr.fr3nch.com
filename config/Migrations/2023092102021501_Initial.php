<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class Initial extends AbstractMigration
{
    use \App\Migrations\QrMigrationTrait;

    /**
     * Change Method.
     *
     * More information on this method is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     * @return void
     */
    public function change(): void
    {
        $this->beforeChange();

        $this->io->out(__('Creating table: {0}', ['categories']));

        $table = $this->table('categories', $this->tableOptions());
        $table->addColumn('id', 'integer', $this->primaryKeyOptions());
        $table->addColumn('name', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => false,
        ])->addIndex(['name']);
        $table->addColumn('description', 'text', [
            'default' => null,
            'null' => false,
        ]);
        $table->addColumn('created', 'datetime', [
            'default' => null,
            'null' => true,
        ]);
        $table->addColumn('modified', 'datetime', [
            'default' => null,
            'null' => true,
        ]);
        $table->addColumn('parent_id', 'integer', [
            'default' => null,
            'null' => true,
        ])->addIndex(['parent_id']);
        $table->create();

        $this->io->out(__('Creating table: {0}', ['sources']));
        $table = $this->table('sources', $this->tableOptions());
        $table->addColumn('id', 'integer', $this->primaryKeyOptions());
        $table->addColumn('key', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => false,
        ])->addIndex(['key'], ['unique' => true]);
        $table->addColumn('qr_code_key_field', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => false,
        ])->addIndex(['qr_code_key_field']);
        $table->addColumn('name', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => false,
        ])->addIndex(['name']);
        $table->addColumn('description', 'text', [
            'default' => null,
            'null' => false,
        ]);
        $table->addColumn('created', 'datetime', [
            'default' => null,
            'null' => true,
        ]);
        $table->addColumn('modified', 'datetime', [
            'default' => null,
            'null' => true,
        ]);
        $table->create();

        $this->io->out(__('Creating table: {0}', ['qr_codes']));
        $table = $this->table('qr_codes', $this->tableOptions());
        $table->addColumn('id', 'integer', $this->primaryKeyOptions());
        $table->addColumn('key', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => false,
        ])->addIndex(['key'], ['unique' => true]);
        $table->addColumn('name', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => false,
        ])->addIndex(['name']);
        $table->addColumn('description', 'text', [
            'default' => null,
            'null' => false,
        ]);
        $table->addColumn('created', 'datetime', [
            'default' => null,
            'null' => true,
        ]);
        $table->addColumn('modified', 'datetime', [
            'default' => null,
            'null' => true,
        ]);
        $table->addColumn('url', 'text', [
            'default' => null,
            'null' => false,
        ]);
        $table->addColumn('bitly_id', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => true,
        ]);
        $table->addColumn('source_id', 'integer', [
            'default' => null,
            'null' => true,
        ]);
        $table->create();

        $this->io->out(__('Creating table: {0}', ['categories_qr_codes']));
        $table = $this->table('categories_qr_codes', $this->tableOptions());
        $table->addColumn('id', 'integer', $this->primaryKeyOptions());
        $table->addColumn('category_id', 'integer', [
            'default' => null,
            'null' => false,
        ])->addIndex(['category_id']);
        $table->addColumn('qr_code_id', 'integer', [
            'default' => null,
            'null' => false,
        ])->addIndex(['qr_code_id']);
        $table->create();

        // create for foreign constraints
        $this->io->out(__('Adding Foreign Keys'));
        if (!$this->table('categories_qr_codes')->hasForeignKey('categories_qr_codes_ibfk_1')) {
            $this->table('categories_qr_codes')
                ->addForeignKey('category_id', 'categories', 'id', [
                    'constraint' => 'categories_qr_codes_ibfk_1',
                    'update' => 'RESTRICT',
                    'delete' => 'CASCADE'
                ])->save();
        }
        if (!$this->table('categories_qr_codes')->hasForeignKey('categories_qr_codes_ibfk_2')) {
            $this->table('categories_qr_codes')
                ->addForeignKey('qr_code_id', 'qr_codes', 'id', [
                    'constraint' => 'categories_qr_codes_ibfk_2',
                    'update' => 'RESTRICT',
                    'delete' => 'CASCADE'
                ])->save();
        }

        $this->afterChange();
    }
}
