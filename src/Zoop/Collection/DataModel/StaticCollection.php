<?php

namespace Zoop\Collection\DataModel;

use Zoop\Collection\DataModel\CollectionInterface;
use Zoop\Collection\DataModel\AbstractCollection;
//Annotation imports
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Zoop\Shard\Annotation\Annotations as Shard;

/**
 * @ODM\Document
 * @Shard\AccessControl({
 *      @Shard\Permission\Basic(
 *          roles={
 *              "owner",
 *              "zoop::admin",
 *              "company::admin",
 *              "partner::admin",
 *              "store::admin",
 *              "store::storefront"
 *          },
 *          allow="read"
 *      ),
 *      @Shard\Permission\Basic(
 *          roles={
 *              "zoop::admin",
 *              "partner::admin",
 *              "company::admin",
 *              "store::admin"
 *          },
 *          allow={
 *              "create",
 *              "delete",
 *              "update::*"
 *          }
 *      )
 * })
 */
class StaticCollection extends AbstractCollection implements CollectionInterface
{
}
