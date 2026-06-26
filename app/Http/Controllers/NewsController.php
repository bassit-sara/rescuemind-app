<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\NewsComment;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    /* ─── Public / User ──────────────────────────────────────── */

    public function index(Request $request)
    {
        $category = $request->query('cat');
        
        $pinned = News::where('is_published', true)->where('is_pinned', true)
                      ->when($category && $category !== 'all', function($q) use ($category) {
                          $q->where('category', $category);
                      })
                      ->with('author')->latest()->get();

        $news = News::where('is_published', true)->where('is_pinned', false)
                    ->when($category && $category !== 'all', function($q) use ($category) {
                        $q->where('category', $category);
                    })
                    ->with('author')->latest()->paginate(12)->withQueryString();

        return view('news.index', compact('pinned', 'news'));
    }

    public function show(News $news)
    {
        abort_unless($news->is_published, 404);
        $news->load('author', 'comments.author');
        $related = News::where('is_published', true)
                       ->where('category', $news->category)
                       ->where('id', '!=', $news->id)
                       ->latest()->take(4)->get();

        return view('news.show', compact('news', 'related'));
    }

    public function comment(Request $request, News $news)
    {
        $request->validate(['body' => 'required|string|max:1000']);
        $news->comments()->create([
            'user_id' => auth()->id(),
            'body'    => $request->body,
        ]);
        return back()->with('success', 'แสดงความคิดเห็นเรียบร้อย');
    }

    public function deleteComment(NewsComment $comment)
    {
        abort_unless(auth()->id() === $comment->user_id || auth()->user()->hasAnyRole(['admin', 'super_admin']), 403);
        $comment->delete();
        return back()->with('success', 'ลบความคิดเห็นเรียบร้อย');
    }

    /* ─── Admin CRUD ─────────────────────────────────────────── */

    public function adminIndex()
    {
        $news = News::with('author')->latest()->paginate(20);
        return view('news.admin.index', compact('news'));
    }

    public function create()
    {
        return view('news.admin.form', ['news' => null]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'        => 'required|string|max:255',
            'category'     => 'required|in:general,disaster,health,alert,community',
            'body'         => 'required|string',
            'media'        => 'nullable|file|mimetypes:image/jpeg,image/png,image/gif,image/webp,video/mp4,video/avi,video/mpeg,video/quicktime|max:51200',
            'is_pinned'    => 'boolean',
            'is_published' => 'boolean',
        ]);
        
        $data['user_id']      = auth()->id();
        $data['is_pinned']    = $request->boolean('is_pinned');
        $data['is_published'] = $request->boolean('is_published', true);

        if ($request->hasFile('media')) {
            $file = $request->file('media');
            $mime = $file->getMimeType();
            
            if (str_starts_with($mime, 'image/')) {
                $data['image_url'] = $file->store('news/images', 'public');
                $data['video_url'] = null;
            } elseif (str_starts_with($mime, 'video/')) {
                $data['video_url'] = $file->store('news/videos', 'public');
                $data['image_url'] = null;
            }
        }

        News::create($data);
        return redirect()->route('admin.news.index')->with('success', 'เพิ่มข่าวสารเรียบร้อยแล้ว');
    }

    public function edit(News $news)
    {
        return view('news.admin.form', compact('news'));
    }

    public function update(Request $request, News $news)
    {
        $data = $request->validate([
            'title'        => 'required|string|max:255',
            'category'     => 'required|in:general,disaster,health,alert,community',
            'body'         => 'required|string',
            'media'        => 'nullable|file|mimetypes:image/jpeg,image/png,image/gif,image/webp,video/mp4,video/avi,video/mpeg,video/quicktime|max:51200',
            'is_pinned'    => 'boolean',
            'is_published' => 'boolean',
        ]);
        
        $data['is_pinned']    = $request->boolean('is_pinned');
        $data['is_published'] = $request->boolean('is_published', true);

        if ($request->hasFile('media')) {
            $file = $request->file('media');
            $mime = $file->getMimeType();

            if ($news->image_url && \Illuminate\Support\Facades\Storage::disk('public')->exists($news->image_url)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($news->image_url);
            }
            if ($news->video_url && \Illuminate\Support\Facades\Storage::disk('public')->exists($news->video_url)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($news->video_url);
            }

            if (str_starts_with($mime, 'image/')) {
                $data['image_url'] = $file->store('news/images', 'public');
                $data['video_url'] = null;
                // If it's an image, set existing video_url to null
                $news->video_url = null;
            } elseif (str_starts_with($mime, 'video/')) {
                $data['video_url'] = $file->store('news/videos', 'public');
                $data['image_url'] = null;
                // If it's a video, set existing image_url to null
                $news->image_url = null;
            }
        }

        $news->update($data);
        return redirect()->route('admin.news.index')->with('success', 'แก้ไขข่าวสารเรียบร้อยแล้ว');
    }

    public function destroy(News $news)
    {
        $news->delete();
        return back()->with('success', 'ลบข่าวสารเรียบร้อยแล้ว');
    }
}
