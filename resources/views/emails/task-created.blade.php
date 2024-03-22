<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Task Assigned to you</title>
</head>

<body>
    <p>Task Management System</p>

    <p>A new task has been Assigned with the following details:</p>

    <ul>
        <li><strong>Title:</strong> {{ $task['title'] }}</li>
        <li><strong>Description:</strong> {{ $task['description'] }}</li>
        <li><strong>Due Date:</strong> {{ $task['duedate'] }}</li>
        <li><strong>Priority:</strong> {{ $task['priority'] }}</li>
        <li><strong>Assigned By:</strong> {{$task->task_creator->name}}</li>
        <li><strong>Assigned To:</strong> {{$task->task_manager->name}}</li>
        <li><strong>Collaborators:</strong>

            <ul>
            @if (!empty($collaboratorsmail['collaborators']) && is_array($collaboratorsmail['collaborators']))
                @foreach($collaboratorsmail['collaborators'] as $collaborator)
                <li>{{ htmlspecialchars($collaborator) }}</li>
                @endforeach
                @else
                <!-- Handle the case when collaborators is not an array -->
             <li>   No collaborator </li>
                @endif
            </ul>
        </li>


        <!-- Add other task fields as needed -->
    </ul>

    <p>Thank you!</p>
</body>

</html>