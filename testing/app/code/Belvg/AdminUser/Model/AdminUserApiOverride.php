<?php

namespace Belvg\AdminUser\Model;


use Magento\Framework\Webapi\Rest\Request;
use MultiStoreRestApi\StoreProductsRestApi\Model\AdminUserApi;
use MultiStoreRestApi\StoreProductsRestApi\Api\AdminUserApiInterface;

class AdminUserApiOverride extends AdminUserApi implements AdminUserApiInterface
{
    public function __construct(
        Request $request,
        \Magento\Framework\Serialize\Serializer\Json $json,
        \Magento\Framework\App\ResourceConnection $resourceConnection,
        \Magento\User\Model\UserFactory $userFactory
    ) {
        parent::__construct($request, $json, $resourceConnection, $userFactory);
    }

    /**
     * Saving Website Data
     *
     * @param \MultiStoreRestApi\StoreProductsRestApi\Api\Data\AdminUserApiDataInterface $adminUser
     * @return string[]|string
     */
    public function createAdminUser(
        \MultiStoreRestApi\StoreProductsRestApi\Api\Data\AdminUserApiDataInterface $adminUser
    ) {
        $roleId = 1;
        $responseJsonDecode = $adminUser->getData();

        $response = [];
        if (!array_key_exists('username', $responseJsonDecode)) {
            $response[] = __("Parameter 'username' is required");
        }
        if (!array_key_exists('firstname', $responseJsonDecode)) {
            $response[] = __("Parameter 'firstname' is required");
        }
        if (!array_key_exists('lastname', $responseJsonDecode)) {
            $response[] = __("Parameter 'lastname' is required");
        }
        if (!array_key_exists('email', $responseJsonDecode)) {
            $response[] = __("Parameter 'email' is required");
        }
        if (!array_key_exists('password', $responseJsonDecode)) {
            $response[] = __("Parameter 'password' is required");
        }

        if (!empty($response)) {
            $response['status'] = "error";
            return json_encode($response);
        }

        if (array_key_exists('role_id', $responseJsonDecode)) {
            $roleId = $responseJsonDecode['role_id'];
        }

        $adminInfo = [
            'username'  => $responseJsonDecode['username'],
            'firstname' => $responseJsonDecode['firstname'],
            'lastname'  => $responseJsonDecode['lastname'],
            'email'     => $responseJsonDecode['email'],
            'password'  => $responseJsonDecode['password'],
            'is_active' => 1,
        ];

        try {
            $userModel = $this->_userFactory->create();
            $userModel->setData($adminInfo);
            $userModel->setRoleId($roleId);
            $userModel->save();
            $response['status'] = "success";
            $response[] = "Admin user created successfully.";
        } catch (\Exception $ex) {
            $response['status'] = "error";
            $response[] = $ex->getMessage();
        }
        return $response;
    }
}
