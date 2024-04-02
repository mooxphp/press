<?php

namespace Moox\Press\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WpTerm extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'term_group'];

    protected $searchableFields = ['*'];

    protected $table = 'wp_terms';
}
