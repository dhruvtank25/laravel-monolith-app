<?php namespace App\Repositories;


interface RepositoryInterface {

    public function getModel();

    public function newInstance();

    public function add($data, $showMessage = true, $message = null);

    public function edit($data, $showMessage = true, $message = null);
}