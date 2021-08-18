<?php
namespace Maras0830\LaravelSRT\Repository;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Maras0830\LaravelSRT\Exceptions\RepositoryException;
use Illuminate\Container\Container as Application;

abstract class Repository
{
    /**
     * @var Application
     */
    protected $app;

    /**
     * @var Builder
     */
    protected $model;

    public function __construct(Application $app)
    {
        $this->app = $app;
        $this->makeModel();
        $this->boot();
    }

    /**
     * Create a new resource instance.
     *
     * @return static
     */
    public static function make()
    {
        return new static(app());
    }

    abstract public function model();

    public function makeModel()
    {
        $model = $this->app->make($this->model());

        if (!$model instanceof Model) {
            throw new RepositoryException("Class {$this->model()} must be an instance of Illuminate\\Database\\Eloquent\\Model");
        }

        return $this->model = $model->query();
    }

    public function resetModel()
    {
        $this->makeModel();

        return $this;
    }

    /**
     *
     */
    public function boot()
    {
        //
    }

    /**
     * @param array $columns
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function all($columns = ['*'])
    {
        $result = $this->model->all($columns);

        $this->resetModel();

        return $result;
    }
    /**
     * @param $attributes
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function create($attributes)
    {
        $result = $this->model->create($attributes);

        $this->resetModel();

        return $result;
    }
    /**
     * @param $id
     * @param array $attributes
     * @param array $options
     * @return bool
     */
    public function update($id, array $attributes, array $options = [])
    {
        $instance = $this->model->findOrFail($id);

        $result = $instance->update($attributes, $options);

        $this->resetModel();

        return $result;
    }
    /**
     * @param $column
     * @param $value
     * @param array $attributes
     * @param array $options
     * @return bool
     */
    public function updateBy($column, $value, array $attributes = [], array $options = [])
    {
        $result = $this->model->where($column, $value)->update($attributes, $options);

        $this->resetModel();

        return $result;
    }
    /**
     * @param array $columns
     * @return mixed
     */
    public function first($columns = ['*'])
    {
        $result = $this->model->first($columns);

        $this->resetModel();

        return $result;
    }
    /**
     * @param $column
     * @param $value
     * @param array $columns
     * @return mixed
     */
    public function firstBy($column, $value, $columns = ['*'])
    {
        $result = $this->model->where($column, $value)->first($columns);

        $this->resetModel();

        return $result;
    }
    /**
     * @param $id
     * @param array $columns
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model
     */
    public function find($id, $columns = ['*'])
    {
        $result = $this->model->find($id, $columns);

        $this->resetModel();

        return $result;
    }
    /**
     * @param $column
     * @param $value
     * @param array $columns
     * @return mixed
     */
    public function findBy($column, $value, $columns = ['*'])
    {
        $result = $this->model->where($column, $value)->first($columns);

        $this->resetModel();

        return $result;
    }
    /**
     * @param array $columns
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function get($columns = ['*'])
    {
        $result = $this->model->get();

        $this->resetModel();

        return $result;
    }
    /**
     * @param $column
     * @param $value
     * @param array $columns
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getBy($column, $value, $columns = ['*'])
    {
        $result = $this->model->where($column, $value)->get($columns);

        $this->resetModel();

        return $result;
    }
    /**
     * @param $ids
     * @return int
     * @internal param $id
     */
    public function destroy($ids)
    {
        $result = $this->model->destroy($ids);

        $this->resetModel();

        return $result;
    }
    /**
     * @param $column
     * @param $value
     * @return bool|null
     */
    public function destroyBy($column, $value)
    {
        $result = $this->model->where($column, $value)->delete();

        $this->resetModel();

        return $result;
    }
    /**
     * @param null $perPage
     * @param array $columns
     * @param string $pageName
     * @param int $page
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function paginate($perPage = null, $columns = ['*'], $pageName = 'page', $page = null)
    {
        $result = $this->model->paginate($perPage, $columns, $pageName, $page);

        $this->resetModel();

        return $result;
    }
    /**
     * @param $column
     * @param $value
     * @param null $perPage
     * @param array $columns
     * @param string $pageName
     * @param int $page
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function paginateBy($column, $value, $perPage = null, $columns = ['*'], $pageName = 'page', $page = null)
    {
        $result = $this->model->where($column, $value)->paginate($perPage, $columns, $pageName, $page);

        $this->resetModel();

        return $result;
    }

    /**
     * @param $column
     * @param string $sortBy
     * @return mixed
     */
    public function orderBy($column, $sortBy = 'DESC')
    {
        $this->model->orderBy($column, $sortBy);

        return $this;
    }
}
