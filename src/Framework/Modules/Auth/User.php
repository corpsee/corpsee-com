<?php

/**
 * Nameless framework
 *
 * @package Nameless framework
 * @author  Corpsee <poisoncorpsee@gmail.com>
 * @license https://github.com/corpsee/nameless-source/blob/master/LICENSE
 * @link    https://github.com/corpsee/nameless-source
 */

namespace Nameless\Modules\Auth;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\RouteCollection;

/**
 * User class
 *
 * @author Corpsee <poisoncorpsee@gmail.com>
 */
class User
{
    /**
     * @var Session
     */
    private $session;

    /**
     * @var array
     */
    private $routes;

    /**
     * @var array
     */
    private $access;

    const USER_ID     = 'user_id';
    const USER_NAME   = 'user_name';
    const USER_GROUPS = 'user_groups';

    /**
     * @param Session $session
     * @param RouteCollection $routes
     * @param array $access
     */
    public function __construct(Session $session, RouteCollection $routes, array $access)
    {
        $this->session = $session;
        $this->routes  = $routes;
        $this->access  = $access;
    }

    /**
     * @param Auth $auth
     * @param integer $duration
     *
     * @return Response|void
     */
    public function login(Auth $auth, $duration = 0 /*, Response $response = NULL, $duration = 0*/)
    {
        $user_groups = serialize($auth->getUserGroups());
        $user_id     = $auth->getUserId();
        $user_name   = $auth->getUserName();

        $this->session->set(self::USER_ID, $user_id);
        $this->session->set(self::USER_NAME, $user_name);
        $this->session->set(self::USER_GROUPS, $user_groups);
    }

    /**
     * @param boolean $destroy
     *
     * @return boolean
     */
    public function logout($destroy = false)
    {
        if ($destroy === true) {
            $this->session->invalidate();
        } else {
            $this->session->remove(self::USER_ID);
            $this->session->remove(self::USER_NAME);
            $this->session->remove(self::USER_GROUPS);

            $this->session->migrate();
        }

        return !$this->isLogin();
    }

    /**
     * @param integer|null $default
     *
     * @return integer|null
     */
    public function getUserId($default = null)
    {
        return (integer)$this->session->get(self::USER_ID, $default);
    }

    /**
     * @param string|null $default
     *
     * @return string|null
     */
    public function getUserName($default = null)
    {
        return $this->session->get(self::USER_NAME, $default);
    }

    /**
     * @param array $default
     *
     * @return array
     */
    public function getUserGroups(array $default = [])
    {
        return unserialize($this->session->get(self::USER_GROUPS, serialize($default)));
    }

    /**
     * @return boolean
     */
    public function isLogin()
    {
        return ($this->getUserName() !== null);
    }

    /**
     * @param string $route
     *
     * @return boolean
     */
    public function getAccessByRoute($route)
    {
        $defaults = $this->routes->get($route)->getDefaults();
        return $this->getAccessByController($defaults['_controller']);
    }

    /**
     * @param string $controller
     *
     * @return boolean
     */
    public function getAccessByController($controller)
    {
        $access = false;
        list($controller, $action) = explode('::', $controller);

        $groups = $this->getUserGroups();

        // если в настройках нет контроллера - разрешен
        if (!isset($this->access[$controller])) {
            $access = true;
        } else {
            // если в настройках нет действия - разрешен
            if (!isset($this->access[$controller][$action])) {
                $access = true;
            } else {
                foreach ($this->access[$controller][$action] as $action_access) {
                    if (in_array($action_access, $groups)) {
                        $access = true;
                    }
                }
            }
        }
        return $access;
    }
}
