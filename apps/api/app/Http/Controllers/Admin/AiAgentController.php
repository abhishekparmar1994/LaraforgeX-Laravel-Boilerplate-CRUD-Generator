<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class AiAgentController extends Controller
{
    /**
     * Render the AI CRM Agent chat interface.
     */
    public function index(): View
    {
        return view('admin.ai_agent.index');
    }
}
