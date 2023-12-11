<form action="{{ $route }}" method="POST" class="inline">
    @csrf
    @method('DELETE')
    <button class="cursor-pointer fas fa-trash text-secondary" style="border: none; background: no-repeat;" data-bs-toggle="tooltip" data-bs-original-title="Delete item"></button>
</form>