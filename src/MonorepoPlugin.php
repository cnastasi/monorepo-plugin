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
        $paths = $this->getPathsFromExtra($composer->getPackage());

        if (count($paths) > 0) {
            $io->write('Monorepo Plugin activated!');

            foreach ($paths as $path) {
                $folder = $this->getFolder($path);

                $this->loadPaths($io, $composer->getRepositoryManager(), $folder);
            }
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
            $io->debug("Found [{$item->getFilename()}]. Is a package? [{$isPackage}]");

            if ($this->isPackage($item)) {
                $config = ['url' => $item->getRealPath()];
                $repo = $repositoryManager->createRepository('path', $config);
                $repositoryManager->addRepository($repo);
            }
        }

        /**
        $repositories = $repositoryManager->getRepositories();

        foreach ($repositories as $repository) {
            echo $repository->getRepoName() . "\n";
        }
         */
    }

    private function isPackage(DirectoryIterator $folder): bool
    {
        return file_exists($folder->getRealPath() . DIRECTORY_SEPARATOR . 'composer.json');
    }

    protected function getPathsFromExtra(PackageInterface $package): array
    {
        $extra = $package->getExtra();

        $paths = $extra['monorepo_paths'] ?? [];

        assert(is_array($paths), 'paths field expected to be an array');

        $path = $extra['monorepo_path'] ?? null;

        assert(is_string($path) || $path === null, 'path field expected to be a string');

        if ($path) {
            $paths[] = $path;
        }

        return $paths;
    }

    protected function getPathFromExtra(PackageInterface $package): ?string
    {
        $extra = $package->getExtra();

        return  $extra['monorepo_path'] ?? null;
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