<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class AddGravatarEmailToUsers extends AbstractMigration
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

        $table = $this->table('users');
        $this->io->out(__('Adding column `{0}` to table: `{1}`', [
            'gravatar_email',
            'users',
        ]));
        $table->addColumn('gravatar_email', 'string');
        $table->update();

        $this->afterChange();
    }
}
