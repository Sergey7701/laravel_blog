<?php
namespace App\Traits;

use App\Tag;
use Illuminate\Support\Facades\Cache;

trait FlushCacheIfNeeded
{

    protected function flushCacheIfTagsChanged($article, $oldTags, $newTags)
    {
        if ($newTags !== $oldTags) {
            get_class($article)::flushQueryCache(['tags-for-' . get_class($article) . '-' . $article->id . '_']);
            get_class($article)::flushQueryCache(['version_' . $article->getUrlPrefix() . '-for-' . get_class($article) . '-' . $article->id . '_']);
            Tag::flushQueryCache();
            Cache::tags(['statistic'])->forever('PleaseClearCache!', true);
        }
    }
}
