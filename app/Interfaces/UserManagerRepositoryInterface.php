<?php

namespace App\Interfaces;

interface UserManagerRepositoryInterface
{
    public function getAll($search, $page);
    public function getTotalUser();
    public function getById($dataId);
    public function create($detailsData);
    public function update($dataId, $newDetailsData);
    public function updatePassword($dataId, $newDetailsData);
    public function delete($dataId);
}
