<?php

return array(
    'zoop' => [
        'api' => [
            'endpoints' => [
                'collections',
            ],
        ],
        'shard' => [
            'manifest' => [
                'commerce' => [
                    'models' => [
                        'Zoop\Collection\DataModel' => __DIR__ . '/../src/Zoop/Collection/DataModel'
                    ],
                ],
            ],
            'rest' => [
                'rest' => [
                    'collections' => [
                        'manifest' => 'commerce',
                        'class' => 'Zoop\Collection\DataModel\StaticCollection',
//                        'class' => 'Zoop\Collection\DataModel\AbstractCollection',
                        'property' => 'id',
                        'listeners' => [
                            'create' => [
                                'zoop.shardmodule.listener.unserialize',
                                'zoop.api.listener.cors',
                                'zoop.shardmodule.listener.create',
                                'zoop.shardmodule.listener.flush',
                                'zoop.shardmodule.listener.location',
                                'zoop.shardmodule.listener.prepareviewmodel'
                            ],
                            'delete' => [
                                'zoop.shardmodule.listener.delete',
                                'zoop.api.listener.cors',
                                'zoop.shardmodule.listener.flush',
                                'zoop.shardmodule.listener.prepareviewmodel'
                            ],
                            'deleteList' => [],
                            'get' => [
                                'zoop.shardmodule.listener.get',
                                'zoop.api.listener.cors',
                                'zoop.shardmodule.listener.serialize',
                                'zoop.shardmodule.listener.prepareviewmodel'
                            ],
                            'getList' => [
                                'zoop.shardmodule.listener.getlist',
                                'zoop.api.listener.cors',
                                'zoop.shardmodule.listener.serialize',
                                'zoop.shardmodule.listener.prepareviewmodel'
                            ],
                            'patch' => [
                                'zoop.shardmodule.listener.unserialize',
                                'zoop.api.listener.cors',
                                'zoop.shardmodule.listener.idchange',
                                'zoop.shardmodule.listener.patch',
                                'zoop.shardmodule.listener.flush',
                                'zoop.shardmodule.listener.prepareviewmodel'
                            ],
                            'patchList' => [],
                            'update' => [
                                'zoop.shardmodule.listener.unserialize',
                                'zoop.api.listener.cors',
                                'zoop.shardmodule.listener.idchange',
                                'zoop.shardmodule.listener.update',
                                'zoop.shardmodule.listener.flush',
                                'zoop.shardmodule.listener.prepareviewmodel'
                            ],
                            'replaceList' => [],
                            'options' => [
                                'zoop.api.listener.options',
                                'zoop.shardmodule.listener.prepareviewmodel'
                            ],
                        ],
                    ],
                ]
            ],
        ],
    ],
    'service_manager' => [
        'invokables' => [],
        'factories' => [],
        'abstract_factories' => []
    ],
);
