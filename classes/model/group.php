<?php

class Model_Group extends \Auth\Model\Auth_Group
{

    public static function ordered($except_ids = null)
    {

        // get all groups
        $groups_query = self::query()->order_by('name', 'ASC');
        // except for the one specified
        if ($except_ids)
            $groups_query->where('id', 'NOT IN', $except_ids);
        // success
        return $groups_query->get();

    }

    public static function filtered($filter)
    {

        // start groups query
        $groups_query = self::query()->order_by('name', 'ASC');
        // apply filter
        if ($filter != '')
        {
            // split up filter into commas
            $filter_parts = explode(',', $filter);
            // add where like conditions for each filter part
            foreach ($filter_parts as $filter_part)
                $groups_query->or_where('name', 'LIKE', '%' . $filter_part . '%');
        }

        // success
        return $groups_query->get();

    }

    public static function for_users($ids)
    {

        // verify not null
        if (is_null($ids))
            return array();

        // select all group ids from passed users
        $users = DB::select('group_id')
            ->from('users')
            ->where('id', 'in', $ids)
            ->execute();
        // make sure we have at least one
        if (count($users) == 0)
            return array();

        // get the group id to verify all users are assigned to
        $group_id = $users[0]['group_id'];
        // verify not default
        if ($group_id == 0)
            return array();

        // make sure all of the user groups are the same
        foreach ($users as $user)
        {
            // if we run into a group that differs, fail
            if ($user['group_id'] != $group_id)
                return array();
        }

        // get single group
        return self::query()->where('id', $group_id)->get();

    }

    public static function for_roles($ids)
    {
        return self::for_items($ids, 'users_group_roles', 'role_id');
    }

    public static function for_permissions($ids, &$actions)
    {
        return self::for_items($ids, 'users_group_permissions', 'perms_id', $actions);
    }

    private static function for_items($ids, $table_name, $column_name, &$permission_actions = null)
    {

        // get shared group ids
        $shared_group_ids = Model_General::shared_item_ids_for_items($ids, $table_name, 'group_id', $column_name, $permission_actions);
        // verify we have some
        if (count($shared_group_ids) == 0)
            return array();

        // query groups
        return self::query()
            ->where('id', 'IN', $shared_group_ids)
            ->order_by('name', 'ASC')
            ->get();

    }

}