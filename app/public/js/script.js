var tasks = [];
var taskIdEdit = null;
var popUpTaskIsOpen = false;

function getTasks() {
    $.ajax({
        url: "api/tasks",
        method: "GET",
        dataType: "json",
        async: true,
        success: function (data) {
            tasks = data;
            renderTaskList(data);
        },
        error: function (xhr, status, error) {
            alert("Ocorreu um erro na requisição: " + error);
        },
    });
}

function renderTaskList(tasks) {
    var htmlItens = tasks.map(function (item) {
        return `
  <div class="task-container">
  <div class="task-info">
      <span>${item.title}</span>
      <span>${item.description}</span>
  </div>
  <div class="task-actions">
      <span name="btn-edit-task" data-task-id="${item.id}"><svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24"
              style="fill: rgba(0, 0, 0, 1);">
              <path
                  d="m7 17.013 4.413-.015 9.632-9.54c.378-.378.586-.88.586-1.414s-.208-1.036-.586-1.414l-1.586-1.586c-.756-.756-2.075-.752-2.825-.003L7 12.583v4.43zM18.045 4.458l1.589 1.583-1.597 1.582-1.586-1.585 1.594-1.58zM9 13.417l6.03-5.973 1.586 1.586-6.029 5.971L9 15.006v-1.589z">
              </path>
              <path
                  d="M5 21h14c1.103 0 2-.897 2-2v-8.668l-2 2V19H8.158c-.026 0-.053.01-.079.01-.033 0-.066-.009-.1-.01H5V5h6.847l2-2H5c-1.103 0-2 .897-2 2v14c0 1.103.897 2 2 2z">
              </path>
          </svg></span>
      <span name="btn-delete-task" data-task-id="${item.id}"><svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24"
              style="fill: rgba(0, 0, 0, 1)">
              <path
                  d="M5 20a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V8h2V6h-4V4a2 2 0 0 0-2-2H9a2 2 0 0 0-2 2v2H3v2h2zM9 4h6v2H9zM8 8h9v12H7V8z">
              </path>
              <path d="M9 10h2v8H9zm4 0h2v8h-2z"></path>
          </svg></span>
  </div>
</div>
  `;
    });

    var htmlCompleto = htmlItens.join("");
    var taskListContent = document.querySelector(".task-list-content");
    taskListContent.innerHTML = htmlCompleto;
}

function createTask() {
    $("body").on("click", "#btn-add-task", function () {
        if ($(".form-container").length > 0) {
            if (taskIdEdit) {
                taskIdEdit = null;
            }

            $(".form-container h2").text("Create New Task");
            $('.form-container form input[name="title"]').val("");
            $('.form-container form input[name="description"]').val("");
            return;
        }

        if (popUpTaskIsOpen) {
            return;
        }

        var html = `
            <section class="form-container">
                <h2>Create New Task</h2>
                <form>
                    <input type="text" name="title" placeholder="Title">
                    <input type="text" name="description" placeholder="Description">
                    <div>
                        <button type="submit">Salva</button>
                    </div>
                </form>
            </section>
        `;

        $("body").append(html);

        popUpTaskIsOpen = true;
    });
}

function editTask() {
    $("body").on("click", 'span[name="btn-edit-task"]', function () {
        var taskId = $(this).data("task-id");

        taskIdEdit = taskId;

        const currentTask = tasks.find((task) => task.id === taskId);

        if ($(".form-container").length > 0) {
            $(".form-container h2").text("Edit Task");
            $('.form-container form input[name="title"]').val(`${currentTask.title}`);
            $('.form-container form input[name="description"]').val(`${currentTask.description}`);
            return;
        }
        if (popUpTaskIsOpen) {
            return;
        }

        var html = `
            <section class="form-container">
                <h2>Edit Task</h2>
                <form>
                    <input type="text" name="title" placeholder="Title" value=${currentTask.title} >
                    <input type="text" name="description" placeholder="Description" value=${currentTask.description}>
                    <div>
                        <button type="submit">Salva</button>
                    </div>
                </form>
            </section>
        `;

        $("body").append(html);

        popUpTaskIsOpen = true;
    });
}

function saveTask() {
    let method = "POST";

    $("body").on("submit", ".form-container form", function (event) {
        event.preventDefault();

        var formValues = $(this).serializeArray();

        const dataTask = {};

        $.each(formValues, function (_, field) {
            Object.assign(dataTask, { [field.name]: field.value });
        });

        if (taskIdEdit) {
            method = "PATCH";

            if (dataTask["title"] === "") {
                delete dataTask.title;
            }

            if (dataTask["description"] === "") {
                delete dataTask.description;
            }
        }

        $.ajax({
            url: taskIdEdit ? `api/tasks/${taskIdEdit}` : `api/tasks`,
            method,
            dataType: "json",
            data: JSON.stringify(dataTask),
            async: true,
            success: function (data) {
                taskIdEdit = null;
                popUpTaskIsOpen = false;
                getTasks();
                $(".form-container").remove();
                alert(data.message);
            },
            error: function ({ responseJSON }) {
                alert(responseJSON.message);
            },
        });

        method = "POST";
    });
}

function deleteTask() {
    $("body").on("click", 'span[name="btn-delete-task"]', function () {
        var taskId = $(this).data("task-id");

        taskIdEdit = taskId;
        if (confirm("Are you sure you want to delete this task??")) {
            $.ajax({
                url: `api/tasks/${taskIdEdit}`,
                method: "DELETE",
                async: true,
                success: function (data) {
                    taskIdEdit = null;
                    popUpTaskIsOpen = false;
                    getTasks();
                    alert(data.message);
                },
                error: function (xhr, status, error) {
                    alert("Ocorreu um erro na requisição: " + error);
                },
            });
        } else {
            taskIdEdit = null;
        }
    });
}

$(document).ready(createTask);
$(document).ready(editTask);
$(document).ready(deleteTask);
$(document).ready(saveTask);

function main() {
    getTasks();
}

main();
