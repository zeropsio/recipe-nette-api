<?php

declare(strict_types=1);

namespace App\Presenters;

use App\OutputDTO\Id;
use App\OutputDTO\Todo as TodoDTO;
use App\Entity\Todo;
use App\Repository\TodoRepository;
use Nette\Application\BadRequestException;
use Nette\Application\Responses\TextResponse;
use Nette\Application\UI\Presenter;
use Nettrine\ORM\EntityManagerDecorator;
use Psr\Log\LoggerInterface;

class TodosPresenter extends Presenter
{
    private EntityManagerDecorator $em;
    private LoggerInterface $log;

    public function __construct(EntityManagerDecorator $em, LoggerInterface $log)
    {
        parent::__construct();
        $this->em = $em;
        $this->log = $log;
    }


    // GET /
    public function actionIndex(): void
    {
        $readme = file_get_contents(__DIR__ . '/../../README.md');

        $this->getHttpResponse()->setContentType('text/plain', 'UTF-8');

        $this->sendResponse(new TextResponse($readme));
    }


    // GET /todos
    public function actionListing(): void
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
    public function actionCreate(): void
    {
        $input = $this->getInput();
        $text = null;

        if (array_key_exists('text', $input) === true) {
            $text = $input['text'];
        }

        if (empty($text)) {
            throw new \UnexpectedValueException('invalid input value - text');
        }

        /** @var TodoRepository */
        $todoRepository = $this->em->getRepository(Todo::class);

        $todo = $todoRepository->created($text);

        $this->log->debug("creating new todo", ["id" => $todo->getId()]);

        $this->sendJson(
            new TodoDTO($todo->getId(), $todo->getCompleted(), $todo->getText())
        );
    }

    /**
     * GET todos/<id>
     *
     * @throws BadRequestException
     * @throws \Nette\Application\AbortException
     */
    public function actionDetail(int $id): void
    {
        $todo = $this->em->getRepository(Todo::class)->find($id);

        if ($todo === null) {
            throw new BadRequestException('record not found');
        }

        $this->sendJson(
            new TodoDTO($todo->getId(), $todo->getCompleted(), $todo->getText())
        );
    }


    /**
     * PATCH /todos/<id>
     *
     * @throws BadRequestException
     * @throws \Nette\Application\AbortException
     */
    public function actionUpdate(int $id): void
    {
        $input = $this->getInput();
        $todo = $this->em->getRepository(Todo::class)->find($id);

        if ($todo === null) {
            throw new BadRequestException('record not found');
        }

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
    public function actionDelete(int $id): void
    {
        $todo = $this->em->getRepository(Todo::class)->find($id);
        if ($todo == null) {
            throw new BadRequestException('record not found');
        }

        $this->em->remove($todo);
        $this->em->flush();

        $this->sendJson(
            new Id($id)
        );
    }


    /**
     * @return array<string, mixed>
     */
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
