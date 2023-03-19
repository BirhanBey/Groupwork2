# Documentation:

GET /localhost
Send a GET request to retrieve all TodoLists and all the todos. The API will respond with a JSON object that contains an array of TodoLists.

GET /api/TodoList&id={id}
Send a GET request to retrieve a TodoList by ID and the respective todos. The API will respond with a JSON object that contains the requested TodoList. If the ID is not provided, the API will respond with a JSON object that contains an error message.

POST /api/TodoList
Send a POST request to create a TodoList. The request body should contain the TodoList name in JSON format. The API will respond with a JSON object that contains a message and the id of the created TodoList. If the TodoList cannot be created, the API will respond with a 500 status code and a message indicating that it was unable to create the TodoList.

POST /api/Todo
Send a POST request to create a Todo. The request body should contain the TodoList_id and the description of the Todo in JSON format. The API will respond with a JSON object that contains a message and the id of the created Todo. If the Todo cannot be created, the API will respond with a 500 status code and a message indicating that it was unable to create the Todo.

DELETE /api/TodoList&id={id}
Send a DELETE request to delete a TodoList by ID. The API will respond with a JSON object that contains a message indicating whether or not the TodoList was deleted. If the ID is not provided, the API will respond with a JSON object that contains an error message.

DELETE /api/Todo&id={id}
Send a DELETE request to delete a Todo by ID. The API will respond with a JSON object that contains a message indicating whether or not the Todo was deleted. If the ID is not provided, the API will respond with a JSON object that contains an error message.

PATCH api/TodoList&id={id} where {id} is the ID of the TodoList to update.
The request body should contain the fields to update and their new values in JSON format.
The API will respond with a JSON object that contains a message indicating whether or not the TodoList was updated.
If the ID is not provided, the API will respond with a JSON object that contains an error message.

PATCH api/Todo&id={id} where {id} is the ID of the Todo to update.
The request body should contain the fields to update and their new values in JSON format.
The API will respond with a JSON object that contains a message indicating whether or not the Todo was updated.
If the ID is not provided, the API will respond with a JSON object that contains an error message.
