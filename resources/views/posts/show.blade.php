@extends('components.layouts.app')

@php
    use Illuminate\Support\Facades\Auth;
@endphp

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mb-6">{{ $post->title }}</h1>
    <div class="text-gray-600 dark:text-gray-400 mb-6 flex flex-wrap items-center gap-2">
        <span>By {{ $post->author->name }}</span> 
        <span class="text-gray-400">•</span>
        <span>{{ $post->published_at ? $post->published_at->format('M d, Y') : 'Not published' }}</span>
        <span class="text-gray-400">•</span>
        <span>{{ $post->comments->count() }} comments</span>
    </div>
    @if($post->featured_image)
        <div class="mb-8 rounded-xl overflow-hidden shadow-lg">
            <img src="{{ asset('storage/' . $post->featured_image) }}" alt="{{ $post->title }}" class="w-full h-auto" loading="lazy">
        </div>
    @endif
    <div class="prose dark:prose-dark max-w-none">
        {!! nl2br(e($post->content)) !!}
    </div>

    <!-- Comments Section -->
    <div class="mt-16">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6 flex items-center">
            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
            </svg>
            Comments ({{ $post->comments->count() }})
        </h2>
        
        <!-- Add Comment Form -->
        <div class="mt-8 mb-12">
            <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Add a Comment</h3>
            <form id="comment-form" class="space-y-4">
                <div>
                    <textarea id="comment-content" name="content" rows="4"
                              class="w-full px-4 py-3 text-gray-700 dark:text-gray-300 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-300"
                              placeholder="Share your thoughts..." required></textarea>
                </div>
                <div>
                    <button type="submit"
                            class="px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-300 transform hover:scale-105 shadow-md">
                        Post Comment
                    </button>
                </div>
            </form>
        </div>

        <!-- Comments List -->
        @if($post->comments->count() > 0)
            <div class="space-y-6" id="comments-container">
                @foreach($post->comments as $comment)
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6 transition-all duration-300 hover:shadow-lg animate-fade-in-up comment-item" data-comment-id="{{ $comment->id }}">
                    <div class="flex justify-between items-start mb-3">
                        <div class="flex items-center">
                            <div class="w-10 h-10 rounded-full bg-blue-500 flex items-center justify-center text-white font-bold mr-3">
                                {{ substr($comment->user->name, 0, 1) }}
                            </div>
                            <div>
                                <span class="font-semibold text-gray-900 dark:text-white">{{ $comment->user->name }}</span>
                                <span class="mx-2 text-gray-500">•</span>
                                <span class="text-sm text-gray-500">{{ $comment->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                        <div class="flex space-x-2">
                            @if(Auth::check() && Auth::id() == $comment->user_id)
                            <button class="flex items-center text-red-600 hover:text-red-800 transition-colors duration-300 delete-btn"
                                    data-comment-id="{{ $comment->id }}" title="Delete comment">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                            @endif
                            <button class="flex items-center text-green-600 hover:text-green-800 transition-colors duration-300 like-btn"
                                    data-comment-id="{{ $comment->id }}">
                                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L9 7m-7 10v-1a2 2 0 012-2h4.017c.163 0 .326.02.485.06L13 15"></path>
                                </svg>
                                <span class="likes-count" data-comment-id="{{ $comment->id }}">{{ $comment->likes }}</span>
                            </button>
                            <button class="flex items-center text-red-600 hover:text-red-800 transition-colors duration-300 dislike-btn"
                                    data-comment-id="{{ $comment->id }}">
                                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14H5.236a2 2 0 01-1.789-2.894l3.5-7A2 2 0 018.736 3h4.018c.163 0 .326.02.485.06L17 4m-7 10v5a2 2 0 002 2h.095c.5 0 .905-.405.905-.905 0-.714.211-1.412.608-2.006L15 17m-7-10V5a2 2 0 00-2-2H3.095C2.5 3 2 3.405 2 3.905 2 4.714 2.211 5.412 2.608 6L3 7"></path>
                                </svg>
                                <span class="dislikes-count" data-comment-id="{{ $comment->id }}">{{ $comment->dislikes }}</span>
                            </button>
                        </div>
                    </div>
                    <p class="text-gray-700 dark:text-gray-300 mt-3">{{ $comment->content }}</p>
                </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-12">
                <svg class="w-16 h-16 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                </svg>
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mt-4">No comments yet</h3>
                <p class="text-gray-600 dark:text-gray-400 mt-2">Be the first to share your thoughts!</p>
            </div>
        @endif
    </div>
</div>

<script>
    // Function to handle comment submission
    document.getElementById('comment-form').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const content = document.getElementById('comment-content').value;
        const postId = {{ $post->id }};
        
        if (!content.trim()) {
            alert('Please enter a comment');
            return;
        }
        
        // Send AJAX request to store comment
        fetch("{{ route('comments.store') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                post_id: postId,
                content: content
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Add the new comment to the list
                const commentsContainer = document.getElementById('comments-container');
                if (!commentsContainer) {
                    // Create container if it doesn't exist
                    const commentsSection = document.querySelector('.mt-16');
                    const newContainer = document.createElement('div');
                    newContainer.id = 'comments-container';
                    newContainer.className = 'space-y-6';
                    commentsSection.appendChild(newContainer);
                }
                
                const newComment = document.createElement('div');
                newComment.className = 'bg-white dark:bg-gray-800 rounded-xl shadow-md p-6 transition-all duration-300 hover:shadow-lg animate-fade-in-up comment-item';
                newComment.setAttribute('data-comment-id', data.comment.id);
                newComment.innerHTML = `
                    <div class="flex justify-between items-start mb-3">
                        <div class="flex items-center">
                            <div class="w-10 h-10 rounded-full bg-blue-500 flex items-center justify-center text-white font-bold mr-3">
                                ${data.comment.user.name.charAt(0)}
                            </div>
                            <div>
                                <span class="font-semibold text-gray-900 dark:text-white">${data.comment.user.name}</span>
                                <span class="mx-2 text-gray-500">•</span>
                                <span class="text-sm text-gray-500">Just now</span>
                            </div>
                        </div>
                        <div class="flex space-x-2">
                            <button class="flex items-center text-red-600 hover:text-red-800 transition-colors duration-300 delete-btn" data-comment-id="${data.comment.id}" title="Delete comment">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                            <button class="flex items-center text-green-600 hover:text-green-800 transition-colors duration-300 like-btn" data-comment-id="${data.comment.id}">
                                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L9 7m-7 10v-1a2 2 0 012-2h4.017c.163 0 .326.02.485.06L13 15"></path>
                                </svg>
                                <span class="likes-count" data-comment-id="${data.comment.id}">${data.comment.likes}</span>
                            </button>
                            <button class="flex items-center text-red-600 hover:text-red-800 transition-colors duration-300 dislike-btn" data-comment-id="${data.comment.id}">
                                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14H5.236a2 2 0 01-1.789-2.894l3.5-7A2 2 0 018.736 3h4.018c.163 0 .326.02.485.06L17 4m-7 10v5a2 2 0 002 2h.095c.5 0 .905-.405.905-.905 0-.714.211-1.412.608-2.006L15 17m-7-10V5a2 2 0 00-2-2H3.095C2.5 3 2 3.405 2 3.905 2 4.714 2.211 5.412 2.608 6L3 7"></path>
                                </svg>
                                <span class="dislikes-count" data-comment-id="${data.comment.id}">${data.comment.dislikes}</span>
                            </button>
                        </div>
                    </div>
                    <p class="text-gray-700 dark:text-gray-300 mt-3">${data.comment.content}</p>
                `;
                
                document.getElementById('comments-container').prepend(newComment);
                
                // Clear the form
                document.getElementById('comment-content').value = '';
                
                // Update comment count
                const commentCountElement = document.querySelector('h2 .comments-count');
                if (commentCountElement) {
                    const currentCount = parseInt(commentCountElement.textContent) || 0;
                    commentCountElement.textContent = currentCount + 1;
                }
            } else {
                alert('Error submitting comment: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error submitting comment');
        });
    });
    
    // Function to handle liking a comment
    document.addEventListener('click', function(e) {
        if (e.target.closest('.like-btn')) {
            const button = e.target.closest('.like-btn');
            const commentId = button.getAttribute('data-comment-id');
            
            // Send AJAX request to like comment
            fetch(`/comments/${commentId}/like`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const likesElement = document.querySelector(`.likes-count[data-comment-id="${commentId}"]`);
                    const dislikesElement = document.querySelector(`.dislikes-count[data-comment-id="${commentId}"]`);
                    if (likesElement) likesElement.textContent = data.likes;
                    if (dislikesElement) dislikesElement.textContent = data.dislikes;
                } else {
                    alert('Error liking comment: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error liking comment');
            });
        }
        
        if (e.target.closest('.dislike-btn')) {
            const button = e.target.closest('.dislike-btn');
            const commentId = button.getAttribute('data-comment-id');
            
            // Send AJAX request to dislike comment
            fetch(`/comments/${commentId}/dislike`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const likesElement = document.querySelector(`.likes-count[data-comment-id="${commentId}"]`);
                    const dislikesElement = document.querySelector(`.dislikes-count[data-comment-id="${commentId}"]`);
                    if (likesElement) likesElement.textContent = data.likes;
                    if (dislikesElement) dislikesElement.textContent = data.dislikes;
                } else {
                    alert('Error disliking comment: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error disliking comment');
            });
        }
        
        // Function to handle deleting a comment
        if (e.target.closest('.delete-btn')) {
            const button = e.target.closest('.delete-btn');
            const commentId = button.getAttribute('data-comment-id');
            const commentElement = document.querySelector(`.comment-item[data-comment-id="${commentId}"]`);
            
            // Confirm deletion
            if (confirm('Are you sure you want to delete this comment?')) {
                // Send AJAX request to delete comment
                fetch(`/comments/${commentId}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Remove comment from DOM
                        if (commentElement) {
                            commentElement.remove();
                            
                            // Update comment count
                            const commentCountElement = document.querySelector('h2 .comments-count');
                            if (commentCountElement) {
                                const currentCount = parseInt(commentCountElement.textContent) || 1;
                                commentCountElement.textContent = currentCount - 1;
                            }
                            
                            // Show success message
                            alert('Comment deleted successfully');
                        }
                    } else {
                        alert('Error deleting comment: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error deleting comment');
                });
            }
        }
    });
</script>

<style>
    @keyframes fade-in-up {
        0% {
            opacity: 0;
            transform: translateY(20px);
        }
        100% {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .animate-fade-in-up {
        animation: fade-in-up 0.5s ease-out;
    }
</style>
@endsection
