setTimeout(() => {
    const alert = document.getElementById('flash-message');
    if (alert) {
        const bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
        bsAlert.close();
    }
}, 2000);


//showing the modal
const modal = document.getElementById('globalModal');
const modalContent = document.getElementById('modalContent');
const modalTitle = document.getElementById('modalTitle');

modal.addEventListener('show.bs.modal', async function (event) {
    const button = event.relatedTarget;
    const type = button.dataset.type;

    const template = document.getElementById('template-' + type);
    modalContent.innerHTML = template.innerHTML;
    modalTitle.innerHTML = button.dataset.title;

    if(type === "category") await loadCategories();
    if(type === "edit-transaction") fillTransactionForm(button);
});

// custom categories
async function addCategory() {
    const nameValue = document.querySelector('.modal-dialog .category-name').value;
    const name =  nameValue.charAt(0).toUpperCase() + nameValue.slice(1).toLowerCase();
    const type = document.querySelector('input[name="type"]:checked')?.value || "expense";

    try {
        const res = await fetch('/categories', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: JSON.stringify({name, type})
        });

        const data = await res.json();

        if(!res.ok) throw data;

        document.querySelector('.modal-dialog .category-name').value = "";
        renderCategory(data);
        document.querySelector('.modal-dialog #category-err').innerHTML = "";
    }
    catch(err) {
        document.querySelector('.modal-dialog #category-err').innerHTML = err.errors.name[0];
    }
}

async function deleteCategory(id) {
    try {
        await fetch(`/categories/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });

        document.getElementById(`cat-${id}`).remove();
    }
    catch (err) {
        console.log(err)
    }
}

function renderCategory(category) {
    const list = document.querySelector('.modal-dialog #categoryList');

    list.innerHTML += `
        <div id="cat-${category.id}" class="color-${category.type} px-2 py-1 rounded m-2">
            ${category.name}
            <button onclick="deleteCategory(${category.id})" class="border-0 bg-transparent ms-1 text-white">X</button>
        </div>
    `;
}

async function loadCategories() {
    const res = await fetch('/categories');
    const data = await res.json();

    const list = document.querySelector('.modal-dialog #categoryList');
    list.innerHTML = '';

    data.forEach(renderCategory);
}


//transactions

async function addEditTransaction(isEdit, isRefresh = false) {
    const amount = Number(document.querySelector('.modal-dialog #amount').value).toFixed(2);
    const category = document.querySelector('.modal-dialog #category')?.value;
    const date = document.querySelector('.modal-dialog #date').value;
    const id = document.querySelector('.modal-dialog #id').value;

    const methodName = isEdit ? "PUT" : "POST";
    const content = isEdit ? {amount, category, date, id} : {amount, category, date };
    const route = isEdit ? `/transactions/${id}` : '/transactions';

    try {
        const res = await fetch(route, {
            method: methodName,
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: JSON.stringify(content)
        });

        const data = await res.json();

        if(!res.ok) throw data;

        const modal = bootstrap.Modal.getInstance(document.getElementById('globalModal'));
        if (modal) {
            modal.hide();
        }

        if(isRefresh) {
            window.location.reload();
            return;
        }
        const current = document.querySelector('#currentReport');
        const currentReport = {start: current.dataset.start, end: current.dataset.end};

        refreshCurrentReport(currentReport);
        refreshReportsList(currentReport.start);

        if(data.message) {
            showFlashMessage(data.message, false)
        }
    }
    catch(err) {
        if(!err.errors) {
            const modal = bootstrap.Modal.getInstance(document.getElementById('globalModal'));
            if (modal) {
                modal.hide();
            }
            showFlashMessage(err.message, true);
        }
        else {
            const errors = Object.entries(err.errors);
            document.querySelectorAll('[id$="err"]').forEach((err) => err.innerHTML = "");

            errors.forEach(([key, value]) => {
                document.querySelector('.modal-dialog #' + key + '-err').innerHTML = value;
            })
        }
    }
}

async function deleteTransaction(id) {
    try {
        await fetch(`/transactions/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        }).then(() => {
            document.getElementById(`transaction-${id}`).remove();
        }).then(() => {
            if(!document.querySelector('#transaction-container div:first-child')) {
                refreshReportsList();
            }
        });

    }
    catch (err) {
        console.log(err)
    }
}

function fillTransactionForm(button) {
    const id = button.dataset.id;
    const amount = button.dataset.amount;
    const date = button.dataset.date;
    const category = button.dataset.category;

    modal.querySelector('.modal-dialog #id').value = id;
    modal.querySelector('.modal-dialog #amount').value = amount;
    modal.querySelector('.modal-dialog #date').value = date;
    modal.querySelector('.modal-dialog #category').value = category;
}

function showFlashMessage(message, isError) {
    const divId = isError ? 'js-flash-message-error' : 'js-flash-message';
    const divElement = document.getElementById(divId);
    document.getElementById(divId).innerText = message;
    divElement.style.display = 'block';

    setTimeout(() => { divElement.style.display = 'none'; }, 2000);
}

function loadReport(event, start) {
    event.preventDefault();

    const url = new URL(window.location.href);
    url.searchParams.set('report', start);
    window.history.pushState({}, '', url);

    if(url.searchParams.get('view') === 'analytics') {
        refreshReportSummary(start);
        document.querySelectorAll('.report-btn').forEach(btn => {
            btn.classList.remove('active-report');
        });
        event.target.classList.add('active-report');
    }

    fetch(url, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
        .then(res => res.json())
        .then(html => {
            document.getElementById('main-content').innerHTML = html.main;
            if(url.searchParams.get('view') === 'analytics') {
                initExpenseChart();
            }
        });
    document.querySelectorAll('.report-btn').forEach(btn => {
        btn.classList.remove('active-report');
    });
    event.target.classList.add('active-report');

    return false;
}

function refreshCurrentReport(currentReport) {
    if (!currentReport.start) return;

    const url = new URL(window.location.href);
    url.searchParams.set('report', currentReport.start);
    window.history.pushState({}, '', url);

    fetch(url, {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
        .then(res => res.json())
        .then(html => {
            document.getElementById('main-content').innerHTML = html.main;
        });
}

function refreshReportsList(reportStart = "") {
    const selector = reportStart === "" ? '.report-btn:first-child' : `.report-btn[data-start="${reportStart}"]`
    fetch('/reports/list', {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(res => res.text())
    .then(html => {
        document.getElementById('reportsList').innerHTML = html;
        const active = document.querySelector(selector);
        active.classList.add('active-report');
        if(reportStart === "") {
            document.querySelector('#reportsList a:first-child').click();
        }
    });
}

function refreshReportSummary(currentReport) {
    if (!currentReport) return;

    fetch(`/reports/summary?report=${currentReport}`, {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
        .then(res => res.text())
        .then(html => {
            document.getElementById('side-content').innerHTML = html;
        });
}

let expenseChart;
function initExpenseChart() {
    document.getElementById('no-expenses').innerHTML="";
    const el = document.getElementById('analytics-data');

    if (!el) return;
    const expenseData = JSON.parse(el.dataset.expense);

    if (!expenseData.labels.length) {
        document.getElementById('no-expenses').innerHTML="No expenses here.";
        return;
    }

    const ctx = document.getElementById('expenseCategoryChart');

    if (expenseChart) {
        expenseChart.destroy();
    }

    expenseChart = new Chart(ctx, {
        type: 'bar',
        data: expenseData,
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    grace: '5%'
                }
            }
        }
    });
}
