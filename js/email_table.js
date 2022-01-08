let cPrev = -1;


function sortBy(c) {
    const rows = document.getElementById("table").rows.length;
    const columns = document.getElementById("table").rows[0].cells.length;
    const arrTable = [...Array(rows)].map(e => Array(columns));

    for (let ro = 0; ro < rows; ro++) {
        for (let co = 0; co < columns; co++) {

            arrTable[ro][co] = document.getElementById("table").rows[ro].cells[co].innerHTML;
        }
    }

    let th = arrTable.shift();

    if (c !== cPrev) {
        arrTable.sort(
            function (a, b) {
                if (a[c] === b[c]) {
                    return 0;
                } else {
                    return (a[c] < b[c]) ? -1 : 1;
                }
            }
        );
    } else {
        arrTable.reverse();
    }

    cPrev = c;

    arrTable.unshift(th);


    for (let ro = 0; ro < rows; ro++) {
        for (let co = 0; co < columns; co++) {
            document.getElementById("table").rows[ro].cells[co].innerHTML = arrTable[ro][co];
        }
    }
}

function searchEmail() {
    let input, filter, table, tr, td, i, txtValue;
    input = document.getElementById("search");
    filter = input.value.toUpperCase();
    table = document.getElementById("table");
    tr = table.getElementsByTagName("tr");
    for (i = 0; i < tr.length; i++) {
        td = tr[i].getElementsByTagName("td")[0];
        if (td) {
            txtValue = td.textContent || td.innerText;
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                tr[i].style.display = "";
            } else {
                tr[i].style.display = "none";
            }
        }
    }
}

function filter(domain) {
    let table, tr, td, i, txtValue;

    table = document.getElementById("table");
    tr = table.getElementsByTagName("tr");
    for (i = 0; i < tr.length; i++) {
        td = tr[i].getElementsByTagName("td")[0];
        if (td) {
            txtValue = td.textContent || td.innerText;
            // console.log(txtValue, domain);
            if (txtValue.toUpperCase().indexOf(domain.toUpperCase()) > -1) {
                tr[i].style.display = "";
            } else {
                tr[i].style.display = "none";
            }
        }
    }
    return false;
}
