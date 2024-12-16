<?php

namespace App\Http\Controllers\Mentor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class QuizController extends Controller
{

    public function quizdetails()
{
    // Get the logged-in mentor's ID
    $mentorId = auth()->user()->id;
    
    // Get the mentees mapped to the logged-in mentor
    $mappedMentees = DB::table('mappings')
                        ->where('mentorname_id', $mentorId)
                        ->pluck('menteename_id');
    
    // Get all modules excluding deleted ones (soft delete check)
    $modules = DB::table('modules')
                    ->whereNull('deleted_at')  // Exclude deleted modules
                    ->get();
    
    // Prepare data for module counts using Query Builder
    $moduleCounts = [];
    $overallCompletedQuizzes = 0;
    $overallPendingQuizzes = 0;
    $modulewiseComplete = [];
    $modulewisePending = [];
    
    foreach ($modules as $module) {
        // Initialize counts for each module
        $completedCount = 0;
        $pendingCount = 0;
        
        foreach ($mappedMentees as $menteeId) {
            // Get quiz results for the module and mentee
            $quizResults = DB::table('quiz_results')
                ->where('module_id', $module->id)
                ->where('user_id', $menteeId)
                ->get();
    
            // Count completed and pending quizzes
            foreach ($quizResults as $result) {
                if ($result->score > 0) {
                    $completedCount++;
                    $overallCompletedQuizzes++; // Increment overall completed quizzes
                } else {
                    $pendingCount++;
                    $overallPendingQuizzes++; // Increment overall pending quizzes
                }
            }
        }
    
        // Store the result in the $moduleCounts array
        $moduleCounts[] = [
            'module_name' => $module->name,
            'completed' => $completedCount,
            'pending' => $pendingCount
        ];
    
        // Store the counts for module-wise chart
        $modulewiseComplete[$module->name] = $completedCount;
        $modulewisePending[$module->name] = $pendingCount;
    }
    
    // Pass the data to the view
    return view('mentor.quiz.quizdetails', compact(
        'moduleCounts',
        'modules',
        'mappedMentees',
        'overallCompletedQuizzes',
        'overallPendingQuizzes',
        'modulewiseComplete',
        'modulewisePending'
    ));
}




    private function calculateScore($module)
    {
        // Implement your logic to calculate the score for the given module
        return rand(50, 100); // Placeholder
    }

    private function getChaptersForModule($module)
    {
        // Implement your logic to retrieve chapters for the given module
        return [
            ['name' => 'Chapter 1', 'status' => 'Completed', 'score' => 80],
            ['name' => 'Chapter 2', 'status' => 'Pending', 'score' => 0],
            // Add more chapters as needed
        ];
    }

    // public function mentorquiz(Request $request, $chapter_id)
    // {
    //     // // Get the logged-in mentor details
    //         $mentor = auth()->user();

    //         $mentorid = DB::table('mentors')
    //         ->where('email', $mentor->email)->first();

    //         $mentorid = $mentorid->id;



    //     // // Get the mapped mentee using query builder
    //     $mappedMentee = DB::table('mappings')
    //         ->where('mentorname_id', $mentorid)
    //         ->first();

    //         // return $mappedMentee ;

            

    //     if (!$mappedMentee) {
    //         return redirect()->back()->with('error', 'No mentee mapped to this mentor.');
    //     }

    //     // // Get the mentee details using query builder
    //     $mentee = DB::table('mentees')
    //         ->where('id', $mappedMentee->menteename_id)
    //         ->first();


    //     if (!$mentee) {
    //         return redirect()->back()->with('error', 'Mentee not found.');
    //     }

    //     // // Get chapter details using query builder
    //     $chapter = DB::table('chapters')
    //         ->where('id', $chapter_id)
    //         ->first();

            
    //         $moduleid = $chapter->module_id;

    //         // return $moduleid;

    //     if (!$chapter) {
    //         return redirect()->back()->with('error', 'Chapter not found.');
    //     }

    //     // // Fetch discussion questions for this chapter (assuming `mcq` is set to 0 for discussion points)
    //     // $discussionQuestions = DB::table('questions')
    //     //     ->where('chapter_id', $chapter_id)
    //     //     ->where('mcq', 0)
    //     //     ->get();

    //     // // Fetch quiz questions for this chapter (assuming `mcq` is set to 1 for quiz questions)
    //     // // $quizQuestions = DB::table('questions')
    //     // //     ->where('chapter_id', $chapter_id)
    //     // //     ->where('mcq', 1)
    //     // //     ->get();


    //     $user = DB::table('users')
    //     ->where('email',$mentee->email )
    //     ->first();

    //     $user =$user->id;


    //         $maxResult = DB::table('quiz_results')
    //             ->where('user_id', $user)
    //             ->where('module_id', $moduleid)
    //             ->orderBy('score', 'desc')
    //             ->first();

    //             // if (!$maxResult) {
    //             //     $maxResult = (object) ['score' => null]; // Default object with null score
    //             // }


    //      // Return the view with the necessary data
    //     return view('mentor.modules.quiz', compact( 'mentor','maxResult','user','mappedMentee','chapter','mentee'));
    // }


public function showmentorquiz(Request $request, $chapter_id)
{
    // Get the logged-in mentor
    $mentor = auth()->user();

    $chapterId = $chapter_id;

    // Fetch mentor ID based on the logged-in mentor's email
    $mentorDetails = DB::table('mentors')
        ->where('email', $mentor->email)
        ->first();

    if (!$mentorDetails) {
        return redirect()->back()->with('error', 'Mentor details not found.');
    }

    $mentorId = $mentorDetails->id;

    // Get the mapped mentee for the mentor
    $mappedMentee = DB::table('mappings')
        ->where('mentorname_id', $mentorId)
        ->whereNull('deleted_at') // Ensure mapping is not deleted
        ->first();

    if (!$mappedMentee) {
        return redirect()->back()->with('error', 'No mentee mapped to this mentor.');
    }

    // Get mentee details
    $mentee = DB::table('mentees')
        ->where('id', $mappedMentee->menteename_id)
        ->first();

    if (!$mentee) {
        return redirect()->back()->with('error', 'Mentee details not found.');
    }

    // Get the chapter details
    $chapter = DB::table('chapters')
        ->where('id', $chapter_id)
        ->first();

    if (!$chapter) {
        return redirect()->back()->with('error', 'Chapter not found.');
    }

    // Retrieve the module ID from the chapter
    $moduleId = $chapter->module_id;

    // Get the user ID linked to the mentee email
    $menteeUser = DB::table('users')
        ->where('email', $mentee->email)
        ->first();

    if (!$menteeUser) {
        return redirect()->back()->with('error', 'Mentee user account not found.');
    }

    $userId = $menteeUser->id;

    // Fetch the maximum quiz result for the mentee in the module
    // $maxResult = DB::table('quiz_results')
    //     ->where('user_id', $userId)
    //     ->where('module_id', $moduleId)
    //     ->orderBy('score', 'desc')
    //     ->first();

    $maxResult = DB::table('quiz_results')
    ->where('user_id', $userId)
    ->where('module_id', $moduleId)
    ->orderBy('score', 'desc')
    ->select('score', 'attempts') // Specify the columns you want to retrieve
    ->first();


    // Fetch discussion answers for the selected chapter and mapped mentee
    $discussionAnswers = DB::table('discussion_answers')
    ->join('questions', 'discussion_answers.question_id', '=', 'questions.id')
    ->join('tests', 'questions.test_id', '=', 'tests.id') // Assuming questions link to tests
    ->where('tests.chapter_id', $chapterId) // Use tests to filter by chapter
    ->where('discussion_answers.menteename_id', $mappedMentee->menteename_id)
    ->whereNull('discussion_answers.deleted_at') // Exclude soft-deleted answers
    ->select('discussion_answers.*', 'questions.question_text')
    ->get();


    // If no discussion answers exist, initialize an empty collection
    if ($discussionAnswers->isEmpty()) {
        $discussionAnswers = collect();
    }

    // Return the view with the necessary data
    return view('mentor.modules.quiz', compact(
        'mentor',
        'maxResult',
        'userId',
        'mappedMentee',
        'chapter',
        'mentee',
        'discussionAnswers'
    ));
}

    public function storeMentorReply(Request $request, $id)
    {
        // Validate the request
        $request->validate([
            'mentorsreply' => 'required|string|max:65535',
        ]);

        // Update the mentor's reply using Query Builder
        $updated = DB::table('discussion_answers')
            ->where('id', $id)
            ->update(['mentorsreply' => $request->input('mentorsreply'), 'updated_at' => now()]);

        // Check if the update was successful
        if ($updated) {
            return redirect()->back()->with('success', 'Reply submitted successfully.');
        } else {
            return redirect()->back()->with('error', 'Failed to submit the reply. Please try again.');
        }
    }



    // public function mentorquiz(Request $request)
    // {
    //     $chapterId = $request->chapter_id;
    //     $menteeId = $request->mentee_id; // Assuming mentee_id is passed in the request

    //     // Retrieve the logged-in mentor details (Assuming you are using Auth)
    //     $mentorEmail = Auth::user()->email;

    //     // Fetch the mentor's details
    //     $mentor = DB::table('mentors')
    //         ->where('email', $mentorEmail)
    //         ->first();
        
    //     // Retrieve the mapped mentee information for the logged-in mentor
    //     $mappedMentee = DB::table('mappings')
    //         ->where('mentorname_id', $mentor->id)
    //         ->whereNull('deleted_at')
    //         ->first();
        
    //     // Fetch mentee details using menteename_id from the mapped table if a mapping exists
    //     $menteeDetails = null;
    //     if ($mappedMentee) {
    //         $menteeDetails = DB::table('mentees')
    //             ->where('id', $mappedMentee->menteename_id)
    //             ->first();
    //     }

    //     // return $menteeDetails;
       

    //     // Retrieve chapter details using Query Builder
    //     $chapter = DB::table('chapters')->where('id', $chapterId)->first();

    //     // Retrieve quiz tests for the chapter using Query Builder
    //     $tests = DB::table('tests')
    //         ->where('chapter_id', $chapterId)
    //         ->get()
    //         ->map(function ($test) {
    //             $test->questions = DB::table('questions')
    //                 ->where('test_id', $test->id)
    //                 ->get()
    //                 ->map(function ($question) {
    //                     $question->options = DB::table('options')
    //                         ->where('question_id', $question->id)
    //                         ->get();
    //                     return $question;
    //                 });
    //             return $test;
    //         });

    //     // Retrieve mentee details using Query Builder
    //     // $mentee = DB::table('mentees')->where('id', $menteeId)->first();


       

        
    //     // Fetch quiz results for the mentee from the quiz_results table
    //     $quizResult = DB::table('quiz_results')
    //         ->where('user_id', $menteeId)
    //         ->where('module_id', $chapter->module_id)
    //         ->first();

    //     $quizDetails = [
    //         'score' => $quizResult ? $quizResult->score : 0, // Score from quiz_results table
    //         'total' => $quizResult ? $quizResult->total_points : 0, // Total points from quiz_results table
    //         'attempts' => $quizResult ? $quizResult->attempts : 0, // Attempts from quiz_results table
    //         // 'chapters_completed' => DB::table('chapter_completions')
    //         //     ->where('mentee_id', $menteeId)
    //         //     ->count(), // Example to fetch completed chapters
    //         // 'review' => DB::table('quiz_submissions')
    //         //     ->where('mentee_id', $menteeId)
    //         //     ->where('chapter_id', $chapterId)
    //         //     ->get()
    //         //     ->map(function ($submission) {
    //         //         $question = DB::table('questions')->where('id', $submission->question_id)->first();
    //         //         $correctAnswer = DB::table('options')
    //         //             ->where('question_id', $question->id)
    //         //             ->where('is_correct', true)
    //         //             ->first();
    //         //         $yourAnswer = DB::table('options')->where('id', $submission->option_id)->first();
    //         //         return [
    //         //             'question' => $question->question_text,
    //         //             'correct_answer' => $correctAnswer ? $correctAnswer->option_text : 'N/A',
    //         //             'your_answer' => $yourAnswer ? $yourAnswer->option_text : 'N/A',
    //         //             'is_correct' => $yourAnswer && $yourAnswer->id == $correctAnswer->id,
    //         //         ];
    //         //     }),
    //     ];

    //     // Pass the data to the view
    //     return view('mentor.modules.quiz', compact('chapter', 'tests','menteeDetails' , 'quizDetails'));
    // }
}
