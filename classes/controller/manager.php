<?php

class Controller_Manager extends Controller_Standard
{

    public function router($method, $params)
    {

        // forward to router
        parent::router($method, $params);
        // authenticate
        if (!Auth::has_access('authority.access'))
        {
            // we failed to authorize
            Response::redirect();
            return;
        }

    }

    public function action_index()
    {
        // create view
        $view = View::forge('manager/index');
        // set template vars
        $this->template->site = 'Authority';
        $this->template->title = 'Manager';
        $this->template->section->body = $view;
    }

    /////////////
    // GETTERS //
    /////////////

    public function get_users()
    {

        // get the filter
        $filter = Input::get('filter');
        // verify we have a filter
        if ($filter == '')
            return $this->response(array());
        // get filtered users
        $users = Model_User::filtered($filter);
        // get array values
        return $this->response(array_values($users));

    }

    public function get_groups()
    {

        // get the filter
        $filter = Input::get('filter');
        // get filtered groups
        $groups = Model_Group::filtered($filter);
        // success
        return $this->response(array_values($groups));

    }

    public function get_roles()
    {

        // get the filter
        $filter = Input::get('filter');
        // get filtered roles
        $roles = Model_Role::filtered($filter);
        // success
        return $this->response(array_values($roles));

    }

    public function get_permissions()
    {

        // get the filter
        $filter = Input::get('filter');
        // get filtered permissions
        $permissions = Model_Permission::filtered($filter);
        // success
        return $this->response(array_values($permissions));

    }

    /////////////
    // LOADERS //
    /////////////

    public function post_load()
    {

        // get the type and ids
        $from_type = Input::post('from_type');
        $to_type = Input::post('to_type');
        $ids = Input::post('ids');

        // assignments array
        $assignments = array();
        // get assigned arrays
        switch ($from_type) {

            case 'user_model':
                if (is_null($to_type) or ($to_type == 'group_model'))
                    $this->load_user_groups($ids, $assignments);
                if (is_null($to_type) or ($to_type == 'role_model'))
                    $this->load_user_roles($ids, $assignments);
                if (is_null($to_type) || ($to_type == 'permission_model'))
                    $this->load_user_permissions($ids, $assignments);
                break;

            case 'group_model':
                if (is_null($to_type) or ($to_type == 'user_model'))
                    $this->load_group_users($ids, $assignments);
                if (is_null($to_type) or ($to_type == 'role_model'))
                    $this->load_group_roles($ids, $assignments);
                if (is_null($to_type) or ($to_type == 'permission_model'))
                    $this->load_group_permissions($ids, $assignments);
                break;

            case 'role_model':
                if (is_null($to_type) or ($to_type == 'user_model'))
                    $this->load_role_users($ids, $assignments);
                if (is_null($to_type) or ($to_type == 'group_model'))
                    $this->load_role_groups($ids, $assignments);
                if (is_null($to_type) or ($to_type == 'permission_model'))
                    $this->load_role_permissions($ids, $assignments);
                break;

            case 'permission_model':
                if (is_null($to_type) or ($to_type == 'user_model'))
                    $this->load_permission_users($ids, $assignments);
                if (is_null($to_type) or ($to_type == 'group_model'))
                    $this->load_permission_groups($ids, $assignments);
                if (is_null($to_type) or ($to_type == 'role_model'))
                    $this->load_permission_roles($ids, $assignments);
                break;

        }

        // success
        return $this->response($assignments);

    }

    protected function load_user_groups($ids, &$assignments)
    {
        $assigned_groups = Model_Group::for_users($ids);
        $assignments['assigned_groups'] = array_values($assigned_groups);
        $assignments['unassigned_groups'] = array_values(Model_Group::ordered(array_keys($assigned_groups)));
    }

    protected function load_user_roles($ids, &$assignments)
    {
        $assigned_roles = Model_Role::for_users($ids);
        $assignments['assigned_roles'] = array_values($assigned_roles);
        $assignments['unassigned_roles'] = array_values(Model_Role::ordered(array_keys($assigned_roles)));
    }

    protected function load_user_permissions($ids, &$assignments)
    {
        $assigned_permission_actions = array();
        $assigned_permissions = Model_Permission::for_users($ids, $assigned_permission_actions);
        $assignments['assigned_permissions'] = array_values($assigned_permissions);
        $assignments['unassigned_permissions'] = array_values(Model_Permission::ordered(array_keys($assigned_permissions)));
        $assignments['assigned_permission_actions'] = $assigned_permission_actions;
    }

    protected function load_group_users($ids, &$assignments)
    {
        $assigned_users = Model_User::for_groups($ids);
        $assignments['assigned_users'] = array_values($assigned_users);
    }

    protected function load_group_roles($ids, &$assignments)
    {
        $assigned_roles = Model_Role::for_groups($ids);
        $assignments['assigned_roles'] = array_values($assigned_roles);
        $assignments['unassigned_roles'] = array_values(Model_Role::ordered(array_keys($assigned_roles)));
    }

    protected function load_group_permissions($ids, &$assignments)
    {
        $assigned_permission_actions = array();
        $assigned_permissions = Model_Permission::for_groups($ids, $assigned_permission_actions);
        $assignments['assigned_permissions'] = array_values($assigned_permissions);
        $assignments['unassigned_permissions'] = array_values(Model_Permission::ordered(array_keys($assigned_permissions)));
        $assignments['assigned_permission_actions'] = $assigned_permission_actions;
    }

    protected function load_role_users($ids, &$assignments)
    {
        $assigned_users = Model_User::for_roles($ids);
        $assignments['assigned_users'] = array_values($assigned_users);
    }

    protected function load_role_groups($ids, &$assignments)
    {
        $assigned_groups = Model_Group::for_roles($ids);
        $assignments['assigned_groups'] = array_values($assigned_groups);
    }

    protected function load_role_permissions($ids, &$assignments)
    {
        $assigned_permission_actions = array();
        $assigned_permissions = Model_Permission::for_roles($ids, $assigned_permission_actions);
        $assignments['assigned_permissions'] = array_values($assigned_permissions);
        $assignments['unassigned_permissions'] = array_values(Model_Permission::ordered(array_keys($assigned_permissions)));
        $assignments['assigned_permission_actions'] = $assigned_permission_actions;
    }

    protected function load_permission_users($ids, &$assignments)
    {
        $assigned_user_permission_actions = array();
        $assigned_users = Model_User::for_permissions($ids, $assigned_user_permission_actions);
        $assignments['assigned_users'] = array_values($assigned_users);
        $assignments['assigned_user_permission_actions'] = $assigned_user_permission_actions;
    }

    protected function load_permission_groups($ids, &$assignments)
    {
        $assigned_group_permission_actions = array();
        $assigned_groups = Model_Group::for_permissions($ids, $assigned_group_permission_actions);
        $assignments['assigned_groups'] = array_values($assigned_groups);
        $assignments['assigned_group_permission_actions'] = $assigned_group_permission_actions;
    }

    protected function load_permission_roles($ids, &$assignments)
    {
        $assigned_role_permission_actions = array();
        $assigned_roles = Model_Role::for_permissions($ids, $assigned_role_permission_actions);
        $assignments['assigned_roles'] = array_values($assigned_roles);
        $assignments['assigned_role_permission_actions'] = $assigned_role_permission_actions;
    }

    ///////////////
    // ASSIGNERS //
    ///////////////

    public function post_assign()
    {

        // get assign data
        $from_type = Input::post('from_type');
        $to_type = Input::post('to_type');
        $from_ids = Input::post('from_ids', array());
        $assign_to_ids = Input::post('assign_to_ids', array());
        $unassign_to_ids = Input::post('unassign_to_ids', array());

        // switch on from type
        switch ($from_type)
        {

            case 'user_model':
                // switch on to type
                switch ($to_type)
                {
                    case 'group_model':
                        $this->assign_users_groups($from_ids, $assign_to_ids, $unassign_to_ids);
                        break;
                    case 'role_model':
                        $this->assign_items_items('users_user_roles', 'user_id', 'role_id', $from_ids, $assign_to_ids, $unassign_to_ids);
                        break;
                    case 'permission_model':
                        $this->assign_items_items('users_user_permissions', 'user_id', 'perms_id', $from_ids, $assign_to_ids, $unassign_to_ids, array());
                        break;
                }
                break;

            case 'group_model':
                // switch on to type
                switch ($to_type)
                {
                    case 'user_model':
                        $this->unassign_groups_users($unassign_to_ids);
                        break;
                    case 'role_model':
                        $this->assign_items_items('users_group_roles', 'group_id', 'role_id', $from_ids, $assign_to_ids, $unassign_to_ids);
                        break;
                    case 'permission_model':
                        $this->assign_items_items('users_group_permissions', 'group_id', 'perms_id', $from_ids, $assign_to_ids, $unassign_to_ids, array());
                        break;
                }
                break;

            case 'role_model':
                // switch on to type
                switch ($to_type)
                {
                    case 'user_model':
                        $this->assign_items_items('users_user_roles', 'role_id', 'user_id', $from_ids, $assign_to_ids, $unassign_to_ids);
                        break;
                    case 'group_model':
                        $this->assign_items_items('users_group_roles', 'role_id', 'group_id', $from_ids, $assign_to_ids, $unassign_to_ids);
                        break;
                    case 'permission_model':
                        $this->assign_items_items('users_role_permissions', 'role_id', 'perms_id', $from_ids, $assign_to_ids, $unassign_to_ids, array());
                        break;
                }
                break;

            case 'permission_model':
                // switch on to type
                switch ($to_type)
                {
                    case 'user_model':
                        $this->assign_items_items('users_user_permissions', 'perms_id', 'user_id', $from_ids, $assign_to_ids, $unassign_to_ids);
                        break;
                    case 'group_model':
                        $this->assign_items_items('users_group_permissions', 'perms_id', 'group_id', $from_ids, $assign_to_ids, $unassign_to_ids);
                        break;
                    case 'role_model':
                        $this->assign_items_items('users_role_permissions', 'perms_id', 'role_id', $from_ids, $assign_to_ids, $unassign_to_ids, array());
                        break;
                }
                break;

        }

        // delete all cached permissions
        \Cache::delete_all(\Config::get('ormauth.cache_prefix', 'auth').'.permissions');
        // success
        return $this->response('SUCCESS');

    }

    protected function assign_users_groups($from_ids, $assign_to_ids, $unassign_to_ids)
    {
        // the group id is null for any unassignments, and the first for any assignments
        $group_id = count($unassign_to_ids) > 0 ? null : current($assign_to_ids);
        // loop over user ids
        foreach ($from_ids as $user_id)
        {
            // get the user
            $user = \Auth\Model\Auth_User::find($user_id);
            // set the group
            $user->group_id = $group_id;
            // save
            $user->save();
        }
    }

    protected function assign_items_items(
        $assignment_table,
        $from_column,
        $to_column,
        $from_ids,
        $assign_to_ids,
        $unassign_to_ids,
        $actions = null)
    {

        ////////////////////////
        // DELETE ASSIGNMENTS //
        ////////////////////////

        // see if we have any unassignments
        if (count($unassign_to_ids) > 0)
        {
            // get the assignment delete query
            $assignment_delete = DB::delete($assignment_table)
                ->where($from_column, 'IN', $from_ids)
                ->where($to_column, 'IN', $unassign_to_ids);
            // delete assignment
            $assignment_delete->execute();
        }

        ////////////////////////
        // INSERT ASSIGNMENTS //
        ////////////////////////

        // loop over user ids
        foreach ($from_ids as $from_id)
        {
            // loop over assignments
            foreach ($assign_to_ids as $assign_to_id)
            {
                // create assignment set
                $assignment_set = array(
                    $from_column => $from_id,
                    $to_column => $assign_to_id
                );
                // if we have actions, assign them
                if (!is_null($actions))
                    $assignment_set['actions'] = serialize($actions);
                // get the assignment insert query
                $assignment_insert = DB::insert($assignment_table)->set($assignment_set);
                // insert assignment
                $assignment_insert->execute();
            }
        }
    }

    protected function unassign_groups_users($unassign_to_ids)
    {
        // loop over user ids
        foreach ($unassign_to_ids as $user_id)
        {
            // get the user
            $user = \Auth\Model\Auth_User::find($user_id);
            // set the group
            $user->group_id = null;
            // save
            $user->save();
        }
    }

    public function post_assign_permission_actions()
    {

        // get assign data
        $from_type = Input::post('from_type');
        $from_ids = Input::post('from_ids', array());
        $perms_ids = Input::post('perms_ids', array());
        $permission_action = Input::post('permission_action');
        $assign = (Input::post('assign') == 'true');

        // switch on from type
        switch ($from_type)
        {
            case 'user_model':
                $this->assign_items_permission_action('Auth_User', 'user_id', $from_ids, 'userpermission', 'Auth_Userpermission', $perms_ids, $permission_action, $assign);
                break;
            case 'group_model':
                $this->assign_items_permission_action('Auth_Group', 'group_id', $from_ids, 'grouppermission', 'Auth_Grouppermission', $perms_ids, $permission_action, $assign);
                break;
            case 'role_model':
                $this->assign_items_permission_action('Auth_Role', 'role_id', $from_ids, 'rolepermission', 'Auth_Rolepermission', $perms_ids, $permission_action, $assign);
                break;
        }

        // delete all cached permissions
        \Cache::delete_all(\Config::get('ormauth.cache_prefix', 'auth').'.permissions');
        // success
        return $this->response('SUCCESS');

    }

    protected function merge_permission_actions(&$old_action_indexes, $new_action_index, $assign)
    {

        // see if this current actions has the index
        if (!is_null($old_action_indexes))
            $old_actions_index = array_search($new_action_index, $old_action_indexes);

        // assign
        if ($assign)
        {

            // if old actions null, generate fresh new actions array
            if (is_null($old_action_indexes))
            {
                $old_action_indexes = array($new_action_index);
                return;
            }

            // if the action is already there, we are done
            if ($old_actions_index !== false)
                return;

            // add new action
            $old_action_indexes[] = $new_action_index;

        }
        else
        {

            // if the action is not already there, we are done
            if ($old_actions_index === false)
                return;

            // if we have one action left, set to empty array, else remove
            if (count($old_action_indexes) == 1)
                $old_action_indexes = array();
            else
                unset($old_action_indexes[$old_actions_index]);

        }

    }

    protected function assign_items_permission_action($from_class, $from_column, $from_ids, $perms_array, $perm_class, $perms_ids, $permission_action, $assign)
    {

        // get the from query
        $from_query = call_user_func("\\Auth\\Model\\" . $from_class . '::query');
        // get all froms
        $froms = $from_query
            ->related('permissions')
            ->where('id', 'IN', $from_ids)
            ->get();

        // loop over all froms to create/update permission assignments
        foreach ($froms as $from)
        {

            // loop over all permissions, seeing if the user has it
            foreach ($perms_ids as $perms_id)
            {

                // get the permission for this perms id
                $permission = \Auth\Model\Auth_Permission::find($perms_id);
                // get the index of the permission action
                $permission_action_index = array_search($permission_action, $permission->actions);

                $found_perm = false;
                // loop over all currently assigned perms, looking for the ones passed in
                foreach ($from->$perms_array as $perm)
                {
                    // if we found an existing permission assignment
                    if ($perm->perms_id == $perms_id)
                    {
                        // merge in new action
                        $this->merge_permission_actions($perm->actions, $permission_action_index, $assign);
                        // set found
                        $found_perm = true;
                        break;
                    }
                }

                // we couldn't find the assigned permission, create
                if (!$found_perm)
                {
                    $perm = call_user_func("\\Auth\\Model\\" . $perm_class . '::forge', array(
                            $from_column =>  $from->id,
                            'perms_id' => $perms_id,
                            'actions' => array($permission_action_index)
                        ));
                }

                // save perm
                $perm->save();

            }

        }

    }

    //////////////////////
    // ADDERS/MODIFIERS //
    //////////////////////

    public function post_add_modify()
    {

        // get post params
        $type = Input::post('type');
        $object = Input::post('object');

        switch ($type)
        {
            case 'user_model':
                $object = $this->add_modify_user($object);
                break;
            case 'group_model':
                $object = $this->add_modify_group($object);
                break;
            case 'role_model':
                $object = $this->add_modify_role($object);
                break;
            case 'permission_model':
                $object = $this->add_modify_permission($object);
                break;
        }

        // success
        return $this->response($object);

    }

    public function add_modify_user($object)
    {

        // if the user is new, we need to do an auth create
        if (!isset($object['id']))
        {

            // create user
            $user_id = Auth::create_user(
                $object['username'],
                $object['password'],
                $object['email']
            );
            // find created user
            $user = \Auth\Model\Auth_User::find($user_id);

        }
        else
        {

            // find existing user
            $user = \Auth\Model\Auth_User::find($object['id']);

            // modify user
            $user->username = $object['username'];
            $user->password = \Auth\Auth::instance()->hash_password($object['password']);
            $user->email = $object['email'];

            // save user
            $user->save();
            // clear password
            $user->password = null;

        }

        // success
        return $user;

    }

    public function add_modify_group($object)
    {

        // create or update
        if (!isset($object['id']))
            $group = \Auth\Model\Auth_Group::forge();
        else
            $group = \Auth\Model\Auth_Group::find($object['id']);

        // set name
        $group->name = $object['name'];
        // save group
        $group->save();
        // flush all the cached groups
        \Cache::delete(\Config::get('ormauth.cache_prefix', 'auth').'.groups');
        // success
        return $group;

    }

    public function add_modify_role($object)
    {

        // create or update
        if (!isset($object['id']))
            $role = \Auth\Model\Auth_Role::forge();
        else
            $role = \Auth\Model\Auth_Role::find($object['id']);

        // set name & filter
        $role->name = $object['name'];
        $role->filter = $object['filter'];

        // save role
        $role->save();
        // flush all the cached roles
        \Cache::delete(\Config::get('ormauth.cache_prefix', 'auth').'.roles');
        // success
        return $role;

    }

    public function add_modify_permission($object)
    {

        // create or update
        if (!isset($object['id']))
            $permission = \Auth\Model\Auth_Permission::forge();
        else
            $permission = \Auth\Model\Auth_Permission::find($object['id']);

        // set area, perm
        $permission->area = $object['area'];
        $permission->permission = $object['permission'];
        $permission->description = $object['description'];
        // set actions
        if (isset($object['actions']))
            $permission->actions = $object['actions'];
        else
            $permission->actions = array();

        // save permission
        $permission->save();
        // flush all the cached permissions
        \Cache::delete_all(\Config::get('ormauth.cache_prefix', 'auth').'.permissions');
        // success
        return $permission;

    }

    //////////////
    // DELETERS //
    //////////////

    public function post_remove()
    {

        // get params
        $type = Input::post('type');
        $ids = Input::post('ids');

        // get table
        switch ($type) {
            case 'user_model':
                $table = 'users';
                break;
            case 'group_model':
                $table = 'users_groups';
                break;
            case 'role_model':
                $table = 'users_roles';
                break;
            case 'permission_model':
                $table = 'users_permissions';
                break;
        }

        // run delete query
        DB::delete()->table($table)->where('id', 'IN', $ids)->execute();

        // flush appropriate cache
        switch ($type) {
            case 'group_model':
                \Cache::delete(\Config::get('ormauth.cache_prefix', 'auth').'.groups');
                break;
            case 'role_model':
                \Cache::delete(\Config::get('ormauth.cache_prefix', 'auth').'.roles');
                break;
            case 'permission_model':
                \Cache::delete_all(\Config::get('ormauth.cache_prefix', 'auth').'.permissions');
                break;
        }

        // success
        return $this->response('SUCCESS');

    }

}
