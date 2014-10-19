<?php

namespace Zoop\Collection\Test\Controller;

use Zend\Http\Header\Origin;
use Zend\Http\Header\Host;
use Zoop\Collection\DataModel\CollectionInterface;
use Zoop\Collection\Test\AbstractTest;
use Zoop\Test\Helper\DataHelper;

class SimpleCrudTest extends AbstractTest
{
    private static $zoopUserKey = 'joshstuart';
    private static $zoopUserSecret = 'password1';

    public function testNoAuthorizationCreate()
    {
        $data = [
            "slug" => "t-shirts",
            "name" => "T-Shirts"
        ];

        $request = $this->getRequest();
        $request->setContent(json_encode($data));

        $this->applyJsonRequest($request);

        $request->setMethod('POST')
            ->getHeaders()->addHeaders([
                Origin::fromString('Origin: http://api.zoopcommerce.local'),
                Host::fromString('Host: api.zoopcommerce.local')
            ]);

        $this->dispatch(sprintf(self::$endpoint, 'apple'));
        $response = $this->getResponse();

        $this->assertResponseStatusCode(500);
    }

    public function testCreateSuccess()
    {
        self::getDocumentManager()->clear();

        $slug = "t-shirts";
        $name = "T-Shirts";
        $data = [
            "slug" => $slug,
            "name" => $name,
            "store" => "apple"
        ];

        DataHelper::createStores(self::getNoAuthDocumentManager(), self::getDbName());
        DataHelper::createZoopUser(self::getNoAuthDocumentManager(), self::getDbName());

        $post = json_encode($data);
        $request = $this->getRequest();
        $request->setContent($post);

        $this->applyJsonRequest($request);
        $this->applyUserToRequest($request, self::$zoopUserKey, self::$zoopUserSecret);

        $request->setMethod('POST')
            ->getHeaders()->addHeaders([
                Origin::fromString('Origin: http://api.zoopcommerce.local'),
                Host::fromString('Host: api.zoopcommerce.local')
            ]);

        $this->dispatch(sprintf(self::$endpoint, 'apple'));
        $response = $this->getResponse();

        $this->assertResponseStatusCode(201);

        $collectionId = str_replace(
            ['Location: ', '/store/apple/collections/'],
            '',
            $response->getHeaders()->get('Location')->toString()
        );

        $this->assertNotNull($collectionId);

        self::getNoAuthDocumentManager()->clear();

        $collection = DataHelper::get(
            self::getNoAuthDocumentManager(),
            'Zoop\Collection\DataModel\AbstractCollection',
            $collectionId
        );
        $this->assertNotEmpty($collection);
        $this->assertEquals($name, $collection->getName());
        $this->assertEquals($slug, $collection->getSlug());
        $this->assertEquals('apple', $collection->getStore());

        return $collectionId;
    }

    /**
     * @depends testCreateSuccess
     */
    public function testGetListSuccess($collectionId)
    {
        self::getDocumentManager()->clear();

        $request = $this->getRequest();

        $this->applyJsonRequest($request);
        $this->applyUserToRequest($request, self::$zoopUserKey, self::$zoopUserSecret);

        $request->setMethod('GET')
            ->getHeaders()->addHeaders([
                Origin::fromString('Origin: http://api.zoopcommerce.local'),
                Host::fromString('Host: api.zoopcommerce.local')
            ]);

        $this->dispatch(sprintf(self::$endpoint, 'apple'));
        $response = $this->getResponse();

        $this->assertResponseStatusCode(200);

        $json = $response->getContent();
        $this->assertJson($json);

        $content = json_decode($json, true);

        $this->assertCount(1, $content);

        $collection = $content[0];

        $this->assertEquals('t-shirts', $collection['slug']);
        $this->assertEquals('T-Shirts', $collection['name']);
    }

    /**
     * @depends testCreateSuccess
     */
    public function testGetSuccess($collectionId)
    {
        self::getDocumentManager()->clear();

        $request = $this->getRequest();

        $this->applyJsonRequest($request);
        $this->applyUserToRequest($request, self::$zoopUserKey, self::$zoopUserSecret);

        $request->setMethod('GET')
            ->getHeaders()->addHeaders([
                Origin::fromString('Origin: http://api.zoopcommerce.local'),
                Host::fromString('Host: api.zoopcommerce.local')
            ]);

        $this->dispatch(sprintf(self::$endpoint . '/%s', 'apple', $collectionId));
        $response = $this->getResponse();

        $this->assertResponseStatusCode(200);

        $json = $response->getContent();
        $this->assertJson($json);

        $collection = json_decode($json, true);

        $this->assetNotNull($collection);

        $this->assertEquals('t-shirts', $collection['slug']);
        $this->assertEquals('T-Shirts', $collection['name']);
    }

    /**
     * @depends testCreateSuccess
     */
    public function testPatchSuccess($collectionId)
    {
        self::getDocumentManager()->clear();

        $name = "Hoodies";
        $data = [
            "name" => $name
        ];

        $request = $this->getRequest();
        $request->setContent(json_encode($data));

        $this->applyJsonRequest($request);
        $this->applyUserToRequest($request, self::$zoopUserKey, self::$zoopUserSecret);

        $request->setMethod('PATCH')
            ->getHeaders()->addHeaders([
                Origin::fromString('Origin: http://api.zoopcommerce.local'),
                Host::fromString('Host: api.zoopcommerce.local')
            ]);

        $this->dispatch(sprintf(self::$endpoint . '/%s', 'apple', $collectionId));
        $response = $this->getResponse();

        $this->assertResponseStatusCode(204);

        self::getNoAuthDocumentManager()->clear();

        $collection = DataHelper::get(
            self::getNoAuthDocumentManager(),
            'Zoop\Collection\DataModel\AbstractCollection',
            $collectionId
        );

        $this->assertTrue($collection instanceof CollectionInterface);
        $this->assertEquals($name, $collection->getName());
    }

    /**
     * @depends testCreateSuccess
     */
    public function testDeleteSuccess($collectionId)
    {
        $request = $this->getRequest();

        $this->applyJsonRequest($request);
        $this->applyUserToRequest($request, self::$zoopUserKey, self::$zoopUserSecret);

        $request->setMethod('DELETE')
            ->getHeaders()->addHeaders([
                Origin::fromString('Origin: http://api.zoopcommerce.local'),
                Host::fromString('Host: api.zoopcommerce.local')
            ]);

        $this->dispatch(sprintf(self::$endpoint . '/%s', 'apple', $collectionId));
        $response = $this->getResponse();

        $this->assertResponseStatusCode(204);

        //we need to just do a soft delete rather than a hard delete
        self::getNoAuthDocumentManager()->clear();
        $collection = DataHelper::get(
            self::getNoAuthDocumentManager(),
            'Zoop\Collection\DataModel\AbstractCollection',
            $collectionId
        );
        $this->assertEmpty($collection);
    }
}
