<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MentalArticle;

class MentalArticleController extends Controller
{
    public function index()
    {
        $articles = MentalArticle::all();
        return view('mental.articles', compact('articles'));
    }

    public function show(MentalArticle $mentalArticle)
    {
        $article = $mentalArticle;
        return view('mental.article-show', compact('article'));
    }

    public function create()
    {
        return view('mental.articles.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category' => 'required|string',
            'title' => 'required|string|max:255',
            'desc' => 'nullable|string',
            'read_time' => 'nullable|string|max:255',
            'author' => 'nullable|string|max:255',
            'icon' => 'nullable|string|max:255',
            'video_url' => 'nullable|string',
            'content' => 'required|string',
        ]);

        MentalArticle::create($validated);

        return redirect()->route('mental.articles')->with('success', 'เพิ่มบทความเรียบร้อยแล้ว');
    }

    public function edit(MentalArticle $mentalArticle)
    {
        return view('mental.articles.edit', compact('mentalArticle'));
    }

    public function update(Request $request, MentalArticle $mentalArticle)
    {
        $validated = $request->validate([
            'category' => 'required|string',
            'title' => 'required|string|max:255',
            'desc' => 'nullable|string',
            'read_time' => 'nullable|string|max:255',
            'author' => 'nullable|string|max:255',
            'icon' => 'nullable|string|max:255',
            'video_url' => 'nullable|string',
            'content' => 'required|string',
        ]);

        $mentalArticle->update($validated);

        return redirect()->route('mental.articles')->with('success', 'แก้ไขบทความเรียบร้อยแล้ว');
    }

    public function destroy(MentalArticle $mentalArticle)
    {
        $mentalArticle->delete();
        return redirect()->route('mental.articles')->with('success', 'ลบบทความเรียบร้อยแล้ว');
    }
}
