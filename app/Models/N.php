<?php
/**
 * Created by O2System Framework File Generator.
 * DateTime: 07/07/2020 16:07
 */

// ------------------------------------------------------------------------

namespace App\Models;

// ------------------------------------------------------------------------

use O2System\Framework\Models\Sql\Model;

/**
 * Class N
 *
 * @package \App\Models
 */
class N extends Model
{
    public $table = 'users_test';
    protected $fillable = [
    'username',
    'password',
    'status',
  ];
}
