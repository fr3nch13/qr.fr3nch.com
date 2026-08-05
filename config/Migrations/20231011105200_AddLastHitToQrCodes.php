<?php
declare(strict_types=1);

use Migrations\BaseMigration;

class AddLastHitToQrCodes extends BaseMigration
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
        $this->migrationIo()->out(__('Adding column `{0}` to table: `{1}`', [
            'last_hit',
            'qr_codes',
        ]));

        $table->addColumn('last_hit', 'datetime', ['null' => true]);
        $table->update();

        $this->afterChange();
    }
}
