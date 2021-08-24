<?php

namespace App\Repositories\Eloquent;

use Illuminate\Database\Eloquent\Model;
use App\Contracts\Repositories\BaseRepositoryInterface;

class BaseRepository implements BaseRepositoryInterface
{
    protected $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function all()
    {
        return $this->model->all();
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }
    
    public function destroy($id)
    {
        return $this->model->destroy($id);
    }

    public function find($id)
    {
        return $this->model->find($id);
    }

    public function getModel()
    {
        return $this->model;
    }

    public function setModel($model)
    {
        $this->model = $model;
        return $this;
    }

    public function update(array $data, object $entity)
    {
        return $entity->update($data);
    }

    public function with($relations)
    {
        return $this->model->with($relations)->get();
    }

    public function select($columns)
    {
        return $this->model->select($columns)->get();
    }

    public function search(array $columns, $type, $value)
    {
        return $this->model->select($columns)->where($type, $value)->get();
    }

    public function where($type, $value)
    {
        return $this->model->where($type, $value)->first();
    }

    public function getByeOffsetLimit($offset, $limit) 
    {
        return $this->model->offset($offset)->limit($limit)->get();
    }

    public function updateOrCreate(array $if, array $value)
    {
        return $this->model->updateOrCreate($if, $value);
    }
}