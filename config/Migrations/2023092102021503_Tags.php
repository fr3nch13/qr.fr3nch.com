<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class Tags extends AbstractMigration
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

        $this->io->out(__('Creating table: {0}', ['tags']));

        $table = $this->table('tags', $this->tableOptions());
        $table->addColumn('id', 'integer', $this->primaryKeyOptions());
        $table->addColumn('name', 'string', [
            'default' => null,
            'limit' => 255,
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


        $this->io->out(__('Creating table: {0}', ['qr_codes_tags']));
        $table = $this->table('qr_codes_tags', $this->tableOptions());
        $table->addColumn('id', 'integer', $this->primaryKeyOptions());
        $table->addColumn('tag_id', 'integer', [
            'default' => null,
            'null' => false,
        ])->addIndex(['tag_id']);
        $table->addColumn('qr_code_id', 'integer', [
            'default' => null,
            'null' => false,
        ])->addIndex(['qr_code_id']);
        $this->io->out(__('Adding Foreign Key: {0} -> {1}.{2}', [
            'tag_id', 'tags', 'id'
        ]));
        // @todo #2 Figure out why my foreign keys aren't being created.
        $table->addForeignKey('tag_id', 'tags', 'id', [
                'update' => 'RESTRICT',
                'delete' => 'CASCADE',
                'constraint' => 'qr_codes_tags_tag_id',
            ]);
        $this->io->out(__('Adding Foreign Key: {0} -> {1}.{2}', [
            'qr_code_id', 'qr_codes', 'id'
        ]));
        $table->addForeignKey('qr_code_id', 'qr_codes', 'id', [
                'update' => 'RESTRICT',
                'delete' => 'CASCADE',
                'constraint' => 'qr_codes_tags_qr_code_id',
            ]);
        $table->create();

        $this->afterChange();
    }
}
