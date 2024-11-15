<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CouponAgentList extends Model
{
    protected $table = 'CouponAgentList';
    protected $fillable = ['AgentID', 'Agent'];
}
