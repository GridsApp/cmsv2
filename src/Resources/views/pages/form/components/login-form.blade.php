<form class="card-body flex flex-col gap-5 p-10"  wire:submit.prevent="handleLogin">
    
    @csrf

    <div class="text-center mb-2.5">
        <h3 class="text-lg font-medium text-gray-900 leading-none mt-2.5 mb-2.5">
            Sign in
        </h3>

    </div>

    {!! field('email') !!}  
    {!! field('password') !!}
  
    <button class="btn btn-primary flex justify-center grow">
        Sign In
    </button>
</form>