<?php

namespace Moox\Press\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WpTermRelationship extends Model
{
    use HasFactory;

    protected $fillable = ['term_taxonomy_id', 'term_order'];

    protected $searchableFields = ['*'];

    protected $table = 'wp_term_relationships';
}
