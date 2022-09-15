<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use \Staudenmeir\LaravelAdjacencyList\Eloquent\HasRecursiveRelationships;

class Document extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia, HasRecursiveRelationships;
    protected $fillable = [
        'name',
        'document_id',
    ];

    /**
     * Get all of the documents for the Document
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    /**
     * Get the document that owns the Document
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function document()
    {
        return $this->belongsTo(Document::class);
    }

    /**
     * Get the user that owns the Document
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getParentKeyName()
    {
        return 'document_id';
    }
}
