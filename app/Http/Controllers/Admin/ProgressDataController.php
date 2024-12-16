<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ModuleProgressExport;
use App\Chapter;
use App\Module;
use App\Mentee;
use App\Models\ModuleCompletionTracker;


use Illuminate\Http\Request;  

class ProgressDataController extends Controller
{

    public function ProgressData()
{
    try {
        // Start a transaction (optional, if updates are planned)
        DB::beginTransaction();

        // Retrieve progress data with LEFT JOIN to handle missing related records
        // Add a condition to only show completed chapters by checking for non-null 'completed_at'
        $progressData = DB::table('module_completion_tracker')
            ->leftJoin('mappings', 'module_completion_tracker.mentee_id', '=', 'mappings.menteename_id')
            ->leftJoin('mentees', 'mappings.menteename_id', '=', 'mentees.id')
            ->leftJoin('mentors', 'mappings.mentorname_id', '=', 'mentors.id')
            ->leftJoin('modules', 'module_completion_tracker.module_id', '=', 'modules.id')
            ->leftJoin('chapters', 'module_completion_tracker.chapter_id', '=', 'chapters.id') // Join chapters using chapter_id
            ->whereNotNull('module_completion_tracker.completed_at') // Filter for only completed chapters
            ->select(
                'mentees.name as mentee_name',
                'mentors.name as mentor_name',
                'modules.name as module_name',
                'chapters.chaptername as chapter_name', // Select chapter name from the chapters table
                'module_completion_tracker.completed_at'
            )
            ->orderBy('module_completion_tracker.completed_at', 'desc')
            ->get();

        // Commit the transaction
        DB::commit();

        // Retrieve chapterwise progress data for pending chapters (where completed_at is null)
        $chapterwiseData = DB::table('chapters')
        ->leftJoin('modules', 'chapters.module_id', '=', 'modules.id') // Join with modules to get module names
        ->leftJoin('module_completion_tracker', 'chapters.id', '=', 'module_completion_tracker.chapter_id') // Join with tracker for completion status
        ->whereNull('chapters.deleted_at') // Only include chapters that are not deleted
        ->whereNull('module_completion_tracker.completed_at') // Only include pending chapters (completed_at is null)
        ->select(
            'chapters.chaptername as chapter_name',
            'modules.name as module_name'
        )
        ->orderBy('modules.name', 'asc') // Order first by module name
        ->orderBy('chapters.chaptername', 'asc') // Then by chapter name
        ->get();


        $mentorOverviewData = DB::table('mappings')
        ->leftJoin('mentees', 'mappings.menteename_id', '=', 'mentees.id') // Join with mentees table to get mentee details
        ->leftJoin('mentors', 'mappings.mentorname_id', '=', 'mentors.id') // Join with mentors table to get mentor details
        ->select(
            'mentees.id as mentee_id', // Add mentee id to the select
            'mentors.id as mentor_id', // Add mentor id to the select
            'mentees.name as mentee_name',
            'mentors.name as mentor_name'
        )
        ->get();
    



        // Return the view with progress data
        return view('admin.progressData.chapterwiseprogress', compact('progressData','chapterwiseData','mentorOverviewData'));
    } catch (\Exception $e) {
        // Roll back the transaction in case of error
        DB::rollBack();

        // Log or handle the error
        logger()->error('Error fetching module progress data:', ['error' => $e->getMessage()]);

        // Optionally redirect back with error message
        return redirect()->back()->withErrors(['error' => 'Failed to load module progress. Please try again.']);
    }
}

    // public function exportModuleProgress()
    // {
    //     try {
    //         // Export the data using a dedicated export class
    //         return Excel::download(new ModuleProgressExport, 'module_progress.xlsx');
    //     } catch (\Exception $e) {
    //         // Log or handle the error
    //         logger()->error('Error exporting module progress data:', ['error' => $e->getMessage()]);
    //         return redirect()->back()->withErrors(['error' => 'Failed to export module progress.']);
    //     }
    // }

    
    public function getModuleProgress() {
        // Fetch all modules
        $modules = Module::all();
    
        $moduleProgressData = [];
    
        foreach ($modules as $module) {
            // Get chapters for the current module
            $chapters = Chapter::where('module_id', $module->id)->get();
    
            // Count total mentees
            $totalMentees = Mentee::count();
    
            // Track completed and pending mentees
            $completedMentees = 0;
    
            foreach (Mentee::all() as $mentee) {
                $completedChapters = ModuleCompletionTracker::where('mentee_id', $mentee->id)
                    ->whereIn('chapter_id', $chapters->pluck('id'))
                    ->count();
    
                if ($completedChapters == $chapters->count()) {
                    $completedMentees++;
                }
            }
    
            $pendingMentees = $totalMentees - $completedMentees;
    
            // Add module data
            $moduleProgressData[] = [
                'module' => $module,
                'chapters' => $chapters,
                'completedMentees' => $completedMentees,
                'pendingMentees' => $pendingMentees,
            ];
        }
    
        return view('progress.module_progress', ['moduleProgressData' => $moduleProgressData]);
    }
    
    
    

}
