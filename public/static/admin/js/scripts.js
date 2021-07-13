//search form
let inputSearch = document.querySelector('#search');

function searchTask() {
    let filter = inputSearch.value.toUpperCase();
    let item = document.querySelectorAll('tbody tr');
    for (let i = 0; i < item.length; i++) {
        let results = item[i];
        if (results) {
            let txtValue = results.textContent || results.innerText;
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                item[i].style.display = 'table-row';
            } else {
                item[i].style.display = 'none';
            }
        }
    }
}


if (inputSearch) {
    inputSearch.addEventListener('input', searchTask);
}