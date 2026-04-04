<div class="p-2 new-category text-center">
    <input type="hidden" value="" id="id" name="id">
    <div class="d-flex align-items-center justify-content-center">
        <label for="amount">Amount:</label>
        <input type="number" id="amount" step="0.01" min="0" name="amount" placeholder="Amount" class="form-control w-45 mx-2 category-name">
    </div>
    <p id="amount-err" class="text-danger small my-1 w-100"></p>

    <div class="d-flex align-items-center justify-content-center">
        <label for="category">Category:</label>
        <select name="category" id="category" class="form-control w-45 m-2">
            <option value="">Select category</option>
            @foreach($categories as $category)
                @if(!$category->is_archived)
                <option value="{{ $category->id }}">
                    {{ $category->name }} ({{$category->type}})
                </option>
                @endif
            @endforeach
        </select>
    </div>
    <p id="category-err" class="text-danger small my-1 w-100"></p>

    <div class="d-flex align-items-center justify-content-center">
        <label for="date">Date:</label>
        <input type="date" name="date" id="date" class="mx-2 form-control w-45" />
    </div>
    <p id="date-err" class="text-danger small my-1 w-100"></p>

    <button type="button" onclick="addEditTransaction({{$edit}}, {{$refresh}})" class="btn btn-yellow ms-3 mt-2">
        @if($edit === "true")
        Edit
        @else
        Add
        @endif
    </button>
</div>
