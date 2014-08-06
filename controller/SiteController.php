<?php
class SiteController extends MyController
{
    public function actionIndex()
	{
        $this->render('index');
	}
    public function actionTest()
	{
        $this->render('test');
	}
}    