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
        ]);
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
        ]);
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
        ])->addIndex(['source_id']);
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
        $this->io->out(__('Adding Foreign Key: {0} -> {1}.{2}', [
            'category_id', 'categories', 'id'
        ]));
        // @todo #2 Figure out why my foreign keys aren't being created.
        $table->addForeignKey('category_id', 'categories', 'id', [
                'update' => 'RESTRICT',
                'delete' => 'CASCADE',
                'constraint' => 'categories_qr_codes_category_id',
            ]);
        $this->io->out(__('Adding Foreign Key: {0} -> {1}.{2}', [
            'qr_code_id', 'qr_codes', 'id'
        ]));
        $table->addForeignKey('qr_code_id', 'qr_codes', 'id', [
                'update' => 'RESTRICT',
                'delete' => 'CASCADE',
                'constraint' => 'categories_qr_codes_qr_code_id',
            ]);
        $table->create();

        $this->afterChange();
    }
}
