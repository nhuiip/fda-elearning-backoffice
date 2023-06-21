<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Exam
 * 
 * @property int $id
 * @property int $memberId
 * @property int $lessonId
 * @property int $score
 * @property bool $isPass
 * @property bool $isFinish
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Lesson $lesson
 * @property Member $member
 * @property Collection|ExamsItem[] $exams_items
 *
 * @package App\Models
 */
class Exam extends Model
{
	protected $table = 'exams';

	protected $casts = [
		'memberId' => 'int',
		'lessonId' => 'int',
		'score' => 'int',
		'isPass' => 'bool',
		'isFinish' => 'bool'
	];

	protected $fillable = [
		'memberId',
		'lessonId',
		'score',
		'isPass',
		'isFinish'
	];

	public function lesson()
	{
		return $this->belongsTo(Lesson::class, 'lessonId');
	}

	public function member()
	{
		return $this->belongsTo(Member::class, 'memberId');
	}

	public function exams_items()
	{
		return $this->hasMany(ExamsItem::class, 'examId');
	}
}
