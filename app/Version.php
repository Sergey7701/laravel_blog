<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class Version extends Model
{

    protected $fillable = [
        'article_id',
        'editor_id',
        'tags',
        'header',
        'description',
        'text',
        'publish',
    ];

    public function article()
    {
        return $this->belongsTo(\App\Models\Article::class);
    }

    public function editor()
    {
        return User::findOrFail($this->editor_id)->name;
    }

    public function scopeRecentArticle()
    {
        return $this->article()->latest()->first();
    }

    public function setHeaderAttribute($newValue)
    {
        
        $this->attributes['old_header']     = $newValue;
        $this->attributes['header'] = $this->recentArticle()->header;
    }

    public function setDescriptionAttribute($newValue)
    {
        $this->attributes['old_description']     = $newValue;
        $this->attributes['description'] = $this->recentArticle()->description;
    }

    public function setTextAttribute($newValue)
    {
        $this->attributes['old_text']     = $newValue;
        $this->attributes['text'] = $this->recentArticle()->text;
    }

    public function setPublishAttribute($newValue)
    {
        $this->attributes['old_publish']     = $newValue ? 1 : 0;
        $this->attributes['publish'] = $this->recentArticle()->publish;
    }

    public function setTagsAttribute($newValue)
    {
        $this->attributes['tags'] = $newValue;
        $this->attributes['old_tags']     = $this->recentArticle()->tags->implode('name', ', ');
    }
}
