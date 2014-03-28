<?php

class Model_Role extends \Auth\Model\Auth_Role
{

    public static function filtered($filter)
    {

        // start roles query
        $roles_query = Model_Role::query()->order_by('name', 'ASC');
        // apply filter
        if ($filter != '')
        {
            // split up filter into commas
            $filter_parts = explode(',', $filter);
            // add where like conditions for each filter part
            foreach ($filter_parts as $filter_part)
                $roles_query->or_where('name', 'LIKE', '%' . $filter_part . '%');
        }

        // success
        return $roles_query->get();

    }

    public static function ordered($except_ids = null)
    {

        // get all roles
        $roles_query = self::query()->order_by('name', 'ASC');
        // except for the ones specified
        if ($except_ids)
            $roles_query->where('id', 'NOT IN', $except_ids);
        // success
        return $roles_query->get();

    }

    public static function for_users($ids)
    {
        return self::for_items($ids, 'users_user_roles', 'user_id');
    }

    public static function for_groups($ids)
    {
        return self::for_items($ids, 'users_group_roles', 'group_id');
    }

    public static function for_permissions($ids, &$actions)
    {
        return self::for_items($ids, 'users_role_permissions', 'perms_id', $actions);
    }

    private static function for_items($ids, $table_name, $column_name, &$permission_actions = null)
    {
        // get shared role ids
        $shared_role_ids = \Promoter\Model\Promoter_General::shared_item_ids_for_items($ids, $table_name, 'role_id', $column_name, $permission_actions);
        // verify we have some
        if (count($shared_role_ids) == 0)
            return array();

        // query roles
        return self::query()
            ->where('id', 'IN', $shared_role_ids)
            ->order_by('name', 'ASC')
            ->get();
    }

}