<?php

namespace almeyda\emcms;

/**
 * The Emcms Module provides content rendering functionality
 *
 */
class Module extends \yii\base\Module
{
	public $cacheFiles = false;
    
	/**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
	}
}
