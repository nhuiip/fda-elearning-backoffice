<?php

namespace App\Imports;

use Illuminate\Support\Str;
use App\Models\Member;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class MemberImport implements ToModel, WithHeadingRow, WithValidation
{
    use Importable;
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */

    public function model(array $row)
    {
        $password = Str::random(8);

        $registerDate = date_create_from_format('d/m/Y, H:i:s', $row['registerdate']);
        $registerDate->getTimestamp();

        $email = trim($row['email']);

        return new Member([
            'registerDate'  => $registerDate,
            'name'          => $row['name'],
            'company'       => $row['company'],
            'department'    => $row['department'],
            'position'      => $row['position'],
            'email'         => $row['email'],
            'businessType'  => $row['businesstype'],
            'password'      => $password,
            'rawPassword'   => $password,
        ]);
    }

    public function rules(): array
    {
        return [
            'registerdate' => 'required',
            'name' => 'required',
            'email' => 'required|email|unique:members',
        ];
    }

    public function headingRow(): int
    {
        return 1;
    }
}
