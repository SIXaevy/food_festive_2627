const btnDelete = document.getElementsByClassName("btn-delete");

for (let i=0; i<btnDelete.length; i++) {
    btnDelete[i].addEventListener("click", function(e) {
        e.preventDefault();
        var result = confirm("Do you want to delete this dish?");
        if(result) {
            var btn = this;
            var id = this.getAttribute("data-id");
            fetch(`/delete?id=${id}`, {
                method:'DELETE',
                headers: {
                    "Content-Type": "application/json",
                },
            }).then(function(response) {
                return response.json(); 
            }).then(function(data) {
                if(data.deleted)
                    btn.closest('tr').remove();
            }).catch(function(e) { 
                console.log(e)
            })        
        }
        checkDishItems();
    });
}

function checkDishItems() {
    let dishItems = document.getElementsByClassName("todo-item");

    if(dishItems.length == 0) {
        var dishItemEmptyRow = document.createElement("tr");

        var dishItemEmpty = document.createElement("td");
        dishItemEmpty.setAttribute("colspan", "6");

        // Create the placeholder message
        var dishItemEmptyText = document.createTextNode(
            "Dishes will be placed here..."
        );

        dishItemEmpty.appendChild(dishItemEmptyText);
        dishItemEmptyRow.appendChild(dishItemEmpty);

        var dishTableBody = document.querySelector("table tbody");
        dishTableBody.appendChild(dishItemEmptyRow);
    }
}
