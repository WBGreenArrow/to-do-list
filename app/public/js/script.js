function getTasks() {
    $.ajax({
        url: 'api/tasks',
        method: 'GET',
        dataType: 'json',
        success: function(data) {
            updateTasksUI(data);
        },
        error: function(xhr, status, error) {
            console.log('Ocorreu um erro na requisição: ' + error);
        }
    });
}

function updateTasksUI(tasks){
    console.log(tasks)
}

getTasks()