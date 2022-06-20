# recipe-nette-api

Sample Nette API for todo list.

## API

- GET /todos
- GET /todos/:id
- POST /todos - create new entity and return it
- PATCH /todos/:id - return updated entity
- DELETE /todos/:id - return deleted id


## DB schema

```sql
CREATE TABLE `todos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `completed` tinyint(1) NOT NULL,
  `text` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```


## Output object

```
Todo {
  id: number
  completed: boolean
  text: string
}
```
