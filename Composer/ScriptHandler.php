<?php
/**
 * This file is part of NoiselabsDistributionBundle
 *
 * NoiselabsDistributionBundle is free software; you can redistribute it
 * and/or modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 3 of the License, or (at your option) any later version.
 *
 * NoiselabsDistributionBundle is distributed in the hope that it will be
 * useful, but WITHOUT ANY WARRANTY; without even the implied warranty
 * of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with NoiselabsDistributionBundle; if not, see
 * <http://www.gnu.org/licenses/>.
 *
 *
 * DISCLAIMER:
 *
 *   This file was adapted from the original ScriptHandler distributed by
 *   the Symfony team in the SensionDistributionBundle.
 *
 *   (c) Fabien Potencier <fabien@symfony.com>
 *
 *
 * @category    NoiseLabs
 * @package     DistributionBundle
 * @copyright   (C) 2013 Vítor Brandão <noisebleed@noiselabs.org>
 * @license     http://www.gnu.org/licenses/lgpl-3.0-standalone.html LGPL-3
 * @link        http://www.noiselabs.org
 */

namespace NoiseLabs\Module\DistributionModule\Composer;

use Symfony\Component\Process\Process;
use Symfony\Component\Process\PhpExecutableFinder;

/**
 * Composer script handler.
 *
 * @author Jordi Boggiano <j.boggiano@seld.be>
 * @author Vítor Brandão <noisebleed@noiselabs.org>
 */
class ScriptHandler
{
    public static function installAssets($event)
    {
        $options = self::getOptions($event);
        $appDir = $options['ppi-app-dir'];
        $webDir = $options['ppi-web-dir'];

        $symlink = '';
        if ($options['ppi-assets-install'] == 'symlink') {
            $symlink = '--symlink ';
        } elseif ($options['ppi-assets-install'] == 'relative') {
            $symlink = '--symlink --relative ';
        }

        if (!is_dir($webDir)) {
            echo 'The ppi-web-dir ('.$webDir.') specified in composer.json was not found in '.getcwd().', can not install assets.'.PHP_EOL;

            return;
        }

        static::executeCommand($event, $appDir, 'assets:install '.$symlink.escapeshellarg($webDir));
    }

    public static function installRequirementsFile($event)
    {
        $options = self::getOptions($event);
        $appDir = $options['ppi-app-dir'];

        if (!is_dir($appDir)) {
            echo 'The ppi-app-dir ('.$appDir.') specified in composer.json was not found in '.getcwd().', can not install the requirements file.'.PHP_EOL;

            return;
        }

        copy(__DIR__.'/../resources/skeleton/app/FrameworkRequirements.php', $appDir.'/FrameworkRequirements.php');
        copy(__DIR__.'/../resources/skeleton/app/check', $appDir.'/check');

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

    protected static function executeCommand($event, $appDir, $cmd, $timeout = 300)
    {
        $php = escapeshellarg(self::getPhp());
        $console = escapeshellarg($appDir.'/console');
        if ($event->getIO()->isDecorated()) {
            $console .= ' --ansi';
        }

        $process = new Process($php.' '.$console.' '.$cmd, null, null, null, $timeout);
        $process->run(function ($type, $buffer) { echo $buffer; });
        if (!$process->isSuccessful()) {
            throw new \RuntimeException(sprintf('An error occurred when executing the "%s" command.', escapeshellarg($cmd)));
        }
    }

    protected static function getOptions($event)
    {
        $options = array_merge(array(
            'ppi-app-dir' => 'app',
            'ppi-web-dir' => 'web',
            'ppi-assets-install' => 'hard'
        ), $event->getComposer()->getPackage()->getExtra());

        $options['ppi-assets-install'] = getenv('PPI_ASSETS_INSTALL') ?: $options['ppi-assets-install'];

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
