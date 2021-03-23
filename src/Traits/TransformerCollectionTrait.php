<?php
namespace Maras0830\LaravelSRT\Traits;

use Illuminate\Support\Facades\Log;
use League\Fractal\Manager;
use League\Fractal\TransformerAbstract;
use Maras0830\LaravelSRT\Exceptions\TransformerException;

trait TransformerCollectionTrait
{
    protected $required_relation_keys = [];

    protected $missing_required_keys = [];

    protected $is_strict_mode = true;

    protected $enable_auto_loaded_required_keys = true;

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
     * @param bool $enable_auto_loaded_required_keys
     * @return $this
     */
    public function setAutoLoadedRequiredKeys(bool $enable_auto_loaded_required_keys = true)
    {
        $this->enable_auto_loaded_required_keys = $enable_auto_loaded_required_keys;

        return $this;
    }

    /**
     * is_strict_model
     * true: TransformerException
     * false: Log::warning
     *
     * @param bool $is_strict_mode
     * @return $this
     */
    public function setStrictMode(bool $is_strict_mode = true)
    {
        $this->is_strict_mode = $is_strict_mode;

        return $this;
    }

    /**
     * Change laravel collection to Transformer format
     *
     * @param $collection
     * @param TransformerAbstract $transformerAbstract
     * @return mixed
     * @throws TransformerException
     * @throws \ReflectionException
     */
    public function transCollection($collection, TransformerAbstract $transformerAbstract = null)
    {
        $this->loadRequiredRelationKeys($collection);

        $this->setUnRelationLoadedKeys($collection);

        $this->logStrictMode();

        $collection = $this->packTransformerData($collection, $transformerAbstract);

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
     * @throws TransformerException
     * @throws \ReflectionException
     */
    public function transCollectionGroup($collection, $group_key, TransformerAbstract $transformerAbstract = null, $keys = false)
    {
        $result = [];

        $this->loadRequiredRelationKeys($collection);

        $this->setUnRelationLoadedKeys($collection);

        $this->logStrictMode();

        $collection = $this->packTransformerData($collection, $transformerAbstract);

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

    private function setUnRelationLoadedKeys($collection)
    {
        if (!empty($this->required_relation_keys)) {
            foreach ($this->required_relation_keys as $required_relation_key) {
                foreach ($collection as $model) {
                    if (!$model->relationLoaded($required_relation_key) &&
                        !in_array($required_relation_key, $this->missing_required_keys)) {
                        $this->missing_required_keys[] = $required_relation_key;
                    }
                }
            }
        }
    }

    /**
     * @throws TransformerException
     */
    private function logStrictMode()
    {
        if (!empty($this->missing_required_keys)) {
            if ($this->is_strict_mode) {
                throw new TransformerException('required relation key not exist', $this->missing_required_keys);
            } else {
                Log::warning("required relation key not exist - " . get_class($this), $this->missing_required_keys);
            }
        }
    }

    /**
     * @param $collection
     * @return mixed
     */
    private function loadRequiredRelationKeys($collection)
    {
        if ($this->enable_auto_loaded_required_keys)
            return $collection->load($this->required_relation_keys);
        else
            return $collection;
    }

    /**
     * @param $collection
     * @param $transformerAbstract
     * @return mixed
     * @throws \ReflectionException
     */
    private function packTransformerData($collection, $transformerAbstract)
    {
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

        return $collection;
    }

    /**
     * @param $object
     * @param $relation_column
     * @param $transformerAbstract
     * @param null $default
     * @return null
     */
    private function getRelationTransform($object, $relation_column, $transformerAbstract, $default = null)
    {
        return ($object->relationLoaded($relation_column) && !empty($object->{$relation_column}))
            ? $transformerAbstract->transform($object->{$relation_column})
            : $default;
    }

    /**
     * @param $object
     * @param $relation_column
     * @param $transformerAbstract
     * @param array $default
     * @return null
     */
    private function getRelationTransCollection($object, $relation_column, $transformerAbstract, $default = [])
    {

        return ($object->relationLoaded($relation_column) && !empty($object->{$relation_column}))
            ? $transformerAbstract->transCollection($object->{$relation_column})
            : $default;
    }

    /**
     * @param $object
     * @param $relation_column
     * @param $callback
     * @param null $default
     * @return mixed|null
     */
    private function getRelationHandler($object, $relation_column, $callback, $default = null)
    {
        return ($object->relationLoaded($relation_column) && !empty($object->{$relation_column}))
            ? $callback($object->{$relation_column})
            : $default;
    }
}
