<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\NewsService;

class NewsController extends Controller
{
    protected $newsService;

    public function __construct(NewsService $newsService)
    {
        $this->newsService = $newsService;
    }

    public function index()
    {
        $newsArticles = $this->newsService->getPaginated(10);
        return view('news.index', compact('newsArticles'));
    }

    public function sync()
    {
        $result = $this->newsService->syncNews();
        
        if ($result['success']) {
            return redirect()->route('news.index')->with('success', $result['message']);
        }
        
        return redirect()->route('news.index')->with('error', $result['message']);
    }
}
