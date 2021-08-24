<?php

namespace App\Contracts\Repositories;

interface BaseRepositoryInterface
{
    public function all();
    
    public function create(array $data);
    
    public function destroy($id);
    
    public function find($id);

    public function getModel();

    public function setModel($model);

    public function update(array $data, object $entity);

    public function with($relations);

    public function select(array $type);

    public function search(array $data, $value, $type);

    public function where($type, $value);

    public function getByeOffsetLimit($offset, $limit);

    public function updateOrCreate(array $if, array $value);
}
