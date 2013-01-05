<?php
/**
 * This file is part of NoiselabsDistributionModule
 *
 * NoiselabsDistributionModule is free software; you can redistribute it
 * and/or modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 3 of the License, or (at your option) any later version.
 *
 * NoiselabsDistributionModule is distributed in the hope that it will be
 * useful, but WITHOUT ANY WARRANTY; without even the implied warranty
 * of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with NoiselabsDistributionModule; if not, see
 * <http://www.gnu.org/licenses/>.
 *
 * Copyright (C) 2013 Vítor Brandão
 *
 * @category    NoiseLabs
 * @package     DistributionBundle
 * @copyright   (C) 2013 Vítor Brandão <noisebleed@noiselabs.org>
 * @license     http://www.gnu.org/licenses/lgpl-3.0-standalone.html LGPL-3
 * @link        http://www.noiselabs.org
 */

namespace NoiseLabs\Module\DistributionModule;

use PPI\Module\AbstractModule;

/**
 * PPI Framework DistributionModule.
 *
 * @author Vítor Brandão <noisebleed@noiselabs.org>
 */
class Module extends AbstractModule
{
    const VERSION = '0.0.1-DEV';

    protected $_moduleName = 'NoiselabsDistributionModule';

    public function init($e)
    {
        Autoload::add(__NAMESPACE__, dirname(__DIR__));
    }
}
