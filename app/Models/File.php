<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Класс Файла
 * @package App\Models
 *
 * @property int $id
 * @property null|int $user_id
 * @property string name
 * @property string path
 * @property int size
 * @property null|string public_path
 * @property created_at
 * @property updated_at
 */
class File extends Model
{
    use HasFactory;

    protected $table = 'files';
    protected $guarded = false;
}
