<?php

namespace Bangpound\Drupal\Composer;

use Bangpound\Drupal\Autoload\ClassMapGenerator;
use Composer\Script\Event;
use Symfony\Component\Finder\Finder;

class ScriptHandler
{
    public static function dumpAutoload(Event $event)
    {
        $io = $event->getIO();

        $options = self::getOptions($event);
        $prefix = $options['drupal-root'];

        $generator = new ClassMapGenerator();
        $dirs = array(
            $prefix . 'includes', $prefix . 'misc', $prefix . 'modules', $prefix . 'scripts',
            $prefix . 'themes', $prefix . 'authorize.php', $prefix . 'cron.php', $prefix . 'index.php',
            $prefix . 'install.php', $prefix . 'update.php', $prefix . 'xmlrpc.php',
        );
        $dirs = array_filter($dirs, 'file_exists');
        $io->write('Dumping classmap for <info>DRUPAL_ROOT</info>');
        $generator->dump($dirs, $prefix . 'classmap.php');

        $finder = Finder::create()
            ->directories()
            ->depth(0)
            ->followLinks()
            ->in(array($prefix . 'profiles', $prefix . 'sites'))
        ;

        $cwd = getcwd();
        foreach ($finder as $file) {
            /** @var \Symfony\Component\Finder\SplFileInfo $file */
            chdir($file->getPathInfo() .'/'. $file->getRelativePathname());
            $io->write(sprintf('Dumping classmap for <info>%s</info>', $file->getPathInfo() .'/'. $file->getRelativePathname()));
            $dirs = array();
            if (file_exists('modules')) {
                $dirs[] = 'modules';
            }
            if (file_exists('themes')) {
                $dirs[] = 'themes';
            }
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
