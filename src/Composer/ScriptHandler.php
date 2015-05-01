<?php
/**
 * This file is part of the PPI Framework.
 *
 * DISCLAIMER:
 *
 *   This file was adapted from the original ScriptHandler distributed by
 *   the Symfony team in the SensioDistributionBundle.
 *
 *   (c) Fabien Potencier <fabien@symfony.com>
 *
 * @category    PPI
 * @package     DistributionModule
 * @copyright   Copyright (c) 2011-2013 Paul Dragoonis <paul@ppi.io>
 * @license     http://opensource.org/licenses/mit-license.php MIT
 * @link        http://www.ppi.io
 */

namespace PPI\DistributionModule\Composer;

use Symfony\Component\Process\Process;
use Symfony\Component\Process\PhpExecutableFinder;
use Composer\Script\Event;

/**
 * Composer script handler.
 *
 * @author Jordi Boggiano <j.boggiano@seld.be>
 * @author Vítor Brandão <vitor@ppi.io>
 * @author Paul Dragoonis <paul@ppi.io>
 */
class ScriptHandler
{

    /**
     * Apply open writable permissions to logs and cache folders
     *
     * @param $event
     */
    public static function permissionDirs(Event $event)
    {
        $options = self::getOptions($event);
        $appDir = $options['ppi-app-dir'];
        $logsDir = realpath($appDir) . '/logs';
        $cacheDir = realpath($appDir) . '/cache';
        foreach(array($logsDir, $cacheDir) as $dir) {
            if(is_dir($dir) && is_writable($dir)) {
                @chmod($dir, 777);
            }
        }

    }

    public static function installAssets(Event $event)
    {
        $options = self::getOptions($event);
        $appDir = $options['ppi-app-dir'];
        $webDir = $options['ppi-web-dir'];

        $symlink = '';
        if ($options['ppi-assets-install'] == 'symlink') {
            $symlink = '--symlink ';
        } elseif ($options['ppi-assets-install'] == 'symlink-relative') {
            $symlink = '--symlink --relative ';
        }

        if (!is_dir($webDir)) {
            echo 'The ppi-web-dir (' . $webDir . ') specified in composer.json was not found in ' . getcwd() . ', can not install assets.' . PHP_EOL;
            return;
        }

        static::executeCommand($event, $appDir, 'assets:install ' . $symlink . escapeshellarg($webDir));
    }

    public static function installRequirementsFile(Event $event)
    {
        $options = self::getOptions($event);
        $appDir = $options['ppi-app-dir'];

        if (!is_dir($appDir)) {
            echo 'The ppi-app-dir (' . $appDir . ') specified in composer.json was not found in ' . getcwd() . ', can not install the requirements file.' . PHP_EOL;
            return;
        }

        copy(__DIR__ . '/../resources/skeleton/app/FrameworkRequirements.php', $appDir . '/FrameworkRequirements.php');
        copy(__DIR__ . '/../resources/skeleton/app/check', $appDir . '/check');

        $webDir = $options['ppi-web-dir'];

        /*
         * TODO: Uncomment when config.php gets finished.
         *
         * <code>
        if (is_file($webDir.'/config.php')) {
            copy(__DIR__.'/../Resources/skeleton/public/config.php', $webDir.'/config.php');
        }
        * </code>
        */
    }

    protected static function executeCommand(Event $event, $appDir, $cmd, $timeout = 300)
    {
        $php = escapeshellarg(self::getPhp());
        $console = escapeshellarg($appDir . '/console');
        if ($event->getIO()->isDecorated()) {
            $console .= ' --ansi';
        }

        $process = new Process($php . ' ' . $console . ' ' . $cmd, null, null, null, $timeout);
        $process->run(function ($type, $buffer) {
            echo $buffer;
        });
        if (!$process->isSuccessful()) {
            throw new \RuntimeException(sprintf('An error occurred when executing the "%s" command.', escapeshellarg($cmd)));
        }
    }

    protected static function getOptions(Event $event)
    {
        $options = array_merge(array(
            'ppi-app-dir' => 'app',
            'ppi-web-dir' => 'web',
            'ppi-assets-install' => 'hard'
        ), $event->getComposer()->getPackage()->getExtra());

        $options['ppi-assets-install'] = getenv('PPI_ASSETS_INSTALL') ? : $options['ppi-assets-install'];

        $options['process-timeout'] = $event->getComposer()->getConfig()->get('process-timeout');

        return $options;
    }

    protected static function getPhp()
    {
        $phpFinder = new PhpExecutableFinder;
        if (!$phpPath = $phpFinder->find()) {
            throw new \RuntimeException('The php executable could not be found, add it to your PATH environment variable and try again');
        }

        return $phpPath;
    }
}
