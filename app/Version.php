<?php
namespace App;

use App\Models\Article;
use Illuminate\Database\Eloquent\Model;

class Version extends Model
{

    protected $fillable = [
        'article_id',
        'editor_id',
        'tags',
        'old_tags',
        'header',
        'description',
        'text',
        'publish',
    ];

    public function article()
    {
        return $this->belongsTo(Article::class);
    }

    public function editor()
    {
        return User::findOrFail($this->editor_id)->name;
    }

    public function scopeRecentArticle()
    {
        return $this->article()->where('id', $this->article_id)->first();
    }

    public function setHeaderAttribute($newValue)
    {
        $this->attributes['header']     = $newValue;
        $this->attributes['old_header'] = $this->recentArticle()->header;
    }

    public function setDescriptionAttribute($newValue)
    {
        $this->attributes['description']     = $newValue;
        $this->attributes['old_description'] = $this->recentArticle()->description;
    }

    public function setTextAttribute($newValue)
    {
        $this->attributes['text']     = $newValue;
        $this->attributes['old_text'] = $this->recentArticle()->text;
    }

    public function setPublishAttribute($newValue)
    {
        $this->attributes['publish']     = $newValue ? 1 : 0;
        $this->attributes['old_publish'] = $this->recentArticle()->publish;
    }
}
