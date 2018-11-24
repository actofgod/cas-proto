<?php
declare(strict_types=1);

namespace App\Util;

/**
 * @package App\Util
 */
class ClassLoader
{
    /**
     * @var string[]
     */
    private $namespaceList;

    /**
     * @param string $applicationNamespace
     * @param string $applicationPath
     */
    public function __construct(string $applicationNamespace, string $applicationPath)
    {
        $this->namespaceList[$applicationNamespace] = realpath($applicationPath) . DIRECTORY_SEPARATOR;
    }

    /**
     * @param string $className
     */
    public function load(string $className): void
    {
        if (!class_exists($className)) {
            $fileName = $this->resolveFileName($className);
            if (!empty($fileName) && file_exists($fileName)) {
                require $fileName;
            }
        }
    }

    /**
     * @param string $className
     * @return string
     */
    public function resolveFileName(string $className): string
    {
        foreach ($this->namespaceList as $namespace => $path) {
            if (0 === strncmp($namespace, $className, strlen($namespace))) {
                return $path . $this->convertClassNameToFileName($namespace, $className) . '.php';
            }
        }
        return '';
    }

    /**
     * @param string $namespace
     * @param string $className
     * @return string
     */
    private function convertClassNameToFileName(string $namespace, string $className): string
    {
        if (DIRECTORY_SEPARATOR === '\\') {
            return substr($className, strlen($namespace));
        }
        return str_replace('\\', '/', substr($className, strlen($namespace)));
    }
}
