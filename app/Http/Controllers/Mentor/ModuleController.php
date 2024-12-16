<?php

namespace App\Http\Controllers\Mentor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Mail\ModuleCompletedNotification;
use App\Mail\AdminModuleCompletedNotification;
use Illuminate\Support\Facades\Mail;
use App\Models\ModuleCompletionTracker; // Make sure the model is imported
use Carbon\Carbon; // For handling dates
use App\Mentor;
use App\AssignTask;
use App\Module;
use App\Session;
use App\Test;
use App\Subchapter;
use App\Moduleresourcebank;
use App\Chapter;
use App\Mapping;
class ModuleController extends Controller
{


    // public function index()
    // {
    //     // Retrieve only non-deleted modules and their progress from the database using Query Builder
    //     $modules = DB::table('modules')
    //                 ->select('id', 'name', 'description', 'objective') // Include progress field
    //                 ->whereNull('deleted_at')
    //                 ->get();

    //     return view('mentor.modules.index', compact('modules'));
    // }
    public function index()
{
    // Retrieve modules and their related chapters including mentorsnote and quiz eligibility
    $modules = DB::table('modules')
        ->select('id', 'name', 'description', 'objective') // Include required fields
        ->whereNull('deleted_at')
        ->get();

    // For each module, retrieve its chapters
    foreach ($modules as $module) {
        $module->chapters = DB::table('chapters')
            ->select('id', 'chaptername', 'description', 'mentorsnote', 'objective')
            ->where('module_id', $module->id)
            ->whereNull('deleted_at')
            ->get();

        // Check quiz eligibility for each chapter
        foreach ($module->chapters as $chapter) {
            // Join questions and tests tables to check for MCQ questions related to the chapter
            $chapter->has_quiz = DB::table('questions')
                ->join('tests', 'questions.test_id', '=', 'tests.id')  // Join tests table to questions
                ->where('tests.chapter_id', $chapter->id)  // Check for chapter_id in the tests table
                ->where('questions.mcq', 1)  // Ensure that the question is an MCQ
                ->exists();  // Check if any matching records exist
        }
    }

    // Pass the modules with chapters and quiz eligibility to the view
    return view('mentor.modules.index', compact('modules'));
}




    public function showmentorchapters(Request $request)
    {

        // Retrieve the module ID from the request
        $module_id = $request->query('module_id');
        

        
        // Check if the module ID exists and retrieve the module
        $module = Module::find($module_id);

        if (!$module) {
            // If the module is not found, redirect back with an error message
            return redirect()->back()->with('error', 'Module not found.');
        }

        // Fetch all chapters related to the module
        $chapters = Chapter::where('module_id', $module_id)->get();

        // Add a flag for each chapter to determine if there is a quiz (based on MCQ questions)
        foreach ($chapters as $chapter) {
            $chapter->has_quiz = DB::table('questions')
                ->join('tests', 'questions.test_id', '=', 'tests.id')
                ->where('tests.chapter_id', $chapter->id)
                ->where('questions.mcq', 1)
                ->exists();
        }



        // If no chapters found, return a message to the view
        if ($chapters->isEmpty()) {
            return view('mentor.modules.mentorchapter', compact('module'))
                ->with('message', 'No chapters available for this module.');
        }

        // Pass the module and chapters to the view
        return view('mentor.modules.mentorchapter', compact('chapters', 'module'));
    }
    
    public function mentorsubchapter(Request $request)
    {

    	$chapter_id = $request->query('chapter_id');


    	$current_subchapter_id = $request->query('chapter_id');
    	// Get all subchapters of the chapter
    	$subchapters = Subchapter::where('chapter_id', $chapter_id)->get();
    	// Get the current subchapter
    	$current_subchapter = Chapter::find($current_subchapter_id);
    	$previousSubchapter='';
    	$nextSubchapter='';
        $moduleresources = Moduleresourcebank::where('chapterid_id', $chapter_id)->get();
                        
        return view('mentor.modules.mentorsubchapter', compact('subchapters', 'current_subchapter', 'previousSubchapter', 'nextSubchapter','moduleresources'));

    }

    
    public function menteemoduleprogress()
    {
        $mentorEmail = auth()->user()->email;
    
        $mentor = DB::table('mentors')->where('email', $mentorEmail)->first();
        $mentorId = $mentor->id;

        $moduleCompletionStatus = DB::table('module_completion_tracker')
                                ->where('mentee_id', $mentorId)
                                ->pluck('module_id') // Get all completed module IDs
                                ->toArray();
    
        // Fetch only non-deleted modules
        $modules = DB::table('modules')->whereNull('deleted_at')->get();
    
        $progressData = [];
        foreach ($modules as $module) {
            $totalChapters = DB::table('chapters')->where('module_id', $module->id)->count();
    
            $completedChapters = DB::table('module_completion_tracker')
                ->where('mentee_id', $mentorId)
                ->where('module_id', $module->id)
                ->count();
    
            $completionPercentage = ($totalChapters > 0) ? ($completedChapters / $totalChapters) * 100 : 0;
    
            $progressData[] = [
                'module_name' => $module->name,
                'completion_percentage' => round($completionPercentage, 2)
            ];
        }
    
        $sessions = [];
        foreach ($modules as $module) {
            $session = DB::table('sessions')
                ->where('modulename_id', $module->id)
                ->where('menteename_id', $mentorId)
                ->get();
    
            if ($session->isNotEmpty()) {
                $sessions[$module->id] = $session;
            } else {
                $sessions[$module->id] = [];
            }
        }
    
        return view('mentor.modules.menteemoduleprogress', compact('modules', 'sessions',  'moduleCompletionStatus' ,'progressData'));
    }
    
  
  

    public function markChapterCompletion($module_id)
{
    // Get the currently authenticated mentor's ID
    $mentorEmail = auth()->user()->email;
    $mentor = DB::table('mentors')->where('email', $mentorEmail)->first();
    $mentorId = $mentor->id;

    // Set chapter_id to 0 as we don't need it to be tied to a specific chapter
    $chapterId = 0;


    $moduleCompletionStatus = DB::table('module_completion_tracker')
                                ->where('mentee_id', $mentorId)
                                ->pluck('module_id') // Get all completed module IDs
                                ->toArray();



    // Otherwise, create a new record in the module_completion_tracker table
    DB::table('module_completion_tracker')->insert([
        'mentee_id' => $mentorId,
        'module_id' => $module_id,
        'chapter_id' => $chapterId,  // Set chapter_id as 0
        'completed_at' => now(), // Store current timestamp
        'created_at' => now(),
        'updated_at' => now()
    ]);

    // Set session variable to indicate the module is completed
    // return redirect()->route('mentor.modules')
    //                  ->with('success', 'Module marked as completed.')
    //                  ->with('completed_module', $module_id); // Ensure the session is set here

    return view('mentor.modules.menteemoduleprogress', compact('modules', 'moduleCompletionStatus'));
}

public function markModuleAsCompleted(Request $request)
{
    // Retrieve the mentee ID, module ID, and mentor ID from the form
    $menteeId = $request->input('mentee_id');
    $moduleId = $request->input('module_id');
    $mentorId = $request->input('mentor_id'); // Logged-in mentor ID

    // Retrieve the score from the quiz_results table for the given mentee and module using query builder
    $quizResult = DB::table('quiz_results')
                    ->where('menteename_id', $menteeId)
                    ->where('module_id', $moduleId)
                    ->first();

    if ($quizResult) {
        // If a quiz result is found, save or update the entry in the overallmodule table using query builder
        DB::table('overallmodule')->updateOrInsert(
            [
                'mentorname_id' => $mentorId, // Mentor ID
                'menteename_id' => $menteeId, // Mentee ID
                'module_id' => $moduleId, // Module ID
            ],
            [
                'score' => $quizResult->score, // Store the score from the quiz result
                'completed_at' => now(), // Store the completion timestamp
                'updated_at' => now() // Ensure that updated_at is set for updates
            ]
        );

        // Return success response and redirect back to the modules page
        return redirect()->route('mentor.modules.index')->with('success', 'Module marked as completed and score saved!');
    }

    // If no quiz result is found, return an error response
    return redirect()->route('mentor.modules.index')->with('error', 'Quiz result not found for this module.');
}

    
    public function moduleList()
    {
        $mentorEmail = Auth::user()->email;
        $mentor = DB::table('mentors')
        ->where('email', $mentorEmail)
        ->first();

        $sessions_list = DB::table('sessions')
        ->where('mentorname_id', $mentor->id)
        ->get();
        $totalSessions=$sessions_list->count();
        $totalMinutesMentored = 0;
        foreach ($sessions_list as $session) {
            if ($session->done === 'Yes') {
                $totalMinutesMentored += (int)$session->session_duration_minutes;
            }
        }
        $modules = DB::table('modules')->get();
        $sessions = [];
        foreach ($modules as $module) {
        // Check if any session exists for the module and mentee
        $session = DB::table('sessions')
            ->where('modulename_id', $module->id)
            ->where('mentorname_id', $mentor->id) // Check mentee ID
            ->get(); // Use get() to fetch multiple sessions
        // Add session(s) to sessions array
        if ($session->isNotEmpty()) {
            $sessions[$module->id] = $session;
        } else {
            $sessions[$module->id] = []; // Initialize as empty array if no sessions found
        }
          	// $modules=Module::all();

        return view('mentor.modules.moduleList',compact('modules','session','sessions_list','mentor'));
        }
    }
    // public function modulecompletionmail()
    // {
    //     // Step 1: Get the logged-in mentor
    //     $mentor = auth()->user(); // Assuming the mentor is logged in

    //     // Step 2: Fetch the mapped mentee details for the logged-in mentor
    //     $mapping = DB::table('mappings')
    //         ->where('mentor_email', $mentor->email)
    //         ->first();

    //     if (!$mapping) {
    //         return redirect()->back()->with('error', 'No mapped mentee found for this mentor.');
    //     }

    //     $mentee_id = $mapping->mentee_id;

    //     // Step 3: Get total chapters per module
    //     $moduleCounts = DB::table('chapters')
    //         ->select('module_id', DB::raw('COUNT(*) as total_chapters'))
    //         ->groupBy('module_id')
    //         ->get();

    //     // Step 4: Get completed chapters per module for the mapped mentee
    //     $completionStatus = DB::table('module_completion_tracker')
    //         ->select('module_id', DB::raw('COUNT(DISTINCT chapter_id) as completed_chapters'))
    //         ->where('mentee_id', $mentee_id)
    //         ->groupBy('module_id')
    //         ->get();

    //     // Step 5: Merge and check if all chapters are completed for the mapped mentee
    //     $completionCheck = [];

    //     foreach ($moduleCounts as $module) {
    //         // Find the completed chapters for the current module
    //         $status = $completionStatus->firstWhere('module_id', $module->module_id);
    //         $completedChapters = $status ? $status->completed_chapters : 0;
    //         $isCompleted = $module->total_chapters == $completedChapters;

    //         $completionCheck[] = [
    //             'mentee_id' => $mentee_id,
    //             'module_id' => $module->module_id,
    //             'total_chapters' => $module->total_chapters,
    //             'completed_chapters' => $completedChapters,
    //             'is_completed' => $isCompleted ? 'Yes' : 'No'
    //         ];
    //     }
    //         }
}