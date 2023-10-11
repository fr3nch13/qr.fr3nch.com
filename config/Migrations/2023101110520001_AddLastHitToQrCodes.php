<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class AddLastHitToQrCodes extends AbstractMigration
{
    use \App\Migrations\QrMigrationTrait;

    /**
     * Adds the last_hit column to the qr_codes table.
     *
     * More information on this method is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     * @return void
     */
    public function change(): void
    {
        $this->beforeChange();

        $table = $this->table('qr_codes');
        $this->io->out(__('Adding column `{0}` to table: `{1}`', [
            'last_hit',
            'qr_codes',
        ]));

        $table->addColumn('last_hit', 'datetime', [
            'default' => null,
            'null' => true,
        ]);
        $table->update();

        $this->afterChange();
    }
}
