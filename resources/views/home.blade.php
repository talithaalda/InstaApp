@extends('partials.layout')
@section('container')
<div class="navbar bg-base-100">
    <div class="flex-1">
        <a class="btn btn-ghost text-xl">InstaApp</a>
    </div>
    <div class="flex-none">
        <ul class="menu menu-horizontal px-1">
            <li>
                <details>
                    <summary><i class="fa-regular fa-user"></i>Account</summary>
                    <ul class="bg-base-100 rounded-t-none p-2">
                        <li>
                            <form action="{{ route('logout') }}" method="POST"
                                onsubmit="return confirm('Are you sure you want to logout?');">
                                @csrf
                                <button type="submit" class="text-red-500 w-full text-left">Sign Out</button>
                            </form>
                        </li>
                    </ul>
                </details>
            </li>
        </ul>
    </div>
</div>
<button
    class="btn fixed bottom-10 right-10 p-6 rounded-full z-40 flex items-center bg-blue-600 hover:bg-blue-700 hover:scale-105 text-white h-16"
    onclick="my_modal_1.showModal()">New
    Post</button>
<dialog id="my_modal_1" class="modal">
    <div class="modal-box">
        <div class="text-lg font-bold mb-2 justify-center flex">Create Post</div>
        <div class="">
            <form method="POST" action="/posts" enctype="multipart/form-data" class="flex flex-col gap-4">
                @csrf
                <div>
                    <div class="label">
                        Upload Image
                    </div>
                    <input type="file" class="file-input file-input-bordered w-full" name="image" />
                </div>
                @error('image')
                <p class="text-red-500 text-sm">{{ $message }}</p>
                @enderror

                <div>
                    <div class="label">
                        <span class="label-text">Caption</span>
                    </div>
                    <textarea class="textarea textarea-bordered h-24 w-full" name="caption"
                        placeholder="Input your caption"></textarea>
                </div>
                @error('caption')
                <p class="text-red-500 text-sm">{{ $message }}</p>
                @enderror

                <button type="submit" class="btn btn-primary w-full">Post</button>
                <button type="button" class="btn" onclick="my_modal_1.close()">Close</button>
            </form>
        </div>
    </div>
</dialog>
<div class="flex justify-center flex-col items-center gap-5">
    @if(session('success'))
    <div class="alert alert-success max-w-xl">
        {{ session('success') }}
    </div>
    @endif
    @if(session('danger'))
    <div class="alert alert-error max-w-xl">
        {{ session('danger') }}
    </div>
    @endif
    @foreach ($posts as $post)
    <div class="card bg-base-100 max-w-xl shadow-xl ">
        <div class="flex flex-row justify-between">
            <h2 class="card-title">
                {{ '@' . $post->user->username }}
            </h2>
            @if (Auth::user()->id == $post->user->id)
            <div class="dropdown dropdown-end ">
                <label tabindex="0" class="btn btn-ghost btn-circle ">
                    <i class="fa-solid fa-ellipsis-vertical text-xl"></i>
                </label>
                <ul tabindex="0" class="dropdown-content menu p-2 shadow bg-base-100 rounded-box w-32">
                    <li>
                        <form action="{{ route('posts.destroy', $post->id) }}" method="POST"
                            onsubmit="return confirm('Are you sure you want to delete this post?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 w-full block">Delete Post</button>
                        </form>
                    </li>
                </ul>
            </div>
            @endif
        </div>
        <figure>
            <img src="{{ asset('storage/' . $post->image_path) }}" alt="Post Image" />
        </figure>
        <div class="flex flex-row">
            <div class="flex items-center space-x-2 p-4">
                <button id="like-button-{{ $post->id }}" data-post-id="{{ $post->id }}"
                    class="flex items-center space-x-2">
                    @if($post->isLikedBy(auth()->user()))
                    <i class="fa-solid fa-heart text-2xl text-red-400"></i>
                    @else
                    <i class="fa-regular fa-heart text-2xl"></i>
                    @endif
                    <span id="like-count-{{ $post->id }}">{{ $post->likes()->count() }}</span>
                    <span class="text-lg">Like</span>
                </button>
            </div>
            <div class="flex items-center space-x-2 p-4 ">
                <button onclick="document.getElementById('comment_modal_{{ $post->id }}').showModal()"><i
                        class="fa-regular fa-comment text-2xl"></i>
                    <span class="text-lg">Comment</span></button>
            </div>
        </div>
        <div class="card-body pt-0">
            <h2 class="card-title">
                {{ $post->user->username }}
            </h2>
            <p>{{ $post->caption }}</p>
            <div class="card-actions justify-end">
                <div class="badge badge-outline">{{ $post->created_at->diffForHumans() }}</div>
            </div>
            <form method="POST" action="/posts/{{ $post->id }}/comments">
                @csrf
                <div class="flex flex-row gap-4 justify-center items-center">
                    <input type="text"
                        class="input w-full input-bordered border-0 border-b-2 focus:border-blue-700 border-blue-800 focus:outline-none rounded-none"
                        placeholder="Type comment..." name="comment" />
                    <button type="submit">
                        Send
                    </button>
                </div>
            </form>
        </div>
    </div>
    <dialog id="comment_modal_{{ $post->id }}" class="modal">
        <div class="modal-box">
            <h3 class="text-lg font-bold mb-4 justify-center flex">Comments</h3>
            <div class="flex flex-col gap-2">
                @foreach ($post->comments as $comment)
                <div class="ps-4 mb-2 flex flex-row gap-2 items-center">
                    <span class="font-semibold text-sm">{{ $comment->user->username }}</span>
                    <p class="text-sm text-gray-400">{{ $comment->comment }}</p>
                    @if (Auth::user()->id == $comment->user->id)
                    <div class="dropdown dropdown-bottom">
                        <button type="button" class="btn btn-ghost btn-circle"
                            onclick="this.nextElementSibling.classList.toggle('hidden')">
                            <i class="fa-solid fa-ellipsis text-xl"></i>
                        </button>
                        <ul class="dropdown-content menu p-2 shadow bg-base-100 rounded-box hidden z-30 w-40">
                            <li>
                                <form action="{{ route('comments.destroy', $comment->id) }}" method="POST"
                                    onsubmit="return confirm('Are you sure you want to delete this comment?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 w-full block">Delete Comment</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                    @endif
                </div>
                @endforeach
            </div>
            <form method="POST" action="/posts/{{ $post->id }}/comments">
                @csrf
                <div class="flex flex-row gap-4 justify-center items-center">
                    <input type="text"
                        class="input w-full input-bordered border-0 border-b-2 focus:border-blue-700 border-blue-800 focus:outline-none rounded-none"
                        placeholder="Type comment..." name="comment" />
                    <button type="submit">
                        Send
                    </button>
                </div>
            </form>
        </div>
        <form method="dialog" class="modal-backdrop">
            <button>close</button>
        </form>
    </dialog>
    @endforeach

</div>
<script>
    $(document).ready(function() {
       $('button[id^="like-button-"]').click(function(e) {
            e.preventDefault();

            var postId = $(this).data('post-id');
            var $this = $(this);

            $.ajax({
                url: '/posts/' + postId + '/like',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                },
                success: function(response) {
                    var likeButton = $('#like-button-' + postId);
                    var likeCount = $('#like-count-' + postId);

                    if (response.liked) {
                        likeButton.find('i').removeClass('fa-regular fa-heart').addClass('fa-solid fa-heart text-red-400');
                    } else {
                        likeButton.find('i').removeClass('fa-solid fa-heart text-red-400').addClass('fa-regular fa-heart');
                    }
                        likeCount.text(response.likeCount);
                        console.log('Like toggled successfully');
                    },
                error: function(xhr) {
                    console.error('Error:', xhr.responseText);
                }
            });
        });
    });
</script>
@endsection
