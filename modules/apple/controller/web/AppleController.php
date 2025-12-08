<?php

declare(strict_types=1);

namespace apple\controller\web;

use apple\controller\web\dto\AppleDto;
use apple\exception\AppleNotFoundException;
use apple\forms\EatForm;
use apple\forms\ListForm;
use apple\query\dto\ListFilterData;
use apple\query\FindAllByFilter;
use apple\service\AppleManager;
use apple\viewModel\ListViewModel;
use Throwable;
use Yii;
use yii\web\Response;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Request;

final class AppleController extends Controller
{
    public function __construct(
        $id,
        $module,
        private readonly AppleManager $manager,
        private readonly FindAllByFilter $findAllByFilterQuery,
        $config = [],
    ) {
        parent::__construct($id, $module, $config);
    }

    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['list', 'generate', 'eat', 'drop'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionGenerate(): Response
    {
        try {
            $randNum = random_int(50, 500);
            $this->manager->randomSeed($randNum);
            Yii::$app->session->setFlash('success', sprintf('Успешно сгенерировано %d записей', $randNum));
        } catch (Throwable $exception) {
            Yii::$app->session->setFlash('error', $exception->getMessage());
        }

        return $this->redirect(['/apple/apple/list']);
    }

    public function actionList(): string
    {
        $listForm = new ListForm();
        $listForm->load($this->getRequest()->getQueryParams(), '');

        if (!$listForm->validate()) {
            $filterDto = new ListFilterData();
        } else {
            $filterDto = $listForm->getFilterDto();
        }

        $viewModel = new ListViewModel(
            $listForm,
            $this->findAllByFilterQuery->fetch($filterDto),
            $this->findAllByFilterQuery->total($filterDto),
        );

        if($this->request->isAjax) {
            return $this->renderPartial('list', ['viewModel' => $viewModel]);
        }

        return $this->render('list', ['viewModel' => $viewModel]);
    }

    public function actionDrop(string $id): Response
    {
        $resp = $this->response;
        try {
            $this->manager->fallToGroundById($id);
            $resp->setStatusCode(204);
        } catch (AppleNotFoundException) {
            $resp->setStatusCode(404);
        }

        return $this->response;
    }

    public function actionEat(string $id): Response
    {
        $resp = $this->response;
        $resp->format = Response::FORMAT_JSON;

        $params = $this->request->getBodyParams();
        $params['id'] = $id;

        $form = new EatForm();
        $form->load($params, '');

        if (!$form->validate()) {
            $resp->setStatusCode(400);
            $resp->data =  ['errors' => $form->getErrors()];

            return $resp;
        }

        try {
            $apple = $this->manager->eatById($id, $form->healthCount);
            $resp->data = AppleDto::fromEntity($apple);
            $resp->setStatusCode(200);
        } catch (AppleNotFoundException) {
            $resp->setStatusCode(404);
        } catch (\DomainException|\InvalidArgumentException $ex) {
            $resp->setStatusCode(422);
            $resp->data = ['errors' => ['global' => [$ex->getMessage()]]];
        }

        return $this->response;
    }

    private function getRequest(): Request
    {
        return $this->request;
    }
}