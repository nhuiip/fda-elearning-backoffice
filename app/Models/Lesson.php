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
 * @property Collection|Exam[] $exams
 * @property Collection|Question[] $questions
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

	public function exams()
	{
		return $this->hasMany(Exam::class, 'lessonId');
	}

	public function questions()
	{
		return $this->hasMany(Question::class, 'lessonId');
	}
}
