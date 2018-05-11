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
     * Resolves the view name currently being requested.
     * Parameter with the name 'themeParam' could be sent to form the url. This case its value added as a prefix to view name resolved
     *
     * @return string the resolved view name
     * @throws NotFoundHttpException if the specified view name is invalid
     */
    protected function resolveViewName()
    {
        $viewName = Yii::$app->request->get($this->viewParam, $this->defaultView);
    
        if (Yii::$app->request->get($this->themeParam)) {
            $viewName = Yii::$app->request->get($this->themeParam) . '/' . $viewName;
        }
    
        if (!is_string($viewName) || !preg_match('~^\w(?:(?!\/\.{0,2}\/)[\w\/\-\.])*$~', $viewName)) {
            if (YII_DEBUG) {
                throw new NotFoundHttpException("The requested view \"$viewName\" must start with a word character, must not contain /../ or /./, can contain only word characters, forward slashes, dots and dashes.");
            }
        
            throw new NotFoundHttpException(Yii::t('yii', 'The requested route "{name}" was not found.', ['name' => $viewName]));
        }
    
        return empty($this->viewPrefix) ? $viewName : $this->viewPrefix . '/' . $viewName;
    }
    
}
