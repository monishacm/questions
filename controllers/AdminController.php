<?php

namespace app\controllers;

use app\models\User;
use app\models\UserSearch;
use Yii;
use app\models\SchoolSearch;
use app\models\ClassesSearch;
use app\models\SubjectSearch;
use app\models\ChapterSearch;
use app\models\QuestionSearch;
use app\models\School;
use app\models\Question;
use app\models\Classes;
use app\models\Subject;
use app\models\Chapter;
use app\models\QuestionOption;

use yii\web\Controller;
use yii\filters\VerbFilter;

/**
 * SubjectController implements the CRUD actions for Subject model.
 */
class AdminController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Subject models.
     * @return mixed
     */
    public function actionSchools($page = 0) {
        if($page > 0) $page --;
        $searchModel = new SchoolSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $page);

        return $this->render('schools', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionAddSchool($id = 0) {
        if($id > 0) {
            $model = School::findOne($id);
        }
        else {
            $model = new School();
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['schools']);
        } else {
            return $this->render('add_school', [
                'model' => $model,
            ]);
        }
    }

    public function actionUsers($schoolId = 0) {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $schoolId);

        return $this->render('users', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionAddUser($id = 0, $schoolId = 0) {
        if($id > 0) {
            $model = User::findOne($id);
        }
        else {
            $model = new User();
            if($schoolId) {
                $model->school_id = $schoolId;
            }
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['users']);
        } else {
            return $this->render('add_user', [
                'model' => $model,
            ]);
        }
    }

    public function actionClasses() {
        $searchModel = new ClassesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('classes', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
	
	public function actionAddClass($id = 0) {
        if($id > 0) {
            $model = Classes::findOne($id);
        }
        else {
            $model = new Classes();
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['classes']);
        } else {
            return $this->render('add_class', [
                'model' => $model,
            ]);
        }
    }

    public function actionSubjects() {
        $searchModel = new SubjectSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('subjects', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
	
	public function actionAddSubject($id = 0) {
        if($id > 0) {
            $model = Subject::findOne($id);
        }
        else {
            $model = new Subject();
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['subjects']);
        } else {
            return $this->render('add_subject', [
                'model' => $model,
            ]);
        }
    }

    public function actionChapters($page = 0) {
        if($page > 0) $page --;
        $searchModel = new ChapterSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $page);

        return $this->render('chapters', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
	
	public function actionAddChapter($id = 0) {
        if($id > 0) {
            $model = Chapter::findOne($id);
        }
        else {
            $model = new Chapter();
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['chapters']);
        } else {
            return $this->render('add_chapter', [
                'model' => $model,
            ]);
        }
    }

    public function actionTaQuestions($page = 0) {
        $searchModel = new QuestionSearch();
        if($page > 0) $page --;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $page);

        return $this->render('ta_questions', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
	
	public function actionAddQuestion($id = 0) {
        if($id > 0) {
            $model = Question::findOne($id);
        }
        else {
            $model = new Question();
        }
        $questionOption = new QuestionOption();

        if ($model->load(Yii::$app->request->post())) {
            if($model->save()) {
                foreach(Yii::$app->request->post()['QuestionOption'] as $option) {
                    if(empty($option['description'])) {
                        continue;
                    }
                    if(isset($option['id'])) {
                        $opModel = QuestionOption::findOne($option['id']);
                    }
                    else {
                        $opModel = new QuestionOption();
                        $opModel->question_id = $model->id;
                    }
                    $opModel->load(array('QuestionOption' => $option));
                    $opModel->save();
                }

                return $this->redirect(['ta-questions']);
            }
        } else {
            return $this->render('add_question', [
                'model' => $model,
                'questionOption' => $questionOption
            ]);
        }
    }

    public function actionSchoolQuestions() {
        $searchModel = new ChapterSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('chapters', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
}
