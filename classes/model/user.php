<?php

class Model_User extends \Promoter\Model\Model_User
{

    public static function for_groups($ids)
    {

        // verify not null
        if (is_null($ids))
            return array();
        // verify one
        if (count($ids) != 1)
            return array();

        // get users for this group
        return self::query()
            ->where('group_id', $ids[0])
            ->get();

    }

    public static function for_roles($ids)
    {
        return self::for_items($ids, 'users_user_roles', 'role_id');
    }

    public static function for_permissions($ids, &$actions)
    {
        return self::for_items($ids, 'users_user_permissions', 'perms_id', $actions);
    }

    protected static function for_items($ids, $table_name, $column_name, &$permission_actions = null)
    {
        // get shared user ids
        $shared_user_ids = Model_General::shared_item_ids_for_items($ids, $table_name, 'user_id', $column_name, $permission_actions);
        // verify we have some
        if (count($shared_user_ids) == 0)
            return array();

        // query users
        return self::query()
            ->where('id', 'IN', $shared_user_ids)
            ->order_by('username', 'ASC')
            ->get();
    }

}
