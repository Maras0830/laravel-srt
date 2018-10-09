<?php
namespace Maras0830\LaravelSRT\Repository;

class Repository
{
    protected $model;

    /**
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function all()
    {
        return $this->model->all();
    }

    /**
     * @param $attributes
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function create($attributes)
    {
        return $this->model->create($attributes);
    }

    /**
     * @return mixed
     */
    public function first()
    {
        return $this->model->first();
    }

    /**
     * @param $column
     * @param $value
     * @return mixed
     */
    public function firstBy($column, $value)
    {
        return $this->model->firstBy($column, $value);
    }

    /**
     * @param $id
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model
     */
    public function find($id)
    {
        return $this->model->find($id);
    }

    /**
     * @param $column
     * @param $value
     * @return mixed
     */
    public function findBy($column, $value)
    {
        return $this->model->findBy($column, $value);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function get()
    {
        return $this->model->get();
    }

    /**
     * @param $column
     * @param $value
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getBy($column, $value)
    {
        return $this->model->getBy($column, $value);
    }

    /**
     * @param $id
     * @param $attributes
     * @return bool|int
     */
    public function update($id, $attributes)
    {
        return $this->model->update($id, $attributes);
    }

    /**
     * @param $column
     * @param $value
     * @param $attributes
     * @return bool
     */
    public function updateBy($column, $value, $attributes)
    {
        return $this->model->updateBy($column, $value, $attributes);
    }

    /**
     * @param $id
     * @return bool|int|null
     */
    public function destroy($id)
    {
        return $this->model->destroy($id);
    }

    /**
     * @param $column
     * @param $value
     * @return bool|null
     */
    public function destroyBy($column, $value)
    {
        return $this->model->destroyBy($column, $value);
    }

    /**
     * @param string $column
     * @param $value
     * @param int $page
     * @return mixed
     * @internal param $id
     */
    public function paginateBy($column, $value, $page = 12)
    {
        return $this->model->paginateBy($column, $value, $page);
    }

    /**
     * @param int $page
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function paginate($page = 12)
    {
        return $this->model->paginate($page);
    }
}