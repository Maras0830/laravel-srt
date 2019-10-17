<?php
namespace Maras0830\LaravelSRT\Traits;

use League\Fractal\Manager;
use League\Fractal\TransformerAbstract;
use Maras0830\LaravelSRT\Exceptions\TransformerException;

trait TransformerCollectionTrait
{
    protected $required_relation_keys = [];

    /**
     * @param array $keys
     * @return $this
     */
    public function setRequiredRelations(array $keys = [])
    {
        $this->required_relation_keys = $keys;

        return $this;
    }

    /**
     * Change laravel collection to Transformer format
     *
     * @param $collection
     * @param TransformerAbstract $transformerAbstract
     * @return mixed
     * @throws TransformerException
     */
    public function transCollection($collection, TransformerAbstract $transformerAbstract = null)
    {
        if (!empty($this->required_relation_keys)) {
            foreach ($this->required_relation_keys as $required_relation_key) {
                foreach ($collection as $model) {
                    if (!$model->relationLoaded($required_relation_key)) {
                        throw new TransformerException('required relation key not exist');
                    }
                }
            }
        }

        if (is_null($transformerAbstract)) {
            $vars = get_object_vars($this);
            $class = new \ReflectionClass($this);
            $constructor = $class->getConstructor();

            $parameters = [];

            if ($constructor) {
                foreach ($constructor->getParameters() as $parameter) {
                    $parameters[$parameter->getName()] = $vars[$parameter->getName()];
                }
            }

            $transformerAbstract = app(get_class($this), $parameters);
        }

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
     * @param bool $keys
     * @return mixed
     */
    public function transCollectionGroup($collection, $group_key, TransformerAbstract $transformerAbstract = null, $keys = false)
    {
        $result = [];

        if (is_null($transformerAbstract)) {
            $vars = get_object_vars($this);
            $class = new \ReflectionClass($this);
            $constructor = $class->getConstructor();

            $parameters = [];

            if ($constructor) {
                foreach ($constructor->getParameters() as $parameter) {
                    $parameters[$parameter->getName()] = $vars[$parameter->getName()];
                }
            }

            $transformerAbstract = app(get_class($this), $parameters);
        }

        $collection = $this->collection($collection, $transformerAbstract);

        $response = app(Manager::class)->createData($collection)->toArray()['data'];

        foreach ($response as $res) {
            $result[$res[$group_key]][] = $res;
        }

        if ($keys) {
            return $result;
        } else {
            return array_values($result);
        }
    }
}
