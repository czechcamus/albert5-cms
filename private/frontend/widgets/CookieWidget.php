<?php
/**
 * Created by PhpStorm.
 * User: Pavel
 * Date: 20.11.2015
 * Time: 22:06
 */

namespace frontend\widgets;


class CookieWidget extends \cinghie\cookieconsent\widgets\CookieWidget
{
	public function run($params = [])
	{
		return $this->render('cookieWidget',[
			'message'    => $this->message,
			'dismiss'    => $this->dismiss,
			'learnMore'  => $this->learnMore,
			'link'       => $this->link,
			'theme'      => $this->theme,
			'container'  => $this->container,
			'path'       => $this->path,
			'domain'     => $this->domain,
			'expiryDays' => $this->expiryDays
		]);
	}
}