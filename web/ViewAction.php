<?php

namespace almeyda\emcms\web;

use Yii;
use yii\web\ViewAction as Action;
use yii\web\NotFoundHttpException;

class ViewAction extends Action
{
    /**
     * @var string the name of the GET parameter that contains the requested theme name.
     */
    public $themeParam = 'theme';
    
    public function run()
    {
        try {
            $output = parent::run();
        } catch (NotFoundHttpException $e) {
            if (YII_DEBUG) {
                throw new NotFoundHttpException($e->getMessage());
            } else {
                throw new NotFoundHttpException(
                    Yii::t('yii', 'Page not found.')
                );
            }
        }

        return $output;
    }
    
    /**
     * @inheritdoc
     */
    protected function resolveViewName()
    {
        $viewNameResolved = parent::resolveViewName();
        $viewName = Yii::$app->request->get($this->viewParam, $this->defaultView);
        
        if (Yii::$app->request->get($this->themeParam)) {
            $viewNameResolved = str_replace($viewName, Yii::$app->request->get($this->themeParam) . '/' . $viewName, $viewNameResolved);
        }


        return $viewNameResolved;
    }

}
