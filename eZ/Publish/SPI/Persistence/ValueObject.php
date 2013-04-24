<?php
/**
 * File containing the ValueObject class
 *
 * @copyright Copyright (C) 1999-2013 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 */

namespace eZ\Publish\SPI\Persistence;

use eZ\Publish\API\Repository\Values\ValueObject as APIValueObject;

/**
 * Base SPI Value object
 *
 * All properties of SPI\ValueObject *must* be serializable for cache & NoSQL use.
 */
abstract class ValueObject extends APIValueObject
{
}
