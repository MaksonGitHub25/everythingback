<?php

namespace App\Http\Controllers\API;
use Faker\Core\File;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FeedbackController extends Controller
{
    public function getFeedbacks(): \Illuminate\Http\JsonResponse
    {
        $feedbacks = DB::table('feedback')->get();
        return response()->json($feedbacks)->header('Content-Type', 'application/json');
    }

    public function addNewFeedback(Request $request)
    {
        $userName = $request->input('userName');
        $date = $request->input('date');
        $feedbackText = $request->input('feedbackText');
        $uniqueFeedbackId = $request->input('uniqueFeedbackId');

        DB::table('feedback')->insert([
            'userName' => $userName,
            'date' => $date,
            'feedbackText' => $feedbackText,
            'uniqueFeedbackId' => $uniqueFeedbackId,
        ]);

        return response()->json(['success' => true]);
    }

    public function deleteFeedback($feedbackId): \Illuminate\Http\JsonResponse
    {
        if (DB::table('feedback')->where('uniqueFeedbackId', $feedbackId)->exists()) {
            DB::table('feedback')->where('uniqueFeedbackId', $feedbackId)->delete();
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false, 'errorMessage' => "Feedback with this uniqueFeedbackId doesn't exist"]);
    }
}
