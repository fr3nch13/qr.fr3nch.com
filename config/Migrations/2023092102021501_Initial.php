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

        $this->io->out(__('Creating table: {0}', ['users']));
        $table = $this->table('users', $this->tableOptions());
        $table->addColumn('id', 'integer', $this->primaryKeyOptions());
        $table->addColumn('name', 'string');
        $table->addColumn('email', 'string');
        $table->addColumn('password', 'string');
        $table->addColumn('created', 'datetime');
        $table->addColumn('modified', 'datetime');
        $table->addColumn('is_admin', 'boolean', ['default' => false, 'null' => false])
            ->addIndex(['is_admin']);
        $table->addColumn('is_active', 'boolean', ['default' => false, 'null' => false])
            ->addIndex(['is_active']);
        $table->create();

        $this->io->out(__('Creating table: {0}', ['sources']));
        $table = $this->table('sources', $this->tableOptions());
        $table->addColumn('id', 'integer', $this->primaryKeyOptions());
        $table->addColumn('name', 'string')->addIndex(['name']);
        $table->addColumn('description', 'text');
        $table->addColumn('created', 'datetime');
        $table->addColumn('modified', 'datetime');
        $table->addColumn('user_id', 'integer')
            ->addIndex(['user_id']);
        $table->addForeignKey('user_id', 'users', 'id', [
                'update' => 'NO_ACTION',
                'delete' => 'SET_NULL',
                'constraint' => 'sources_user_id',
            ]);
        $table->create();

        $this->io->out(__('Creating table: {0}', ['qr_codes']));
        $table = $this->table('qr_codes', $this->tableOptions());
        $table->addColumn('id', 'integer', $this->primaryKeyOptions());
        $table->addColumn('qrkey', 'string')
            ->addIndex(['qrkey'], ['unique' => true]);
        $table->addColumn('name', 'string');
        $table->addColumn('description', 'text');
        $table->addColumn('created', 'datetime');
        $table->addColumn('modified', 'datetime');
        $table->addColumn('url', 'text', ['null' => false]);
        $table->addColumn('hits', 'integer', ['default' => 0, 'null' => false]);
        $table->addColumn('is_active', 'boolean', ['default' => true, 'null' => false])
            ->addIndex(['is_active']);
        $table->addColumn('source_id', 'integer')->addIndex(['source_id']);
        $table->addColumn('user_id', 'integer')->addIndex(['user_id']);
        $table->addForeignKey('source_id', 'sources', 'id', [
                'update' => 'NO_ACTION',
                'delete' => 'SET_NULL',
                'constraint' => 'qr_codes_source_id',
            ]);
        $table->addForeignKey('user_id', 'users', 'id', [
                'update' => 'NO_ACTION',
                'delete' => 'SET_NULL',
                'constraint' => 'qr_codes_user_id',
            ]);
        $table->create();

        $this->io->out(__('Creating table: {0}', ['qr_images']));
        $table = $this->table('qr_images', $this->tableOptions());
        $table->addColumn('id', 'integer', $this->primaryKeyOptions());
        $table->addColumn('name', 'string', ['null' => false]);
        $table->addColumn('ext', 'string', ['null' => false]);
        $table->addColumn('created', 'datetime');
        $table->addColumn('modified', 'datetime');
        $table->addColumn('is_active', 'boolean', ['default' => true, 'null' => false])
            ->addIndex(['is_active']);
        $table->addColumn('imorder', 'integer', ['default' => 0, 'null' => false])
            ->addIndex(['imorder']);
        $table->addColumn('qr_code_id', 'integer', ['null' => false])
            ->addIndex(['qr_code_id']);
        $table->addForeignKey('qr_code_id', 'qr_codes', 'id', [
                'update' => 'RESTRICT',
                'delete' => 'CASCADE',
                'constraint' => 'qr_images_qr_code_id',
            ]);
        $table->create();

        $this->io->out(__('Creating table: {0}', ['tags']));
        $table = $this->table('tags', $this->tableOptions());
        $table->addColumn('id', 'integer', $this->primaryKeyOptions());
        $table->addColumn('name', 'string', ['null' => false]);
        $table->addColumn('created', 'datetime');
        $table->addColumn('modified', 'datetime');
        $table->addColumn('user_id', 'integer')->addIndex(['user_id']);
        $table->addForeignKey('user_id', 'users', 'id', [
                'update' => 'NO_ACTION',
                'delete' => 'SET_NULL',
                'constraint' => 'tags_user_id',
            ]);
        $table->create();

        $this->io->out(__('Creating table: {0}', ['qr_codes_tags']));
        $table = $this->table('qr_codes_tags', $this->tableOptions());
        $table->addColumn('id', 'integer', $this->primaryKeyOptions());
        $table->addColumn('tag_id', 'integer', ['null' => false])
            ->addIndex(['tag_id']);
        $table->addColumn('qr_code_id', 'integer', ['null' => false])
            ->addIndex(['qr_code_id']);
        $table->addForeignKey('tag_id', 'tags', 'id', [
                'update' => 'RESTRICT',
                'delete' => 'CASCADE',
                'constraint' => 'qr_codes_tags_tag_id',
            ]);
        $table->addForeignKey('qr_code_id', 'qr_codes', 'id', [
                'update' => 'RESTRICT',
                'delete' => 'CASCADE',
                'constraint' => 'qr_codes_tags_qr_code_id',
            ]);
        $table->create();

        $this->afterChange();
    }
}
