@extends('mail/layout')

@section('title', $post->title)

@section('content')
<p>{{ $customMessage['message']}}</p>
<br>
<p><b>Title</b>: {{ $post->title }}</p>
<p><b>Topic/Article</b>: {{ $post->subject }}</p>
<p><b>Content</b>: {!! $post->content !!}</p>
@endsection