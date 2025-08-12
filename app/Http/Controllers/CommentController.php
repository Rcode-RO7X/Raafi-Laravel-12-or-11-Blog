<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\BlogPost;
use App\Models\CommentVote;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class CommentController extends Controller
{
    public function store(Request $request)
    {
        try {
            if (!Auth::check()) {
                return response()->json([
                    'success' => false,
                    'message' => 'You must be logged in to comment'
                ], 401);
            }

            $request->validate([
                'post_id' => 'required|exists:blog_posts,id',
                'content' => 'required|string|max:1000'
            ]);

            $comment = Comment::create([
                'post_id' => $request->post_id,
                'user_id' => Auth::id(),
                'content' => $request->content
            ]);

            $comment->load('user');

            return response()->json([
                'success' => true,
                'comment' => $comment
            ]);
        } catch (\Exception $e) {
            Log::error('Error creating comment: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to create comment'
            ], 500);
        }
    }

    public function like($id)
    {
        try {
            if (!Auth::check()) {
                return response()->json([
                    'success' => false,
                    'message' => 'You must be logged in to like comments'
                ], 401);
            }
            
            $comment = Comment::findOrFail($id);
            $userId = Auth::id();
            
            // Check if user has already voted on this comment
            $existingVote = CommentVote::where('comment_id', $id)
                ->where('user_id', $userId)
                ->first();
                
            if ($existingVote) {
                if ($existingVote->is_like) {
                    // User already liked, so remove the like
                    $existingVote->delete();
                    $comment->decrement('likes');
                } else {
                    // User disliked, change to like
                    $existingVote->update(['is_like' => true]);
                    $comment->increment('likes');
                    $comment->decrement('dislikes');
                }
            } else {
                // New vote, create it
                CommentVote::create([
                    'comment_id' => $id,
                    'user_id' => $userId,
                    'is_like' => true
                ]);
                $comment->increment('likes');
            }

            return response()->json([
                'success' => true,
                'likes' => $comment->likes,
                'dislikes' => $comment->dislikes
            ]);
        } catch (\Exception $e) {
            Log::error('Error liking comment: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to like comment'
            ], 500);
        }
    }

    public function dislike($id)
    {
        try {
            if (!Auth::check()) {
                return response()->json([
                    'success' => false,
                    'message' => 'You must be logged in to dislike comments'
                ], 401);
            }
            
            $comment = Comment::findOrFail($id);
            $userId = Auth::id();
            
            // Check if user has already voted on this comment
            $existingVote = CommentVote::where('comment_id', $id)
                ->where('user_id', $userId)
                ->first();
                
            if ($existingVote) {
                if (!$existingVote->is_like) {
                    // User already disliked, so remove the dislike
                    $existingVote->delete();
                    $comment->decrement('dislikes');
                } else {
                    // User liked, change to dislike
                    $existingVote->update(['is_like' => false]);
                    $comment->increment('dislikes');
                    $comment->decrement('likes');
                }
            } else {
                // New vote, create it
                CommentVote::create([
                    'comment_id' => $id,
                    'user_id' => $userId,
                    'is_like' => false
                ]);
                $comment->increment('dislikes');
            }

            return response()->json([
                'success' => true,
                'likes' => $comment->likes,
                'dislikes' => $comment->dislikes
            ]);
        } catch (\Exception $e) {
            Log::error('Error disliking comment: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to dislike comment'
            ], 500);
        }
    }
    
    public function destroy($id)
    {
        try {
            if (!Auth::check()) {
                return response()->json([
                    'success' => false,
                    'message' => 'You must be logged in to delete comments'
                ], 401);
            }
            
            $comment = Comment::findOrFail($id);
            
            // Check if the authenticated user is the owner of the comment
            if ($comment->user_id !== Auth::id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'You are not authorized to delete this comment'
                ], 403);
            }
            
            // Delete associated votes first
            CommentVote::where('comment_id', $id)->delete();
            
            // Delete the comment
            $comment->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Comment deleted successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error deleting comment: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete comment'
            ], 500);
        }
    }
}