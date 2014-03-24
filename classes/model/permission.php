<?php

class Model_Permission extends \Auth\Model\Auth_Permission
{

    public static function filtered($filter)
    {

        // start permissions query
        $permissions_query = Model_Permission::query()->order_by('area', 'ASC')->order_by('permission', 'ASC');
        // apply filter
        if ($filter != '')
        {
            // split up filter into commas
            $filter_parts = explode(',', $filter);
            // add where like conditions for each filter part
            foreach ($filter_parts as $filter_part)
            {
                // permission filters apply to the area and permission name
                $permissions_query->or_where('area', 'LIKE', '%' . $filter_part . '%');
                $permissions_query->or_where('permission', 'LIKE', '%' . $filter_part . '%');
            }
        }

        // success
        return $permissions_query->get();

    }

    public static function ordered($except_ids = null)
    {

        // get all permissions
        $permissions_query = self::query()->order_by('area', 'ASC')->order_by('permission', 'ASC');
        // except for the ones specified
        if ($except_ids)
            $permissions_query->where('id', 'NOT IN', $except_ids);
        // success
        return $permissions_query->get();

    }

    public static function for_users($ids, &$actions)
    {
        return self::for_items($ids, $actions, 'users_user_permissions', 'user_id');
    }

    public static function for_groups($ids, &$actions)
    {
        return self::for_items($ids, $actions, 'users_group_permissions', 'group_id');
    }

    public static function for_roles($ids, &$actions)
    {
        return self::for_items($ids, $actions, 'users_role_permissions', 'role_id');
    }

    private static function for_items($ids, &$actions, $table_name, $column_name)
    {

        // verify not null
        if (is_null($ids))
            return array();

        /////////////////////////////
        // GET RELATION TABLE DATA //
        /////////////////////////////

        // select all permission ids assigned to items
        $items_permissions = DB::select()
            ->from($table_name)
            ->where($column_name, 'in', $ids)
            ->order_by($column_name, 'ASC')
            ->execute()
            ->as_array();

        ////////////////////////
        // INITIAL GUT CHECKS //
        ////////////////////////

        // get count of items permissions
        $items_permissions_count = count($items_permissions);
        // if we have no items permissions, we are done
        if ($items_permissions_count == 0)
            return array();
        // if the users user permissions count is less than the ids count, we are done
        if ($items_permissions_count < count($ids))
            return array();

        ///////////////////////////////////////////////
        // FIND SHARED PERMISSIONS AMONGST ALL ITEMS //
        ///////////////////////////////////////////////

        $shared_permissions = null;
        // sort the item ids numerically
        sort($ids, SORT_NUMERIC);
        // loop over all item ids, ensuring that they share at least one permission
        foreach ($ids as $id)
        {

            // get current users user permission
            $current_items_permission = current($items_permissions);
            // get current item id
            $current_item_id = $current_items_permission[$column_name];
            // verify we have the item ids sequentially, else we are missing an item
            if ($current_item_id != $id)
                return array();

            // create temp array to hold current item permission ids
            $current_item_permissions = array();

            ////////////////////////////////////////
            // WALK OVER USER PERMISSIONS/ACTIONS //
            ////////////////////////////////////////

            do
            {

                // get current user permission id and actions
                $current_item_permission_id = $current_items_permission['perms_id'];
                $current_item_permission_actions = unserialize($current_items_permission['actions']);
                // add the actions to the permission or an empty array if actions null
                if ($current_item_permission_actions)
                    $current_item_permissions[$current_item_permission_id] = $current_item_permission_actions;
                else
                    $current_item_permissions[$current_item_permission_id] = array();
                // move to next items permission
                $current_items_permission = next($items_permissions);

            } while ($current_items_permission[$column_name] == $id);

            // initialize shared permission ids with the first item's permission ids
            if (is_null($shared_permissions))
            {
                $shared_permissions = $current_item_permissions;
                continue;
            }

            // intersect shared permissions with current to get shared
            $shared_permissions = array_intersect_key($shared_permissions, $current_item_permissions);
            // verify we have any, else fail
            if (count($shared_permissions) == 0)
                return array();

            // if we don't have this permission id key, fail
            foreach ($shared_permissions as $shared_permission_id => $shared_permission_actions)
                $shared_permissions[$shared_permission_id] = array_intersect($shared_permission_actions, $current_item_permissions[$shared_permission_id]);

        }

        ////////////////////////////////////////////////
        // GATHER SHARED PERMISSIONS, SET ASSIGNMENTS //
        ////////////////////////////////////////////////

        // get shared permission ids
        $shared_permission_ids = array_keys($shared_permissions);
        // verify we have some
        if (count($shared_permission_ids) == 0)
            return array();

        // get all shared permissions
        $permissions = self::query()
            ->where('id', 'IN', $shared_permission_ids)
            ->order_by('area', 'ASC')
            ->order_by('permission', 'ASC')
            ->get();

        // for each permission, set assignments
        foreach ($permissions as $permission_id => $permission)
            $actions[$permission_id] = array_values(array_intersect_key($permission->actions, array_flip($shared_permissions[$permission_id])));

        // success
        return $permissions;

    }

}