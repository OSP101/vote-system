<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VotesDetail extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = ['name_choice','id_votes','description'];
    protected $dates = ['deleted_at'];
}
