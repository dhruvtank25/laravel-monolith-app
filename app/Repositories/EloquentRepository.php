<?php 

namespace App\Repositories;
use DB;

abstract class EloquentRepository implements RepositoryInterface
{
    protected $model;

    public function getModel()
    {
        return $this->model;
    }

    public function newInstance()
    {
        return $this->model->newInstance();
    }

    // ----------------------------------------------------------------
    // DEFAULT CRUD
    // ----------------------------------------------------------------

    /*protected function getAll()
    {
        return $this->model::all();
    }*/

    /**
     * Common Search Function
     * @param $condition_arr array with key value as where condition
     * @param $condition_arr array to get with
     * @param $select_arr array to get only selected columns
     * @return Result Array
     * @author 
     **/
    public function get_search_custom($condition_arr,$with_arr='',$select_arr='',$order_by_arr='')
    {
        //DB::connection('mongodb')->enableQueryLog();
        $query_parent = $this->model;
        foreach ($condition_arr as $condition => $value) {
            $column_name_arr = explode("|", $condition);
            $column_name = $column_name_arr[0];
            $column_condition = count($column_name_arr)>1?$column_name_arr[1]:'=';
            $query_parent = $query_parent->where($column_name,$column_condition,$value);
        }
        if($with_arr!=''){
            foreach ($with_arr as $key => $value) {
                if(is_array($value)){
                    $query_parent = $query_parent->with([$key=>
                        function($query) use ($value){
                            $query->select($value);
                        }
                    ]);
                }else{
                    $query_parent= $query_parent->with($value);
                }
            }
        }
        if($order_by_arr!=''){
            foreach ($order_by_arr as $key => $value) {
                $query_parent = $query_parent->orderBy($key, $value);
            }
        }
        if($select_arr!='')
            $result = $query_parent->get($select_arr);
        else
            $result = $query_parent->get();
        //dd(DB::connection('mongodb')->getQueryLog());
        //print_r(DB::connection('mongodb')->getQueryLog());
        return $result;
    }

    public function get($id)
    {
        return $this->model->where('id', $id)->firstOrFail();
    }

    public function get_dropdown_custom($display_col,$val_col,$condition_arr='')
    {
        if($condition_arr==''){
            $condition_arr = array();
        }
        $select_arr = array($display_col,$val_col);
        $results = $this->get_search_custom($condition_arr,'',$select_arr);
        $dropdown_arr= array();
        foreach ($results as $result) {
            $dropdown_arr[$result->{$val_col}] = $result->{$display_col};
        }
        return $dropdown_arr;
    }

    protected function fillNSave($data)
    {
        $this->model->fill($data);

        return $this->model->save();
    }

    public function add($data, $showMessage = true, $message = null)
    {
        $this->model = $this->newInstance();
        if (is_null($message)) {
            $message = 'Data was successfully added.';
        }

        $saved = $this->fillNSave($data);

        if ($saved && $showMessage) {
            $this->setSavedMessage($message);
        }

        if($saved){
            return $this->model->id;
        }else{
            return false;
        }
    }

    public function edit($data, $showMessage = true, $message = null)
    {
        $this->model = $this->get($data['id']);
        if (is_null($message)) {
            $message = 'Data was successfully updated.';
        }

        $saved = $this->fillNSave($data);

        if ($saved && $showMessage) {
            $this->setSavedMessage($message);
        }

        if($saved){
            return $this->model->id;
        }else{
            return false;
        }
    }
    
    /**
     * @param $id
     * @return mixed
     */
    public function restore($id)
    {
        return $this->model->where('id',$id)->restore();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function delete($id) 
    {
        return $this->model->destroy($id);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function forceDelete($id)
    {
        return $this->model->where('id',$id)->forceDelete();
    }

    protected function setSavedMessage($message = null)
    {
        if (is_null($message)) {
            $message = 'Data was successfully saved.';
        }

        session()->flash('success', $message);
    }

    public function __call($method, $parameters)
    {
        return call_user_func_array([$this->model, $method], $parameters);
    }
}