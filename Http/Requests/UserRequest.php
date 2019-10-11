<?php

namespace Innerent\People\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    protected $internID;

    protected $tableNames;

    public function rules()
    {
        $this->internID = $this->route('user');
        $this->tableNames = config('permission.table_names');

        if ($this->getMethod() == 'POST') {
            return $this->storeRules();
        } elseif($this->getMethod() == 'PUT') {
            return $this->updateRules();
        }

        return [
            // No validation rules
        ];
    }

    public function authorize()
    {
        return true;
    }

    private function storeRules()
    {
        return [
            'name' => [
                'required',
                'max:255'
            ],
            'email' => [
                'required',
                "unique:users,email,{$this->internID},uuid,deleted_at,NULL"
            ],
            'password' => [
                'required',
                'max:255',
                'confirmed'
            ],
            'roles' => 'required',
            'roles.*' => 'exists:' . $this->tableNames['roles'] . ',id'
        ];
    }

    private function updateRules()
    {
        return [
            'name' => [
                'max:255'
            ],
            'email' => [
                "unique:users,email,{$this->internID},uuid,deleted_at,NULL"
            ],
            'password' => [
                'max:255',
                'confirmed'
            ],
            'roles.*' => 'exists:' . $this->tableNames['roles'] . ',id'
        ];
    }
}
