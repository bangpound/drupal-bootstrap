<?php

namespace Bangpound\Drupal\Composer;

use Bangpound\Drupal\Autoload\ClassMapGenerator;
use Composer\Script\Event;
use Symfony\Component\Finder\Finder;

class ScriptHandler
{
    /**
     * Paths in Drupal root to scan for classes.
     *
     * This should never include profiles and sites, because those are scanned to
     * generate separate classmaps.
     *
     * @var array
     */
    protected static $root_paths = array(
        'includes', 'misc', 'modules', 'scripts', 'themes',

        // None of these files actually contain PHP classes, but the are
        // scanned anyway.
        'authorize.php', 'cron.php', 'index.php', 'install.php',
        'update.php', 'xmlrpc.php',
    );

    /**
     * Paths in subdirectories (profiles and sites) to scan for classes.
     *
     * @var array
     */
    protected static $subdir_paths = array(
        'modules', 'themes', 'plugins',
    );

    public static function dumpAutoload(Event $event)
    {
        $cwd = getcwd();
        $io = $event->getIO();

        $options = self::getOptions($event);
        if (!empty($options['drupal-root'])) {
            chdir($options['drupal-root']);
        }

        $generator = new ClassMapGenerator();
        $dirs = array_filter(self::$root_paths, 'file_exists');
        $io->write('Dumping classmap for <info>DRUPAL_ROOT</info>');
        $generator->dump($dirs, 'classmap.php');

        $finder = Finder::create()
            ->directories()
            ->depth(0)
            ->followLinks()
            ->in(array('profiles', 'sites'))
        ;

        foreach ($finder as $file) {
            /** @var \Symfony\Component\Finder\SplFileInfo $file */
            chdir($file->getPathInfo() .'/'. $file->getRelativePathname());
            $io->write(sprintf('Dumping classmap for <info>%s</info>', $file->getPathInfo() .'/'. $file->getRelativePathname()));
            $dirs = array_filter(self::$subdir_paths, 'file_exists');
            $generator->dump($dirs, 'classmap.php');
            chdir($cwd);
        }
    }

    protected static function getOptions(Event $event)
    {
        $options = array_merge(array(
            'drupal-root' => '',
        ), $event->getComposer()->getPackage()->getExtra());

        return $options;
    }
}
