<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Member
 * 
 * @property int $id
 * @property string $name
 * @property string|null $company
 * @property string|null $department
 * @property string|null $position
 * @property string|null $businessType
 * @property string|null $email
 * @property string $password
 * @property string $rawPassword
 * @property bool $notified
 * @property bool $passed
 * @property Carbon $registerDate
 * @property Carbon|null $firstLoginDate
 * @property Carbon|null $lastVisitDate
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * 
 * @property Collection|Exam[] $exams
 *
 * @package App\Models
 */
class Member extends Model
{
	const businessType = array("เจ้าของผลิตภัณฑ์", "ผู้นำเข้า", "ผู้นำสั่ง", "ตัวแทนจำหน่าย", "อื่นๆ");
	
	public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }

	use SoftDeletes;
	protected $table = 'members';

	protected $casts = [
		'notified' => 'bool',
		'passed' => 'bool',
		'registerDate' => 'datetime',
		'firstLoginDate' => 'datetime',
		'lastVisitDate' => 'datetime'
	];

	protected $hidden = [
		'password'
	];

	protected $fillable = [
		'name',
		'company',
		'department',
		'position',
		'businessType',
		'email',
		'password',
		'rawPassword',
		'notified',
		'passed',
		'registerDate',
		'firstLoginDate',
		'lastVisitDate'
	];

	public function exams()
	{
		return $this->hasMany(Exam::class, 'memberId');
	}
}
