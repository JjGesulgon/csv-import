<?php

namespace App\Imports;

use App\Contact;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;
use Maatwebsite\Excel\Concerns\Importable;


HeadingRowFormatter::default('none');

class ContactsImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Contact([
          'team_id'  => $row['Team ID'],
          'name' => $row['Name'],
          'phone' => $row['Phone'],
          'email' => $row['Email'],
          'sticky_phone_number_id' => $row['Sticky Phone Number ID'],
        ]);
    }
}
