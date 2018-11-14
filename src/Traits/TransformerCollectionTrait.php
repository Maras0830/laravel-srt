<?php
namespace Maras0830\LaravelSRT\Traits;

use League\Fractal\Manager;
use League\Fractal\TransformerAbstract;

trait TransformerCollectionTrait
{
    /**
     * Change laravel collection to Transformer format
     *
     * @param $collection
     * @param TransformerAbstract $transformerAbstract
     * @return mixed
     */
    public function transCollection($collection, TransformerAbstract $transformerAbstract = null)
    {
        if (is_null($transformerAbstract))
            $transformerAbstract = app(get_class($this));

        $collection = $this->collection($collection, $transformerAbstract);

        $response = app(Manager::class)->createData($collection)->toArray()['data'];

        return $response;
    }

    /**
     * Change laravel collection to Transformer format
     *
     * @param $collection
     * @param $group_key
     * @param TransformerAbstract $transformerAbstract
     * @return mixed
     */
    public function transCollectionGroup($collection, $group_key, TransformerAbstract $transformerAbstract = null)
    {
        $result = [];

        if (is_null($transformerAbstract))
            $transformerAbstract = app(get_class($this));

        $collection = $this->collection($collection, $transformerAbstract);

        $response = app(Manager::class)->createData($collection)->toArray()['data'];

        foreach ($response as $res) {
            $result[$res[$group_key]][] = $res;
        }

        return array_values($result);
    }
}