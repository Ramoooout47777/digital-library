{{-- In resources/views/books/show.blade.php --}}

@auth
    <div class="flex items-center gap-2">
        <form action="{{ route('favorites.toggle') }}" method="POST" class="inline">
            @csrf
            <input type="hidden" name="book_id" value="{{ $book->id }}">
            <button type="submit" 
                    class="neu-button w-12 h-12 rounded-xl flex items-center justify-center transition hover:scale-105
                           @if(auth()->user()->isFavorited($book->id)) 
                               text-red-400 border-red-400/30
                           @else
                               text-slate-400 dark:text-slate-500 light:text-slate-500
                           @endif">
                <i class="fas fa-heart text-xl"></i>
            </button>
        </form>
    </div>
@endauth