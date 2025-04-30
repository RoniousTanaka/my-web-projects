<h2>Interns</h2>
<a href="{{ route('interns.create') }}">+ Add New</a> | 
<a href="{{ route('interns.match') }}">🔍 Match Interns to Jobs</a>
<ul>
@foreach($interns as $intern)
    <li>
        {{ $intern->name }} - {{ $intern->skills }} 
        [<a href="{{ route('interns.edit', $intern) }}">edit</a>]
        <form method="POST" action="{{ route('interns.destroy', $intern) }}" style="display:inline;">
            @csrf @method('DELETE')
            <button>delete</button>
        </form>
    </li>
@endforeach
</ul>



<--match Algorithm-->

<h2> Matching Interns to Jobs</h2>

@foreach($interns as $intern)
    <b>{{ $intern->name }}</b> ({{ $intern->availability }})<br>
    @php
        $iSkills = array_map('trim', explode(',', strtolower($intern->skills ?? '')));
    @endphp
    @php $matched = false; @endphp

    @foreach($jobs as $job)
        @php
            $jSkills = array_map('trim', explode(',', strtolower($job->required_skills ?? '')));
            $skillMatch = count(array_intersect($iSkills, $jSkills)) > 0;
        @endphp

        @if($skillMatch && $intern->availability == $job->availability_required)
            - Matched with <b>{{ $job->title }}</b><br>
            @php $matched = true; @endphp
        @endif
    @endforeach

    @if(!$matched)
        - No match found.<br>
    @endif
    <hr>
@endforeach
?><?php ?>

//cv blade.php

<h2> New CV </h2>
<form method="POST" action="{{ route('interns.store') }}">
    @csrf
    Name: <input name="name"><br>
    Email: <input name="email"><br>
    Phone: <input name="phone"><br>
    Education: <textarea name="education"></textarea><br>
    Skills (comma-separated): <input name="skills"><br>
    Experience: <textarea name="experience"></textarea><br>
    Availability:
    <select name="availability">
        <option>Full-time</option>
        <option>Part-time</option>
    </select><br>
    <button>Submit</button>
</form>
