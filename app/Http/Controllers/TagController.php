<?php
namespace App\Http\Controllers;

//use Illuminate\Http\Request;


use App\Entry;
use App\Models\Article;
use App\News;
use App\Tag;

class TagController extends Controller
{

    public function index(Tag $tag)
    {
        /* Почему идёт выборка Entry?
         * Я хочу выводить вместе и Article и News, привязанные к тегам.
         * 
         * Почему не выборка из отдельных таблиц Article и News?
         * Потому что отображение welcome хочет именно Entry.
         * Ну или делать отдельное отображение, а уже там определять тип выводимой записи.
         * В итоге тоже самое, но больше операций.
         * 
         * Почему так?
         * Tag можно привязывать не к каждому подтипу Entry,
         * а Comment привязывается ко всем Entry
         * 
         * Почему используется orWhere?
         * Article и News могут иметь одинаковые id  в своих таблицах,
         * поэтому выборка по только taggable_id даёт много ошибочных коллизий
         */ 
        $entries = Entry::where('entryable_type', News::class)
            ->whereIn('entryable_id',
                      \DB::table('taggables')
                        ->where('tag_id', $tag->id)
                        ->where('taggable_type', News::class)
                        ->pluck('taggable_id')
            )
            ->orWhere('entryable_type', Article::class)
                    ->whereIn('entryable_id',
                      \DB::table('taggables')
                        ->where('tag_id', $tag->id)
                        ->where('taggable_type', Article::class)
                        ->pluck('taggable_id')
                     );
        if (!\auth()->check() || !\auth()->user()->can('manage-articles')) {
            $items = $entries->wherePublish(1)->orderByDesc('created_at')->paginate(10);
        } else {
            $items = $entries->orderByDesc('created_at')->paginate(10);
        }
        return view('welcome', [
            'entries' => $items,
        ]);
    }
}
