<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
* files found in the top-level directory of this distribution.
 */

/**
 * A class map for storing class relationships.
 *
 * @deprecated Use $modx->getDescendants as of 2.2.
 *
 * @property string $class The name of the class
 * @property string $parent_class The parent class this class extends
 * @property string $name_field The name of the unique field to grab as a title for this field
 * @property string $path The path to the class file
 * @property string $lexicon Any lexicon items to load for this class
 * @package modx
 */
class modClassMap extends xPDOSimpleObject {
}
