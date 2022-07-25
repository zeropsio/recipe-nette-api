<?php

declare(strict_types=1);

namespace App\Presenters;

use App\OutputDTO\Id;
use App\OutputDTO\Todo as TodoDTO;
use App\Entity\Todo;
use App\Repository\TodoRepository;
use Doctrine\ORM\EntityManager;
use Nette\Application\Responses\TextResponse;
use Nette\Application\UI\Presenter;
use Nettrine\ORM\EntityManagerDecorator;

class TodosPresenter extends Presenter
{
    private EntityManagerDecorator $em;

    public function __construct(EntityManagerDecorator $em)
    {
        parent::__construct();
        $this->em = $em;
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
        $todos = $this->em->getRepository(Todo::class)->findAll();
        $list = [];

        foreach ($todos as $record) {
            $list[] = new TodoDTO($record->getId(), (bool)$record->getCompleted(), $record->getText());
        }

        $this->sendJson(
            $list
        );
    }

    // POST /todos
    public function actionCreate()
    {
        $input = $this->getInput();
        $text = null;

        if (array_key_exists('text', $input) === true) {
            $text = $input['text'];
        }

        if (empty($text)) {
            throw new \UnexpectedValueException('invalid input value - text');
        }

        $todoRepository = $this->em->getRepository(Todo::class);

        $todo = $todoRepository->created($text);

        $this->sendJson(
            new TodoDTO($todo->getId(), $todo->getCompleted(), $todo->getText())
        );
    }

    // GET /todos/<id>
    public function actionDetail(int $id)
    {
        $todo = $this->em->getRepository(Todo::class)->find($id);

        if ($todo === null) {
            throw new \InvalidArgumentException('record not found');
        }

        $this->sendJson(
            new TodoDTO($todo->getId(), $todo->getCompleted(), $todo->getText())
        );
    }

    // PATCH /todos/<id>
    public function actionUpdate(int $id)
    {
        $input = $this->getInput();
        $todo = $this->em->getRepository(Todo::class)->find($id);

        if (array_key_exists('text', $input) === true) {
            $todo->setText($input["text"]);
        }
        if (array_key_exists('completed', $input) === true) {
            $todo->setCompleted((bool)$input["completed"]);
        }

        $this->em->persist($todo);
        $this->em->flush();

        $this->sendJson(
            new TodoDTO($todo->getId(), (bool)$todo->getCompleted(), $todo->getText())
        );
    }

    // DELETE /todos/<id>
    public function actionDelete(int $id)
    {
        $todo = $this->em->getRepository(Todo::class)->find($id);
        $this->em->remove($todo);
        $this->em->flush();

        $this->sendJson(
            new Id($id)
        );
    }


    private function getInput(): array
    {
        $input = file_get_contents('php://input');
        if (empty($input)) {
            return [];
        }
        $data = \json_decode($input, true);
        if (empty($data)) {
            return [];
        }

        return (array)$data;
    }
}
