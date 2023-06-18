<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class MemberImportLog
 * 
 * @property int $id
 * @property string $fileUrl
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class MemberImportLog extends Model
{
	protected $table = 'member_import_log';

	protected $fillable = [
		'fileUrl'
	];
}
