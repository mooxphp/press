<?php

namespace Moox\Press\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WpTermTaxonomy extends Model
{
    use HasFactory;

    protected $fillable = [
        'term_id',
        'taxonomy',
        'description',
        'parent',
        'count',
    ];

    protected $searchableFields = ['*'];

    protected $table = 'wp_term_taxonomy';
}
