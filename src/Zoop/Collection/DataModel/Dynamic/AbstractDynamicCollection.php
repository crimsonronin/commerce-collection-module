<?php

namespace Zoop\Collection\DataModel\Dynamic;

use Zoop\Collection\DataModel\AbstractCollection;
//Annotation imports
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Zoop\Shard\Annotation\Annotations as Shard;

/**
 * @ODM\Document
 */
abstract class AbstractDynamicCollection extends AbstractCollection
{

}
