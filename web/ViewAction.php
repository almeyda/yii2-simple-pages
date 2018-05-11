<?php
/**
 * @link https://github.com/deitsolutions/yii2-simple-pages
 * @copyright Copyright (c) 2018 Almeyda LLC
 *
 * The full copyright and license information is stored in the LICENSE file distributed with this source code.
 */

namespace deitsolutions\pages\web;

use Yii;
use yii\web\ViewAction as Action;
use yii\web\NotFoundHttpException;


/**
 * {@inheritdoc}
 */
class ViewAction extends Action
{
    /**
     * @var string the name of the GET parameter that contains the requested theme name.
     */
    public $themeParam = 'theme';

    /**
     * {@inheritdoc}
     */
    public function run()
    {
        try {
            $output = parent::run();
        } catch (NotFoundHttpException $e) {
            if (YII_DEBUG) {
                throw new NotFoundHttpException($e->getMessage());
            } else {
                throw new NotFoundHttpException(Yii::t('yii', 'Page not found.'));
            }
        }

        return $output;
    }

    /**
     * Adds prefix of 'themeParam' to view name resolved
     * {@inheritdoc}
     */
    protected function resolveViewName()
    {
        if (Yii::$app->request->get($this->themeParam)) {
            if (Yii::$app->request->get($this->viewParam) != $this->defaultView) {
                $queryParams = Yii::$app->request->getQueryParams();
                $queryParams[$this->viewParam] = Yii::$app->request->get($this->themeParam) . '/' . Yii::$app->request->get($this->viewParam);
                Yii::$app->request->setQueryParams($queryParams);
            } else {
                $this->viewParam = Yii::$app->request->get($this->themeParam) . '/' . $this->viewParam;
                $this->defaultView = Yii::$app->request->get($this->themeParam) . '/' . $this->defaultView;
            }
        }
        return parent::resolveViewName();
    }

}
