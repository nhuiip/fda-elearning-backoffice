<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ExamsItem
 * 
 * @property int $id
 * @property int $examId
 * @property int $questionId
 * @property int $choiceId
 * @property int $score
 * @property bool $isRight
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Choice $choice
 * @property Exam $exam
 * @property Question $question
 *
 * @package App\Models
 */
class ExamsItem extends Model
{
	protected $table = 'exams_item';

	protected $casts = [
		'examId' => 'int',
		'questionId' => 'int',
		'choiceId' => 'int',
		'score' => 'int',
		'isRight' => 'bool'
	];

	protected $fillable = [
		'examId',
		'questionId',
		'choiceId',
		'score',
		'isRight'
	];

	public function choice()
	{
		return $this->belongsTo(Choice::class, 'choiceId');
	}

	public function exam()
	{
		return $this->belongsTo(Exam::class, 'examId');
	}

	public function question()
	{
		return $this->belongsTo(Question::class, 'questionId');
	}
}
