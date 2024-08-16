@extends('layout')

@section('title', 'Course Units')

@section('content')
<div class="container mt-5">
    <h2>{{ $course->title }}</h2>
    <p>{{ $course->description }}</p>

    <h3>Units</h3>
    @if($course->units->isEmpty())
        <p>No units available for this course.</p>
    @else
        <ul class="list-group">
            @foreach($course->units as $unit)
                <li class="list-group-item mb-4">
                    <h4>{{ $unit->unit_name }}</h4>
                    
                    <!-- Display lessons within each unit -->
                    @if($unit->lessons->isEmpty())
                        <p>No lessons available in this unit.</p>
                    @else
                        <ul class="list-unstyled" id="lessons-list-{{ $unit->id }}">
                            @foreach($unit->lessons->take(2) as $lesson)
                                <li class="mb-2">
                                    <h5 class="mb-1">
                                        <a href="#lesson-{{ $lesson->id }}" data-toggle="collapse" aria-expanded="false" class="d-block">
                                            {{ $lesson->title }}
                                        </a>
                                    </h5>
                                    
                                    <div id="lesson-{{ $lesson->id }}" class="collapse">
                                        <p>{{ $lesson->description }}</p>
                                        <a href="{{ route('lesson.watch', $lesson->id) }}" class="btn btn-primary">Watch</a>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                        @if($unit->lessons->count() > 2)
                            <button class="btn btn-secondary" onclick="showAllLessons({{ $unit->id }})">View All Lessons</button>
                            <ul id="hidden-lessons-list-{{ $unit->id }}" style="display:none;">
                                @foreach($unit->lessons->skip(2) as $lesson)
                                    <li class="mb-2">
                                        <h5 class="mb-1">
                                            <a href="#lesson-{{ $lesson->id }}" data-toggle="collapse" aria-expanded="false" class="d-block">
                                                {{ $lesson->title }}
                                            </a>
                                        </h5>
                                        
                                        <div id="lesson-{{ $lesson->id }}" class="collapse">
                                            <p>{{ $lesson->description }}</p>
                                            <a href="{{ route('lesson.watch', $lesson->id) }}" class="btn btn-primary">Watch</a>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    @endif
                </li>
            @endforeach
        </ul>
    @endif

    <!-- Display quiz after units -->
    @if($course->units->pluck('questions')->flatten()->isNotEmpty())
        <h3 class="mt-5">Quiz</h3>
        <form action="{{ route('unit.submit', $course->id) }}" method="POST">
            @csrf
            @foreach($course->units as $unit)
                @if($unit->questions->isNotEmpty())
                    <h4>{{ $unit->unit_name }} Quiz</h4>
                    @foreach($unit->questions as $question)
                        <div class="mb-4">
                            <h5>{{ $question->question }}</h5>
                            @foreach($question->answers as $answer)
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="question_{{ $question->id }}" value="{{ $answer->id }}" id="answer_{{ $answer->id }}">
                                    <label class="form-check-label" for="answer_{{ $answer->id }}">
                                        {{ $answer->answer }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                @endif
            @endforeach
            
            <button type="submit" class="btn btn-success">Submit Answers</button>
        </form>
    @else
        <p>No quiz available for this course.</p>
    @endif
</div>

<script>
    function showAllLessons(unitId) {
        var hiddenLessonsList = document.getElementById('hidden-lessons-list-' + unitId);
        hiddenLessonsList.style.display = 'block';
        event.target.style.display = 'none';
    }
</script>
@endsection
