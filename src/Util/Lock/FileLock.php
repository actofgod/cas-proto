<?php
declare(strict_types=1);

namespace App\Util\Lock;

/**
 * @package App\Util\Lock
 */
class FileLock implements LockInterface
{
    /**
     * @var string
     */
    private $fileName;

    /**
     * @var resource|null
     */
    private $fileDescriptor;

    /**
     * @var bool
     */
    private $isLocked;

    /**
     * @param string $fileName
     */
    public function __construct(string $fileName)
    {
        $this->fileName = $fileName;
        $this->isLocked = false;
    }

    /**
     */
    public function __destruct()
    {
        if (null !== $this->fileDescriptor) {
            if ($this->isLocked) {
                $this->unlock();
            }
            fclose($this->fileDescriptor);
        }
    }

    /**
     * @inheritdoc
     * @throws \RuntimeException
     */
    public function lock(bool $block = true): bool
    {
        if (!$this->isLocked) {
            $flags = LOCK_EX;
            if (!$block) {
                $flags |= LOCK_NB;
            }
            $this->isLocked = flock($this->getFileDescriptor(), $flags);
        }
        return $this->isLocked;
    }

    /**
     * @inheritdoc
     */
    public function unlock()
    {
        if ($this->isLocked) {
            flock($this->getFileDescriptor(), LOCK_UN);
            $this->isLocked = false;
        }
    }

    /**
     * @return resource
     * @throws \RuntimeException
     */
    public function getFileDescriptor()
    {
        if (null === $this->fileDescriptor) {
            if (file_exists($this->fileName)) {
                $fd = fopen($this->fileName, 'r+');
            } else {
                $fd = fopen($this->fileName, 'a+');
            }
            if (false === $fd) {
                throw new \RuntimeException(sprintf('Failed to open lock file "%s"', $this->fileName));
            }
            $this->fileDescriptor = $fd;
        }
        return $this->fileDescriptor;
    }
}