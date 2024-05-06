<?php

namespace App\Http\Controllers;

use App\Http\Requests\JobApplicationAttemptCreate;
use App\Http\Requests\JobApplicationAttemptProcess;
use App\Http\Requests\JobApplicationDelete;
use App\Http\Requests\JobApplicationEdit;
use App\Http\Requests\JobApplicationJobCreate;
use App\Http\Requests\JobQuestion;
use App\Http\Requests\JobQuestionCreate;
use App\Models\JobApplication;
use App\Models\JobApplicationAttempt;
use App\Models\JobApplicationQuestion;

class JobApplicationController extends Controller
{
    public function showJobApplications()
    {
        $jobApplications = JobApplication::paginate(5);

        return view('job_applications', ['jobApplications' => $jobApplications]);
    }

    public function processJobApplicationCreation(JobApplicationAttemptCreate $request)
    {
        $jobApplication = JobApplication::where('id', $request->get('jobApplicationID'))->firstOrFail();
        $questions = array();

        foreach ($jobApplication->jobApplicationQuestions as $jobApplicationQuestion) {
            array_push($questions, array('id' => $jobApplicationQuestion->qid, 'type' => $jobApplicationQuestion->type, 'label' => $jobApplicationQuestion->label, 'answer' => $request->get($jobApplicationQuestion->qid)));
        }

        JobApplicationAttempt::create([
            'user_id' => auth()->user()->id,
            'job_application_id' => $request->get('jobApplicationID'),
            'status' => 'Waiting approval',
            'questions' => json_encode($questions)
        ]);

        return back()->with('success', 'Successfully applied for this job!');
    }

    public function processJobApplicationJobCreation(JobApplicationJobCreate $request)
    {
        JobApplication::create([
            'job' => $request->get("job"),
            'status' => $request->get('status')
        ]);

        return back()->with('success', 'Successfully created job application!');
    }

    public function showJobQuestions()
    {
        if (auth()->user()->role == "user" or auth()->user()->role == "banned") return back();

        $jobs = JobApplication::all();
        $questions = JobApplicationQuestion::paginate(10);

        return view('job_questions', ['jobs' => $jobs, 'questions' => $questions]);
    }

    public function processJobQuestionCreation(JobQuestionCreate $request)
    {
        if ($jobQuestion = JobApplicationQuestion::where('qid', $request->get('qid'))->first()) {
            return back()->with('error', 'Question with this id already exist. ('. $jobQuestion->label .')');
        }

        JobApplicationQuestion::create([
            'qid' => $request->get('qid'),
            'job_application_id' => $request->get('job'),
            'type' => $request->get('type'),
            'label' => $request->get('label')
        ]);

        return back()->with('success', 'Successfully create job question!');
    }

    public function processJobQuestion(JobQuestion $request)
    {
        $jobQuestion = JobApplicationQuestion::where('id', $request->get('jobQuestionID'))->firstOrFail();

        if ($request->input('action') == "edit") {
            $jobQuestion->update([
                'qid' => $request->get('questionID'),
                'job_application_id' => $request->get('job'),
                'type' => $request->get('questionType'),
                'label' => $request->get('questionLabel')
            ]);

            return back()->with('success', 'Successfully edited the question!');
        } elseif ($request->input('action') == "remove") {
            $jobQuestion->delete();

            return back()->with('success', 'Successfully deleted the question!');
        }

        return back();
    }

    public function processJobApplicationEdit(JobApplicationEdit $request)
    {
        $jobApplication = JobApplication::where('id', $request->get('jobID'))->firstOrFail();

        $jobApplication->update([
            'status' => $request->get('status'),
            'job' => $request->get('name')
        ]);

        return back()->with('success', 'Successfully edited the job application!');
    }

    public function processJobApplicationDelete(JobApplicationDelete $request)
    {
        $jobApplication = JobApplication::where('id', $request->get('jobID'))->firstOrFail();

        $jobApplication->delete();

        return back()->with('success', 'Successfully deleted the job application!');
    }

    public function showJobApplicationAttempts()
    {
        $attempts = JobApplicationAttempt::paginate(10);

        return view('job_application_attempts', ['attempts' => $attempts]);
    }

    public function processJobApplicationAttempt(JobApplicationAttemptProcess $request)
    {
        $applicationAttempt = JobApplicationAttempt::where('id', $request->get('attemptID'))->firstOrFail();

        if ($request->input('action') == "approve") {
            $applicationAttempt->update([
                'status' => 'Approved'
            ]);

            return back()->with('success', 'Successfully approved the job application!');
        } elseif ($request->input('action') == "reject") {
            $applicationAttempt->update([
                'status' => 'Rejected'
            ]);

            return back()->with('success', 'Successfully rejected the job application!');
        }

        return back();
    }
}
