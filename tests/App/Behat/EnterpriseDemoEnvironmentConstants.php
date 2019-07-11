<?php
/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Tests\App\Behat;

use EzSystems\EzPlatformPageBuilder\Tests\Behat\Environment\EnterpriseEnvironmentConstants;

class EnterpriseDemoEnvironmentConstants extends EnterpriseEnvironmentConstants
{
    /** @var string[] */
    public $values;

    public function __construct()
    {
        parent::__construct();
        $this->values['ARTICLE_MAIN_FIELD_NAME'] = 'Summary';
        $this->values['CREATE_REGISTRATION_ROLE_POLICIES'] = 'user/login,content/read,tags/read';
        $this->values['REGISTRATION_CONFIRMATION_MESSAGE'] = 'Registration completed';
        $this->values['MAIN_LANDING_PAGE_HEADER'] = 'Explore Tastes and Places of the World';
    }
}
