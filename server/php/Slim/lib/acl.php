<?php
/**
 * Roles configurations
 *
 * PHP version 5
 *
 * @category   PHP
 * @package    OFOS
 * @subpackage Core
 * @author     Agriya <info@agriya.com>
 * @copyright  2018 Agriya Infoway Private Ltd
 * @license    http://www.agriya.com/ Agriya Infoway Licence
 * @link       http://www.agriya.com
 */
namespace Acl;

class ACL
{
    public function __construct($scope)
    {
        $this->scope = $scope;
    }

    public function __invoke($request, $response, $next)
    {
        global $authUser;
        $token = "";
        if (isset($request->getHeaders() ['HTTP_AUTHORIZATION']) && !empty($request->getHeaders() ['HTTP_AUTHORIZATION'])) {
            $token = $request->getHeaders() ['HTTP_AUTHORIZATION'][0];
            if (preg_match('/Bearer\s(\S+)/', $token, $matches)) {
                $token = $matches[1];
            }
        }
        if (!empty($token)) {
            if (((empty($authUser) || (!empty($authUser['role_id']) && $authUser['role_id'] != \Constants\ConstUserTypes::ADMIN)) && !in_array($this->scope, $authUser['scope']))) {
                $result = array(
                    'error' => true,
                    'message' => 'Authorization Failed'
                );
                return $response->withJson($result, 401);
            } else {
                $response = $next($request, $response);
            }
        } else {
            $result = array(
                'error' => true,
                'message' => 'Authorization Failed'
            );
            return $response->withJson($result, 401);
        }
        return $response;
    }
}
