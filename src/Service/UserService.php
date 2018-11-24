<?php
declare(strict_types=1);

namespace App\Service;

use App\Entity\User;

/**
 * @package App\Service
 */
class UserService extends AbstractService
{
    /**
     * @var User[]
     */
    private $userList;

    /**
     * @param string $username
     * @return User|null
     */
    public function findByUsername(string $username): ?User
    {
        return $this->userList[$username] ?? null;
    }

    /**
     * @param array $config
     */
    protected function init(array $config)
    {
        foreach ($config['users'] as $id => $userData) {
            $user = new User($id, $userData);
            $this->userList[$user->getUsername()] = $user;
        }
    }
}
