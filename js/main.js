const addForm = document.getElementById("add-user-form");
const updateForm = document.getElementById("edit-user-form");
const showAlert = document.getElementById("showAlert");
const addModal = new bootstrap.Modal(document.getElementById("addNewUserModal"));
const editModal = new bootstrap.Modal(document.getElementById("editUserModal"));
const tbody = document.querySelector("tbody");
let selectFather = $( 'select.father' ).select2({theme: "bootstrap-5",closeOnSelect: false} );
let selectMother = $( 'select.mother' ).select2({theme: 'bootstrap-5'});

addForm.addEventListener("submit", async (e) => {
    e.preventDefault();
    e.stopPropagation();
    const formData = new FormData(addForm);
    formData.append("add", 1);

    if (addForm.checkValidity() === false) {
        e.preventDefault();
        e.stopPropagation();
        addForm.classList.add("was-validated");
        return false;
    } else {
        document.getElementById("add-user-btn").value = "Минуточку...";

        const data = await fetch("action.php", {
            method: "POST",
            body: formData,
        });
        const response = await data.text();

        showAlert.innerHTML = response;
        document.getElementById("add-user-btn").value = "Добавить";
        addForm.reset();
        addForm.classList.remove("was-validated");
        addModal.hide();

        fetchAllUsers();
    }
});



const fetchAllUsers = async (sorting, dir) => {
    const data = await fetch("action.php?read=1&sorting="+(sorting !== undefined ? sorting : 'first_name')+'&dir='+(dir !== undefined ? dir : 'asc'), {
        method: "GET",
    });
    const response = await data.text();
    tbody.innerHTML = response;
};
fetchAllUsers();



tbody.addEventListener("click", (e) => {
    if (e.target && e.target.matches("a.editLink")) {
        e.preventDefault();
        e.stopPropagation();
        let id = e.target.getAttribute("id");

        editUser(id);
    }
});



const editUser = async (id) => {
    const data = await fetch(`action.php?edit=1&id=${id}`, {
        method: "GET",
    });
    const response = await data.json();
    document.getElementById("id").value = response.id;
    document.getElementById("fname").value = response.first_name;
    document.getElementById("birthDate").value = response.birthDate;
    // document.getElementById("father").value = response.father;

    $('#gender'+response.gender).click()
    $('#mother').val(value = response.mother);
    $('#mother').trigger('change');
};



updateForm.addEventListener("submit", async (e) => {
    e.preventDefault();
    e.stopPropagation();
    const formData = new FormData(updateForm);
    formData.append("update", 1);

    if (updateForm.checkValidity() === false) {
        e.preventDefault();
        e.stopPropagation();
        updateForm.classList.add("was-validated");
        return false;
    } else {
        document.getElementById("edit-user-btn").value = "Минуточку...";
        const data = await fetch("action.php", {
            method: "POST",
            body: formData,
        });
        const response = await data.text();

        showAlert.innerHTML = response;
        document.getElementById("edit-user-btn").value = "Сохранить";
        updateForm.reset();
        updateForm.classList.remove("was-validated");
        editModal.hide();

        fetchAllUsers();
    }
});



tbody.addEventListener("click", (e) => {
    if (e.target && e.target.matches("a.deleteLink")) {
        e.preventDefault();
        e.stopPropagation();
        if (confirm('Удалить?')) {
            let id = e.target.getAttribute("id");
            deleteUser(id);
        }
    }
});



const deleteUser = async (id) => {
    const data = await fetch(`action.php?delete=1&id=${id}`, {
        method: "GET",
    });
    const response = await data.text();
    showAlert.innerHTML = response;

    fetchAllUsers();
};



const fetchParents = async (id) => {
    const data = await fetch(`action.php?fetchParents=1&exclude=${id}`, {
        method: "GET",
    });
    let response = await data.json();

    // разнесем данные по селектам прямо здесь
    // можно (с натяжкой) сказать что это нарушает принцип SRP, но зато меньше кода писать :)
    selectFather.text('');
    selectMother.text('');

    $.each(response.fathers, function() {
        let newOption = new Option(this.first_name, this.id, false, false);
        selectFather.append(newOption);

        if (response.selected.father !== undefined) {
            selectFather.val(JSON.parse(response.selected.father));
            selectFather.trigger('change');
        }
    });

    $.each(response.mothers, function() {
        let newOption = new Option(this.first_name, this.id, false, false);
        selectMother.append(newOption);

        selectMother.val(response.selected.mother);
        selectMother.trigger('change');
    });
};


// вспомогательный метод
$.fn.toggleAttr = function(attr, value1, value2) {
    return this.each(function() {
        var self = $(this);
        if (self.attr(attr) == value1)
            self.attr(attr, value2);
        else
            self.attr(attr, value1);
    });
};



$(document).ready(function () {
    // при вызове модала заполним возможных родителей
    $('.modal').on('shown.bs.modal', function (event) {
        fetchParents($(event.target).find('form#edit-user-form>#id').val() ?? 0);
    })

    // запретим установку будущей даты в модалах
    $('input[type=date]').attr('max', new Date().toJSON().slice(0, 10))+' 23:59:59';


    // сортировка
    $('th').on('click', function (event) {
        if (!event.target.dataset.sortable) return;
        if ($(this).hasClass('sorted')) {
            $(this).toggleAttr("data-dir", 'asc', 'desc');
        } else {
            $('th').removeClass('sorted');
            $(this).addClass("sorted");
        }
        fetchAllUsers(event.target.dataset.sortable, event.target.dataset.dir)
    })
})

