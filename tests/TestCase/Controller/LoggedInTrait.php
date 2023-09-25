<?php
declare(strict_types=1);

namespace App\Test\TestCase\Controller;

use App\Model\Table\UsersTable;

trait LoggedInTrait
{
    /**
     * @var \App\Model\Table\UsersTable
     */
    public $Users;

    /**
     * Logs in a User
     */
    public function loginUser(int $id): void
    {
        if (!$this->Users) {
            $config = $this->getTableLocator()->exists('Users') ? [] : ['className' => UsersTable::class];
            /** @var \App\Model\Table\UsersTable $Users */
            $Users = $this->getTableLocator()->get('Users', $config);
            $this->Users = $Users;
        }

        $user = $this->Users->get((int)$id);

        $this->session([
            'Auth' => $user,
        ]);
    }

    /**
     * Logs in an Admin user
     *
     * @return void
     */
    public function loginUserAdmin(): void
    {
        $this->loginUser(1);
    }

    /**
     * Logs in a Regular user
     *
     * @return void
     */
    public function loginUserRegular(): void
    {
        $this->loginUser(2);
    }
}
