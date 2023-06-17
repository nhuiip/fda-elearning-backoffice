<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Lesson
 * 
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property string|null $videoUrl
 * @property int $passScore
 * @property int $sort
 * @property bool $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 *
 * @package App\Models
 */
class Lesson extends Model
{
	use SoftDeletes;
	protected $table = 'lessons';

	protected $casts = [
		'passScore' => 'int',
		'sort' => 'int',
		'status' => 'bool'
	];

	protected $fillable = [
		'name',
		'description',
		'videoUrl',
		'passScore',
		'sort',
		'status'
	];
}
