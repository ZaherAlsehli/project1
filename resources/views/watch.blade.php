@extends('layout')

@section('title', 'Watch Lesson')

@section('content')
<div class="container mt-5">
    <h2>{{ $lesson->title }}</h2>
    <p>{{ $lesson->description }}</p>

    <div class="embed-responsive embed-responsive-16by9">
        <iframe src="https://player.vimeo.com/video/{{ substr($lesson->vimeo_uri, strrpos($lesson->vimeo_uri, '/') + 1) }}?title=0&byline=0&portrait=0&badge=0&autopause=0&player_id=0&app_id=58479" width="1280" height="720" frameborder="0" allow="autoplay; fullscreen; picture-in-picture; clipboard-write" title="{{ $lesson->title }}"></iframe>
    </div>

    <div class="mt-3">
        <form action="{{ route('favorites.store', $lesson->id) }}" method="POST" id="likeForm">
            @csrf
            <button type="button" id="likeButton" name="favorite" value="1" class="btn btn-success">
                <img src="{{ asset('frontend/assets/like.png') }}" alt="Like" style="width: 20px; height: 20px;">
                LIKE
            </button>
        </form>
        <form action="{{ route('favorites.store', $lesson->id) }}" method="POST" id="dislikeForm" style="display:inline;">
            @csrf
            <button type="button" id="dislikeButton" name="favorite" value="0" class="btn btn-danger">
                <img src="{{ asset('frontend/assets/dislike.png') }}" alt="Dislike" style="width: 20px; height: 20px;">
                DISLIKE
            </button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#likeButton').click(function() {
                var form = $('#likeForm');
                sendAjaxRequest(form);
            });

            $('#dislikeButton').click(function() {
                var form = $('#dislikeForm');
                sendAjaxRequest(form);
            });

            function sendAjaxRequest(form) {
                $.ajax({
                    url: form.attr('action'),
                    method: 'POST',
                    data: form.serialize(),
                    success: function(response) {
                        alert('Your preference has been saved.');
                    },
                    error: function(xhr) {
                        if(xhr.status === 401) {
                            alert('You need to log in first.');
                            window.location.href = "{{ route('login') }}"; // إعادة توجيه المستخدم إلى صفحة تسجيل الدخول
                        } else {
                            alert('An error occurred while processing your request.');
                        }
                    }
                });
            }
        });
    </script>

    <h3 class="mt-5">Comments</h3>
    <div id="comments-section">
        @foreach($lesson->comments as $comment)
            <div class="mb-4">
                <strong>{{ $comment->user->name }}</strong>:
                <p>{{ $comment->comment }}</p>
            </div>
        @endforeach
    </div>

    <form action="{{ route('lesson.comment.store', $lesson->id) }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="comment">Add Comment</label>
            <textarea name="comment" id="comment" rows="3" class="form-control"></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>
@endsection
