<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Choice
 * 
 * @property int $id
 * @property int $questionId
 * @property string|null $name
 * @property bool $hasImage
 * @property string|null $imageUrl
 * @property bool $isRight
 * @property int $sort
 * @property bool $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * 
 * @property Question $question
 *
 * @package App\Models
 */
class Choice extends Model
{
	use SoftDeletes;
	protected $table = 'choices';

	protected $casts = [
		'questionId' => 'int',
		'hasImage' => 'bool',
		'isRight' => 'bool',
		'sort' => 'int',
		'status' => 'bool'
	];

	protected $fillable = [
		'questionId',
		'name',
		'hasImage',
		'imageUrl',
		'isRight',
		'sort',
		'status'
	];

	public function question()
	{
		return $this->belongsTo(Question::class, 'questionId');
	}
}
