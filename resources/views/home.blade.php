@extends('partials.layout')
@section('container')
<button class="btn" onclick="my_modal_1.showModal()">New Post</button>
<dialog id="my_modal_1" class="modal">
    <div class="modal-box">
        <h3 class="text-lg font-bold">Create Post</h3>
        <div class="">
            <form method="POST" action="/posts" enctype="multipart/form-data" class="flex flex-col gap-4">
                @csrf
                <div>
                    <div class="label">
                        Pick a file
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
                {{ $post->user->username }}
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
            <form action="/posts/{{ $post->id }}/like" method="POST">
                @csrf
                <div class="flex items-center space-x-2 p-4 ">
                    <button type="submit" class="flex items-center space-x-2">
                        @if($post->isLikedBy(auth()->user()))
                        <!-- Cek apakah posting telah dilike -->
                        <i class="fa-solid fa-heart text-2xl text-red-400"></i> <!-- Ikon penuh jika dilike -->
                        @else
                        <i class="fa-regular fa-heart text-2xl"></i> <!-- Ikon kosong jika belum dilike -->
                        @endif
                        <span class="text-lg">Like</span>
                    </button>
                </div>
            </form>
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
                        class="input w-full input-bordered border-0 border-b-2 focus:border-purple-700 border-purple-800 focus:outline-none rounded-none"
                        placeholder="Type comment..." name="comment" />
                    <button type="submit">
                        <x-ionicon-send class="mr-2 size-6" />
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
                        class="input w-full input-bordered border-0 border-b-2 focus:border-purple-700 border-purple-800 focus:outline-none rounded-none"
                        placeholder="Type comment..." name="comment" />
                    <button type="submit">
                        <x-ionicon-send class="mr-2 size-6" />
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

@endsection