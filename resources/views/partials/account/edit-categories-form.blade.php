<p class="small primary-text-color fw-bold">Delete or add your own categories.</p>
<div class="p-2 new-category d-flex align-items-center flex-wrap justify-content-center">
    <label>Name:</label>
    <input type="text" name="category-name" placeholder="New category" class="form-control w-45 mx-2 category-name">
    <div>
        <input type="radio" name="type" id="expense" value="expense" checked />
        <label for="expense">Expense</label>
        <input type="radio" name="type" id="income" value="income" class="ms-1" />
        <label for="income">Income</label>
    </div>
    <button type="button" onclick="addCategory()" class="btn btn-yellow ms-3 mt-2">Add</button>
    <p id="category-err" class="text-danger small my-1 w-100"></p>
</div>

<p class="d-flex mt-2 mb-0 align-items-center small"><span class="rounded-circle primary-bg-color square me-2"> </span> Expense | <span class="rounded-circle primary-bg-color-dark square me-2 ms-1"> </span> Income</p>
<p class="small bold my-1">Your current categories: </p>
<div id="categoryList" class="d-flex flex-wrap"></div>
