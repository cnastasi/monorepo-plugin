<?php

namespace Cnastasi\MonorepoPlugin;

use Composer\Composer;
use Composer\IO\IOInterface;
use Composer\Package\PackageInterface;
use Composer\Plugin\PluginInterface;
use Composer\Repository\RepositoryManager;
use DirectoryIterator;

class MonorepoPlugin implements PluginInterface
{
    public function activate(Composer $composer, IOInterface $io)
    {
        $path = $this->getPathsFromExtra($composer->getPackage());

        if ($path) {
            $folder = $this->getFolder($path);

            $this->loadPaths($io, $composer->getRepositoryManager(), $folder);
        }
//
    }

    private function getFolder(string $path): DirectoryIterator
    {
        return new DirectoryIterator(getcwd() . DIRECTORY_SEPARATOR . $path);
    }

    /**
     * @param string[] $paths
     * @return void
     */
    protected function loadPaths(IOInterface $io, RepositoryManager $repositoryManager, DirectoryIterator $folder): void
    {
        /** @var DirectoryIterator $item */
        foreach ($folder as $item) {
            if ($item->isDot() || $item->isFile()) continue;

            $isPackage = $this->isPackage($item) ? 'YES' : 'NO';
            echo "{$item->getFilename()} [{$isPackage}]";

            if ($this->isPackage($item)) {
                $config = ['url' => $item->getRealPath()];
                $repo = $repositoryManager->createRepository('path', $config);
                $repositoryManager->addRepository($repo);
            }

            echo "\n";
        }

        $repositories = $repositoryManager->getRepositories();

        foreach ($repositories as $repository) {
            echo $repository->getRepoName() . "\n";
        }
    }

    private function isPackage(DirectoryIterator $folder): bool
    {
        return file_exists($folder->getRealPath() . DIRECTORY_SEPARATOR . 'composer.json');
    }

    /**
     * @return string|null
     */
    protected function getPathsFromExtra(PackageInterface $package): ?string
    {
        $extra = $package->getExtra();

        return $extra['monorepo_path'] ?? null;
    }

    public function deactivate(Composer $composer, IOInterface $io)
    {
        // TODO: Implement deactivate() method.
    }

    public function uninstall(Composer $composer, IOInterface $io)
    {
        // TODO: Implement uninstall() method.
    }
}