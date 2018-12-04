<?php

declare(strict_types=1);

namespace Soa\IdentifierGeneratorMongoDb;

use Soa\IdentifierGenerator\IdentifierGenerator;
use MongoDB\Collection;
use MongoDB\Operation\FindOneAndUpdate;

class IdentifierGeneratorAutoIncrementMongoDb implements IdentifierGenerator
{
    /**
     * @var Collection
     */
    private $collection;

    public function __construct(Collection $collection)
    {
        $this->collection = $collection;
    }

    public function nextIdentity(string $entity = ''): string
    {
        $result = $this->collection->findOneAndUpdate(
            ['_id' => $entity],
            ['$inc' => ['seq' => 1]],
            [
                'upsert'         => true,
                'projection'     => ['seq' => 1],
                'returnDocument' => FindOneAndUpdate::RETURN_DOCUMENT_AFTER,
            ]
        );

        return (string) $result['seq'];
    }
}
