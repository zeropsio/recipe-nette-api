<?php

declare(strict_types=1);

namespace App\Presenters;

use App\OutputDTO\Id;
use App\OutputDTO\Todo;
use App\OutputDTO\TodoList;
use App\Repository\TodoRepository;
use Nette\Application\Responses\TextResponse;

class TodosPresenter extends \Nette\Application\UI\Presenter
{
    /** @var TodoRepository */
    private $todoRepository;

    public function __construct(TodoRepository $todoRepository)
    {
        parent::__construct();

        $this->todoRepository = $todoRepository;
    }


    // GET /
    public function actionIndex()
    {
        $readme = file_get_contents(__DIR__ . '/../../README.md');

        $this->getHttpResponse()->setContentType('text/plain', 'UTF-8');

        $this->sendResponse(new TextResponse($readme));
    }


    // GET /todos
    public function actionListing()
    {
        $todos = $this->todoRepository->findAll();
        $list = [];

        foreach ($todos as $record){
            $list[] = new Todo($record->id, (bool)$record->completed, $record->text);
        }

        $this->sendJson(
            new TodoList($list)
        );
    }

    // POST /todos
    public function actionCreate()
    {
        $input = $this->getInput();
        $text = null;

        if (array_key_exists('text', $input) === true){
            $text = $input['text'];
        }

        if  (empty($text)){
            throw new \UnexpectedValueException('invalid input value - text');
        }

        $newId = $this->todoRepository->created($text);

        if ($newId <= 0){
            throw new \RuntimeException;
        }

        $todo = $this->todoRepository->find($newId);

        $this->sendJson(
            new Todo($todo->id, (bool)$todo->completed, $todo->text)
        );
    }

    // GET /todos/<id>
    public function actionDetail(int $id)
    {
        $todo = $this->todoRepository->find($id);

        if ($todo === null){
            throw new \InvalidArgumentException('record not found');
        }

        $this->sendJson(
            new Todo($todo->id, (bool)$todo->completed, $todo->text)
        );
    }

    // PATCH /todos/<id>
    public function actionUpdate(int $id)
    {
        $patch = [];
        $input = $this->getInput();

        if (array_key_exists('text', $input) === true){
            $patch['text'] = $input['text'];
        }
        if (array_key_exists('completed', $input) === true){
            $patch['completed'] = (bool)$input['completed'];
        }

        if (!empty($patch)){
            $this->todoRepository->patch($id, $patch);
        }

        $todo = $this->todoRepository->find($id);

        if ($todo === null){
            throw new \InvalidArgumentException('record not found');
        }

        $this->sendJson(
            new Todo($todo->id, (bool)$todo->completed, $todo->text)
        );
    }

    // DELETE /todos/<id>
    public function actionDelete(int $id)
    {
        $this->todoRepository->delete($id);

        $this->sendJson(
            new Id($id)
        );
    }


    private function getInput(): array
    {
        $input = file_get_contents('php://input');
        if (empty($input)){
            return [];
        }
        $data = \json_decode($input, true);
        if (empty($data)){
            return [];
        }

        return (array)$data;
    }
}
