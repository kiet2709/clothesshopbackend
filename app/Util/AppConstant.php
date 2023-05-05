<?php

namespace app\Util;

class AppConstant {
    public static $UPLOAD_DIRECTORY_USER_IMAGE = 'uploads/users';
    public static $UPLOAD_DIRECTORY_CATEGORY_IMAGE = 'uploads/categories';
    public static $UPLOAD_DIRECTORY_CLOTH_IMAGE = 'uploads/clothes';
    public static $UPLOAD_DIRECTORY_BANNER_IMAGE = 'uploads/banners';

    public static $PERMISSION_DENY = "You don't have permission to do this!";

    public static $ERROR_WITH_USER_ROLE = "There're some errors with user and role!";

    public static $VALIDATE_ERROR = "Validate error, please check your input!";

    public static $ERROR_WITH_USER = "There're some errors with user!";

    public static $ERROR_WITH_INSERT = "Error insert!";

    public static $UPLOAD_SUCCESS = "Upload successfully!";

    public static $UPLOAD_FAILURE = "Upload fail, please check again!";

    

    public static $ERROR_WITH_IMAGE = "There're some errors with image!";

    public static $ERROR_WITH_BRAND = "There're some errors with brand!";

    public static $ERROR_WITH_SIZE = "There're some errors with size!";

    public static $ERROR_WITH_CATEGORY = "There're some errors with category!";

    public static $ERROR_WITH_CLOTH = "There're some errors with cloth!";

    public static $ERROR_WITH_CART = "There're some errors with cart!";
    public static $UPDATE_SUCCESS = "Update successfully!";

    public static $DELETE_SUCCESS = "Delete successfully";

    public static $DELETE_FAILURE = "Delete fail, please check again!";

    public static $deleviryCost = [
        1 => 3,
        2 => 6,
        3 => 9
    ];
    public static $deleviryMethod = [
        1 => 'Standard',
        2 => 'Fast',
        3 => 'Rocket'
    ];

    public static $ERROR_WITH_ORDER = "There're some errors with order!";

}