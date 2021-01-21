<?php

namespace App\Imports;

use App\Contact;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ContactsImport implements ToModel, WithHeadingRow
{
    public $group_id;
    public $user_id;
    public function __construct($g_id, $id)
    {
        $this->group_id = $g_id;
        $this->user_id = $id;
    }
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Contact([
            'group_id' => $this->group_id,
            'number'     => $row['number'],
            'name'     => $row['name'],
            'age'     => $row['age'],
            'area'     => $row['area'],
            'city'     => $row['city'],
            'gender'     => $row['gender'],
            'user_id'  => $this->user_id,
        ]);
    }
}
