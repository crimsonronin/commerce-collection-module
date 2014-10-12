<?php

namespace Zoop\Collection\DataModel;

use Zoop\Shard\Stamp\DataModel\CreatedOnTrait;
use Zoop\Shard\Stamp\DataModel\CreatedByTrait;
use Zoop\Shard\Stamp\DataModel\UpdatedOnTrait;
use Zoop\Shard\Stamp\DataModel\UpdatedByTrait;
use Zoop\Shard\SoftDelete\DataModel\SoftDeleteableTrait;
use Zoop\Common\DataModel\StoreTrait;
//Annotation imports
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Zoop\Shard\Annotation\Annotations as Shard;

/**
 * @ODM\Document(collection="Collection")
 * @ODM\InheritanceType("SINGLE_COLLECTION")
 * @ODM\DiscriminatorField(fieldName="type")
 * @ODM\DiscriminatorMap({
 *     "StaticCollection"               = "StaticCollection"
 * })
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
abstract class AbstractCollection
{
    use CreatedOnTrait;
    use CreatedByTrait;
    use UpdatedOnTrait;
    use UpdatedByTrait;
    use SoftDeleteableTrait;
    use StoreTrait;

    /**
     * @ODM\Id(strategy="UUID")
     */
    protected $id;

    /**
     * @ODM\String
     * @ODM\Index(order="asc")
     */
    protected $name;

    /**
     *
     * @ODM\String
     * @ODM\UniqueIndex(order="asc")
     * @Shard\Validator\Chain({
     *     @Shard\Validator\Required,
     *     @Shard\Validator\Slug
     * })
     */
    protected $slug;
    
    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getSlug()
    {
        return $this->slug;
    }

    public function setSlug($slug)
    {
        $this->slug = $slug;
    }
}
