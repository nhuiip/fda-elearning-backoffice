<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Question
 * 
 * @property int $id
 * @property int $lessonId
 * @property string $name
 * @property string|null $description
 * @property bool $hasImage
 * @property string|null $imageUrl
 * @property int $score
 * @property int $sort
 * @property bool $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * 
 * @property Lesson $lesson
 *
 * @package App\Models
 */
class Question extends Model
{
	use SoftDeletes;
	protected $table = 'questions';

	protected $casts = [
		'lessonId' => 'int',
		'hasImage' => 'bool',
		'score' => 'int',
		'sort' => 'int',
		'status' => 'bool'
	];

	protected $fillable = [
		'lessonId',
		'name',
		'description',
		'hasImage',
		'imageUrl',
		'score',
		'sort',
		'status'
	];

	public function lesson()
	{
		return $this->belongsTo(Lesson::class, 'lessonId');
	}
}
