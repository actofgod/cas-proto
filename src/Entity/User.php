<?php
declare(strict_types=1);

namespace App\Entity;

/**
 * @package App\Entity
 */
class User
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $passwordHash;

    /**
     * @param array $data
     */
    public function __construct(int $id, array $data)
    {
        $this->id = $id;
        $this->username = mb_strtolower($data['username'], 'utf-8');
        $this->passwordHash = $data['password'];
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @param string $plainPassword
     * @return bool
     */
    public function comparePassword(string $plainPassword): bool
    {
        return password_verify($plainPassword, $this->passwordHash);
    }
}
